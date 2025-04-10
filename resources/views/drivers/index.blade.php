@extends('layouts.app')

@section('title', 'Gerenciar Motoristas')

@section('content')
<div class="container mt-4">
  <div class="card shadow rounded-4">
    <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
      <h4 class="mb-0"><i class="bi bi-truck me-2"></i>Motoristas</h4>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="driversTable" class="table table-hover align-middle">
          <thead>
            <tr>
              <th>Nome</th>
              <th>CPF</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($drivers as $driver)
              <tr>
                <td>{{ $driver->name }}</td>
                <td>{{ $driver->cpf }}</td>
                <td>
                  <span class="badge bg-{{ $driver->status == 'active' ? 'success' : ($driver->status == 'blocked' ? 'danger' : 'secondary') }}">
                    {{ ucfirst($driver->status) }}
                  </span>
                </td>
                <td>
                  <button class="btn btn-outline-primary btn-sm" onclick="viewBalance({{ $driver->id }})">
                    <i class="bi bi-wallet2 me-1"></i>Saldo
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal de Saldo e Transferências --}}
<div class="modal fade" id="balanceModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
      <div class="modal-header bg-gradient text-white" style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
        <h5 class="modal-title fw-bold"><i class="bi bi-wallet2 me-2"></i>Saldo e Transferências</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" id="balanceContent">
        <div class="text-center py-4">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Carregando...</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
@endpush

@push('scripts')
  <script>
    $(document).ready(function () {
      $('#driversTable').DataTable({
        language: {
          url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        }
      });
    });

    function viewBalance(driverId) {
      const modal = new bootstrap.Modal('#balanceModal');
      $('#balanceContent').html(`
          <div class="text-center py-4">
              <div class="spinner-border text-primary" role="status"></div>
              <p class="mt-2">Carregando saldo e transferências...</p>
          </div>
      `);
      modal.show();

      $.get(`/drivers/${driverId}/balance`, data => {
        $('#balanceContent').html(`
          <div class="mb-4 text-center">
            <h5 class="fw-bold">Saldo Atual</h5>
            <h2 class="display-5 text-success fw-bold">
              <i class="bi bi-cash-stack me-1"></i> R$ ${parseFloat(data.balance).toFixed(2)}
            </h2>
          </div>
          <div class="table-responsive animate__animated animate__fadeIn">
            <table class="table table-bordered table-hover table-sm align-middle">
              <thead class="table-light">
                <tr>
                  <th><i class="bi bi-calendar-event"></i> Data</th>
                  <th><i class="bi bi-currency-dollar"></i> Valor</th>
                  <th><i class="bi bi-shield-check"></i> Status</th>
                </tr>
              </thead>
              <tbody>
                ${data.transfers.map(t => `
                  <tr>
                    <td>${formatDateBR(t.created_at)}</td>
                    <td>R$ ${parseFloat(t.amount).toFixed(2)}</td>
                    <td><span class="badge bg-${t.status === 'completed' ? 'success' : 'secondary'}">${t.status}</span></td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `);
      }).fail(() => {
        $('#balanceContent').html(`<div class="alert alert-danger">❌ Erro ao buscar dados financeiros.</div>`);
      });
    }

    function formatDateBR(dateStr) {
      const d = new Date(dateStr);
      return d.toLocaleDateString('pt-BR') + ' ' + d.toLocaleTimeString('pt-BR').slice(0, 5);
    }
  </script>
@endpush
