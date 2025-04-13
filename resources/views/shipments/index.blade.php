@extends('layouts.app')

@section('title', 'Lista de Cargas')

@section('content')
<div class="container-fluid px-4 py-5">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="display-5 fw-bold mb-0">
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
                        <span class="status-badge bg-danger me-2"></span>
                        <span class="text-muted">Carga sem frete</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="status-badge bg-warning me-2"></span>
                        <span class="text-muted">Frete solicitado</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="status-badge bg-success me-2"></span>
                        <span class="text-muted">Frete confirmado</span>
                    </div>
                </div>
                
                <div class="flex-grow-1" style="min-width: 250px;">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="customSearch" class="form-control border-start-0" 
                               placeholder="Pesquisar...">
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
                            <th>Peso (kg)</th>
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
                    <div>
                        <i class="fas fa-database me-1"></i>
                        Total: <span id="total-records" class="fw-semibold"></span>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-2 mt-sm-0">
                    <button id="export-excel" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-file-excel me-1"></i>Excel
                    </button>
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
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializa o DataTable
    const table = $('#shipments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('shipments.index') }}",
            type: "GET",
            data: function(d) {
                // Adicione parâmetros adicionais se necessário
                d.custom_search = $('#customSearch').val();
            }
        },
        columns: [
            { 
                data: 'id', 
                name: 'id',
                className: 'ps-4 fw-semibold'
            },
            { 
                data: 'company.name', 
                name: 'company.name',
                render: function(data, type, row) {
                    return `
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-light text-primary rounded-3">
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">${data}</h6>
                                <small class="text-muted">ID: ${row.company_id}</small>
                            </div>
                        </div>
                    `;
                }
            },
            { 
                data: 'weight', 
                name: 'weight',
                render: function(data) {
                    return `${data} kg`;
                }
            },
            { 
                data: 'cargo_type', 
                name: 'cargo_type',
                render: function(data) {
                    return `
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            ${data.charAt(0).toUpperCase() + data.slice(1)}
                        </span>
                    `;
                }
            },
            { 
                data: 'dimensions', 
                name: 'dimensions' 
            },
            {
                data: 'status',
                name: 'status',
                render: function(data) {
                    let badgeClass = 'bg-secondary';
                    if (data === 'pending') badgeClass = 'bg-warning';
                    if (data === 'approved') badgeClass = 'bg-success';
                    if (data === 'rejected') badgeClass = 'bg-danger';
                    
                    return `
                        <span class="badge ${badgeClass}">
                            ${data.charAt(0).toUpperCase() + data.slice(1)}
                        </span>
                    `;
                }
            },
            { 
                data: 'action', 
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-end pe-4',
                render: function(data, type, row) {
                    return `
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="${row.freight_url}" class="btn btn-success btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
                                <i class="fas fa-truck me-2"></i>Solicitar Frete
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 dropdown-toggle" 
                                        type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="${row.edit_url}"><i class="fas fa-edit me-2"></i>Editar</a></li>
                                    <li><a class="dropdown-item" href="${row.view_url}"><i class="fas fa-eye me-2"></i>Visualizar</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="${row.delete_url}"><i class="fas fa-trash me-2"></i>Excluir</a></li>
                                </ul>
                            </div>
                        </div>
                    `;
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        pageLength: 10,
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-outline-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }
        ],
        initComplete: function() {
            // Adiciona os botões de exportação
            this.api().buttons().container().appendTo('#export-buttons');
            
            // Atualiza os contadores
            updateCounters(this.api());
        },
        drawCallback: function(settings) {
            // Atualiza os contadores após cada desenho da tabela
            updateCounters(this.api());
        }
    });

    // Função para atualizar contadores
    function updateCounters(api) {
        const pageInfo = api.page.info();
        $('#page-info').html(
            `Mostrando ${pageInfo.start + 1} a ${pageInfo.end} de ${pageInfo.recordsDisplay} registros`
        );
        $('#total-records').text(pageInfo.recordsTotal);
    }

    // Pesquisa personalizada
    $('#customSearch').keyup(function() {
        table.search($(this).val()).draw();
    });

    // Botão de recarregar
    $('#reload-table').click(function() {
        table.ajax.reload();
    });

    // Botão de exportar para Excel
    $('#export-excel').click(function() {
        table.button('.buttons-excel').trigger();
    });
});
</script>
@endpush

@section('styles')
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css" rel="stylesheet">
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

.avatar-sm {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
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

.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    width: 100%;
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

.dropdown-menu {
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    border: none;
}

.dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}

.dropdown-item:hover {
    background-color: rgba(67, 97, 238, 0.1);
    color: var(--primary-color);
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
    
    .dataTables_wrapper .dataTables_info, 
    .dataTables_wrapper .dataTables_paginate {
        text-align: center;
        width: 100%;
    }
    
    .card-footer > div {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endsection