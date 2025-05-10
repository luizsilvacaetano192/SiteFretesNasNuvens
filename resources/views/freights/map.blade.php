@extends('layouts.app')

@section('title', 'Rota e Histórico do Frete')

@section('content')
<div class="container-fluid px-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-map-marked-alt me-2"></i>Rota do Frete #{{ $freight->id }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('freights.index') }}">Fretes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rota</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('freights.show', $freight->id) }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Voltar para Frete
            </a>
            <button id="export-route" class="btn btn-primary">
                <i class="fas fa-file-pdf me-1"></i>Exportar Rota
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Coluna do Mapa -->
        <div class="col-lg-8">
            <!-- Card do Mapa -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-map me-2"></i>Mapa da Rota
                    </h6>
                    <div class="btn-group">
                        <button id="map-type-road" class="btn btn-sm btn-outline-secondary active">
                            <i class="fas fa-road"></i> Rodovia
                        </button>
                        <button id="map-type-satellite" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-satellite"></i> Satélite
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="map-container" style="position: relative;">
                        <!-- Controles do Mapa -->
                        <div id="map-controls" class="position-absolute top-0 end-0 mt-2 me-2" style="z-index: 1000;">
                            <div class="btn-group-vertical shadow-sm">
                                <button id="track-toggle" class="btn btn-sm btn-primary">
                                    <i class="fas fa-lock"></i> Travar Mapa
                                </button>
                                <button id="zoom-toggle" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search-plus"></i> Zoom
                                </button>
                                <button id="center-route" class="btn btn-sm btn-primary">
                                    <i class="fas fa-expand"></i> Ver Rota
                                </button>
                            </div>
                        </div>
                        
                        <!-- Informações de Localização -->
                        <div id="location-info" class="p-3 bg-light border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>📍 Posição atual:</strong> 
                                    <span id="current-position">
                                        @php
                                            $lastLocation = $freight->history()
                                                ->orderBy('date', 'desc')
                                                ->first();
                                        @endphp
                                        {{ $lastLocation->address ?? 'Não disponível' }}
                                    </span>
                                    <span id="updating-indicator" class="d-none ms-2">
                                        <i class="fas fa-sync-alt fa-spin"></i> Atualizando...
                                    </span>
                                </div>
                                <div>
                                    <strong>🔄 Atualizado em:</strong> 
                                    <span id="last-update">
                                        @if($lastLocation)
                                            {{ \Carbon\Carbon::parse($lastLocation->date)->format('d/m/Y H:i:s') }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mapa -->
                        <div id="map" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna do Histórico -->
        <div class="col-lg-4">
            <!-- Card de Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Status do Frete
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-{{ $statusBadgeClass }} p-2">
                                <i class="fas fa-truck me-1"></i> {{ $freight->freightStatus->name }}
                            </span>
                        </div>
                    
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Informações</h6>
                                <p class="mb-1"><strong>Empresa:</strong> {{ $freight->company->name }}</p>
                                <p class="mb-1"><strong>Motorista:</strong> {{ $freight->driver->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Veículo:</strong> {{ $freight->truck_plate ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Datas</h6>
                                <p class="mb-1"><strong>Início:</strong> {{ $freight->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-1"><strong>Coleta:</strong> {{ $freight->pickup_date ? $freight->pickup_date->format('d/m/Y H:i') : 'N/A' }}</p>
                                <p class="mb-1"><strong>Entrega:</strong> {{ $freight->delivery_date ? $freight->delivery_date->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Histórico -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Histórico de Localização
                    </h6>
                    <button id="refresh-history" class="btn btn-sm btn-primary">
                        <i class="fas fa-sync-alt me-1"></i> Atualizar
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="history-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Localização</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($freight->history()->orderBy('date', 'desc')->get() as $location)
                                <tr data-lat="{{ $location->latitude }}" data-lng="{{ $location->longitude }}">
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($location->date)->format('d/m/Y') }}</small><br>
                                        <small>{{ \Carbon\Carbon::parse($location->time)->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;" title="{{ $location->address }}">
                                            {{ $location->address }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $location->status === 'em_transito' ? 'info' : ($location->status === 'entregue' ? 'success' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $location->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">Nenhum registro de localização encontrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-2">
                    <small class="text-muted">Mostrando {{ $freight->history()->count() }} registros</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<style>
    #map { 
        width: 100%;
        height: 100%;
        min-height: 500px;
    }
    
    .leaflet-control-layers-toggle {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-layers' viewBox='0 0 16 16'%3E%3Cpath d='M8.235 1.559a.5.5 0 0 0-.47 0l-7.5 4a.5.5 0 0 0 0 .882L3.188 8 .264 9.559a.5.5 0 0 0 0 .882l7.5 4a.5.5 0 0 0 .47 0l7.5-4a.5.5 0 0 0 0-.882L12.813 8l2.922-1.559a.5.5 0 0 0 0-.882l-7.5-4zM8 9.433 1.562 6 8 2.567 14.438 6 8 9.433z'/%3E%3C/svg%3E") !important;
    }
    
    .leaflet-routing-container {
        background-color: white;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.4);
        max-height: 300px;
        overflow-y: auto;
    }
    
    .leaflet-routing-alt {
        display: none;
    }
    
    .leaflet-control-locate a {
        font-size: 1.4em;
        color: #444;
    }
    
    .leaflet-touch .leaflet-bar a {
        width: 30px;
        height: 30px;
        line-height: 30px;
    }
    
    #history-table tbody tr {
        cursor: pointer;
    }
    
    #history-table tbody tr:hover {
        background-color: rgba(0,0,0,0.03);
    }
    
    #updating-indicator {
        font-size: 0.8rem;
        color: #4e73df;
    }
    
    @media (max-width: 992px) {
        #map {
            min-height: 400px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
// Configurações iniciais
const UPDATE_INTERVAL = 10000; // 10 segundos
const ZOOM_DEFAULT = 12;
const ZOOM_CLOSE = 15;
let map, routingControl;
let currentMarker, routePolyline;
let isTracking = true;
let isSatelliteView = false;
let lastPosition = null;
let updateInterval;
let historyTable;

// Inicialização do mapa
function initMap() {
    // Coordenadas padrão (centro do Brasil)
    const defaultCenter = [-15.7801, -47.9292];
    
    // Criar o mapa
    map = L.map('map', {
        center: defaultCenter,
        zoom: ZOOM_DEFAULT,
        zoomControl: false
    });

    // Adicionar camadas base
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    });

    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    });

    osmLayer.addTo(map);
    
    // Adicionar controle de camadas
    const baseLayers = {
        "Mapa": osmLayer,
        "Satélite": satelliteLayer
    };
    
    L.control.layers(baseLayers, null, {position: 'topright'}).addTo(map);
    
    // Adicionar controle de zoom personalizado
    L.control.zoom({
        position: 'topright'
    }).addTo(map);
    
    // Configurar rota
    initRoute();
    
    // Configurar eventos
    setupMapEvents();
    
    // Inicializar tabela de histórico
    initHistoryTable();
    
    // Iniciar atualização automática
    startAutoUpdate();
}

// Inicializar a rota
function initRoute() {
    @if($freight->start_lat && $freight->start_lng && $freight->destination_lat && $freight->destination_lng)
        const startPoint = L.latLng({{ $freight->start_lat }}, {{ $freight->start_lng }});
        const endPoint = L.latLng({{ $freight->destination_lat }}, {{ $freight->destination_lng }});
        
        // Adicionar marcadores de origem e destino
        L.marker(startPoint, {
            icon: L.divIcon({
                html: '<i class="fas fa-map-marker-alt fa-2x text-success"></i>',
                iconSize: [30, 30],
                className: 'my-html-icon'
            })
        }).addTo(map).bindPopup("Origem: {{ $freight->start_address }}");
        
        L.marker(endPoint, {
            icon: L.divIcon({
                html: '<i class="fas fa-map-marker-alt fa-2x text-danger"></i>',
                iconSize: [30, 30],
                className: 'my-html-icon'
            })
        }).addTo(map).bindPopup("Destino: {{ $freight->destination_address }}");
        
        // Configurar roteamento
        routingControl = L.Routing.control({
            waypoints: [startPoint, endPoint],
            routeWhileDragging: false,
            show: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: false,
            lineOptions: {
                styles: [{color: '#4e73df', opacity: 0.8, weight: 5}]
            },
            createMarker: function() { return null; }
        }).addTo(map);
        
        // Ajustar visualização para mostrar toda a rota
        setTimeout(() => {
            const bounds = L.latLngBounds([startPoint, endPoint]);
            map.fitBounds(bounds, {padding: [50, 50]});
        }, 500);
    @endif
    
    // Adicionar marcador da posição atual
    @if($lastLocation = $freight->history()->orderBy('date', 'desc')->first())
        lastPosition = L.latLng({{ $lastLocation->latitude }}, {{ $lastLocation->longitude }});
        updateCurrentPosition(lastPosition, "{{ $lastLocation->address }}");
    @endif
}

// Atualizar posição atual
function updateCurrentPosition(position, address) {
    // Remover marcador anterior se existir
    if (currentMarker) {
        map.removeLayer(currentMarker);
    }
    
    // Criar novo marcador
    currentMarker = L.marker(position, {
        icon: L.divIcon({
            html: '<i class="fas fa-truck fa-2x text-primary"></i>',
            iconSize: [30, 30],
            className: 'my-html-icon'
        }),
        zIndexOffset: 1000
    }).addTo(map);
    
    // Atualizar informações na interface
    $('#current-position').text(address || 'Não disponível');
    $('#last-update').text(new Date().toLocaleString('pt-BR'));
    
    // Centralizar no marcador se o rastreamento estiver ativado
    if (isTracking) {
        map.setView(position, map.getZoom());
    }
    
    lastPosition = position;
}

// Configurar eventos do mapa
function setupMapEvents() {
    // Botão de alternar rastreamento
    $('#track-toggle').click(function() {
        isTracking = !isTracking;
        $(this).html(isTracking ? 
            '<i class="fas fa-lock"></i> Travar Mapa' : 
            '<i class="fas fa-lock-open"></i> Acompanhar');
        
        if (isTracking && lastPosition) {
            map.setView(lastPosition, map.getZoom());
        }
    });
    
    // Botão de alternar zoom
    $('#zoom-toggle').click(function() {
        if (map.getZoom() >= ZOOM_CLOSE) {
            map.setZoom(ZOOM_DEFAULT);
            $(this).html('<i class="fas fa-search-plus"></i> Zoom');
        } else {
            map.setZoom(ZOOM_CLOSE);
            $(this).html('<i class="fas fa-search-minus"></i> Zoom');
        }
    });
    
    // Botão de centralizar rota
    $('#center-route').click(function() {
        if (routingControl) {
            const waypoints = routingControl.getWaypoints();
            if (waypoints.length >= 2) {
                const bounds = L.latLngBounds([
                    waypoints[0].latLng, 
                    waypoints[waypoints.length - 1].latLng
                ]);
                map.fitBounds(bounds, {padding: [50, 50]});
            }
        }
    });
    
    // Botão de alternar tipo de mapa
    $('#map-type-road').click(function() {
        if (isSatelliteView) {
            map.eachLayer(layer => {
                if (layer.options && layer.options.attribution && 
                    layer.options.attribution.includes('OpenStreetMap')) {
                    layer.bringToFront();
                }
            });
            isSatelliteView = false;
            $(this).addClass('active');
            $('#map-type-satellite').removeClass('active');
        }
    });
    
    $('#map-type-satellite').click(function() {
        if (!isSatelliteView) {
            map.eachLayer(layer => {
                if (layer.options && layer.options.attribution && 
                    layer.options.attribution.includes('Esri')) {
                    layer.bringToFront();
                }
            });
            isSatelliteView = true;
            $(this).addClass('active');
            $('#map-type-road').removeClass('active');
        }
    });
    
    // Botão de exportar rota
    $('#export-route').click(function() {
        exportMapToPDF();
    });
    
    // Botão de atualizar histórico
    $('#refresh-history').click(function() {
        updateHistory();
    });
}

// Inicializar tabela de histórico
function initHistoryTable() {
    historyTable = $('#history-table').DataTable({
        order: [[0, 'desc']],
        pageLength: 10,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        columnDefs: [
            { targets: [2], orderable: false }
        ]
    });
    
    // Evento de clique nas linhas da tabela
    $('#history-table tbody').on('click', 'tr', function() {
        const lat = $(this).data('lat');
        const lng = $(this).data('lng');
        if (lat && lng) {
            map.setView([lat, lng], ZOOM_CLOSE);
        }
    });
}

// Atualizar histórico via AJAX
function updateHistory() {
    $('#refresh-history').html('<i class="fas fa-spinner fa-spin me-1"></i> Atualizando');
    
    $.get('{{ route("freights.history", $freight->id) }}', function(data) {
        historyTable.clear();
        
        data.forEach(item => {
            historyTable.row.add([
                `<small>${new Date(item.date).toLocaleDateString('pt-BR')}</small><br>
                 <small>${new Date(item.time).toLocaleTimeString('pt-BR')}</small>`,
                `<div class="text-truncate" style="max-width: 150px;" title="${item.address}">
                    ${item.address}
                 </div>`,
                `<span class="badge bg-${item.status === 'em_transito' ? 'info' : (item.status === 'entregue' ? 'success' : 'warning')}">
                    ${item.status.replace('_', ' ')}
                </span>`
            ]).nodes().to$().attr('data-lat', item.latitude).attr('data-lng', item.longitude);
        });
        
        historyTable.draw();
        $('#refresh-history').html('<i class="fas fa-sync-alt me-1"></i> Atualizar');
        
        // Atualizar última posição se houver dados
        if (data.length > 0) {
            const last = data[0];
            lastPosition = L.latLng(last.latitude, last.longitude);
            updateCurrentPosition(lastPosition, last.address);
        }
    }).fail(() => {
        $('#refresh-history').html('<i class="fas fa-sync-alt me-1"></i> Atualizar');
        alert('Erro ao atualizar histórico');
    });
}

// Iniciar atualização automática
function startAutoUpdate() {
    updateInterval = setInterval(() => {
        $('#updating-indicator').removeClass('d-none');
        
        $.get('{{ route("freights.last-position", $freight->id) }}', function(data) {
            if (data.latitude && data.longitude) {
                const position = L.latLng(data.latitude, data.longitude);
                updateCurrentPosition(position, data.address);
            }
            $('#updating-indicator').addClass('d-none');
        }).fail(() => {
            $('#updating-indicator').addClass('d-none');
        });
    }, UPDATE_INTERVAL);
}

// Exportar mapa para PDF
function exportMapToPDF() {
    const { jsPDF } = window.jspdf;
    const mapContainer = document.getElementById('map-container');
    
    $('#export-route').html('<i class="fas fa-spinner fa-spin me-1"></i> Gerando...');
    
    html2canvas(mapContainer, {
        scale: 2,
        logging: false,
        useCORS: true,
        allowTaint: true
    }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new jsPDF('landscape');
        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
        
        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save(`rota-frete-{{ $freight->id }}.pdf`);
        $('#export-route').html('<i class="fas fa-file-pdf me-1"></i> Exportar Rota');
    }).catch(err => {
        console.error(err);
        alert('Erro ao exportar mapa');
        $('#export-route').html('<i class="fas fa-file-pdf me-1"></i> Exportar Rota');
    });
}

// Inicializar o mapa quando a página carregar
$(document).ready(function() {
    initMap();
    
    // Parar atualização quando a página for fechada
    $(window).on('beforeunload', function() {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
    });
});
</script>
@endpush