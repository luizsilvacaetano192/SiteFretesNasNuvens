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
                            <th width="40"></th>
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

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
      rel="stylesheet"
      integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer" />
      
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
/* ESTILOS GERAIS */
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

.status-badge {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

/* ESTILOS DA TABELA */
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

.table tbody tr.shown {
    background-color: rgba(67, 97, 238, 0.03);
}

.table tbody td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    border-top: 1px solid #e9ecef;
}

/* ÍCONE DE EXPANSÃO - SOLUÇÃO DEFINITIVA */
td.dt-control {
    position: relative;
    text-align: center;
    cursor: pointer;
    width: 40px;
    padding: 0.75rem !important;
}

/* Estilo para o ícone de expansão */
td.dt-control .expand-icon {
    display: inline-block;
    width: 100%;
    height: 100%;
    position: relative;
}

/* Fallback padrão (funciona SEM Font Awesome) */
td.dt-control .expand-icon::before {
    content: "+";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.3rem;
    font-weight: bold;
    color: #28a745;
}

/* Estado expandido */
tr.shown td.dt-control .expand-icon::before {
    content: "-";
    color: #dc3545;
}

/* Se Font Awesome estiver carregado */
.fa-loaded td.dt-control .expand-icon::before {
    content: "\f055" !important; /* Ícone plus do Font Awesome */
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
}

.fa-loaded tr.shown td.dt-control .expand-icon::before {
    content: "\f056" !important; /* Ícone minus do Font Awesome */
}

/* BOTÕES E COMPONENTES */
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

/* RESPONSIVIDADE */
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
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Função para verificar se o Font Awesome está carregado
    function isFontAwesomeLoaded() {
        const test = document.createElement('div');
        test.className = 'fa fa-font-awesome';
        test.style.display = 'none';
        document.body.appendChild(test);
        
        const style = window.getComputedStyle(test, ':before');
        const isLoaded = style && style.fontFamily.includes('Awesome');
        document.body.removeChild(test);
        return isLoaded;
    }

    // Carrega o Font Awesome se não estiver disponível
    if (!isFontAwesomeLoaded()) {
        const fa = document.createElement('link');
        fa.rel = 'stylesheet';
        fa.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
        fa.onload = function() {
            $('body').addClass('fa-loaded');
            initializeTable();
        };
        document.head.appendChild(fa);
    } else {
        $('body').addClass('fa-loaded');
        initializeTable();
    }

    function initializeTable() {
        // Função para formatar os detalhes expandidos
        function formatDetails(d) {
            return `
                <div class="row px-4 py-3">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Descrição Completa da Carga</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">${d.description || 'Nenhuma descrição disponível'}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Informações da Carga</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Volume</small>
                                        <p class="mb-0">${d.volume || 'N/A'}</p>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Peso</small>
                                        <p class="mb-0">${d.weight_formatted || '0 kg'}</p>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Frágil</small>
                                        <p class="mb-0">
                                            ${d.is_fragile ? '<span class="badge bg-danger">Sim</span>' : '<span class="badge bg-secondary">Não</span>'}
                                        </p>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Perigoso</small>
                                        <p class="mb-0">
                                            ${d.is_hazardous ? '<span class="badge bg-danger">Sim</span>' : '<span class="badge bg-secondary">Não</span>'}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Controle de Temperatura</h6>
                            </div>
                            <div class="card-body">
                                ${d.requires_temperature_control ? `
                                    <div class="row">
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">Temperatura Mínima</small>
                                            <p class="mb-0">${d.min_temperature}°${d.temperature_unit === 'celsius' ? 'C' : 'F'}</p>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">Temperatura Máxima</small>
                                            <p class="mb-0">${d.max_temperature}°${d.temperature_unit === 'celsius' ? 'C' : 'F'}</p>
                                        </div>
                                    </div>
                                    ${d.temperature_notes ? `
                                        <div class="mt-2">
                                            <small class="text-muted">Observações</small>
                                            <p class="mb-0">${d.temperature_notes}</p>
                                        </div>
                                    ` : ''}
                                ` : '<p class="mb-0 text-muted">Não requer controle de temperatura</p>'}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Inicializa a DataTable
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
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                    width: '40px',
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).html('<span class="expand-icon"></span>');
                    }
                },
                { 
                    data: 'id',
                    className: 'ps-4 fw-semibold'
                },
                { 
                    data: 'company_name',
                    render: data => data || 'N/A'
                },
                { 
                    data: 'weight_formatted',
                    render: data => data || '0 kg'
                },
                { 
                    data: 'cargo_type_formatted',
                    render: data => `<span class="badge bg-primary bg-opacity-10 text-primary">
                        ${(data || '').charAt(0).toUpperCase() + (data || '').slice(1)}
                    </span>`
                },
                { 
                    data: 'dimensions_formatted',
                    render: data => data || 'N/A'
                },
                { 
                    data: 'status_badge',
                    orderable: false,
                    searchable: false
                },
                { 
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-end pe-4'
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
            },
            drawCallback: function(settings) {
                const api = this.api();
                $('#page-info').html(
                    `Mostrando ${api.page.info().start + 1} a ${api.page.info().end} de ${api.page.info().recordsDisplay} registros`
                );
            }
        });

        // Evento de clique para expandir/recolher
        $('#shipments-table').on('click', 'td.dt-control', function() {
            const tr = $(this).closest('tr');
            const row = table.row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(formatDetails(row.data())).show();
                tr.addClass('shown');
            }
        });

        // Pesquisa customizada
        $('#customSearch').keyup(function() {
            table.search(this.value).draw();
        });

        // Botão de recarregar
        $('#reload-table').click(function() {
            table.ajax.reload(null, false);
        });
    }
});
</script>
@endpush