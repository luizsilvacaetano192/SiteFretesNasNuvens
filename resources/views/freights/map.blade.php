@extends('layouts.app')

@section('title', 'Detalhes do Frete')

@section('content')
<div class="container-fluid px-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-truck-moving me-2"></i>Detalhes do Frete #{{ $freight->id }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('freights.index') }}">Fretes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('freights.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Voltar para Fretes
            </a>
            <a href="#" class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Imprimir
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Coluna Esquerda -->
        <div class="col-lg-8">
            <!-- Card de Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Status do Frete
                    </h6>
                    <span class="badge bg-{{ $statusBadgeClass }}">{{ $freight->freightStatus->name }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Informações Básicas</h6>
                                <p class="mb-1"><strong>Empresa:</strong> {{ $freight->company->name }}</p>
                                <p class="mb-1"><strong>Criado em:</strong> {{ $freight->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-1"><strong>Valor Total:</strong> R$ {{ number_format($freight->freight_value, 2, ',', '.') }}</p>
                                <p class="mb-1"><strong>Valor Motorista:</strong> R$ {{ number_format($freight->driver_freight_value, 2, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Datas Importantes</h6>
                                <p class="mb-1"><strong>Coleta:</strong> {{ $freight->pickup_date ? $freight->pickup_date->format('d/m/Y H:i') : 'N/A' }}</p>
                                <p class="mb-1"><strong>Entrega:</strong> {{ $freight->delivery_date ? $freight->delivery_date->format('d/m/Y H:i') : 'N/A' }}</p>
                                <p class="mb-1"><strong>Concluído em:</strong> {{ $freight->completed_at ? $freight->completed_at->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Rota e Mapa -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-map-marked-alt me-2"></i>Rota e Localização
                    </h6>
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
                                    <i class="fas fa-search-plus"></i> Visão de Rua
                                </button>
                            </div>
                        </div>
                        
                        <div id="location-info" class="p-3 bg-light border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>📍 Posição atual:</strong> 
                                    <span id="current-position">
                                        @php
                                            $lastLocation = $freight->history()
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        @endphp
                                        {{ $lastLocation->address ?? 'Não disponível' }}
                                    </span>
                                </div>
                                <div>
                                    <strong>🔄 Atualizado em:</strong> 
                                    <span id="last-update">
                                        @if($lastLocation)
                                            {{ \Carbon\Carbon::parse($lastLocation->created_at)->format('d/m/Y H:i:s') }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Card de Histórico -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Histórico de Localizações
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="history-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Data</th>
                                    <th>Hora</th>
                                    <th>Endereço</th>
                                    <th>Status</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                </tr>
                            </thead>
                            <tbody id="activity-history">
                                @forelse($freight->history()->orderBy('created_at', 'desc')->get() as $location)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($location->date)->format('d/m/Y') }}</td>
                                    <td>{{ $location->time }}</td>
                                    <td>{{ $location->address }}</td>
                                    <td>
                                        <span class="badge bg-{{ $location->status === 'em_transito' ? 'info' : ($location->status === 'entregue' ? 'success' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $location->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $location->latitude }}</td>
                                    <td>{{ $location->longitude }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Nenhum registro de localização encontrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita -->
        <div class="col-lg-4">
            <!-- Card de Carga -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>Detalhes da Carga
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Informações Gerais</h6>
                        <p class="mb-1"><strong>Tipo:</strong> {{ $freight->shipment->cargo_type }}</p>
                        <p class="mb-1"><strong>Peso:</strong> {{ number_format($freight->shipment->weight, 2, ',', '.') }} kg</p>
                        <p class="mb-1"><strong>Dimensões:</strong> {{ $freight->shipment->dimensions }}</p>
                        <p class="mb-1"><strong>Volume:</strong> {{ $freight->shipment->volume }} m³</p>
                        <p class="mb-1"><strong>Descrição:</strong> {{ $freight->shipment->description ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Características</h6>
                        <p class="mb-1"><strong>Frágil:</strong> 
                            <span class="badge bg-{{ $freight->shipment->is_fragile ? 'warning' : 'secondary' }}">
                                {{ $freight->shipment->is_fragile ? 'Sim' : 'Não' }}
                            </span>
                        </p>
                        <p class="mb-1"><strong>Perigosa:</strong> 
                            <span class="badge bg-{{ $freight->shipment->is_hazardous ? 'danger' : 'secondary' }}">
                                {{ $freight->shipment->is_hazardous ? 'Sim' : 'Não' }}
                            </span>
                        </p>
                        <p class="mb-1"><strong>Controle de Temperatura:</strong> 
                            <span class="badge bg-{{ $freight->shipment->requires_temperature_control ? 'info' : 'secondary' }}">
                                {{ $freight->shipment->requires_temperature_control ? 'Sim' : 'Não' }}
                            </span>
                        </p>
                        @if($freight->shipment->requires_temperature_control)
                        <p class="mb-1"><strong>Faixa de Temperatura:</strong> 
                            <span class="badge bg-danger">
                                {{ $freight->shipment->min_temperature }}°{{ $freight->shipment->temperature_unit }} a 
                                {{ $freight->shipment->max_temperature }}°{{ $freight->shipment->temperature_unit }}
                            </span>
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card de Motorista e Veículo -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-truck me-2"></i>Motorista e Veículo
                    </h6>
                </div>
                <div class="card-body">
                    @if($freight->freightsDriver)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <img src="{{ $freight->driver->photo_url ?? asset('img/default-driver.png') }}" 
                                 class="rounded-circle" width="50" height="50" alt="Foto do Motorista">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $freight->freightsDriver->name }}</h6>
                            <small class="text-muted">{{ $freight->freightsDriver->phone }}</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Informações do Veículo</h6>
                        <p class="mb-1"><strong>Tipo:</strong> {{ $freight->truck_type ? ucwords(str_replace('_', ' ', $freight->truck_type)) : 'N/A' }}</p>
                        <p class="mb-1"><strong>Placa:</strong> {{ $freight->driver->truck_plate ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Capacidade:</strong> {{ $freight->driver->truck_capacity ?? 'N/A' }} kg</p>
                    </div>
                    @else
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>Nenhum motorista atribuído a este frete.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Card de Endereços -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Endereços
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Origem</h6>
                        <p class="mb-1"><strong>Endereço:</strong> {{ $freight->start_address }}</p>
                        <p class="mb-1"><strong>Instruções:</strong> {{ $freight->loading_instructions ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Contato:</strong> {{ $freight->start_contact ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="mb-0">
                        <h6 class="text-muted mb-2">Destino</h6>
                        <p class="mb-1"><strong>Endereço:</strong> {{ $freight->destination_address }}</p>
                        <p class="mb-1"><strong>Instruções:</strong> {{ $freight->unloading_instructions ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Contato:</strong> {{ $freight->destination_contact ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Card de Pagamento -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>Pagamento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1"><strong>Status:</strong> 
                            <span class="badge bg-{{ $paymentBadgeClass }}">{{ ucfirst(str_replace('_', ' ', $freight->payment_status)) }}</span>
                        </p>
                        <p class="mb-1"><strong>Método:</strong> {{ $freight->payment_method ? strtoupper($freight->payment_method) : 'N/A' }}</p>
                        <p class="mb-1"><strong>Seguradoras:</strong> 
                            @if($freight->insurance_carriers && count($freight->insurance_carriers) > 0)
                                @foreach($freight->insurance_carriers as $carrier)
                                    <span class="badge bg-info me-1">{{ ucwords(str_replace('_', ' ', $carrier)) }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">Nenhuma</span>
                            @endif
                        </p>
                    </div>
                    
                    @if($freight->charge)
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Distância</h6>
                            <p class="h5">{{ $freight->distance }} km</p>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Tempo Estimado</h6>
                            <p class="h5">{{ $freight->duration ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        @if($freight->payment_status === 'paid' && $freight->charge->receipt_url)
                        <a href="{{ $freight->charge->receipt_url }}" class="btn btn-sm btn-info me-2" target="_blank">
                            <i class="fas fa-file-invoice-dollar me-1"></i>Recibo
                        </a>
                        @elseif($freight->charge->charge_url)
                        <a href="{{ $freight->charge->charge_url }}" class="btn btn-sm btn-success" target="_blank">
                            <i class="fas fa-credit-card me-1"></i>Pagar
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos gerais */
    body {
        background-color: #f8f9fc;
        color: #333;
        font-family: 'Nunito', sans-serif;
    }
    
    .container-fluid {
        padding: 20px;
    }
    
    /* Cards */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.85em;
        letter-spacing: 0.5px;
        border-radius: 0.25rem;
    }
    
    .bg-primary {
        background-color: #4e73df !important;
    }
    
    .bg-success {
        background-color: #1cc88a !important;
    }
    
    .bg-info {
        background-color: #36b9cc !important;
    }
    
    .bg-warning {
        background-color: #f6c23e !important;
    }
    
    .bg-danger {
        background-color: #e74a3b !important;
    }
    
    .bg-secondary {
        background-color: #858796 !important;
    }
    
    /* Textos */
    .text-muted {
        color: #5a5c69 !important;
    }
    
    /* Mapa */
    #map-container {
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid #e3e6f0;
        position: relative;
    }
    
    #map {
        height: 400px;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    #location-info {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    
    #map-controls {
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 0.25rem;
        padding: 0.5rem;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    #map-controls .btn-group-vertical .btn {
        margin-bottom: 0.25rem;
        border-radius: 0.25rem !important;
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Tabelas */
    .table {
        width: 100%;
        margin-bottom: 0;
    }
    
    .table thead th {
        background-color: #f8f9fc;
        border-bottom-width: 1px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #5a5c69;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }
    
    /* Botões */
    .btn {
        border-radius: 0.375rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    /* Formulários */
    .form-control {
        border-radius: 0.375rem;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d3e2;
    }
    
    /* Responsividade */
    @media (max-width: 992px) {
        .card-body {
            padding: 1rem;
        }
    }
    
    @media (max-width: 768px) {
        #map {
            height: 300px;
        }
    }
    
    /* Impressão */
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background-color: white !important;
            font-size: 12pt;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            page-break-inside: avoid;
        }
        
        #map {
            height: 300px !important;
        }
        
        #map-controls {
            display: none !important;
        }
        
        .table {
            font-size: 10pt;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places,geometry&callback=initMap" async defer></script>
<script>
    // Configurações
    const ZOOM_STREET_LEVEL = 15;
    const ZOOM_ROUTE_LEVEL = 12;
    const UPDATE_INTERVAL = 60000; // 1 minuto
    const MIN_DISTANCE_UPDATE = 100; // 100 metros (distância mínima para considerar movimento)

    // Variáveis globais
    let map;
    let directionsRenderer;
    let truckMarker;
    let updateInterval;
    let currentPosition = null;
    let animationInterval;
    let isStreetView = true;
    let isTracking = true;
    let lastUpdateTime = null;

    // Inicialização do mapa
    function initMap() {
        const mapElement = document.getElementById("map");
        if (!mapElement) return;
        
        const defaultCenter = { lat: -15.7801, lng: -47.9292 };
        
        try {
            // Configuração do mapa
            map = new google.maps.Map(mapElement, {
                zoom: ZOOM_STREET_LEVEL,
                center: defaultCenter,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                gestureHandling: "greedy",
                styles: getMapStyles()
            });

            // Configuração do renderizador de rotas
            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                map: map,
                polylineOptions: {
                    strokeColor: '#4e73df',
                    strokeOpacity: 0.8,
                    strokeWeight: 4
                }
            });

            // Inicializa a rota e atualizações
            initRoute();
            startAutoUpdate();

            // Configura os botões de controle
            setupMapControls();

        } catch (error) {
            console.error("Erro ao inicializar o mapa:", error);
            showMapError();
        }
    }

    // Estilos do mapa
    function getMapStyles() {
        return [
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
                "stylers": [{"color": "#4e73df"}, {"visibility": "on"}]
            }
        ];
    }

    // Configura os controles do mapa
    function setupMapControls() {
        document.getElementById('zoom-toggle')?.addEventListener('click', toggleZoomLevel);
        document.getElementById('track-toggle')?.addEventListener('click', toggleTracking);
    }

    // Mostra erro no mapa
    function showMapError() {
        const mapContainer = document.getElementById('map-container');
        if (mapContainer) {
            mapContainer.innerHTML = `
                <div class="alert alert-danger m-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erro ao carregar o mapa. Por favor, recarregue a página.
                </div>
            `;
        }
    }

    // Inicializa a rota
    function initRoute() {
        const directionsService = new google.maps.DirectionsService();

        @if($freight->start_lat && $freight->start_lng && $freight->destination_lat && $freight->destination_lng)
            const start = new google.maps.LatLng({{ $freight->start_lat }}, {{ $freight->start_lng }});
            const end = new google.maps.LatLng({{ $freight->destination_lat }}, {{ $freight->destination_lng }});

            directionsService.route({
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode.DRIVING,
                provideRouteAlternatives: false
            }, (response, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(response);
                    addRouteMarkers(start, end);
                    initLastPosition();
                } else {
                    console.error("Erro ao traçar rota:", status);
                }
            });
        @endif
    }

    // Adiciona marcadores de origem e destino
    function addRouteMarkers(start, end) {
        // Marcador de origem
        new google.maps.Marker({
            position: start,
            map: map,
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png",
                scaledSize: new google.maps.Size(32, 32)
            },
            title: "Ponto de Partida"
        });

        // Marcador de destino
        new google.maps.Marker({
            position: end,
            map: map,
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                scaledSize: new google.maps.Size(32, 32)
            },
            title: "Ponto de Destino"
        });
    }

    // Inicializa a última posição conhecida
    function initLastPosition() {
        @php
            $lastLocation = $freight->history()
                ->orderBy('created_at', 'desc')
                ->first();
        @endphp

        @if($lastLocation && $lastLocation->latitude && $lastLocation->longitude)
            currentPosition = new google.maps.LatLng(
                {{ $lastLocation->latitude }}, 
                {{ $lastLocation->longitude }}
            );
            createTruckMarker(currentPosition);
            centerMapOnMarker(currentPosition);
            
            // Atualiza o último horário de atualização
            lastUpdateTime = new Date("{{ $lastLocation->created_at }}");
            updateLastUpdateTime();
        @endif
    }

    // Cria/atualiza o marcador do caminhão
    function createTruckMarker(position) {
        if (truckMarker) {
            truckMarker.setMap(null);
        }
        
        truckMarker = new google.maps.Marker({
            position: position,
            map: map,
            icon: {
                url: "{{ asset('images/icon-truck.png') }}",
                scaledSize: new google.maps.Size(40, 40),
                anchor: new google.maps.Point(20, 20)
            },
            title: "Posição Atual do Caminhão"
        });
    }

    // Centraliza o mapa no marcador
    function centerMapOnMarker(position) {
        if (!isTracking) return;
        
        map.setCenter(position);
        map.setZoom(isStreetView ? ZOOM_STREET_LEVEL : ZOOM_ROUTE_LEVEL);
    }

    // Move o caminhão para nova posição com animação suave
    function moveTruckTo(newLatLng) {
        if (!truckMarker || !currentPosition) {
            createTruckMarker(newLatLng);
            currentPosition = newLatLng;
            centerMapOnMarker(newLatLng);
            return;
        }

        const distance = google.maps.geometry.spherical.computeDistanceBetween(
            currentPosition, newLatLng);
        
        // Só anima se a distância for significativa
        if (distance < MIN_DISTANCE_UPDATE) {
            truckMarker.setPosition(newLatLng);
            currentPosition = newLatLng;
            centerMapOnMarker(newLatLng);
            return;
        }

        const heading = google.maps.geometry.spherical.computeHeading(
            currentPosition, newLatLng);
        
        // Ajusta a rotação do ícone para a direção do movimento
        truckMarker.setIcon({
            url: "{{ asset('images/icon-truck.png') }}",
            scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 20),
            rotation: heading
        });

        // Animação suave do movimento
        const steps = 20;
        const step = 1/steps;
        let currentStep = 0;
        
        if (animationInterval) {
            clearInterval(animationInterval);
        }
        
        animationInterval = setInterval(() => {
            currentStep += step;
            
            if (currentStep >= 1) {
                clearInterval(animationInterval);
                currentPosition = newLatLng;
                truckMarker.setPosition(newLatLng);
                centerMapOnMarker(newLatLng);
                return;
            }
            
            const interpolatedLat = currentPosition.lat() + 
                (newLatLng.lat() - currentPosition.lat()) * currentStep;
            const interpolatedLng = currentPosition.lng() + 
                (newLatLng.lng() - currentPosition.lng()) * currentStep;
            
            const interpolatedLatLng = new google.maps.LatLng(interpolatedLat, interpolatedLng);
            truckMarker.setPosition(interpolatedLatLng);
            
            if (isTracking) {
                map.panTo(interpolatedLatLng);
            }
            
        }, 100);
    }

    // Alterna entre visão de rua e visão de rota
    function toggleZoomLevel() {
        isStreetView = !isStreetView;
        
        const zoomToggleBtn = document.getElementById('zoom-toggle');
        if (isStreetView) {
            map.setZoom(ZOOM_STREET_LEVEL);
            zoomToggleBtn.innerHTML = '<i class="fas fa-search-minus"></i> Visão Geral';
        } else {
            map.setZoom(ZOOM_ROUTE_LEVEL);
            zoomToggleBtn.innerHTML = '<i class="fas fa-search-plus"></i> Visão de Rua';
        }
        
        if (isTracking && truckMarker) {
            centerMapOnMarker(truckMarker.getPosition());
        }
    }

    // Alterna o modo de acompanhamento
    function toggleTracking() {
        isTracking = !isTracking;
        
        const trackToggleBtn = document.getElementById('track-toggle');
        if (isTracking) {
            trackToggleBtn.innerHTML = '<i class="fas fa-lock"></i> Travar Mapa';
            if (truckMarker) {
                centerMapOnMarker(truckMarker.getPosition());
            }
        } else {
            trackToggleBtn.innerHTML = '<i class="fas fa-lock-open"></i> Acompanhar';
        }
    }

    // Inicia a atualização automática
    function startAutoUpdate() {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
        
        updatePosition();
        updateInterval = setInterval(updatePosition, UPDATE_INTERVAL);
    }

    // Atualiza a posição do caminhão
    function updatePosition() {
        fetch(`/freights/{{ $freight->id }}/position`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta da rede');
                }
                return response.json();
            })
            .then(data => {
                if (data.latitude && data.longitude) {
                    const newPosition = new google.maps.LatLng(
                        parseFloat(data.latitude),
                        parseFloat(data.longitude)
                    );
                    
                    // Atualiza a posição atual
                    updateCurrentPosition(data.address, newPosition, data.created_at);
                }
                
                if (data.history && data.history.length > 0) {
                    updateHistoryTable(data.history);
                }
            })
            .catch(error => {
                console.error("Erro ao atualizar posição:", error);
                // Tenta novamente em 30 segundos se houver erro
                setTimeout(updatePosition, 30000);
            });
    }

    // Atualiza a posição atual e informações
    function updateCurrentPosition(address, newPosition, created_at) {
        document.getElementById('current-position').textContent = address || 'Posição atual';
        
        // Atualiza o horário da última atualização
        lastUpdateTime = new Date(created_at);
        updateLastUpdateTime();
        
        // Move o marcador para a nova posição
        moveTruckTo(newPosition);
    }

    // Atualiza o texto do último horário de atualização
    function updateLastUpdateTime() {
        if (!lastUpdateTime) return;
        
        const now = new Date();
        const diffSeconds = Math.floor((now - lastUpdateTime) / 1000);
        
        let updateText;
        if (diffSeconds < 60) {
            updateText = `${diffSeconds} segundos atrás`;
        } else if (diffSeconds < 3600) {
            const minutes = Math.floor(diffSeconds / 60);
            updateText = `${minutes} minuto${minutes !== 1 ? 's' : ''} atrás`;
        } else {
            updateText = lastUpdateTime.toLocaleTimeString('pt-BR');
        }
        
        document.getElementById('last-update').textContent = updateText;
    }

    // Atualiza a tabela de histórico
    function updateHistoryTable(history) {
        const historyTable = document.getElementById('activity-history');
        if (!historyTable) return;
        
        // Ordena do mais recente para o mais antigo
        const sortedHistory = history.sort((a, b) => 
            new Date(b.created_at) - new Date(a.created_at));
        
        // Limpa a tabela
        historyTable.innerHTML = '';
        
        // Adiciona os registros
        sortedHistory.forEach(item => {
            const dateObj = new Date(item.created_at);
            const formattedDate = dateObj.toLocaleDateString('pt-BR');
            const formattedTime = dateObj.toLocaleTimeString('pt-BR', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${formattedDate}</td>
                <td>${formattedTime}</td>
                <td>${item.address || 'N/A'}</td>
                <td>
                    <span class="badge bg-${
                        item.status === 'em_transito' ? 'info' : 
                        (item.status === 'entregue' ? 'success' : 'warning')
                    }">
                        ${item.status.replace('_', ' ')}
                    </span>
                </td>
                <td>${item.latitude}</td>
                <td>${item.longitude}</td>
            `;
            
            // Insere no início da tabela
            historyTable.insertBefore(row, historyTable.firstChild);
        });
    }

    // Eventos quando o DOM estiver carregado
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof google !== 'undefined') {
            // Atualiza o horário a cada minuto
            setInterval(updateLastUpdateTime, 60000);
            startAutoUpdate();
        }
    });

    // Limpeza quando a página for fechada
    window.addEventListener('beforeunload', function() {
        if (updateInterval) clearInterval(updateInterval);
        if (animationInterval) clearInterval(animationInterval);
    });
</script>
@endpush