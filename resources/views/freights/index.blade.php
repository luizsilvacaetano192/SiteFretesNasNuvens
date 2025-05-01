@extends('layouts.app')

@section('title', 'Gestão de Fretes')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-truck-moving me-2"></i>Gestão de Fretes
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fretes</li>
                </ol>
            </nav>
        </div>
        <div>
            <button id="refresh-table" class="btn btn-outline-primary me-2">
                <i class="fas fa-sync-alt me-1"></i>Atualizar (10s)
            </button>
            <button id="export-excel" class="btn btn-success me-2">
                <i class="fas fa-file-excel me-1"></i>Exportar
            </button>
            <button id="delete-all-freights" class="btn btn-danger">
                <i class="fas fa-trash-alt me-1"></i>Limpar Tudo
            </button>
        </div>
    </div>

    <!-- Estatísticas no topo -->
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row">
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-clock fa-lg text-warning"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Aguardando Pagamento</div>
                            <div class="h5 mb-0 text-warning" id="waiting-payment-count">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-user-clock fa-lg text-info"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Aguardando Motorista</div>
                            <div class="h5 mb-0 text-info" id="waiting-driver-count">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-building fa-lg text-secondary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Aguardando Aprovação</div>
                            <div class="h5 mb-0 text-secondary" id="waiting-approval-count">0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-truck-loading fa-lg text-primary"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Aguardando Retirada</div>
                            <div class="h5 mb-0 text-primary" id="waiting-pickup-count">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-truck-moving fa-lg text-warning"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Indo Retirar Carga</div>
                            <div class="h5 mb-0 text-warning" id="going-pickup-count">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-shipping-fast fa-lg text-info"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Em Processo</div>
                            <div class="h5 mb-0 text-info" id="in-progress-count">0</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-check-circle fa-lg text-success"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Carga Entregue</div>
                            <div class="h5 mb-0 text-success" id="delivered-count">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-times-circle fa-lg text-danger"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Cancelados</div>
                            <div class="h5 mb-0 text-danger" id="cancelled-count">0</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark bg-opacity-10 p-3 rounded me-3">
                            <i class="fas fa-file-invoice-dollar fa-lg text-dark"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Fretes</div>
                            <div class="h5 mb-0 text-dark" id="total-count">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex flex-column flex-md-row gap-3 w-100 w-md-auto">
                <div class="input-group" style="width: 200px;">
                    <span class="input-group-text bg-transparent">
                        <i class="fas fa-filter"></i>
                    </span>
                    <select id="status-filter" class="form-select">
                        <option value="">Todos Status</option>
                    </select>
                </div>
                <div class="input-group" style="width: 200px;">
                    <span class="input-group-text bg-transparent">
                        <i class="fas fa-building"></i>
                    </span>
                    <select id="company-filter" class="form-select">
                        <option value="">Todas Empresas</option>
                    </select>
                </div>
                <div class="input-group" style="width: 200px;">
                    <span class="input-group-text bg-transparent">
                        <i class="fas fa-truck"></i>
                    </span>
                    <select id="driver-filter" class="form-select">
                        <option value="">Todos Motoristas</option>
                    </select>
                </div>
                <div class="input-group" style="width: 200px;">
                    <span class="input-group-text bg-transparent">
                        <i class="fas fa-calendar"></i>
                    </span>
                    <input type="date" id="start-date-filter" class="form-control" placeholder="Data inicial">
                </div>
                <div class="input-group" style="width: 200px;">
                    <span class="input-group-text bg-transparent">
                        <i class="fas fa-calendar"></i>
                    </span>
                    <input type="date" id="end-date-filter" class="form-control" placeholder="Data final">
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-wrapper">
                <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table id="freights-table" class="table table-hover align-middle mb-0" style="width:100%; margin:0;">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Empresa</th>
                                <th style="width:10px">Origem</th>
                                <th>Destino</th>
                                <th>Motorista</th>
                                <th>Status</th>
                                <th>Valor</th>
                                <th>Pagamento</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dados carregados via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <span id="table-info"></span>
                </div>
                <div class="text-muted small">
                    Atualizado em: <span id="last-update-time">{{ now()->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

<style>
/* CORREÇÕES PARA O SCROLL INDESEJADO */
html {
    overflow-y: auto;
}

body {
    overflow-x: hidden;
    width: 100vw;
    position: relative;
}

.container-fluid {
    max-width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
    overflow: hidden;
}

/* ESTILOS GERAIS */
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    padding: 1rem 1.35rem;
}

/* ESTILOS DA TABELA */
.table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin: 0;
    padding: 0;
}

.table-responsive {
    width: 100% !important;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

#freights-table {
    width: 100% !important;
    margin: 0;
}

/* ESTILOS PARA CÉLULAS COM TEXTO TRUNCADO */
.text-truncate-container {
    max-width: 150px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
    vertical-align: middle;
}

.text-truncate-container:hover {
    overflow: visible;
    white-space: normal;
    word-break: break-all;
    background-color: white;
    z-index: 10;
    position: relative;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

/* ESTILOS DA TABELA */
.table thead th {
    vertical-align: middle;
    padding: 1rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #858796;
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.table tbody td {
    vertical-align: middle;
    padding: 1rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.05);
}

/* BOTÕES E BADGES */
.btn {
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 0.35rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
}

/* RESPONSIVIDADE */
@media (max-width: 992px) {
    .card-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .input-group {
        width: 100% !important;
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 768px) {
    .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between.align-items-center.mb-4 > div {
        width: 100%;
    }
    
    .d-flex.justify-content-between.align-items-center.mb-4 > div:last-child {
        justify-content: flex-start;
    }
    
    .stats-card .d-flex {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-card .me-3 {
        margin-right: 0 !important;
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
// Configuração do Toastr
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": 15000,
    "extendedTimeOut": 5000,
    "newestOnTop": true,
    "preventDuplicates": false,
    "tapToDismiss": false
};

$(document).ready(function() {
    // Inicializa a tabela
    initializeDataTable();
    
    // Inicia a atualização automática
    startAutoRefresh();
    
    // Configura os eventos
    setupEventHandlers();
    
    // Atualiza o horário inicial
    updateLastUpdateTime();
    
    // Força a correção do layout
    fixLayout();
    $(window).on('resize', fixLayout);
});

function fixLayout() {
    $('body').css('overflow-x', 'hidden');
    $('.container-fluid').css('overflow', 'hidden');
}

function initializeDataTable() {
    freightTable = $('#freights-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('freights.data') }}',
            type: 'GET',
            data: function(d) {
                d.status_filter = $('#status-filter').val();
                d.company_filter = $('#company-filter').val();
                d.driver_filter = $('#driver-filter').val();
                d.start_date = $('#start-date-filter').val();
                d.end_date = $('#end-date-filter').val();
            },
            error: function(xhr, error, thrown) {
                console.error('Erro ao carregar dados:', xhr.responseText);
                toastr.error('Erro ao carregar dados da tabela');
            }
        },
        responsive: true,
        order: [[0, 'desc']],
        columns: [
            { 
                data: 'id', 
                name: 'id',
                className: 'fw-semibold'
            },
            { 
                data: 'company_name', 
                name: 'company.name',
                render: function(data, type, row) {
                    if (!data) return 'N/A';
                    return `
                        <div class="text-truncate-container" title="${data}">
                            <span class="fw-semibold">${data}</span>
                        </div>
                    `;
                }
            },
            { 
                data: 'start_address', 
                name: 'start_address',
                render: function(data) {
                    if (!data) return 'N/A';
                    return `
                        <div class="text-truncate-container" title="${data}">
                            ${data}
                        </div>
                    `;
                }
            },
            { 
                data: 'destination_address', 
                name: 'destination_address',
                render: function(data) {
                    if (!data) return 'N/A';
                    return `
                        <div class="text-truncate-container" title="${data}">
                            ${data}
                        </div>
                    `;
                }
            },
            { 
                data: 'driver_name', 
                name: 'driver.name',
                render: function(data, type, row) {
                    if (!data) return '<span >Não atribuído</span>';
                    
                        return `<span class="badge bg-success">${data}</span>`;
                }
            },
            { 
                data: 'status_badge', 
                name: 'status.name',
                orderable: false,
                searchable: false
            },
            { 
                data: 'formatted_value', 
                name: 'freight_value',
                orderable: true,
                searchable: false,
                className: 'text-end'
            },
            { 
                data: 'payment_button', 
                name: 'payment_button',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            { 
                data: 'created_at', 
                name: 'created_at',
                render: function(data) {
                    return data ? new Date(data).toLocaleDateString('pt-BR') : 'N/A';
                }
            },
            { 
                data: 'actions', 
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                    <div class="d-flex gap-2">
                        <a href="/freights/${row.id}" class="btn btn-sm btn-primary view-freight" data-id="${row.id}" title="Visualizar">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button class="btn btn-sm btn-danger delete-freight" data-id="${row.id}" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    `;
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i>Exportar',
                className: 'btn btn-success',
                title: 'Fretes'
            }
        ],
        initComplete: function() {
            loadStatusFilter();
            loadCompanyFilter();
            loadDriverFilter();
        },
        drawCallback: function(settings) {
            updateTableInfo();
            updateStats();
        }
    });
}

function loadStatusFilter() {
    $.get('{{ route('freights.statuses') }}', function(response) {
        const select = $('#status-filter');
        select.empty();
        select.append('<option value="">Todos Status</option>');
        
        response.forEach(status => {
            select.append(`<option value="${status.id}">${status.name}</option>`);
        });
    }).fail(function() {
        toastr.error('Erro ao carregar filtro de status');
    });
}

function loadCompanyFilter() {
    $.get('{{ route('companies.list') }}', function(response) {
        const select = $('#company-filter');
        select.empty();
        select.append('<option value="">Todas Empresas</option>');
        
        response.forEach(company => {
            select.append(`<option value="${company.id}">${company.name}</option>`);
        });
    }).fail(function() {
        toastr.error('Erro ao carregar filtro de empresas');
    });
}

function loadDriverFilter() {
    $.get('{{ route('drivers.list') }}', function(response) {
        const select = $('#driver-filter');
        select.empty();
        select.append('<option value="">Todos Motoristas</option>');
        
        response.forEach(driver => {
            select.append(`<option value="${driver.id}">${driver.name}</option>`);
        });
    }).fail(function() {
        toastr.error('Erro ao carregar filtro de motoristas');
    });
}

function setupEventHandlers() {
    $('#refresh-table').click(function() {
        manualRefreshTable();
    });

    $('#export-excel').click(function() {
        freightTable.button('.buttons-excel').trigger();
    });

    $('#delete-all-freights').click(function() {
        confirmDeleteAll();
    });

    $(document).on('click', '.delete-freight', function(e) {
        e.preventDefault();
        const freightId = $(this).data('id');
        confirmDeleteFreight(freightId);
    });

    $(document).on('change', '#status-filter, #company-filter, #driver-filter, #start-date-filter, #end-date-filter', function() {
        freightTable.ajax.reload();
    });
}

function startAutoRefresh() {
    updateTableWithNotifications();
    setInterval(updateTableWithNotifications, 10000);
    setInterval(updateCountdown, 1000);
}

function updateTableWithNotifications() {
    $.get(freightTable.ajax.url(), function(newData) {
        freightTable.ajax.reload(null, false);
        updateLastUpdateTime();
    }).fail(function() {
        toastr.error('Erro ao atualizar dados. Tentando novamente...');
    });
}

function updateCountdown() {
    const refreshBtn = $('#refresh-table');
    let countdown = parseInt(refreshBtn.text().match(/\d+/)) || 10;
    countdown = countdown <= 1 ? 10 : countdown - 1;
    refreshBtn.html(`<i class="fas fa-sync-alt me-1"></i>Atualizar (${countdown}s)`);
}

function updateLastUpdateTime() {
    const now = new Date();
    const formattedTime = now.toLocaleString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    $('#last-update-time').text(formattedTime);
}

function manualRefreshTable() {
    clearInterval(countdownInterval);
    updateTableWithNotifications();
    countdownInterval = setInterval(updateCountdown, 1000);
    toastr.success('Tabela atualizada com sucesso!');
}

function confirmDeleteAll() {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Todos os fretes serão excluídos permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir tudo!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteAllFreights();
        }
    });
}

function deleteAllFreights() {
    $.ajax({
        url: '{{ route('freights.deleteAll') }}',
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                freightTable.ajax.reload();
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            toastr.error('Erro ao excluir fretes: ' + xhr.responseText);
        }
    });
}

function confirmDeleteFreight(freightId) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Você não poderá reverter isso!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteFreight(freightId);
        }
    });
}

function deleteFreight(freightId) {
    $.ajax({
        url: `/freights/${freightId}`,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if(response.success) {
                toastr.success(response.message);
                freightTable.ajax.reload();
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr) {
            toastr.error('Erro ao excluir frete: ' + xhr.responseText);
        }
    });
}

function updateTableInfo() {
    const info = freightTable.page.info();
    $('#table-info').html(
        `Mostrando ${info.start + 1} a ${info.end} de ${info.recordsDisplay} registros`
    );
}

function updateStats() {
    $.get('{{ route('freights.stats') }}', function(response) {
        $('#waiting-payment-count').text(response['Aguardando pagamento'] || 0);
        $('#waiting-driver-count').text(response['Aguardando motorista'] || 0);
        $('#waiting-approval-count').text(response['Aguardando Aprovação empresa'] || 0);
        $('#waiting-pickup-count').text(response['Aguardando retirada'] || 0);
        $('#going-pickup-count').text(response['Indo retirar carga'] || 0);
        $('#in-progress-count').text(response['Em processo de entrega'] || 0);
        $('#delivered-count').text(response['Carga entregue'] || 0);
        $('#cancelled-count').text(response['Cancelado'] || 0);
        $('#total-count').text(response['total'] || 0);
    }).fail(function() {
        console.error('Erro ao carregar estatísticas');
    });
}
</script>
@endpush