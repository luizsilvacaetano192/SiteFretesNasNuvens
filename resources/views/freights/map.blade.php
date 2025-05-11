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
        <div class="col-lg-6">
            <!-- Card do Mapa -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-map me-2"></i>Mapa da Rota
                    </h6>
                    <div class="btn-group">
                        <button id="map-type-road" class="btn btn-sm btn-outline-secondary active">
                            <i class="fas fa-road"></i> Padrão
                        </button>
                        <button id="map-type-satellite" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-satellite"></i> Satélite
                        </button>
                        <button id="map-type-hybrid" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-layer-group"></i> Híbrido
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="map-container">
                        <!-- Informações de Localização -->
                        <div id="location-info" class="p-3 bg-light border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>📍 Posição atual:</strong> 
                                    <span id="current-position">
                                        @php
                                            $lastLocation = $freight->history()
                                                ->orderBy('date', 'desc')
                                                ->orderBy('time', 'desc')
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
                                        @if($lastLocation && $lastLocation->date && $lastLocation->time)
                                            {{ \Carbon\Carbon::parse($lastLocation->date)->format('d/m/Y') }} às 
                                            {{ \Carbon\Carbon::parse($lastLocation->time)->format('H:i:s') }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Controles do Mapa -->
                        <div id="map-controls" class="d-flex justify-content-end pe-3 pt-2 bg-white">
                            <div class="btn-group shadow-sm">
                                <button id="track-toggle" class="btn btn-sm btn-primary">
                                    <i class="fas fa-lock"></i> Travar
                                </button>
                                <button id="zoom-toggle" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search-plus"></i> Zoom
                                </button>
                                <button id="center-route" class="btn btn-sm btn-primary">
                                    <i class="fas fa-expand"></i> Rota
                                </button>
                                <button id="toggle-auto-update" class="btn btn-sm btn-primary">
                                    <i class="fas fa-power-off"></i> Auto
                                </button>
                            </div>
                        </div>
                        
                        <!-- Mapa Google -->
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna do Histórico -->
        <div class="col-lg-6">
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
                                <p class="mb-1"><strong>Início:</strong> {{ $freight->created_at ? $freight->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
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
                    <div>
                        <button id="refresh-history" class="btn btn-sm btn-primary">
                            <i class="fas fa-sync-alt me-1"></i> Atualizar
                        </button>
                    </div>
                </div>
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="history-table">
                            <thead class="thead-light sticky-top">
                                <tr>
                                    <th width="20%">Data/Hora</th>
                                    <th width="70%">Localização</th>
                                    <th width="10%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($freight->history()->orderBy('date', 'desc')->orderBy('time', 'desc')->get() as $location)
                                <tr data-lat="{{ $location->latitude ?? 0 }}" data-lng="{{ $location->longitude ?? 0 }}">
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small>{{ $location->date ? \Carbon\Carbon::parse($location->date)->format('d/m/Y') : 'N/A' }}</small>
                                            <small>{{ $location->time ? \Carbon\Carbon::parse($location->time)->format('H:i:s') : 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="{{ $location->address ?? 'N/A' }}">
                                            {{ $location->address ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $location->status === 'em_transito' ? 'info' : ($location->status === 'entregue' ? 'success' : 'warning') }}">
                                            {{ $location->status ? ucfirst(str_replace('_', ' ', $location->status)) : 'N/A' }}
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<style>
    /* Estilos do Mapa */
    #map-container {
        display: flex;
        flex-direction: column;
        height: 550px;
        position: relative;
    }
    
    #location-info {
        order: 1;
        z-index: 10;
    }
    
    #map-controls {
        order: 2;
        background: white;
        padding: 5px 0;
        z-index: 10;
    }
    
    #map {
        order: 3;
        flex-grow: 1;
        width: 100%;
    }
    
    /* Estilos da Tabela */
    #history-table {
        width: 100% !important;
    }
    
    #history-table thead th {
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    #history-table tbody tr {
        cursor: pointer;
    }
    
    #history-table tbody tr:hover {
        background-color: rgba(0,0,0,0.03);
    }
    
    /* Estilos dos Botões */
    .btn-group-vertical .btn,
    .btn-group .btn {
        margin: 2px;
    }
    
    /* Indicador de Atualização */
    #updating-indicator {
        font-size: 0.8rem;
        color: #4e73df;
    }
    
    /* Janela de Informações do Mapa */
    .gm-style .gm-style-iw-c {
        padding: 12px !important;
        max-width: 300px !important;
    }
    
    /* Badges de Status */
    .badge.bg-info { background-color: #17a2b8 !important; }
    .badge.bg-success { background-color: #28a745 !important; }
    .badge.bg-warning { background-color: #ffc107 !important; color: #212529; }
    .badge.bg-danger { background-color: #dc3545 !important; }
    .badge.bg-secondary { background-color: #6c757d !important; }
    
    /* Responsividade */
    @media (max-width: 992px) {
        #map-container {
            height: 400px;
        }
        
        .col-lg-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        #map-controls .btn-group {
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        #map-controls .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCi-A7nNanHXhUiBS3_71XeLa6bE0aX9Ts&libraries=geometry"></script>

<script>
// Configurações globais
const UPDATE_INTERVAL = 30000; // 30 segundos
let map, directionsService, directionsRenderer;
let currentMarker, routePolyline;
let isTracking = true;
let isAutoUpdateActive = true;
let updateInterval;
let historyTable;
let lastPosition = null;

// Inicialização do Mapa
function initMap() {
    const defaultCenter = { lat: -15.7801, lng: -47.9292 }; // Centro do Brasil
    
    map = new google.maps.Map(document.getElementById('map'), {
        center: defaultCenter,
        zoom: 12,
        mapTypeId: 'roadmap',
        streetViewControl: false,
        fullscreenControl: false
    });
    
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
        preserveViewport: false,
        polylineOptions: {
            strokeColor: '#4e73df',
            strokeOpacity: 0.8,
            strokeWeight: 5
        }
    });
    directionsRenderer.setMap(map);
    
    initRoute();
    setupMapEvents();
    initHistoryTable();
    startAutoUpdate();
}

// Inicializar Rota
function initRoute() {
    @if($freight->start_lat && $freight->start_lng && $freight->destination_lat && $freight->destination_lng)
        const startPoint = { 
            lat: parseFloat({{ $freight->start_lat }}), 
            lng: parseFloat({{ $freight->start_lng }}) 
        };
        const endPoint = { 
            lat: parseFloat({{ $freight->destination_lat }}), 
            lng: parseFloat({{ $freight->destination_lng }}) 
        };
        
        // Marcadores de origem e destino
        new google.maps.Marker({
            position: startPoint,
            map: map,
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png",
                scaledSize: new google.maps.Size(30, 30)
            },
            title: "Origem: {{ $freight->start_address }}"
        });
        
        new google.maps.Marker({
            position: endPoint,
            map: map,
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                scaledSize: new google.maps.Size(30, 30)
            },
            title: "Destino: {{ $freight->destination_address }}"
        });
        
        calculateAndDisplayRoute(startPoint, endPoint);
    @endif
    
    @if($lastLocation = $freight->history()->orderBy('date', 'desc')->orderBy('time', 'desc')->first())
        const lat = parseFloat({{ $lastLocation->latitude ?? 0 }});
        const lng = parseFloat({{ $lastLocation->longitude ?? 0 }});
        
        if (!isNaN(lat) && !isNaN(lng)) {
            lastPosition = { lat, lng };
            updateCurrentPosition(lastPosition, "{{ $lastLocation->address ?? 'N/A' }}");
        }
    @endif
}

// Calcular e exibir rota
function calculateAndDisplayRoute(startPoint, endPoint) {
    directionsService.route({
        origin: startPoint,
        destination: endPoint,
        travelMode: 'DRIVING'
    }, (response, status) => {
        if (status === 'OK') {
            directionsRenderer.setDirections(response);
            
            try {
                const bounds = new google.maps.LatLngBounds();
                const route = response.routes[0];
                
                bounds.extend(startPoint);
                bounds.extend(endPoint);
                
                if (route.legs) {
                    route.legs.forEach(leg => {
                        if (leg.steps) {
                            leg.steps.forEach(step => {
                                if (step.path) {
                                    step.path.forEach(point => {
                                        bounds.extend(point);
                                    });
                                }
                            });
                        }
                    });
                }
                
                map.fitBounds(bounds, {top: 50, bottom: 50, left: 50, right: 50});
            } catch (error) {
                console.error('Erro ao ajustar rota:', error);
                map.setCenter(startPoint);
                map.setZoom(10);
            }
        } else {
            console.error('Falha ao calcular rota:', status);
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(startPoint);
            bounds.extend(endPoint);
            map.fitBounds(bounds);
        }
    });
}

// Atualizar posição atual
function updateCurrentPosition(position, address) {
    if (!position || typeof position.lat !== 'number' || typeof position.lng !== 'number') {
        console.error('Posição inválida:', position);
        return;
    }
    
    if (currentMarker) {
        currentMarker.setMap(null);
    }
    
    currentMarker = new google.maps.Marker({
        position: position,
        map: map,
        icon: {
            url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
            scaledSize: new google.maps.Size(30, 30)
        },
        title: "Posição atual: " + (address || 'Não disponível')
    });
    
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div class="p-2">
                <h6 class="mb-1">Posição Atual</h6>
                <p class="mb-1"><strong>Endereço:</strong> ${address || 'N/A'}</p>
                <p class="mb-1"><strong>Latitude:</strong> ${position.lat.toFixed(6)}</p>
                <p class="mb-0"><strong>Longitude:</strong> ${position.lng.toFixed(6)}</p>
            </div>
        `
    });
    
    infoWindow.open(map, currentMarker);
    setTimeout(() => infoWindow.close(), 5000);
    
    $('#current-position').text(address || 'N/A');
    $('#last-update').text(new Date().toLocaleString('pt-BR'));
    
    if (isTracking) {
        map.setCenter(position);
        map.setZoom(15);
    }
    
    lastPosition = position;
}

// Configurar eventos do mapa
function setupMapEvents() {
    $('#track-toggle').click(function() {
        isTracking = !isTracking;
        $(this).html(isTracking ? 
            '<i class="fas fa-lock"></i> Travar' : 
            '<i class="fas fa-lock-open"></i> Acompanhar');
        
        if (isTracking && lastPosition) {
            map.setCenter(lastPosition);
            map.setZoom(15);
        }
    });
    
    $('#zoom-toggle').click(function() {
        if (map.getZoom() >= 15) {
            map.setZoom(12);
            $(this).html('<i class="fas fa-search-plus"></i> Zoom');
        } else {
            map.setZoom(15);
            $(this).html('<i class="fas fa-search-minus"></i> Zoom');
        }
    });
    
    $('#center-route').click(function() {
        if (directionsRenderer.getDirections()) {
            try {
                const bounds = new google.maps.LatLngBounds();
                const route = directionsRenderer.getDirections().routes[0];
                
                route.legs.forEach(leg => {
                    leg.steps.forEach(step => {
                        step.path.forEach(point => {
                            bounds.extend(point);
                        });
                    });
                });
                
                map.fitBounds(bounds, {top: 50, bottom: 50, left: 50, right: 50});
            } catch (error) {
                console.error('Erro ao centralizar rota:', error);
            }
        }
    });
    
    $('#map-type-road').click(function() {
        map.setMapTypeId('roadmap');
        $(this).addClass('active').siblings().removeClass('active');
    });
    
    $('#map-type-satellite').click(function() {
        map.setMapTypeId('satellite');
        $(this).addClass('active').siblings().removeClass('active');
    });
    
    $('#map-type-hybrid').click(function() {
        map.setMapTypeId('hybrid');
        $(this).addClass('active').siblings().removeClass('active');
    });
    
    $('#export-route').click(exportMapToPDF);
    $('#refresh-history').click(updateHistory);
    
    $('#toggle-auto-update').click(function() {
        isAutoUpdateActive = !isAutoUpdateActive;
        $(this).toggleClass('btn-primary btn-outline-primary')
               .html(isAutoUpdateActive ? 
                   '<i class="fas fa-power-off"></i> Auto' : 
                   '<i class="fas fa-power-off"></i> Auto');
        
        if (isAutoUpdateActive) {
            startAutoUpdate();
        } else if (updateInterval) {
            clearInterval(updateInterval);
            updateInterval = null;
        }
    });
}

// Inicializar tabela de histórico
function initHistoryTable() {
    historyTable = $('#history-table').DataTable({
        order: [[0, 'desc']],
        pageLength: 10,
        scrollY: '300px',
        scrollCollapse: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        }
    });
    
    $('#history-table tbody').on('click', 'tr', function() {
        const lat = parseFloat($(this).data('lat')) || 0;
        const lng = parseFloat($(this).data('lng')) || 0;
        
        if (!isNaN(lat) && !isNaN(lng)) {
            map.setCenter({ lat, lng });
            map.setZoom(15);
        }
    });
}

// Atualizar histórico
function updateHistory() {
    $('#refresh-history').html('<i class="fas fa-spinner fa-spin me-1"></i>');
    $('#updating-indicator').removeClass('d-none');
    
    $.get('{{ route("freights.history", $freight->id) }}', function(data) {
        historyTable.clear();
        
        data.sort((a, b) => {
            const dateA = a.date && a.time ? new Date(`${a.date}T${a.time}`) : 0;
            const dateB = b.date && b.time ? new Date(`${b.date}T${b.time}`) : 0;
            return dateB - dateA;
        }).forEach(item => {
            historyTable.row.add([
                `<div>
                    <small>${item.date ? new Date(item.date).toLocaleDateString('pt-BR') : 'N/A'}</small>
                    <small>${item.time ? new Date('1970-01-01T' + item.time).toLocaleTimeString('pt-BR') : 'N/A'}</small>
                </div>`,
                `<div class="text-truncate" title="${item.address || 'N/A'}">${item.address || 'N/A'}</div>`,
                `<span class="badge bg-${getStatusClass(item.status)}">${item.status ? item.status.replace('_', ' ') : 'N/A'}</span>`
            ]).nodes().to$()
              .attr('data-lat', item.latitude || 0)
              .attr('data-lng', item.longitude || 0);
        });
        
        historyTable.draw();
        
        if (data.length > 0) {
            const last = data[0];
            const lat = parseFloat(last.latitude) || 0;
            const lng = parseFloat(last.longitude) || 0;
            
            if (!isNaN(lat) && !isNaN(lng)) {
                lastPosition = { lat, lng };
                updateCurrentPosition(lastPosition, last.address || 'N/A');
            }
            
            updateFreightStatus();
        }
    }).always(() => {
        $('#refresh-history').html('<i class="fas fa-sync-alt me-1"></i> Atualizar');
        $('#updating-indicator').addClass('d-none');
    });
}

// Atualizar status do frete
function updateFreightStatus() {
    $.get('{{ route("freights.status", $freight->id) }}', function(data) {
        if (data.status) {
            $('.card-body .badge')
                .removeClass('bg-info bg-success bg-warning bg-danger bg-secondary')
                .addClass(`bg-${getStatusClass(data.status)}`)
                .html(`<i class="fas fa-truck me-1"></i> ${data.status}`);
        }
    });
}

// Helper para classes de status
function getStatusClass(status) {
    if (!status) return 'secondary';
    status = status.toLowerCase();
    if (status.includes('transito')) return 'info';
    if (status.includes('entregue')) return 'success';
    if (status.includes('cancelado')) return 'danger';
    if (status.includes('pendente')) return 'warning';
    return 'secondary';
}

// Iniciar atualização automática
function startAutoUpdate() {
    if (updateInterval) clearInterval(updateInterval);
    
    const update = () => {
        if (!isAutoUpdateActive) return;
        
        $('#updating-indicator').removeClass('d-none');
        
        Promise.all([
            $.get('{{ route("freights.last-position", $freight->id) }}'),
            $.get('{{ route("freights.history", $freight->id) }}')
        ]).then(([position, history]) => {
            if (position && position.latitude && position.longitude) {
                const lat = parseFloat(position.latitude);
                const lng = parseFloat(position.longitude);
                
                if (!isNaN(lat) && !isNaN(lng)) {
                    updateCurrentPosition({ lat, lng }, position.address || 'N/A');
                }
            }
            
            if (history && history.length) {
                historyTable.clear().rows.add(history.map(item => [
                    `<div>
                        <small>${item.date ? new Date(item.date).toLocaleDateString('pt-BR') : 'N/A'}</small>
                        <small>${item.time ? new Date('1970-01-01T' + item.time).toLocaleTimeString('pt-BR') : 'N/A'}</small>
                    </div>`,
                    `<div class="text-truncate" title="${item.address || 'N/A'}">${item.address || 'N/A'}</div>`,
                    `<span class="badge bg-${getStatusClass(item.status)}">${item.status ? item.status.replace('_', ' ') : 'N/A'}</span>`
                ])).draw();
            }
            
            updateFreightStatus();
        }).finally(() => {
            $('#updating-indicator').addClass('d-none');
        });
    };
    
    update();
    updateInterval = setInterval(update, UPDATE_INTERVAL);
}

// Exportar mapa para PDF
function exportMapToPDF() {
    const { jsPDF } = window.jspdf;
    $('#export-route').html('<i class="fas fa-spinner fa-spin me-1"></i>');
    
    html2canvas(document.querySelector('#map-container'), {
        scale: 2,
        logging: false,
        useCORS: true
    }).then(canvas => {
        const pdf = new jsPDF('landscape');
        const imgData = canvas.toDataURL('image/png');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
        
        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save(`rota-frete-{{ $freight->id }}.pdf`);
    }).catch(error => {
        console.error('Erro ao exportar:', error);
        alert('Erro ao gerar PDF');
    }).finally(() => {
        $('#export-route').html('<i class="fas fa-file-pdf me-1"></i> Exportar');
    });
}

// Inicializar quando o documento estiver pronto
$(document).ready(function() {
    initMap();
    
    $(window).on('beforeunload', function() {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
    });
});
</script>
@endpush