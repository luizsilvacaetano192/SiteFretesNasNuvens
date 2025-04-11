@extends('layouts.app')

@section('title', 'Saldo do Motorista')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-wallet me-2"></i>Saldo do Motorista: {{ $driver->name }}
                    </h3>
                    <div>
                        <span class="badge bg-light text-dark fs-6">
                            <i class="fas fa-id-card me-1"></i> {{ $account->asaas_identifier ?? 'N/A' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Saldo Total -->
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-muted mb-3">
                                        <i class="fas fa-money-bill-wave me-2"></i>Saldo Total
                                    </h5>
                                    <h2 class="text-primary mb-0">
                                        R$ {{ number_format($account->total_balance, 2, ',', '.') }}
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <!-- Saldo Disponível -->
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-success h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-muted mb-3">
                                        <i class="fas fa-check-circle me-2"></i>Disponível
                                    </h5>
                                    <h2 class="text-success mb-0">
                                        R$ {{ number_format($account->available_balance, 2, ',', '.') }}
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <!-- Saldo Bloqueado -->
                        <div class="col-md-4">
                            <div class="card border-warning h-100">
                                <div class="card-body text-center">
                                    <h5 class="card-title text-muted mb-3">
                                        <i class="fas fa-lock me-2"></i>Bloqueado
                                    </h5>
                                    <h2 class="text-warning mb-0">
                                        R$ {{ number_format($account->blocked_balance, 2, ',', '.') }}
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>Histórico de Transferências
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="transfersTable" class="table table-hover table-bordered" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th width="120">Data</th>
                                    <th>Tipo</th>
                                    <th width="150">Valor</th>
                                    <th>Descrição</th>
                                    <th width="120">Frete</th>
                                    <th>Cliente</th>
                                    <th width="150">ID Transferência</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transfers as $transfer)
                                <tr class="align-middle">
                                    <td>{{ $transfer->formatted_date }}</td>
                                    <td>
                                        <span class="badge {{ $transfer->badge_color }}">
                                            {{ $transfer->type_formatted }}
                                        </span>
                                    </td>
                                    <td class="fw-bold {{ $transfer->amount < 0 ? 'text-danger' : 'text-success' }}">
                                        R$ {{ $transfer->formatted_amount }}
                                    </td>
                                    <td>{{ $transfer->description ?? 'Transferência' }}</td>
                                    <td>
                                        @if($transfer->freight)
                                            <a href="{{ route('freights.show', $transfer->freight->id) }}" 
                                               class="btn btn-sm btn-outline-primary w-100">
                                                #{{ $transfer->freight->id }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($transfer->freight && $transfer->freight->company)
                                            {{ $transfer->freight->company->name }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="font-monospace">{{ $transfer->asaas_identifier ?? 'N/A' }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle me-2"></i> Nenhuma transferência encontrada
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0">
                                <strong>Total de Transferências:</strong> 
                                <span class="badge bg-primary">{{ $transfers->count() }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0">
                                <strong>Valor Total:</strong> 
                                <span class="fw-bold text-success">
                                    R$ {{ number_format($transfers->sum('amount'), 2, ',', '.') }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .card-header {
        border-radius: 0 !important;
    }
    .table {
        font-size: 0.9rem;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
    }
    .badge {
        font-size: 0.8em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .font-monospace {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }
    #transfersTable tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endpush

@push('scripts')
<!-- DataTables -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-html5-2.2.2/b-print-2.2.2/datatables.min.css"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-html5-2.2.2/b-print-2.2.2/datatables.min.js"></script>

<script>
$(document).ready(function() {
    $('#transfersTable').DataTable({
        dom: '<"row"<"col-md-6"B><"col-md-6"f>><"row"<"col-md-12"tr>><"row"<"col-md-5"i><"col-md-7"p>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i> Imprimir',
                className: 'btn btn-secondary btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            }
        ],
        order: [[0, 'desc']],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        columnDefs: [
            { targets: 0, type: 'date' },
            { targets: 2, orderData: [2], type: 'num-fmt' }
        ]
    });
});
</script>
@endpush