@extends('layouts.app')

@section('title', 'Dashboard de Fretes')

@section('content')
<div class="container-fluid px-4">
    <!-- Container para notificações toast -->
    <div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <div class="row">
        <div class="col-12">
            <h1 class="mt-4">
                <i class="fas fa-truck me-2"></i>Dashboard de Fretes
            </h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Visão geral dos fretes</li>
            </ol>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row mb-4 summary-cards">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4 rounded-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal mb-2">Total de Fretes</h6>
                            <h3 class="mb-0 fw-bold" id="total-freights">0</h3>
                        </div>
                        <i class="fas fa-truck fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between bg-primary-dark">
                    <a class="small text-white stretched-link" href="#freights-table">Ver detalhes</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4 rounded-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal mb-2">Em Andamento</h6>
                            <h3 class="mb-0 fw-bold" id="in-progress">0</h3>
                        </div>
                        <i class="fas fa-spinner fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between bg-success-dark">
                    <a class="small text-white stretched-link" href="#freights-table">Ver detalhes</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4 rounded-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal mb-2">Pendentes</h6>
                            <h3 class="mb-0 fw-bold" id="pending">0</h3>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between bg-warning-dark">
                    <a class="small text-white stretched-link" href="#freights-table">Ver detalhes</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4 rounded-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-normal mb-2">Valor Total</h6>
                            <h3 class="mb-0 fw-bold" id="total-value">R$ 0,00</h3>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between bg-danger-dark">
                    <a class="small text-white stretched-link" href="#freights-table">Ver detalhes</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card mb-4 rounded-3 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie me-2"></i>Status dos Fretes
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary refresh-chart" data-chart="status">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="card-body position-relative">
                    <div class="chart-overlay">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4 rounded-3 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line me-2"></i>Fretes por Mês
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary refresh-chart" data-chart="monthly">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <div class="card-body position-relative">
                    <div class="chart-overlay">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <canvas id="monthlyChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapa de Calor -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card rounded-3 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-map-marked-alt me-2"></i>Distribuição Geográfica
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary refresh-map">
                            <i class="fas fa-sync-alt me-1"></i>Atualizar
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="mapFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i>Filtrar
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mapFilterDropdown">
                                <li><a class="dropdown-item filter-map active" href="#" data-filter="all">Todos</a></li>
                                <li><a class="dropdown-item filter-map" href="#" data-filter="pending">Pendentes</a></li>
                                <li><a class="dropdown-item filter-map" href="#" data-filter="in_progress">Em Andamento</a></li>
                                <li><a class="dropdown-item filter-map" href="#" data-filter="completed">Concluídos</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 position-relative" style="height: 400px;">
                    <div class="map-overlay">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="freightMap" style="height: 100%; width: 100%; border-radius: 0 0 0.3rem 0.3rem;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Fretes -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-3 rounded-top-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-list me-2"></i>Lista de Fretes
                        </h5>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i>Status
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="statusFilterDropdown">
                                    <li><a class="dropdown-item filter-status active" href="#" data-filter="all">Todos</a></li>
                                    @foreach($statuses as $status)
                                    <li><a class="dropdown-item filter-status" href="#" data-filter="{{ $status->id }}">{{ $status->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="flex-grow-1" style="min-width: 250px;">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" id="freightSearch" class="form-control" placeholder="Pesquisar...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0 position-relative">
                    <div class="table-overlay">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="freights-table" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th width="40"></th>
                                    <th class="ps-4">ID</th>
                                    <th>Transportadora</th>
                                    <th>Status</th>
                                    <th>Origem</th>
                                    <th>Destino</th>
                                    <th>Data Coleta</th>
                                    <th>Data Entrega</th>
                                    <th>Valor</th>
                                    <th class="text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Mostrando <span id="showing-entries">0</span> de <span id="total-entries">0</span> registros
                        </div>
                        <div class="d-flex gap-2">
                            <button id="export-excel" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-file-excel me-1"></i>Excel
                            </button>
                            <button id="reload-freights" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-sync-alt me-1"></i>Recarregar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div class="modal fade" id="freightDetailsModal" tabindex="-1" aria-labelledby="freightDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="freightDetailsModalLabel">Detalhes do Frete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="freightDetailsContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalhes de Motorista/Caminhão -->
<div class="modal fade" id="driverTruckModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Motorista e Caminhão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Informações do Motorista</h6>
                            </div>
                            <div class="card-body" id="driverDetails">
                                <!-- Conteúdo carregado via AJAX -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Informações do Caminhão</h6>
                            </div>
                            <div class="card-body" id="truckDetails">
                                <!-- Conteúdo carregado via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Implementos</h6>
                            </div>
                            <div class="card-body" id="implementsDetails">
                                <!-- Conteúdo carregado via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet">
<style>
    /* Estilos para os cards */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .bg-primary-dark {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    .bg-success-dark {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    .bg-warning-dark {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    .bg-danger-dark {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    /* Overlay styles */
    .chart-overlay,
    .map-overlay,
    .table-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        border-radius: 0.3rem;
    }
    
    .map-overlay {
        border-radius: 0 0 0.3rem 0.3rem;
    }
    
    /* Estilos para a tabela */
    #freights-table tbody tr {
        transition: all 0.2s ease;
    }
    
    #freights-table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }
    
    /* Estilos para o mapa */
    #freightMap {
        z-index: 1;
        border-radius: 0 0 0.3rem 0.3rem;
    }
    
    /* Ajustes para o info window do Google Maps */
    .gm-style .gm-style-iw {
        padding: 10px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .gm-style .gm-style-iw-c {
        padding: 0;
        border-radius: 0.5rem;
    }
    
    .gm-style .gm-style-iw-d {
        overflow: hidden !important;
    }
    
    .gm-ui-hover-effect {
        top: 8px !important;
        right: 8px !important;
    }
    
    /* Estilos para os dropdowns */
    .dropdown-menu {
        min-width: 12rem;
    }
    
    .filter-status.active, .filter-map.active {
        background-color: #f8f9fa;
        font-weight: 500;
    }
    
    /* Ajustes responsivos */
    @media (max-width: 768px) {
        .card-body canvas {
            height: 200px !important;
        }
        
        .summary-cards .col {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .card {
            background-color: #2a3042;
            color: #fff;
        }
        
        .card-header {
            background-color: #343a46 !important;
            border-color: #3e4452;
        }
        
        .table-light {
            background-color: #343a46;
            color: #fff;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.2) !important;
            color: #fff;
        }
    }
    
    /* Modal styles */
    .modal-body img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    
    .implement-photo {
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin-right: 10px;
        margin-bottom: 10px;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCi-A7nNanHXhUiBS3_71XeLa6bE0aX9Ts&libraries=visualization"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
// Variáveis globais
let statusChart, monthlyChart, freightMap = null, mapMarkers = [], table;
let mapInitialized = false;
let mapLoadTimeout;

// Formatar datas
function formatDateBR(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('pt-BR');
}

function formatDateTimeBR(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleString('pt-BR');
}

// Formatar valores monetários
function formatCurrency(value) {
    if (!value) return 'R$ 0,00';
    return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
}

// Mostrar toast de notificação
function showToast(message, type = 'success') {
    const toast = $(`
        <div class="toast align-items-center text-white bg-${type} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `);
    
    $('#toastContainer').append(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

// Inicializar gráficos
function initCharts() {
    // Gráfico de status
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b',
                    '#858796',
                    '#5a5c69'
                ],
                hoverBackgroundColor: [
                    '#2e59d9',
                    '#17a673',
                    '#2c9faf',
                    '#dda20a',
                    '#be2617',
                    '#6c757d',
                    '#4a4c54'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '70%',
        }
    });
    
    // Gráfico mensal
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            datasets: [{
                label: 'Fretes',
                backgroundColor: '#4e73df',
                hoverBackgroundColor: '#2e59d9',
                borderColor: '#4e73df',
                data: Array(12).fill(0),
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(context) {
                            return `Fretes: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#858796'
                    }
                },
                y: {
                    grid: {
                        color: "rgb(234, 236, 244)",
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#858796',
                        precision: 0
                    }
                }
            }
        }
    });
}

// Carregar dados dos gráficos
function loadChartData() {
    $('.chart-overlay').show();
    
    $.ajax({
        url: "{{ route('freights.chart-data') }}",
        method: 'GET',
        success: function(data) {
            // Atualizar gráfico de status
            if (data.status_chart) {
                statusChart.data.labels = data.status_chart.labels;
                statusChart.data.datasets[0].data = data.status_chart.data;
                statusChart.update();
            }
            
            // Atualizar gráfico mensal
            if (data.monthly_chart) {
                monthlyChart.data.datasets[0].data = data.monthly_chart.data;
                monthlyChart.update();
            }
            
            $('.chart-overlay').hide();
        },
        error: function(xhr) {
            console.error('Error loading chart data:', xhr.responseText);
            $('.chart-overlay').hide();
            showToast('Erro ao carregar dados dos gráficos', 'danger');
        }
    });
}

// Função para carregar a API do Google Maps
function loadGoogleMapsAPI() {
    return new Promise((resolve, reject) => {
        // Verificar se a API já está carregada
        if (window.google && window.google.maps) {
            initMap();
            resolve();
            return;
        }

        // Configurar timeout para fallback (15 segundos)
        mapLoadTimeout = setTimeout(() => {
            reject(new Error('Timeout ao carregar a API do Google Maps'));
            $('.map-overlay').hide();
            showToast('O mapa está demorando muito para carregar. Verifique sua conexão.', 'warning');
        }, 15000);

        // Criar elemento script
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyCi-A7nNanHXhUiBS3_71XeLa6bE0aX9Ts&libraries=visualization`;
        script.async = true;
        script.defer = true;
        
        script.onload = function() {
            clearTimeout(mapLoadTimeout);
            initMap();
            resolve();
        };
        
        script.onerror = function() {
            clearTimeout(mapLoadTimeout);
            reject(new Error('Falha ao carregar a API do Google Maps'));
            $('.map-overlay').hide();
            showToast('Erro ao carregar o Google Maps. Verifique sua conexão.', 'danger');
        };

        document.head.appendChild(script);
    });
}

// Inicializar mapa do Google
function initMap() {
    try {
        console.log('Inicializando mapa...');
        
        if (!window.google || !window.google.maps) {
            throw new Error('Google Maps API not loaded');
        }
        
        // Configuração inicial do mapa (centro no Brasil)
        const mapOptions = {
            center: { lat: -15.7889, lng: -47.8792 },
            zoom: 4,
            mapTypeId: 'roadmap',
            styles: [
                {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": [{"color": "#444444"}]
                },
                {
                    "featureType": "landscape",
                    "elementType": "all",
                    "stylers": [{"color": "#f2f2f2"}]
                },
                {
                    "featureType": "poi",
                    "elementType": "all",
                    "stylers": [{"visibility": "off"}]
                },
                {
                    "featureType": "road",
                    "elementType": "all",
                    "stylers": [{"saturation": -100}, {"lightness": 45}]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "all",
                    "stylers": [{"visibility": "simplified"}]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.icon",
                    "stylers": [{"visibility": "off"}]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [{"visibility": "off"}]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [{"color": "#46bcec"}, {"visibility": "on"}]
                }
            ]
        };
        
        freightMap = new google.maps.Map(document.getElementById('freightMap'), mapOptions);
        mapInitialized = true;
        
        // Adicionar listeners para eventos do mapa
        google.maps.event.addListenerOnce(freightMap, 'tilesloaded', function() {
            console.log('Mapa carregado com sucesso');
            $('.map-overlay').hide();
        });
        
        google.maps.event.addListenerOnce(freightMap, 'error', function() {
            console.error('Erro ao carregar o mapa');
            $('.map-overlay').hide();
            showToast('Erro ao carregar o mapa. Verifique sua conexão ou a chave da API.', 'danger');
        });
        
    } catch (e) {
        console.error('Erro ao inicializar o mapa:', e);
        $('.map-overlay').hide();
        showToast('Erro crítico ao carregar o mapa', 'danger');
    }
}

// Atualizar marcadores do mapa
function updateMapMarkers(freights) {
    try {
        console.log('Atualizando marcadores do mapa. Total de fretes:', freights.length);
        
        // Verificar se o mapa foi inicializado
        if (!freightMap || !mapInitialized) {
            console.error('O mapa não foi inicializado corretamente');
            $('.map-overlay').hide();
            return;
        }
        
        // Limpar marcadores existentes
        mapMarkers.forEach(marker => marker.setMap(null));
        mapMarkers = [];
        
        // Se não houver fretes, sair
        if (!freights || freights.length === 0) {
            console.log('Nenhum frete para exibir no mapa');
            $('.map-overlay').hide();
            return;
        }
        
        const bounds = new google.maps.LatLngBounds();
        let hasValidMarkers = false;
        let validMarkersCount = 0;
        
        freights.forEach(freight => {
            // Verificar se as coordenadas existem e são válidas
            if (freight.start_lat && freight.start_lng && 
                !isNaN(freight.start_lat) && !isNaN(freight.start_lng)) {
                
                const lat = parseFloat(freight.start_lat);
                const lng = parseFloat(freight.start_lng);
                
                // Verificar se as coordenadas estão dentro de limites geográficos válidos
                if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    const position = new google.maps.LatLng(lat, lng);
                    bounds.extend(position);
                    
                    const marker = new google.maps.Marker({
                        position: position,
                        map: freightMap,
                        title: `Frete #${freight.id}`,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            fillColor: getStatusColor(freight.status_id),
                            fillOpacity: 0.9,
                            strokeColor: '#fff',
                            strokeWeight: 1,
                            scale: 8
                        }
                    });
                    
                    // Criar conteúdo do info window
                    const contentString = `
                        <div class="freight-popup">
                            <h6 class="fw-bold mb-1">Frete #${freight.id}</h6>
                            <p class="mb-1"><small>Status: ${freight.freight_status?.name || 'N/A'}</small></p>
                            <p class="mb-1"><small>Origem: ${freight.start_address || 'N/A'}</small></p>
                            <p class="mb-1"><small>Destino: ${freight.destination_address || 'N/A'}</small></p>
                            <p class="mb-0"><small>Valor: ${formatCurrency(freight.freight_value)}</small></p>
                        </div>
                    `;
                    
                    const infowindow = new google.maps.InfoWindow({
                        content: contentString
                    });
                    
                    marker.addListener('click', () => {
                        infowindow.open(freightMap, marker);
                    });
                    
                    mapMarkers.push(marker);
                    hasValidMarkers = true;
                    validMarkersCount++;
                } else {
                    console.warn(`Coordenadas inválidas para frete ${freight.id}: lat=${lat}, lng=${lng}`);
                }
            } else {
                console.warn(`Frete ${freight.id} não possui coordenadas válidas`, freight);
            }
        });
        
        console.log(`Marcadores válidos adicionados: ${validMarkersCount}`);
        
        if (hasValidMarkers) {
            try {
                // Ajustar o zoom para mostrar todos os marcadores
                freightMap.fitBounds(bounds, { 
                    padding: 50,
                    maxZoom: 15 // Limite máximo de zoom para evitar zoom excessivo
                });
                
                // Se houver apenas um marcador, centralizar nele com zoom padrão
                if (validMarkersCount === 1) {
                    freightMap.setCenter(bounds.getCenter());
                    freightMap.setZoom(10);
                }
            } catch (e) {
                console.error('Erro ao ajustar bounds do mapa:', e);
                // Centralizar no Brasil como fallback
                freightMap.setCenter({ lat: -15.7889, lng: -47.8792 });
                freightMap.setZoom(4);
            }
        } else {
            console.log('Nenhum marcador válido para exibir. Centralizando no Brasil.');
            // Centralizar no Brasil se nenhum marcador válido
            freightMap.setCenter({ lat: -15.7889, lng: -47.8792 });
            freightMap.setZoom(4);
        }
        
        $('.map-overlay').hide();
        
    } catch (e) {
        console.error('Erro ao atualizar marcadores do mapa:', e);
        $('.map-overlay').hide();
        showToast('Erro ao atualizar o mapa', 'danger');
    }
}

// Obter cor baseada no status
function getStatusColor(statusId) {
    const colors = {
        1: '#4e73df',  // Pendente (azul)
        2: '#f6c23e',  // Em andamento (amarelo)
        3: '#1cc88a',  // Concluído (verde)
        4: '#e74a3b',  // Cancelado (vermelho)
        5: '#36b9cc'   // Outros (ciano)
    };
    
    return colors[statusId] || '#858796'; // Cinza para status desconhecido
}

// Obter classe de cor baseada no status (para badges)
function getStatusColorClass(statusId) {
    const colors = {
        1: 'bg-primary',  // Pendente
        2: 'bg-warning',  // Em andamento
        3: 'bg-success',  // Concluído
        4: 'bg-danger',   // Cancelado
        5: 'bg-info'      // Outros
    };
    
    return colors[statusId] || 'bg-secondary';
}

// Atualizar cards de resumo
function updateSummaryCards(data) {
    $('#total-freights').text(data.total_freights || 0);
    $('#in-progress').text(data.in_progress || 0);
    $('#pending').text(data.pending || 0);
    $('#total-value').text(formatCurrency(data.total_value || 0));
}

// Carregar detalhes do frete via AJAX
function loadFreightDetails(freightId) {
    const modal = $('#freightDetailsModal');
    const content = $('#freightDetailsContent');
    
    content.html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
    
    modal.modal('show');
    
    $.ajax({
        url: `/freights/${freightId}/details`,
        method: 'GET',
        success: function(data) {
            content.html(data);
        },
        error: function(xhr) {
            content.html(`
                <div class="alert alert-danger">
                    Erro ao carregar detalhes do frete. Por favor, tente novamente.
                </div>
            `);
        }
    });
}

// Mostrar detalhes do motorista e caminhão
function showDriverTruckDetails(freightsDriverId) {
    const modal = $('#driverTruckModal');
    const driverContent = $('#driverDetails');
    const truckContent = $('#truckDetails');
    const implementsContent = $('#implementsDetails');
    
    // Mostrar loaders
    driverContent.html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>');
    truckContent.html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>');
    implementsContent.html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>');
    
    modal.modal('show');
    
    $.ajax({
        url: `/freights/driver-truck-details/${freightsDriverId}`,
        method: 'GET',
        success: function(data) {
            // Atualizar informações do motorista
            if (data.driver) {
                driverContent.html(`
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="${data.driver.face_photo_url || '/images/default-user.png'}" 
                                 class="img-thumbnail mb-3" 
                                 alt="Foto do Motorista"
                                 style="max-width: 150px;">
                        </div>
                        <div class="col-md-8">
                            <h5>${data.driver.name}</h5>
                            <p><strong>CPF:</strong> ${data.driver.cpf || 'Não informado'}</p>
                            <p><strong>CNH:</strong> ${data.driver.driver_license_number || 'Não informado'}</p>
                            <p><strong>Validade CNH:</strong> ${formatDateBR(data.driver.driver_license_expiration) || 'Não informada'}</p>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <img src="${data.driver.driver_license_front_url || '/images/default-license.jpg'}" 
                                         class="img-thumbnail" 
                                         alt="CNH Frente">
                                </div>
                                <div class="col-md-6">
                                    <img src="${data.driver.driver_license_back_url || '/images/default-license.jpg'}" 
                                         class="img-thumbnail" 
                                         alt="CNH Verso">
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
            
            // Atualizar informações do caminhão
            if (data.truck) {
                truckContent.html(`
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Placa:</strong> ${data.truck.license_plate || 'Não informada'}</p>
                            <p><strong>Modelo:</strong> ${data.truck.model || 'Não informado'}</p>
                            <p><strong>Ano:</strong> ${data.truck.year || 'Não informado'}</p>
                            <p><strong>Cor:</strong> ${data.truck.color || 'Não informada'}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <img src="${data.truck.front_photo_full_url || '/images/default-truck.jpg'}" 
                                         class="img-thumbnail" 
                                         alt="Foto Dianteira">
                                </div>
                                <div class="col-6 mb-2">
                                    <img src="${data.truck.rear_photo_full_url || '/images/default-truck.jpg'}" 
                                         class="img-thumbnail" 
                                         alt="Foto Traseira">
                                </div>
                                <div class="col-6">
                                    <img src="${data.truck.crv_photo_full_url || '/images/default-document.jpg'}" 
                                         class="img-thumbnail" 
                                         alt="CRV">
                                </div>
                                <div class="col-6">
                                    <img src="${data.truck.crlv_photo_full_url || '/images/default-document.jpg'}" 
                                         class="img-thumbnail" 
                                         alt="CRLV">
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
            
            // Atualizar informações dos implementos
            if (data.implements && data.implements.length > 0) {
                let implementsHtml = '<div class="d-flex flex-wrap">';
                data.implements.forEach(implement => {
                    implementsHtml += `
                        <div class="me-3 mb-3 text-center">
                            <img src="${implement.photo_url || '/images/default-implement.jpg'}" 
                                 class="implement-photo img-thumbnail" 
                                 alt="${implement.type}">
                            <p class="mb-0"><strong>${implement.type}</strong></p>
                        </div>
                    `;
                });
                implementsHtml += '</div>';
                implementsContent.html(implementsHtml);
            } else {
                implementsContent.html('<p class="text-muted">Nenhum implemento cadastrado</p>');
            }
        },
        error: function(xhr) {
            showToast('Erro ao carregar detalhes do motorista/caminhão', 'danger');
            console.error('Error loading driver/truck details:', xhr.responseText);
        }
    });
}

// Formatar detalhes expandidos
function formatFreightDetails(d) {
    return `
        <div class="row px-4 py-3 bg-light rounded-3 mx-1 my-2">
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Detalhes do Frete</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <small class="text-muted">Tipo de Caminhão</small>
                                <p class="mb-0 fw-semibold">${d.truck_type_name || 'N/A'}</p>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Distância</small>
                                <p class="mb-0 fw-semibold">${d.distance_km || '0'} km</p>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Tempo Estimado</small>
                                <p class="mb-0 fw-semibold">${d.duration || 'N/A'}</p>
                            </div>
                            <div class="col-6 mb-2">
                                <small class="text-muted">Valor Motorista</small>
                                <p class="mb-0 fw-semibold">${formatCurrency(d.driver_freight_value) || 'R$ 0,00'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Instruções</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Carregamento</small>
                            <p class="mb-0">${d.loading_instructions || 'Nenhuma instrução'}</p>
                        </div>
                        <div>
                            <small class="text-muted">Descarga</small>
                            <p class="mb-0">${d.unloading_instructions || 'Nenhuma instrução'}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Descrição</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">${d.freight_description || 'Nenhuma descrição disponível'}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Inicializar DataTable
function initDataTable() {
    return $('#freights-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('freights.dashboard') }}",
            type: "GET",
            data: function(d) {
                d.status_filter = $('#statusFilterDropdown').data('filter') || 'all';
                d.map_filter = $('#mapFilterDropdown').data('filter') || 'all';
            },
            dataSrc: function(json) {
                // Atualizar dados do dashboard
                if (json.summary) {
                    updateSummaryCards(json.summary);
                }
                
                if (json.data) {
                    updateMapMarkers(json.data);
                }
                
                // Atualizar contagem de registros
                $('#showing-entries').text(json.recordsFiltered || 0);
                $('#total-entries').text(json.recordsTotal || 0);
                
                return json.data;
            },
            error: function(xhr) {
                showToast('Erro ao carregar dados da tabela', 'danger');
                $('.table-overlay').hide();
            }
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i>Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 8]
                }
            }
        ],
        order: [[1, 'desc']],
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
                className: 'ps-4 fw-semibold',
                orderable: true,
            },
            { 
                data: 'company.name',
                name: 'company_id',
                render: data => data || 'N/A'
            },
            { 
                data: 'freight_status',
                name: 'status_id',
                render: (data, type, row) => {
                    return `<span class="badge ${getStatusColorClass(data.id)} bg-opacity-10 text-${getStatusColorClass(data.id).replace('bg-', '')}">
                        ${data.name}
                    </span>`;
                }
            },
            { 
                data: 'start_address',
                render: data => data || 'N/A'
            },
            { 
                data: 'destination_address',
                render: data => data || 'N/A'
            },
            { 
                data: 'pickup_date',
                render: data => formatDateTimeBR(data) || 'Não agendado'
            },
            { 
                data: 'delivery_date',
                render: data => formatDateTimeBR(data) || 'Não agendado'
            },
            { 
                data: 'freight_value',
                render: data => formatCurrency(data) || 'R$ 0,00'
            },
            { 
                data: 'id',
                orderable: false,
                searchable: false,
                className: 'text-end pe-4',
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary view-freight" data-id="${data}">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="/freights/${data}/edit" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    `;
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
        },
        initComplete: function() {
            $('.table-overlay').hide();
        },
        drawCallback: function() {
            $('.table-overlay').hide();
        }
    });
}

// Inicializar a aplicação
function initializeApp() {
    initCharts();
    table = initDataTable();
    loadChartData();
    
    // Carregar a API do Google Maps
    loadGoogleMapsAPI()
        .then(() => {
            console.log('API do Google Maps carregada com sucesso');
        })
        .catch(error => {
            console.error('Erro ao carregar a API do Google Maps:', error);
        });
}

// Inicializar quando o documento estiver pronto
$(document).ready(function() {
    initializeApp();
    
    // Evento para expandir/recolher detalhes
    $('#freights-table tbody').on('click', 'td.dt-control', function() {
        const tr = $(this).closest('tr');
        const row = table.row(tr);
        
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(formatFreightDetails(row.data())).show();
            tr.addClass('shown');
        }
    });
    
    // Pesquisa personalizada
    $('#freightSearch').keyup(function() {
        table.search(this.value).draw();
    });
    
    // Recarregar tabela
    $('#reload-freights').click(function() {
        $('.table-overlay').show();
        table.ajax.reload(null, false);
    });
    
    // Recarregar gráficos
    $('.refresh-chart').click(function() {
        const chartType = $(this).data('chart');
        $(this).find('i').addClass('fa-spin');
        
        if (chartType === 'status') {
            $('.card-body:has(#statusChart) .chart-overlay').show();
        } else {
            $('.card-body:has(#monthlyChart) .chart-overlay').show();
        }
        
        loadChartData();
        
        setTimeout(() => {
            $(this).find('i').removeClass('fa-spin');
        }, 1000);
    });
    
    // Recarregar mapa
    $('.refresh-map').click(function() {
        $(this).find('i').addClass('fa-spin');
        $('.map-overlay').show();
        
        table.ajax.reload(() => {
            $(this).find('i').removeClass('fa-spin');
        }, false);
    });
    
    // Filtro por status
    $(document).on('click', '.filter-status', function(e) {
        e.preventDefault();
        const filter = $(this).data('filter');
        
        // Atualizar UI
        $('.filter-status').removeClass('active');
        $(this).addClass('active');
        $('#statusFilterDropdown').html(`<i class="fas fa-filter me-1"></i>${$(this).text()}`);
        
        // Aplicar filtro
        $('#statusFilterDropdown').data('filter', filter);
        $('.table-overlay').show();
        table.ajax.reload();
    });
    
    // Filtro do mapa
    $(document).on('click', '.filter-map', function(e) {
        e.preventDefault();
        const filter = $(this).data('filter');
        
        // Atualizar UI
        $('.filter-map').removeClass('active');
        $(this).addClass('active');
        $('#mapFilterDropdown').html(`<i class="fas fa-filter me-1"></i>${$(this).text()}`);
        
        // Aplicar filtro
        $('#mapFilterDropdown').data('filter', filter);
        $('.table-overlay').show();
        table.ajax.reload();
    });
    
    // Visualizar detalhes do frete
    $(document).on('click', '.view-freight', function() {
        const freightId = $(this).data('id');
        loadFreightDetails(freightId);
    });
    
    // Exportar para Excel
    $('#export-excel').click(function() {
        table.button('.buttons-excel').trigger();
    });
    
    // Função global para mostrar detalhes do motorista/caminhão
    window.detailsDriverTruck = function(freightsDriverId) {
        showDriverTruckDetails(freightsDriverId);
    };
    
    // Funções globais para aprovação/reprovação
    window.aprovar = function(freightsDriverId, statusId) {
        if (confirm('Tem certeza que deseja aprovar este motorista?')) {
            updateFreightDriverStatus(freightsDriverId, statusId);
        }
    };
    
    window.reprovar = function(freightsDriverId, statusId) {
        if (confirm('Tem certeza que deseja recusar este motorista?')) {
            updateFreightDriverStatus(freightsDriverId, statusId);
        }
    };
    
    function updateFreightDriverStatus(freightsDriverId, statusId) {
        $.ajax({
            url: `/freights-driver/${freightsDriverId}/update-status`,
            method: 'POST',
            data: {
                status_id: statusId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showToast(response.message, 'success');
                    table.ajax.reload(null, false);
                } else {
                    showToast('Erro ao atualizar status', 'danger');
                }
            },
            error: function(xhr) {
                showToast('Erro ao atualizar status', 'danger');
                console.error('Error updating status:', xhr.responseText);
            }
        });
    }
});
</script>
@endpush