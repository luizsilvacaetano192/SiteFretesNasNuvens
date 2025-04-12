@extends('layouts.app')

@section('title', 'Transferências')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('content')
<div class="container">
    <h2 class="mb-4">Transferências</h2>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="date_range" class="form-label">Período</label>
            <input type="text" class="form-control" id="date_range" name="date_range"
                value="{{ request('date_range') }}">
        </div>
        <div class="col-md-3">
            <label for="freight_id" class="form-label">ID do Frete</label>
            <input type="text" class="form-control" id="freight_id" name="freight_id"
                value="{{ request('freight_id') }}">
        </div>
        <div class="col-md-3">
            <label for="type" class="form-label">Tipo</label>
            <select class="form-select" id="type" name="type">
                <option value="">Todos</option>
                <option value="available_balance" {{ request('type') == 'available_balance' ? 'selected' : '' }}>
                    Liberação de Saldo
                </option>
                <option value="blocked_balance" {{ request('type') == 'blocked_balance' ? 'selected' : '' }}>
                    Saldo Bloqueado
                </option>
                <option value="debited_balance" {{ request('type') == 'debited_balance' ? 'selected' : '' }}>
                    Pix Cliente
                </option>
                <option value="entrada" {{ request('type') == 'entrada' ? 'selected' : '' }}>
                    Entrada
                </option>
                <option value="saída" {{ request('type') == 'saída' ? 'selected' : '' }}>
                    Saída
                </option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> Filtrar</button>
        </div>
    </form>

   <!-- No cabeçalho da tabela (mantém igual) -->
<table class="table table-bordered table-striped" id="transferTable">
    <thead class="table-dark">
        <tr>
            <th>Data</th>
            <th>Frete</th>
            <th>Motorista</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transfers as $transfer)
            <tr data-group="{{ $transfer->freight_id ?? 'no-group' }}">
                <td>{{ \Carbon\Carbon::parse($transfer->created_at)->format('d/m/Y H:i') }}</td>
                <td>
                    @isset($transfer->freight_id)
                        #{{ $transfer->freight_id }}
                    @else
                        -
                    @endisset
                </td>
                <td>{{ $transfer->driver->name ?? '-' }}</td>
                <td>
                    @php
                        $label = match($transfer->type) {
                            'available_balance' => 'Liberação de Saldo',
                            'blocked_balance' => 'Saldo Bloqueado',
                            'debited_balance' => 'Pix Cliente',
                            'entrada' => 'Entrada',
                            'saída' => 'Saída',
                            default => ucfirst($transfer->type),
                        };

                        $color = match($transfer->type) {
                            'available_balance', 'entrada' => 'success',
                            'blocked_balance' => 'warning',
                            'debited_balance', 'saída' => 'danger',
                            default => 'secondary',
                        };
                    @endphp

                    <span class="badge bg-{{ $color }}">
                        {{ $label }}
                    </span>
                </td>
                <td>R$ {{ number_format($transfer->amount, 2, ',', '.') }}</td>
                <td>{{ $transfer->description }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

    <div class="d-flex justify-content-end mt-4">
        {{ $transfers->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function () {
    $('#transferTable').DataTable({
        paging: false,
        info: false,
        ordering: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        },
        rowGroup: {
            dataSrc: function(row) {
                // Só agrupa se tiver freight_id
                return row.dataset.group !== 'no-group' ? row.dataset.group : null;
            }
        }
    });

    $('#date_range').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            daysOfWeek: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            firstDay: 0
        },
        autoUpdateInput: false,
    });

    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    });

    $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});
</script>
@endpush