@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">🚚 Lista de Motoristas</h1>

    <table id="driversTable" class="table table-bordered table-hover w-100">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drivers as $driver)
                <tr>
                    <td>{{ $driver->name }}</td>
                    <td>{{ $driver->cpf }}</td>
                    <td>{{ ucfirst($driver->status) }}</td>
                    <td>
                        <button class="btn btn-outline-success" onclick="openBalanceModal({{ $driver->id }})">
                            💰 Saldo
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Modal de saldo --}}
@include('drivers.modals.balance')

@endsection

@section('scripts')
<script>
function openBalanceModal(driverId) {
    $('#balanceModal').modal('show');
    $('#balanceInfo').html('<div class="text-center"><div class="spinner-border text-success"></div> Carregando saldo...</div>');
    $('#transfersTable').DataTable().clear().destroy();

    $.get(`/drivers/${driverId}/balance-info`, function (res) {
        const acc = res.account;

        if (!acc) {
            $('#balanceInfo').html('<div class="alert alert-warning">Nenhuma conta encontrada para este motorista.</div>');
            return;
        }

        $('#balanceInfo').html(`
            <div class="row text-center">
                <div class="col-md-3">
                    <strong>💳 Identificador:</strong><br>
                    ${acc.asaas_identifier ?? '—'}
                </div>
                <div class="col-md-3 text-success">
                    <strong>💰 Total:</strong><br>
                    R$ ${parseFloat(acc.total_balance).toFixed(2)}
                </div>
                <div class="col-md-3 text-danger">
                    <strong>⛔ Bloqueado:</strong><br>
                    R$ ${parseFloat(acc.blocked_balance).toFixed(2)}
                </div>
                <div class="col-md-3 text-primary">
                    <strong>✅ Disponível:</strong><br>
                    R$ ${parseFloat(acc.available_balance).toFixed(2)}
                </div>
                <div class="col-12 mt-2">
                    <strong>🕒 Última Atualização:</strong> ${acc.last_updated_at ? new Date(acc.last_updated_at).toLocaleString('pt-BR') : '—'}
                </div>
            </div>
        `);

        $('#transfersTable').DataTable({
            data: res.transfers,
            language: { url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json" },
            columns: [
                {
                    data: 'type',
                    render: type => {
                        const labels = {
                            'available_balance': '✅ Disponível',
                            'blocked_balance': '⛔ Bloqueado',
                            'debited_balance': '💸 Débito'
                        };
                        return labels[type] ?? type;
                    }
                },
                {
                    data: 'amount',
                    render: val => `R$ ${parseFloat(val).toFixed(2)}`
                },
                {
                    data: 'description',
                    defaultContent: '—'
                },
                {
                    data: 'transfer_date',
                    render: date => new Date(date).toLocaleDateString('pt-BR')
                }
            ]
        });
    }).fail(() => {
        $('#balanceInfo').html('<div class="alert alert-danger">Erro ao carregar saldo.</div>');
    });
}
</script>
@endsection
