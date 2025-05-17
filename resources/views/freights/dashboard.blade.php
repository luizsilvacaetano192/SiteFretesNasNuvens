@extends('layouts.app')

@section('title', 'Dashboard de Fretes')

@section('content')
<div class="container-fluid px-4">
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
    <div class="row mb-4">
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
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-pie me-2"></i>Status dos Fretes
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4 rounded-3 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-chart-line me-2"></i>Fretes por Mês
                    </h5>
                </div>
                <div class="card-body">
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
                <div class="card-body p-0" style="height: 400px;">
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
                
                <div class="card-body p-0">
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
                        <button id="reload-freights" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sync-alt me-1"></i>Recarregar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    /* Estilos para os cards */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
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
    
    /* Estilos para a tabela */
    #freights-table tbody tr {
        transition: all 0.2s ease;
    }
    
    #freights-table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }
    
    /* Estilos para o mapa */
    .leaflet-container {
        z-index: 1;
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
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
$(document).ready(function() {
    // Variáveis globais
    let statusChart, monthlyChart, freightMap, mapMarkers = [];
    
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
        return 'R$ ' + parseFloat(value).toFixed(2).replace('.', ',').replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
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
    
    // Inicializar mapa
    function initMap() {
        freightMap = L.map('freightMap').setView([-15.7889, -47.8792], 4);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(freightMap);
    }
    
    // Atualizar marcadores do mapa
    function updateMapMarkers(freights) {
        // Limpar marcadores existentes
        mapMarkers.forEach(marker => freightMap.removeLayer(marker));
        mapMarkers = [];
        
        // Adicionar novos marcadores
        freights.forEach(freight => {
            if (freight.start_lat && freight.start_lng) {
                const marker = L.marker([freight.start_lat, freight.start_lng], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: `<div class="marker-pin ${getStatusColorClass(freight.status_id)}"></div>`,
                        iconSize: [30, 42],
                        iconAnchor: [15, 42]
                    })
                }).addTo(freightMap);
                
                marker.bindPopup(`
                    <div class="freight-popup">
                        <h6 class="fw-bold mb-1">Frete #${freight.id}</h6>
                        <p class="mb-1"><small>Status: ${freight.freight_status.name}</small></p>
                        <p class="mb-1"><small>Origem: ${freight.start_address}</small></p>
                        <p class="mb-1"><small>Destino: ${freight.destination_address}</small></p>
                        <p class="mb-0"><small>Valor: ${formatCurrency(freight.freight_value)}</small></p>
                    </div>
                `);
                
                mapMarkers.push(marker);
            }
        });
        
        // Ajustar o zoom para mostrar todos os marcadores
        if (mapMarkers.length > 0) {
            const group = new L.featureGroup(mapMarkers);
            freightMap.fitBounds(group.getBounds(), { padding: [50, 50] });
        }
    }
    
    // Obter classe de cor baseada no status
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
        console.log('data', data);
        $('#total-freights').text(data.total_freights);
        $('#in-progress').text(data.in_progress);
        $('#pending').text(data.pending);
        $('#total-value').text(formatCurrency(data.total_value));
    }
    
    // Atualizar gráficos
    function updateCharts(data) {
        // Gráfico de status
        statusChart.data.labels = data.status_chart.labels;
        statusChart.data.datasets[0].data = data.status_chart.data;
        statusChart.update();
        
        // Gráfico mensal
        monthlyChart.data.datasets[0].data = data.monthly_chart.data;
        monthlyChart.update();
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
                    console.log('json', json);
                    // Atualizar dados do dashboard
                    updateSummaryCards(json.summary);
                    updateCharts(json.charts);
                    
                    if (json.freights && json.freights.data) {
                        updateMapMarkers(json.freights.data);
                    }
                    
                    // Atualizar contagem de registros
                    $('#showing-entries').text(json.freights.recordsFiltered);
                    $('#total-entries').text(json.freights.recordsTotal);
                    
                    return json.freights.data;
                }
            },
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
              
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
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
    
    // Inicializar tudo
    initCharts();
    initMap();
    const table = initDataTable();
    
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
        table.ajax.reload(null, false);
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
        table.ajax.reload();
    });
});
</script>
@endpush