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
                        <div id="map-controls" class="position-relative top-0 end-0 mt-2 me-2" 
                        style="z-index: 1000; ">
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
                                                ->orderBy('date', 'desc')
                                                ->first();
                                        @endphp
                                        {{ $lastLocation->address ?? 'Não disponível' }}
                                    </span>
                                    <span id="updating-indicator" class="d-none ms-2">
                                        <i class="fas fa-sync-alt fa-spin"></i>
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
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Card de Histórico -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Histórico de Localizações
                    </h6>
                    <button id="refresh-history" class="btn btn-sm btn-primary">
                        <i class="fas fa-sync-alt me-1"></i>Atualizar
                    </button>
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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($freight->history()->orderBy('date', 'desc')->get() as $location)
                                <tr>
                                    <td data-order="{{ $location->date }}">
                                        {{ \Carbon\Carbon::parse($location->date)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($location->date)->format('H:i:s') }}
                                    </td>
                                    <td>{{ $location->address }}</td>
                                    <td>
                                        <span class="badge bg-{{ $location->status === 'em_transito' ? 'info' : ($location->status === 'entregue' ? 'success' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $location->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Nenhum registro de localização encontrado</td>
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
                           <!--  <img src="{{ $freight->driver->photo_url ?? asset('img/default-driver.png') }}" 
                                 class="rounded-circle" width="50" height="50" alt="Foto do Motorista"> -->
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css"/>
<style>
    /* Estilos para o DataTable */
    #history-table_wrapper {
        padding: 15px;
    }
    
    #history-table thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fc;
        z-index: 10;
        white-space: nowrap;
    }
    
    #history-table .dataTables_filter input {
        border: 1px solid #d1d3e2;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        margin-left: 0.5rem;
    }
    
    #history-table .dataTables_length select {
        border: 1px solid #d1d3e2;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
    }
    
    #history-table .dataTables_info,
    #history-table .dataTables_paginate {
        padding: 10px 0;
    }
    
    #history-table .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    #history-table .page-link {
        color: #4e73df;
    }
    
    /* Botão de atualização */
    #refresh-history {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Indicador de atualização */
    #updating-indicator {
        font-size: 0.8rem;
        color: #4e73df;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        #history-table_wrapper .dataTables_length,
        #history-table_wrapper .dataTables_filter {
            text-align: left;
            margin-bottom: 0.5rem;
        }
        
        #history-table th, #history-table td {
            white-space: normal;
            font-size: 0.875rem;
        }
    }
</style>
@endpush


@push('scripts')
<!-- Substitua SUA_CHAVE_API pela sua chave real do Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc
9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places,geometry&callback=initMap" 
        async defer onerror="handleMapError()"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"></script>

<script>
    // =============================================
    // CONFIGURAÇÕES GLOBAIS E VARIÁVEIS
    // =============================================
    const ZOOM_STREET_LEVEL = 15;
    const ZOOM_ROUTE_LEVEL = 12;
    const UPDATE_INTERVAL = 10000; // 30 segundos
    const MIN_DISTANCE_UPDATE = 100; // 100 metros
    const MAX_MAP_LOAD_ATTEMPTS = 5;

    // Variáveis globais
    let map;
    let directionsRenderer;
    let truckMarker;
    let currentPosition = null;
    let animationInterval;
    let isStreetView = true;
    let isTracking = true;
    let lastUpdateTime = null;
    let positionUpdateInterval;
    let historyTable;
    let mapLoaded = false;
    let mapLoadAttempts = 0;

    // =============================================
    // FUNÇÕES DE INICIALIZAÇÃO E CONTROLE DO MAPA
    // =============================================

    // Tratamento de erro no carregamento do mapa
    function handleMapError() {
        console.error("Falha ao carregar a API do Google Maps");
        showMapError();
        attemptMapReload();
    }

    // Tenta recarregar o mapa automaticamente
    function attemptMapReload() {
        if (mapLoadAttempts >= MAX_MAP_LOAD_ATTEMPTS) {
            console.log("Número máximo de tentativas atingido");
            return;
        }

        mapLoadAttempts++;
        console.log(`Tentativa ${mapLoadAttempts} de recarregar o mapa`);

        setTimeout(() => {
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=SUA_CHAVE_API&libraries=places,geometry&callback=initMap`;
            script.async = true;
            script.defer = true;
            script.onerror = handleMapError;
            document.head.appendChild(script);
        }, 5000);
    }

    // Mostra mensagem de erro no container do mapa
    function showMapError() {
        const mapContainer = document.getElementById('map-container');
        if (mapContainer) {
            mapContainer.innerHTML = `
                <div class="alert alert-danger m-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Erro ao carregar o mapa. 
                    <a href="javascript:location.reload()" class="alert-link">Clique aqui</a> para recarregar a página.
                    <div class="mt-2">Tentando reconectar automaticamente (${mapLoadAttempts}/${MAX_MAP_LOAD_ATTEMPTS})...</div>
                </div>
            `;
        }
    }

    // Verifica se a API do Google Maps foi carregada corretamente
    function checkGoogleMapsLoaded() {
        if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
            console.warn("API do Google Maps não carregada");
            return false;
        }
        return true;
    }

    // Estilos personalizados para o mapa
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

    // Inicialização principal do mapa
    function initMap() {
        if (!checkGoogleMapsLoaded()) {
            handleMapError();
            return;
        }

        try {
            const mapElement = document.getElementById("map");
            if (!mapElement) {
                console.error("Elemento do mapa não encontrado");
                return;
            }

            const defaultCenter = { lat: -15.7801, lng: -47.9292 };
            
            map = new google.maps.Map(mapElement, {
                zoom: ZOOM_STREET_LEVEL,
                center: defaultCenter,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                gestureHandling: "greedy",
                styles: getMapStyles()
            });

            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                map: map,
                polylineOptions: {
                    strokeColor: '#4e73df',
                    strokeOpacity: 0.8,
                    strokeWeight: 4
                }
            });

            initRoute();
            setupMapControls();
            
            positionUpdateInterval = setInterval(updatePosition, UPDATE_INTERVAL);
            updatePosition();

            mapLoaded = true;
            console.log("Mapa inicializado com sucesso");

        } catch (error) {
            console.error("Erro na inicialização do mapa:", error);
            showMapError();
            attemptMapReload();
        }
    }

    // =============================================
    // FUNÇÕES DE ROTEAMENTO E MAPA
    // =============================================

    // Inicializa a rota entre origem e destino
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
        new google.maps.Marker({
            position: start,
            map: map,
            icon: {
                url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png",
                scaledSize: new google.maps.Size(32, 32)
            },
            title: "Ponto de Partida"
        });

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
                ->orderBy('date', 'desc')
                ->first();
        @endphp

        @if($lastLocation && $lastLocation->latitude && $lastLocation->longitude)
            currentPosition = new google.maps.LatLng(
                {{ $lastLocation->latitude }}, 
                {{ $lastLocation->longitude }}
            );
            createTruckMarker(currentPosition);
            centerMapOnMarker(currentPosition);
            
            lastUpdateTime = new Date("{{ $lastLocation->date }}");
            updateLastUpdateTime();
        @endif
    }

    // Cria ou atualiza o marcador do caminhão
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

    // =============================================
    // FUNÇÕES DE ATUALIZAÇÃO EM TEMPO REAL
    // =============================================

    // Atualiza a posição do caminhão via AJAX
    function updatePosition() {
        if (!mapLoaded) return;

        $('#updating-indicator').removeClass('d-none');
        
        $.ajax({
            url: '{{ route("freights.last-position", $freight->id) }}',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.latitude && data.longitude) {
                    const newPosition = new google.maps.LatLng(
                        parseFloat(data.latitude), 
                        parseFloat(data.longitude)
                    );
                    
                    moveTruckTo(newPosition);
                    document.getElementById('current-position').textContent = data.address || 'Não disponível';
                    lastUpdateTime = new Date(data.date);
                    updateLastUpdateTime();
                    
                    if (data.status_changed) {
                        updateHistory();
                    }
                }
                $('#updating-indicator').addClass('d-none');
            },
            error: function(xhr) {
                console.error('Erro ao atualizar posição:', xhr.responseText);
                $('#updating-indicator').addClass('d-none');
            }
        });
    }

    // Move o caminhão para nova posição com animação
    function moveTruckTo(newLatLng) {
        if (!truckMarker || !currentPosition) {
            createTruckMarker(newLatLng);
            currentPosition = newLatLng;
            centerMapOnMarker(newLatLng);
            return;
        }

        const distance = google.maps.geometry.spherical.computeDistanceBetween(
            currentPosition, newLatLng);
        
        if (distance < MIN_DISTANCE_UPDATE) {
            truckMarker.setPosition(newLatLng);
            currentPosition = newLatLng;
            centerMapOnMarker(newLatLng);
            return;
        }

        const heading = google.maps.geometry.spherical.computeHeading(
            currentPosition, newLatLng);
        
        truckMarker.setIcon({
            url: "{{ asset('images/icon-truck.png') }}",
            scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 20),
            rotation: heading
        });

        const steps = 20;
        const step = 1/steps;
        let currentStep = 0;
        
        clearAnimationInterval();
        
        animationInterval = setInterval(() => {
            currentStep += step;
            
            if (currentStep >= 1) {
                clearAnimationInterval();
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

    // Limpa o intervalo de animação
    function clearAnimationInterval() {
        if (animationInterval) {
            clearInterval(animationInterval);
            animationInterval = null;
        }
    }

    // Centraliza o mapa no marcador
    function centerMapOnMarker(position) {
        if (!isTracking) return;
        
        map.setCenter(position);
        map.setZoom(isStreetView ? ZOOM_STREET_LEVEL : ZOOM_ROUTE_LEVEL);
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

    // =============================================
    // CONTROLES DO MAPA
    // =============================================

    // Configura os controles do mapa
    function setupMapControls() {
        document.getElementById('zoom-toggle')?.addEventListener('click', toggleZoomLevel);
        document.getElementById('track-toggle')?.addEventListener('click', toggleTracking);
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

    // =============================================
    // DATA TABLE E HISTÓRICO
    // =============================================

    // Inicialização do DataTable
    $(document).ready(function() {
        initializeDataTable();
        setupEventListeners();
        
        // Verificação periódica se o mapa foi carregado
        setTimeout(() => {
            if (!mapLoaded && !checkGoogleMapsLoaded()) {
                showMapError();
                attemptMapReload();
            }
        }, 3000);
    });

    // Inicializa a tabela de histórico
    function initializeDataTable() {
        historyTable = $('#history-table').DataTable({
            dom: '<"top"Bf>rt<"bottom"lip><"clear">',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            responsive: true,
            stateSave: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
            },
            columnDefs: [
                { 
                    type: 'date-eu', 
                    targets: 0,
                    render: function(data, type, row) {
                        if (type === 'sort') {
                            return data;
                        }
                        return row[0];
                    }
                },
                { orderable: false, targets: [3] }
            ],
            initComplete: function() {
                $('.dt-buttons button').removeClass('dt-button');
            }
        });
    }

    // Configura os listeners de eventos
    function setupEventListeners() {
        $('#refresh-history').click(function(e) {
            e.preventDefault();
            updateHistory();
        });

        // Atualização automática do histórico a cada 1 minuto
        setInterval(updateHistory, 60000);
    }

    // Atualiza o histórico via AJAX
   // Substitua a função updateHistory() pelo seguinte código:
function updateHistory() {
    $.ajax({
        url: '{{ route("freights.history", $freight->id) }}',
        type: 'GET',
        dataType: 'json',
        beforeSend: function() {
            $('#refresh-history').html('<i class="fas fa-spinner fa-spin me-1"></i> Carregando...');
        },
        success: function(response) {
            // Limpa os dados antigos
            historyTable.clear().draw();
            
            // Verifica se a resposta contém dados
            if (response.data && Array.isArray(response.data)) {
                // Processa cada item do histórico
                response.data.forEach(function(item) {
                    // Formata a data para exibição
                    const date = new Date(item.date);
                    const formattedDate = date.toLocaleDateString('pt-BR');
                    const formattedTime = date.toLocaleTimeString('pt-BR');
                    
                    // Determina a classe do badge com base no status
                    let badgeClass;
                    switch(item.status) {
                        case 'em_transito':
                            badgeClass = 'info';
                            break;
                        case 'entregue':
                            badgeClass = 'success';
                            break;
                        default:
                            badgeClass = 'warning';
                    }
                    
                    // Adiciona a linha à tabela
                    historyTable.row.add([
                        formattedDate,  // Coluna 0: Data formatada
                        formattedTime,   // Coluna 1: Hora formatada
                        item.address || 'N/A',  // Coluna 2: Endereço
                        `<span class="badge bg-${badgeClass}">
                            ${item.status.replace('_', ' ')}
                        </span>`        // Coluna 3: Status com badge
                    ]);
                });
            } else {
                // Adiciona mensagem se não houver dados
                historyTable.row.add([
                    '',
                    '',
                    'Nenhum dado de histórico disponível',
                    ''
                ]);
            }
            
            // Renderiza a tabela e mantém a ordenação
            historyTable.draw();
            $('#refresh-history').html('<i class="fas fa-sync-alt me-1"></i> Atualizar');
            
            // Força a ordenação pela primeira coluna (data) de forma decrescente
            historyTable.order([0, 'desc']).draw();
        },
        error: function(xhr) {
            console.error('Erro ao carregar histórico:', xhr.responseText);
            $('#refresh-history').html('<i class="fas fa-sync-alt me-1"></i> Atualizar');
            
            // Mostra mensagem de erro na tabela
            historyTable.clear().draw();
            historyTable.row.add([
                '',
                '',
                'Erro ao carregar dados. Tente novamente.',
                '<span class="badge bg-danger">Erro</span>'
            ]).draw();
        }
    });
}

// E atualize a inicialização do DataTable para:
function initializeDataTable() {
    historyTable = $('#history-table').DataTable({
        dom: '<"top"<"d-flex justify-content-between align-items-center"<"me-3"l><"ms-auto"B>f>>rt<"bottom"ip><"clear">',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3],
                    format: {
                        body: function(data, row, column, node) {
                            // Remove tags HTML para exportação
                            return $(data).text() || data;
                        }
                    }
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3],
                    format: {
                        body: function(data, row, column, node) {
                            return $(data).text() || data;
                        }
                    }
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i> Imprimir',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3],
                    format: {
                        body: function(data, row, column, node) {
                            return $(data).text() || data;
                        }
                    }
                }
            }
        ],
        order: [[0, 'desc']], // Ordena pela coluna de data (índice 0) decrescente
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        responsive: true,
        stateSave: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
        },
        columns: [
            { 
                title: "Data",
                data: null,
                render: function(data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        return data[0]; // Data formatada
                    }
                    return new Date(data.date).toISOString(); // Para ordenação
                }
            },
            { 
                title: "Hora",
                data: null,
                render: function(data, type, row) {
                    return data[1]; // Hora formatada
                }
            },
            { 
                title: "Endereço",
                data: null,
                render: function(data, type, row) {
                    return data[2]; // Endereço
                }
            },
            { 
                title: "Status",
                data: null,
                render: function(data, type, row) {
                    return data[3]; // Badge de status
                },
                orderable: false
            }
        ],
        initComplete: function() {
            $('.dt-buttons button').removeClass('dt-button');
        }
    });
}

    // =============================================
    // GERENCIAMENTO DE EVENTOS E LIMPEZA
    // =============================================

    // Fallback para erros de autenticação do Google Maps
    window.gm_authFailure = function() {
        showMapError();
        console.error("Falha de autenticação com a API do Google Maps");
    };

    // Limpeza quando a página for fechada
    window.addEventListener('beforeunload', function() {
        clearAnimationInterval();
        if (positionUpdateInterval) clearInterval(positionUpdateInterval);
    });
</script>
@endpush