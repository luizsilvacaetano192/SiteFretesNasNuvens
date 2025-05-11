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
                        
                        <!-- Mapa Google -->
                        <div id="map" style="height: 550px;"></div>
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
                        <table class="table table-hover mb-0" id="history-table" style="width: 100%;">
                            <thead class="thead-light sticky-top" style="top: 0;">
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
    #map { 
        width: 100%;
        height: 100%;
        min-height: 550px;
    }
    
    #history-table {
        width: 100% !important;
    }
    
    #history-table thead th {
        background-color: #f8f9fa;
        position: sticky;
        z-index: 10;
        white-space: nowrap;
    }
    
    #history-table tbody tr {
        cursor: pointer;
    }
    
    #history-table tbody tr:hover {
        background-color: rgba(0,0,0,0.03);
    }
    
    #history-table tbody td {
        vertical-align: middle;
    }
    
    #updating-indicator {
        font-size: 0.8rem;
        color: #4e73df;
    }
    
    .gm-style .gm-style-iw-c {
        padding: 12px !important;
        max-width: 300px !important;
    }
    
    .gm-style .gm-style-iw-d {
        overflow: auto !important;
    }
    
    .map-controls {
        margin: 10px;
        padding: 5px;
        background: white;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.4);
    }
    
    @media (max-width: 992px) {
        #map {
            min-height: 400px;
        }
        
        .col-lg-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        #history-table td, #history-table th {
            padding: 8px 5px;
            font-size: 0.9rem;
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
<!-- API do Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCi-A7nNanHXhUiBS3_71XeLa6bE0aX9Ts&libraries=geometry"></script>

<script>
// Configurações iniciais
const UPDATE_INTERVAL = 10000; // 10 segundos
const ZOOM_DEFAULT = 12;
const ZOOM_CLOSE = 15;
let map, directionsService, directionsRenderer;
let currentMarker, routePolyline;
let isTracking = true;
let mapType = 'roadmap';
let lastPosition = null;
let updateInterval;
let historyTable;

// Inicialização do mapa
function initMap() {
    // Coordenadas padrão (centro do Brasil)
    const defaultCenter = { lat: -15.7801, lng: -47.9292 };
    
    // Criar o mapa
    map = new google.maps.Map(document.getElementById('map'), {
        center: defaultCenter,
        zoom: ZOOM_DEFAULT,
        mapTypeId: 'roadmap',
        streetViewControl: false,
        fullscreenControl: false,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
            position: google.maps.ControlPosition.TOP_RIGHT
        }
    });
    
    // Serviço de rotas
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
        const startPoint = { lat: {{ $freight->start_lat }}, lng: {{ $freight->start_lng }} };
        const endPoint = { lat: {{ $freight->destination_lat }}, lng: {{ $freight->destination_lng }} };
        
        // Adicionar marcadores de origem e destino
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
        
        // Configurar rota
        calculateAndDisplayRoute(startPoint, endPoint);
    @endif
    
    // Adicionar marcador da posição atual
    @if($lastLocation = $freight->history()->orderBy('date', 'desc')->orderBy('time', 'desc')->first())
        lastPosition = { 
            lat: {{ $lastLocation->latitude ?? 0 }}, 
            lng: {{ $lastLocation->longitude ?? 0 }} 
        };
        updateCurrentPosition(lastPosition, "{{ $lastLocation->address ?? 'N/A' }}");
    @endif
}

// Calcular e exibir rota
function calculateAndDisplayRoute(startPoint, endPoint) {
    directionsService.route({
        origin: startPoint,
        destination: endPoint,
        travelMode: 'DRIVING',
        provideRouteAlternatives: false
    }, (response, status) => {
        if (status === 'OK') {
            directionsRenderer.setDirections(response);
            
            // Ajustar visualização para mostrar toda a rota
            const bounds = new google.maps.LatLngBounds();
            const route = response.routes[0];
            
            for (let i = 0; i < route.legs.length; i++) {
                bounds.union(route.legs[i].bounds);
            }
            
            map.fitBounds(bounds);
        } else {
            console.error('Falha ao calcular rota: ' + status);
        }
    });
}

// Atualizar posição atual
function updateCurrentPosition(position, address) {
    // Remover marcador anterior se existir
    if (currentMarker) {
        currentMarker.setMap(null);
    }
    
    // Criar novo marcador
    currentMarker = new google.maps.Marker({
        position: position,
        map: map,
        icon: {
            url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
            scaledSize: new google.maps.Size(30, 30)
        },
        title: "Posição atual: " + (address || 'Não disponível'),
        zIndex: 1000
    });
    
    // Atualizar informações na interface
    $('#current-position').text(address || 'Não disponível');
    $('#last-update').text(new Date().toLocaleString('pt-BR'));
    
    // Centralizar no marcador se o rastreamento estiver ativado
    if (isTracking) {
        map.setCenter(position);
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
            map.setCenter(lastPosition);
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
        if (directionsRenderer.getDirections()) {
            const bounds = new google.maps.LatLngBounds();
            const route = directionsRenderer.getDirections().routes[0];
            
            for (let i = 0; i < route.legs.length; i++) {
                bounds.union(route.legs[i].bounds);
            }
            
            map.fitBounds(bounds);
        }
    });
    
    // Botões de alternar tipo de mapa
    $('#map-type-road').click(function() {
        map.setMapTypeId('roadmap');
        $(this).addClass('active');
        $('#map-type-satellite, #map-type-hybrid').removeClass('active');
    });
    
    $('#map-type-satellite').click(function() {
        map.setMapTypeId('satellite');
        $(this).addClass('active');
        $('#map-type-road, #map-type-hybrid').removeClass('active');
    });
    
    $('#map-type-hybrid').click(function() {
        map.setMapTypeId('hybrid');
        $(this).addClass('active');
        $('#map-type-road, #map-type-satellite').removeClass('active');
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
        scrollY: '300px',
        scrollCollapse: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        columnDefs: [
            { targets: [2], orderable: false }
        ],
        createdRow: function(row, data, dataIndex) {
            // Adiciona tooltip para a coluna de localização
            $('td:eq(1)', row).attr('title', $('td:eq(1) div', row).attr('title'));
        }
    });
    
    // Evento de clique nas linhas da tabela
    $('#history-table tbody').on('click', 'tr', function() {
        const lat = $(this).data('lat') || 0;
        const lng = $(this).data('lng') || 0;
        map.setCenter({ lat: lat, lng: lng });
        map.setZoom(ZOOM_CLOSE);
    });
}

// Atualizar histórico via AJAX
function updateHistory() {
    $('#refresh-history').html('<i class="fas fa-spinner fa-spin me-1"></i> Atualizando');
    
    $.get('{{ route("freights.history", $freight->id) }}', function(data) {
        historyTable.clear();
        
        // Ordenar por date e time decrescente (mesmo critério do backend)
        data.sort((a, b) => {
            // Combina date e time para cada registro
            const dateTimeA = (a.date && a.time) ? new Date(`${a.date}T${a.time}`).getTime() : 0;
            const dateTimeB = (b.date && b.time) ? new Date(`${b.date}T${b.time}`).getTime() : 0;
            return dateTimeB - dateTimeA;
        });
        
        data.forEach(item => {
            const dateFormatted = item.date ? new Date(item.date).toLocaleDateString('pt-BR') : 'N/A';
            const timeFormatted = item.time ? new Date(`1970-01-01T${item.time}`).toLocaleTimeString('pt-BR') : 'N/A';
            
            historyTable.row.add([
                `<div class="d-flex flex-column">
                    <small>${dateFormatted}</small>
                    <small>${timeFormatted}</small>
                </div>`,
                `<div class="text-truncate" style="max-width: 250px;" title="${item.address || 'N/A'}">
                    ${item.address || 'N/A'}
                </div>`,
                `<span class="badge bg-${item.status === 'em_transito' ? 'info' : (item.status === 'entregue' ? 'success' : 'warning')}">
                    ${item.status ? item.status.replace('_', ' ') : 'N/A'}
                </span>`
            ]).nodes().to$()
                .attr('data-lat', item.latitude || 0)
                .attr('data-lng', item.longitude || 0)
                .attr('title', item.address || 'N/A');
        });
        
        historyTable.draw();
        $('#refresh-history').html('<i class="fas fa-sync-alt me-1"></i> Atualizar');
        
        // Atualizar última posição com o primeiro item da lista ordenada
        if (data.length > 0) {
            const last = data[0];
            lastPosition = { 
                lat: last.latitude || 0, 
                lng: last.longitude || 0 
            };
            updateCurrentPosition(lastPosition, last.address || 'N/A');
            
            // Atualizar também o texto exibido
            const dateFormatted = last.date ? new Date(last.date).toLocaleDateString('pt-BR') : 'N/A';
            const timeFormatted = last.time ? new Date(`1970-01-01T${last.time}`).toLocaleTimeString('pt-BR') : 'N/A';
            
            $('#current-position').text(last.address || 'N/A');
            $('#last-update').text(
                last.date && last.time ? 
                `${dateFormatted} às ${timeFormatted}` : 
                'N/A'
            );
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
                const position = { lat: data.latitude, lng: data.longitude };
                updateCurrentPosition(position, data.address || 'N/A');
                
                // Atualizar data/hora se disponível
                if (data.date && data.time) {
                    const dateFormatted = new Date(data.date).toLocaleDateString('pt-BR');
                    const timeFormatted = new Date(`1970-01-01T${data.time}`).toLocaleTimeString('pt-BR');
                    $('#last-update').text(`${dateFormatted} às ${timeFormatted}`);
                }
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