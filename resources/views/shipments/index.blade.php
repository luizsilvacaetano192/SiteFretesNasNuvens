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


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@push('styles')
<style>
/* ESTILOS ESPECÍFICOS PARA O BOTÃO DE EXPANSÃO */
td.dt-control {
    position: relative;
    cursor: pointer;
    text-align: center;
}

td.dt-control::before {
    content: "+";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    font-size: 1.2rem;
    color: #4361ee;
    display: inline-block;
    width: 100%;
    height: 100%;
    line-height: 1;
}

tr.shown td.dt-control::before {
    content: "-";
    color: #f72585;
}

/* MELHORIAS DE VISUALIZAÇÃO DA TABELA */
#shipments-table tbody tr {
    transition: all 0.2s ease;
}

#shipments-table tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.05) !important;
}

#shipments-table tbody tr.shown {
    background-color: rgba(67, 97, 238, 0.03);
}

/* GARANTIR QUE O ÍCONE FIQUE VISÍVEL */
#shipments-table td.dt-control {
    padding: 0;
    vertical-align: middle;
}

#shipments-table td.dt-control::before {
    position: relative;
    top: 2px;
}

/* CORREÇÕES PARA SCROLL LATERAL */
.container-fluid.px-4 {
    padding-left: 15px !important;
    padding-right: 15px !important;
    overflow-x: hidden;
}

.table-responsive {
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    margin-left: -1px;
    margin-right: -1px;
}

#shipments-table {
    width: 100% !important;
    table-layout: auto;
}

.card, .table, .row {
    margin-left: 0;
    margin-right: 0;
}

/* AJUSTE PARA COLUNAS DA TABELA */
#shipments-table th, 
#shipments-table td {
    white-space: nowrap;
}

/* GARANTIR QUE O CONTEÚDO EXPANDIDO NÃO CAUSE SCROLL */
tr.shown div.row {
    margin-left: 0;
    margin-right: 0;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Verifica se a tabela já foi inicializada e destrói se necessário
    if ($.fn.DataTable.isDataTable('#shipments-table')) {
        $('#shipments-table').DataTable().destroy();
    }

    // Função para formatar os detalhes expandidos
    function formatDetails(d) {
        return `
            <div class="row px-4 py-3 bg-light rounded-3 mx-1 my-2">
                <div class="col-md-12 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fw-bold">Descrição Completa da Carga</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">${d.description || 'Nenhuma descrição disponível'}</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fw-bold">Informações da Carga</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Volume</small>
                                    <p class="mb-0 fw-semibold">${d.volume || 'N/A'}</p>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Peso</small>
                                    <p class="mb-0 fw-semibold">${d.weight || '0 kg'}</p>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Dimensões</small>
                                    <p class="mb-0 fw-semibold">${d.dimensions || 'N/A'}</p>
                                </div>
                                <div class="col-6 mb-2">
                                    <small class="text-muted">Tipo de Carga</small>
                                    <p class="mb-0 fw-semibold">${d.cargo_type || 'N/A'}</p>
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
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0 fw-bold">Controle de Temperatura</h6>
                        </div>
                        <div class="card-body">
                            ${d.requires_temperature_control ? `
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Temperatura Mínima</small>
                                        <p class="mb-0 fw-semibold">${d.min_temperature || 'N/A'}°${d.temperature_unit === 'celsius' ? 'C' : 'F'}</p>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Temperatura Máxima</small>
                                        <p class="mb-0 fw-semibold">${d.max_temperature || 'N/A'}°${d.temperature_unit === 'celsius' ? 'C' : 'F'}</p>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Tolerância</small>
                                        <p class="mb-0 fw-semibold">${d.temperature_tolerance || 'N/A'}</p>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <small class="text-muted">Tipo de Controle</small>
                                        <p class="mb-0 fw-semibold">${d.temperature_control_type || 'N/A'}</p>
                                    </div>
                                </div>
                                ${d.temperature_notes ? `
                                    <div class="mt-2">
                                        <small class="text-muted">Observações</small>
                                        <p class="mb-0 fw-semibold">${d.temperature_notes}</p>
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
                width: '40px'
            },
            { 
                data: 'id',
                className: 'ps-4 fw-semibold'
            },
            { 
                data: 'company.name',
                name: 'company_id',
                render: data => data || 'N/A'
            },
            { 
                data: 'weight',
                render: data => data ? `${data} kg` : '0 kg'
            },
            { 
                data: 'cargo_type',
                render: data => `<span class="badge bg-primary bg-opacity-10 text-primary">
                    ${(data || '').charAt(0).toUpperCase() + (data || '').slice(1)}
                </span>`
            },
            { 
                data: 'dimensions',
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

    // Adiciona evento de clique para expandir/recolher linhas
    $('#shipments-table tbody').on('click', 'td.dt-control', function() {
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

    // Pesquisa personalizada
    $('#customSearch').keyup(function() {
        table.search(this.value).draw();
    });

    // Recarregar tabela
    $('#reload-table').click(function() {
        table.ajax.reload(null, false);
    });
});
</script>
@endpush