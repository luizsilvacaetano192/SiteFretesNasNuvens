@extends('layouts.app')

@section('title', 'Solicitar Frete')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-0">Solicitar Frete para Carga #{{ $shipment->id }}</h1>
        </div>
    </div>

    <form id="freightRequestForm" method="POST" action="{{ route('freights.store') }}">
        @csrf
        <input type="hidden" name="shipment_id" value="{{ $shipment->id }}">
        <input type="hidden" name="company_id" value="{{ $shipment->company->id }}">
        <input type="hidden" name="status_id" value="2">

        <!-- Informações da Carga -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informações da Carga</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Empresa Contratante</label>
                        <input type="text" class="form-control-plaintext" value="{{ $shipment->company->name }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tipo de Carga</label>
                        <input type="text" class="form-control-plaintext" value="{{ ucfirst($shipment->load_type) }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Endereços -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Endereços</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_address" class="form-label fw-bold">Origem</label>
                        <input id="start_address" name="start_address" type="text" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="destination_address" class="form-label fw-bold">Destino</label>
                        <input id="destination_address" name="destination_address" type="text" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuração do Frete -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Configuração do Frete</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="truck_type" class="form-label fw-bold">Tipo de Veículo</label>
                        <select id="truck_type" name="truck_type" class="form-select" required>
                            <option value="" disabled selected>Selecione o tipo</option>
                            <option value="pequeno">Pequeno (até 3 ton)</option>
                            <option value="medio">Médio (3-8 ton)</option>
                            <option value="grande">Grande (8+ ton)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="freight_value" class="form-label fw-bold">Valor do Frete (R$)</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" id="freight_value" name="freight_value" class="form-control" step="0.01" min="0" required>
                        </div>
                        <small class="text-muted">Valor sugerido: <span id="suggested_value">R$ 0,00</span></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campos ocultos para coordenadas -->
        <input type="hidden" id="current_position" name="current_position">
        <input type="hidden" id="current_lat" name="current_lat">
        <input type="hidden" id="current_lng" name="current_lng">
        <input type="hidden" id="start_lat" name="start_lat">
        <input type="hidden" id="start_lng" name="start_lng">
        <input type="hidden" id="destination_lat" name="destination_lat">
        <input type="hidden" id="destination_lng" name="destination_lng">

        <!-- Rota e Detalhes -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Rota e Detalhes</h5>
            </div>
            <div class="card-body">
                <div id="map" style="height: 400px; width: 100%; margin-bottom: 20px;"></div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-info">
                            <div class="card-body text-center">
                                <h6 class="mb-2">Distância</h6>
                                <p id="distance" class="h4 mb-0">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-info">
                            <div class="card-body text-center">
                                <h6 class="mb-2">Tempo Estimado</h6>
                                <p id="duration" class="h4 mb-0">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-success">
                            <div class="card-body text-center">
                                <h6 class="mb-2">Valor Sugerido</h6>
                                <p id="calculated_value" class="h4 mb-0">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Valor Final -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Valor Final do Frete</h6>
                            </div>
                            <div class="card-body text-center">
                                <p id="final_value" class="display-5 text-success fw-bold mb-1">R$ 0,00</p>
                                <small class="text-muted">Este será o valor cobrado</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
            <button type="button" class="btn btn-secondary me-md-2" onclick="window.history.back()">
                Cancelar
            </button>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                Confirmar Frete
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initMap" async defer></script>

<script>
    let map, directionsService, directionsRenderer, autocompleteStart, autocompleteDestination;
    let calculatedFreightValue = 0;

    function initMap() {
        const mapDiv = document.getElementById('map');
        if (!mapDiv) return;

        map = new google.maps.Map(mapDiv, {
            center: { lat: -15.7801, lng: -47.9292 },
            zoom: 5
        });

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: false,
            polylineOptions: {
                strokeColor: '#4285F4',
                strokeOpacity: 1.0,
                strokeWeight: 5
            }
        });
        directionsRenderer.setMap(map);

        initAutocomplete();
    }

    function initAutocomplete() {
        const startAddressInput = document.getElementById('start_address');
        const destinationAddressInput = document.getElementById('destination_address');

        if (!startAddressInput || !destinationAddressInput) return;

        autocompleteStart = new google.maps.places.Autocomplete(startAddressInput, {
            fields: ['address_components', 'geometry'],
            componentRestrictions: { country: 'br' }
        });

        autocompleteDestination = new google.maps.places.Autocomplete(destinationAddressInput, {
            fields: ['address_components', 'geometry'],
            componentRestrictions: { country: 'br' }
        });

        // Cálculo automático quando os endereços mudam
        autocompleteStart.addListener('place_changed', calculateRoute);
        autocompleteDestination.addListener('place_changed', calculateRoute);
    }

    function calculateFreightValue(distanceInKm, truckType) {
        const rates = {
            'pequeno': 3.20,
            'medio': 4.80,
            'grande': 6.50
        };
        
        let value = distanceInKm * rates[truckType];
        value = Math.max(value, 50); // Valor mínimo
        return Math.round(value * 100) / 100;
    }

    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', { 
            style: 'currency', 
            currency: 'BRL',
            minimumFractionDigits: 2
        });
    }

    function calculateRoute() {
        const startPlace = autocompleteStart.getPlace();
        const destinationPlace = autocompleteDestination.getPlace();
        const truckType = $('#truck_type').val();

        if (!startPlace?.geometry || !destinationPlace?.geometry || !truckType) {
            return;
        }

        const startLatLng = startPlace.geometry.location;
        const destinationLatLng = destinationPlace.geometry.location;

        // Atualizar campos ocultos
        $('#current_position').val($('#start_address').val());
        $('#current_lat').val(startLatLng.lat());
        $('#current_lng').val(startLatLng.lng());
        $('#start_lat').val(startLatLng.lat());
        $('#start_lng').val(startLatLng.lng());
        $('#destination_lat').val(destinationLatLng.lat());
        $('#destination_lng').val(destinationLatLng.lng());

        const request = {
            origin: startLatLng,
            destination: destinationLatLng,
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC
        };

        directionsService.route(request, (response, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(response);
                const route = response.routes[0].legs[0];

                // Atualizar informações de distância e tempo
                $('#distance').text(route.distance.text);
                $('#duration').text(route.duration.text);

                // Calcular valor do frete
                const distanceText = route.distance.text.replace(' km', '').replace(',', '.');
                const distanceInKm = parseFloat(distanceText);
                calculatedFreightValue = calculateFreightValue(distanceInKm, truckType);

                // Atualizar campos de valor
                $('#suggested_value').text(formatCurrency(calculatedFreightValue));
                $('#calculated_value').text(formatCurrency(calculatedFreightValue));
                $('#freight_value').val(calculatedFreightValue.toFixed(2));
                $('#final_value').text(formatCurrency(calculatedFreightValue));
            }
        });
    }

    $(document).ready(function() {
        // Atualizar cálculo quando o tipo de caminhão mudar
        $('#truck_type').change(function() {
            if (autocompleteStart.getPlace() && autocompleteDestination.getPlace()) {
                calculateRoute();
            }
        });

        // Quando o valor for alterado manualmente
        $('#freight_value').on('change', function() {
            const manualValue = parseFloat($(this).val());
            if (!isNaN(manualValue)) {
                $('#final_value').text(formatCurrency(manualValue));
            }
        });

        // Validação antes do envio
        $('#freightRequestForm').on('submit', function(e) {
            e.preventDefault();

            const freightValue = parseFloat($('#freight_value').val());
            if (isNaN(freightValue) || freightValue <= 0) {
                alert('Por favor, insira um valor válido para o frete.');
                return;
            }

            // Desativar botão para evitar múltiplos envios
            $('#submitBtn').prop('disabled', true).text('Enviando...');

            // Enviar formulário
            this.submit();
        });
    });
</script>
@endpush

@section('styles')
<style>
    .pac-container {
        z-index: 1051;
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    .pac-item {
        padding: 8px 10px;
        font-size: 14px;
        cursor: pointer;
    }
    .pac-item:hover {
        background-color: #f5f5f5;
    }
    #map {
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    #freight_value {
        font-weight: bold;
        text-align: right;
    }
    .form-control-plaintext {
        padding: 0.375rem 0;
        background-color: transparent;
    }
    .display-5 {
        font-size: 2.5rem;
    }
    .form-select {
        cursor: pointer;
    }
</style>
@endsection