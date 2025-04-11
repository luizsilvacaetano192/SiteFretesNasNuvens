@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Transferências por Frete</h2>

    <!-- Filtros -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">ID do Frete</label>
            <input type="text" name="freight_id" class="form-control" placeholder="Ex: 10" value="{{ request('freight_id') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tipo de Transferência</label>
            <select name="type" class="form-select">
                <option value="">Todos</option>
                <option value="credito" @selected(request('type') == 'credito')>Crédito</option>
                <option value="debito" @selected(request('type') == 'debito')>Débito</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Data</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <!-- DataTable -->
    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <table id="transfers-table" class="table table-bordered table-hover nowrap" style="width: 100%;">
                <thead class="table-dark">
                    <tr>
                        <th>Frete ID</th>
                        <th>Motorista</th>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Descrição</th>
                        <th>Carga</th>
                        <th>Empresa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transfers as $transfer)
                        <tr>
                            <td>{{ optional(optional(optional($transfer->userAccount)->driver)->freights->first())->id }}</td>
                            <td>{{ $transfer->userAccount->driver->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($transfer->transfer_date)->format('d/m/Y') }}</td>
                            <td>{{ ucfirst($transfer->type) }}</td>
                            <td>R$ {{ number_format($transfer->amount, 2, ',', '.') }}</td>
                            <td>{{ $transfer->description }}</td>
                            <td>{{ $transfer->userAccount->driver->freights->first()->shipment->cargo_type ?? '-' }}</td>
                            <td>{{ $transfer->userAccount->driver->freights->first()->shipment->company->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery (deve vir antes de DataTables e outros plugins que usam $) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Moment.js e Daterangepicker -->
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
    $(document).ready(function () {
        $('#transfers-table').DataTable({
            pageLength: 25,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            }
        });

        $('#date_range').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                daysOfWeek: ["Dom","Seg","Ter","Qua","Qui","Sex","Sab"],
                monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"]
            }
        });

        $('#reset-filters').click(function () {
            $('#filters')[0].reset();
            $('#date_range').val('');
        });
    });
</script>
@endpush

