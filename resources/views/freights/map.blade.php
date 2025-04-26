@extends('layouts.app')

@section('title', 'Detalhes do Frete')

@section('content')
<div class="container-fluid px-4">
    <!-- Botão Voltar no Topo -->
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
                        <div id="location-info" class="p-3 bg-light border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>📍 Posição atual:</strong> 
                                    <span id="current-position">{{ $freight->current_position ?? 'Não disponível' }}</span>
                                </div>
                                <div>
                                    <strong>🔄 Atualizado em:</strong> 
                                    <span id="last-update">{{ now()->format('d/m/Y H:i:s') }}</span>
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
                        <i class="fas fa-history me-2"></i>Histórico de Atividades
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="history-table">
                            <thead>
                                <tr>
                                    <th width="120">Data/Hora</th>
                                    <th>Evento</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody id="activity-history">
                                @forelse($freight->history as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $activity->event }}</td>
                                    <td>{{ $activity->details }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Nenhum histórico disponível</td>
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
                        <p class="mb-1"><strong>Peso:</strong> {{ $freight->shipment->weight }} kg</p>
                        <p class="mb-1"><strong>Dimensões:</strong> {{ $freight->shipment->dimensions }}</p>
                        <p class="mb-1"><strong>Volume:</strong> {{ $freight->shipment->volume }}</p>
                        <p class="mb-1"><strong>Descrição:</strong> {{ $freight->shipment->description ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Características</h6>
                        <p class="mb-1"><strong>Frágil:</strong> {{ $freight->shipment->is_fragile ? 'Sim' : 'Não' }}</p>
                        <p class="mb-1"><strong>Perigosa:</strong> {{ $freight->shipment->is_hazardous ? 'Sim' : 'Não' }}</p>
                        <p class="mb-1"><strong>Controle de Temperatura:</strong> {{ $freight->shipment->requires_temperature_control ? 'Sim' : 'Não' }}</p>
                        @if($freight->shipment->requires_temperature_control)
                        <p class="mb-1"><strong>Faixa de Temperatura:</strong> 
                            {{ $freight->shipment->min_temperature }}°{{ $freight->shipment->temperature_unit }} a 
                            {{ $freight->shipment->max_temperature }}°{{ $freight->shipment->temperature_unit }}
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
                    @if($freight->driver)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <img src="{{ $freight->driver->photo_url ?? asset('img/default-driver.png') }}" 
                                 class="rounded-circle" width="50" height="50" alt="Foto do Motorista">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $freight->driver->name }}</h6>
                            <small class="text-muted">{{ $freight->driver->phone }}</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Informações do Veículo</h6>
                        <p class="mb-1"><strong>Tipo:</strong> {{ $freight->truck_type ? ucwords(str_replace('_', ' ', $freight->truck_type)) : 'N/A' }}</p>
                        <p class="mb-1"><strong>Placa:</strong> {{ $freight->driver->truck_plate ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Capacidade:</strong> {{ $freight->driver->truck_capacity ?? 'N/A' }}</p>
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
                            <span class="badge bg-{{ $paymentBadgeClass }}">{{ $freight->payment_status }}</span>
                        </p>
                        <p class="mb-1"><strong>Método:</strong> {{ $freight->payment_method ? strtoupper($freight->payment_method) : 'N/A' }}</p>
                        <p class="mb-1"><strong>Seguradoras:</strong> 
                            @if($freight->insurance_carriers && count($freight->insurance_carriers) > 0)
                                {{ implode(', ', array_map(function($item) { return ucwords(str_replace('_', ' ', $item)); }, $freight->insurance_carriers)) }}
                            @else
                                Nenhuma seguradora específica
                            @endif
                        </p>
                    </div>
                    
                    @if($freight->charge)
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Distância</h6>
                            <p class="h5">{{ $freight->distance ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Tempo Estimado</h6>
                            <p class="h5">{{ $freight->duration ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        @if($freight->payment_status === 'paid' && $freight->charge->receipt_url)
                        <a href="{{ $freight->charge->receipt_url }}" class="btn btn-sm btn-info" target="_blank">
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
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    
    #map-container {
        border-radius: 0.35rem;
        overflow: hidden;
        border: 1px solid #e3e6f0;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .bg-warning {
        background-color: #f6c23e !important;
    }
    
    .bg-success {
        background-color: #1cc88a !important;
    }
    
    .bg-danger {
        background-color: #e74a3b !important;
    }
    
    .bg-primary {
        background-color: #4e73df !important;
    }
    
    .bg-secondary {
        background-color: #858796 !important;
    }
    
    .text-muted {
        color: #5a5c69 !important;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background-color: white !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #e3e6f0 !important;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
        
        #map {
            height: 300px !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initMap" async defer></script>
<script>
    let map;
    let directionsRenderer;
    let truckMarker;
    let updateInterval;

    function initMap() {
        const mapElement = document.getElementById("map");
        if (!mapElement) return;
        
        const defaultCenter = { lat: -15.7801, lng: -47.9292 };
        
        try {
            map = new google.maps.Map(mapElement, {
                zoom: 7,
                center: defaultCenter,
                mapTypeId: google.maps.MapTypeId.ROADMAP
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
            startAutoUpdate();

        } catch (error) {
            console.error("Erro ao inicializar o mapa:", error);
        }
    }

    function initRoute() {
        const directionsService = new google.maps.DirectionsService();

        @if($freight->start_lat && $freight->start_lng && $freight->destination_lat && $freight->destination_lng)
            const start = new google.maps.LatLng({{ $freight->start_lat }}, {{ $freight->start_lng }});
            const end = new google.maps.LatLng({{ $freight->destination_lat }}, {{ $freight->destination_lng }});

            directionsService.route({
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode.DRIVING
            }, (response, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(response);
                    
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

                    @if($freight->current_lat && $freight->current_lng)
                        updateTruckPosition({{ $freight->current_lat }}, {{ $freight->current_lng }}, true);
                    @endif
                }
            });
        @endif
    }

    function updateTruckPosition(lat, lng, position, initialLoad = false) {
        const truckPosition = new google.maps.LatLng(lat, lng);
        
        if (truckMarker) {
            truckMarker.setMap(null);
        }
        
        truckMarker = new google.maps.Marker({
            position: truckPosition,
            map: map,
            icon: {
                url: "https://img.icons8.com/ios-filled/50/000000/truck.png",
                scaledSize: new google.maps.Size(40, 40)
            },
            title: "Posição Atual do Caminhão"
        });
        
        document.getElementById('current-position').textContent = position;
        document.getElementById('last-update').textContent = new Date().toLocaleString();
        
        if (!initialLoad) {
            // Ajusta o zoom para nível 15 (ruas visíveis) e centraliza no caminhão
            map.setCenter(truckPosition);
            map.setZoom(15);
            
            // Efeito de animação
            truckMarker.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => {
                truckMarker.setAnimation(null);
            }, 1500);
        }
    }

    function startAutoUpdate() {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
        
        updateInterval = setInterval(() => {
            fetch(`/freights/{{ $freight->id }}/position`)
                .then(response => response.json())
                .then(data => {
                    if (data.current_lat && data.current_lng) {
                        updateTruckPosition(data.current_lat, data.current_lng, data.position);
                    }
                    
                    if (data.history && data.history.length > 0) {
                        updateHistoryTable(data.history);
                    }
                })
                .catch(error => {
                    console.error("Erro ao atualizar posição:", error);
                });
        }, 10000);
    }

    function updateHistoryTable(history) {
        const historyTable = document.getElementById('activity-history');
        historyTable.innerHTML = '';
        
        history.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${new Date(item.created_at).toLocaleString()}</td>
                <td>${item.event}</td>
                <td>${item.details}</td>
            `;
            historyTable.appendChild(row);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof google !== 'undefined') {
            startAutoUpdate();
        }
    });

    window.addEventListener('beforeunload', function() {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
    });
</script>
@endpush