@extends('layouts.app')

@section('title', 'Solicitar Frete')

@section('content')
    <div class="container">
        <h1>Solicitar Frete para Carga #{{ $shipment->id }}</h1>

        <form id="freightRequestForm">
            @csrf
            <input type="hidden" id="shipment_id" value="{{ $shipment->id }}">
            <input type="hidden" id="company_id" value="{{ $shipment->company->id }}">
            <input type="hidden" id="status_id" value="2"> <!-- Status ID fixo -->

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informações da Carga</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Empresa</label>
                            <input type="text" class="form-control" value="{{ $shipment->company->name }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Carga</label>
                            <input type="text" class="form-control" value="{{ $shipment->load_type }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Detalhes do Frete</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_address" class="form-label">Endereço de Coleta</label>
                            <input id="start_address" name="start_address" type="text" class="form-control" required>
                            <small class="text-muted">Digite o endereço completo</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="destination_address" class="form-label">Endereço de Entrega</label>
                            <input id="destination_address" name="destination_address" type="text" class="form-control" required>
                            <small class="text-muted">Digite o endereço completo</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Caminhão</label>
                            <select id="truck_type" name="truck_type" class="form-control">
                                <option value="pequeno">Pequeno (até 3 ton)</option>
                                <option value="medio">Médio (3-8 ton)</option>
                                <option value="grande">Grande (8+ ton)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="freight_value" class="form-label">Valor do Frete (R$)</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" id="freight_value" name="freight_value" class="form-control" readonly>
                            </div>
                            <small class="text-muted">Calculado automaticamente</small>
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

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Rota do Frete</h5>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px; width: 100%;"></div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Resumo</h5>
                </div>
                <div class="card-body">
                    <div id="result">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="alert alert-info">
                                    <h6>Distância</h6>
                                    <p id="distance" class="h4">Calculando...</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-info">
                                    <h6>Tempo Estimado</h6>
                                    <p id="duration" class="h4">Calculando...</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-success">
                                    <h6>Valor Total</h6>
                                    <p id="freight_value_display" class="h4">R$ 0,00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-secondary me-md-2" onclick="window.history.back()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Confirmar Frete</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initMap" async defer></script>

    <script>
        let map, directionsService, directionsRenderer, autocompleteStart, autocompleteDestination;

        function initMap() {
            const mapDiv = document.getElementById('map');
            if (!mapDiv) return;

            map = new google.maps.Map(mapDiv, {
                center: { lat: -23.5505, lng: -46.6333 },
                zoom: 13
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: false,
                polylineOptions: {
                    strokeColor: '#4285F4',
                    strokeOpacity: 1.0,
                    strokeWeight: 4
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
            
            // Adicionar taxa mínima
            value = Math.max(value, 50);
            
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

            if (!startPlace?.geometry || !destinationPlace?.geometry) {
                alert('Por favor, selecione endereços válidos para coleta e entrega.');
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
                unitSystem: google.maps.UnitSystem.METRIC,
                provideRouteAlternatives: false
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
                    const truckType = $('#truck_type').val();
                    const freightValue = calculateFreightValue(distanceInKm, truckType);

                    // Atualizar campo de valor
                    $('#freight_value').val(freightValue.toFixed(2));
                    $('#freight_value_display').text(formatCurrency(freightValue));
                } else {
                    alert('Não foi possível calcular o trajeto. Verifique os endereços.');
                }
            });
        }

        $(document).ready(function() {
            // Recalcular valor quando mudar o tipo de caminhão
            $('#truck_type').change(function() {
                const distanceText = $('#distance').text().replace(' km', '').replace(',', '.');
                const distanceInKm = parseFloat(distanceText);
                
                if (!isNaN(distanceInKm)) {
                    const truckType = $(this).val();
                    const freightValue = calculateFreightValue(distanceInKm, truckType);
                    
                    $('#freight_value').val(freightValue.toFixed(2));
                    $('#freight_value_display').text(formatCurrency(freightValue));
                }
            });

            // Envio do formulário
            $('#freightRequestForm').on('submit', function(e) {
                e.preventDefault();

                if (!$('#freight_value').val() || $('#freight_value').val() == '0.00') {
                    alert('Por favor, calcule a rota antes de enviar.');
                    return;
                }

                const formData = {
                    shipment_id: $('#shipment_id').val(),
                    company_id: $('#company_id').val(),
                    start_address: $('#start_address').val(),
                    destination_address: $('#destination_address').val(),
                    current_position: $('#current_position').val(),
                    current_lat: $('#current_lat').val(),
                    current_lng: $('#current_lng').val(),
                    start_lat: $('#start_lat').val(),
                    start_lng: $('#start_lng').val(),
                    destination_lat: $('#destination_lat').val(),
                    destination_lng: $('#destination_lng').val(),
                    truck_type: $('#truck_type').val(),
                    freight_value: $('#freight_value').val(),
                    status_id: $('#status_id').val(),
                    distance: $('#distance').text(),
                    duration: $('#duration').text()
                };

                $.ajax({
                    url: "{{ route('freights.store') }}",
                    method: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert('Frete criado com sucesso! ID: ' + response.id);
                        window.location.href = "{{ route('freights.index') }}";
                    },
                    error: function(xhr) {
                        let errorMsg = 'Erro ao salvar o frete';
                        if (xhr.responseJSON?.message) {
                            errorMsg += ': ' + xhr.responseJSON.message;
                        }
                        alert(errorMsg);
                        console.error(xhr.responseJSON);
                    }
                });
            });
        });
    </script>
@endpush

@section('styles')
    <style>
        .pac-container {
            z-index: 1051;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }
        .pac-item {
            padding: 10px;
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        #freight_value {
            font-weight: bold;
            background-color: #f8f9fa;
        }
    </style>
@endsection