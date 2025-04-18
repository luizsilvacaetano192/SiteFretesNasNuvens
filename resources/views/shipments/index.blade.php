@extends('layouts.app')

@section('title', 'Lista de Cargas')

@section('content')
<div class="container-fluid px-4 py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shipping-fast me-2"></i>Gerenciamento de Cargas
            </h1>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('shipments.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle me-2"></i>Nova Carga
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 rounded-top-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="status-legend d-flex flex-wrap gap-3">
                    <div class="d-flex align-items-center">
                        <span class="status-badge bg-warning me-2"></span>
                        <span class="text-muted">Pendente</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="status-badge bg-success me-2"></span>
                        <span class="text-muted">Aprovado</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="status-badge bg-danger me-2"></span>
                        <span class="text-muted">Rejeitado</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="status-badge bg-primary me-2"></span>
                        <span class="text-muted">Completo</span>
                    </div>
                </div>
                
                <div class="flex-grow-1" style="min-width: 250px;">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="customSearch" class="form-control" placeholder="Pesquisar...">

                      
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="shipments-table" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Empresa</th>
                            <th>Peso</th>
                            <th>Tipo de Carga</th>
                            <th>Dimensões</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="text-muted d-flex align-items-center">
                    <div class="me-3 d-none d-sm-block">
                        <i class="fas fa-info-circle me-1"></i>
                        <span id="page-info"></span>
                    </div>
                </div>
                <div class="mt-2 mt-sm-0">
                    <button id="reload-table" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-sync-alt me-1"></i>Recarregar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializa o DataTable
    const table = $('#shipments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('shipments.index') }}",
            type: "GET",
            error: function(xhr, error, thrown) {
                console.error("Erro ao carregar dados:", error);
            }
        },
        columns: [
            { 
                data: 'id', 
                name: 'id',
                className: 'ps-4 fw-semibold'
            },
            { 
                data: 'company_name', 
                name: 'company.name',
                render: function(data) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'weight_formatted', 
                name: 'weight',
                render: function(data) {
                    return data || '0 kg';
                }
            },
            { 
                data: 'cargo_type_formatted', 
                name: 'cargo_type',
                render: function(data) {
                    const cargoType = data || '';
                    return `<span class="badge bg-primary bg-opacity-10 text-primary">
                        ${cargoType.charAt(0).toUpperCase() + cargoType.slice(1)}
                    </span>`;
                }
            },
            { 
                data: 'dimensions', 
                name: 'dimensions',
                render: function(data) {
                    return data || 'N/A';
                }
            },
            { 
                data: 'status_badge', 
                name: 'status',
                orderable: false,
                searchable: false
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                className: 'text-end pe-4'
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json',
            emptyTable: "Nenhum registro encontrado",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
            infoFiltered: "(filtrado de _MAX_ registros no total)",
            loadingRecords: "Carregando...",
            processing: "Processando...",
            zeroRecords: "Nenhum registro correspondente encontrado"
        },
        drawCallback: function(settings) {
            // Atualiza o contador de registros
            const api = this.api();
            const pageInfo = api.page.info();
            $('#page-info').html(
                `Mostrando ${pageInfo.start + 1} a ${pageInfo.end} de ${pageInfo.recordsDisplay} registros`
            );
            
            // Inicializa tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    // Pesquisa personalizada
    $('#customSearch').keyup(function() {
        table.search($(this).val()).draw();
    });

    // Botão de recarregar
    $('#reload-table').click(function() {
        table.ajax.reload(null, false);
    });

    // Tratamento de erros
    $.fn.dataTable.ext.errMode = 'throw';
});
</script>
@endpush

@section('styles')
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
:root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --success-color: #4cc9f0;
    --danger-color: #f72585;
    --warning-color: #f8961e;
    --light-color: #f8f9fa;
    --dark-color: #212529;
}

body {
    background-color: #f5f7fb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card {
    border: none;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    overflow: hidden;
}

.card:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
}

.status-badge {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

.table thead th {
    border-bottom: 1px solid #e9ecef;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #6c757d;
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.05) !important;
}

.table tbody td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    border-top: 1px solid #e9ecef;
}

.btn {
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    border-radius: 0.375rem;
}

.btn-sm {
    padding: 0.35rem 0.75rem;
    font-size: 0.875rem;
}

.rounded-4 {
    border-radius: 1rem !important;
}

.input-group-text {
    background-color: transparent;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border: none !important;
    padding: 0.5rem 0.75rem;
    margin-left: 0.25rem;
    border-radius: 0.375rem !important;
    transition: all 0.2s;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--primary-color) !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #e9ecef !important;
    color: var(--dark-color) !important;
}

/* Responsividade */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .status-legend {
        width: 100%;
        justify-content: flex-start;
    }
    
    .table-search {
        width: 100%;
    }
    
    .card-footer > div {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start !important;
    }
    
    .table td:nth-child(2), 
    .table th:nth-child(2) {
        min-width: 150px;
    }
}
</style>
@endsection