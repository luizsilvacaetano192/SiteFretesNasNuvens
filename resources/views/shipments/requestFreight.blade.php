@extends('layouts.app')

@section('title', 'Solicitar Frete')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="mb-0">Solicitar Frete para Carga #{{ $shipment->id }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('shipments.index') }}">Cargas</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Solicitar Frete</li>
                    </ol>
                </nav>
            </div>
        </div>

        <form id="freightRequestForm" method="POST" action="{{ route('freights.store') }}">
            @csrf
            <input type="hidden" name="shipment_id" value="{{ $shipment->id }}">
            <input type="hidden" name="company_id" value="{{ $shipment->company->id }}">
            <input type="hidden" name="status_id" value="2">

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Informações da Carga</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Empresa Contratante</label>
                            <input type="text" class="form-control-plaintext" value="{{ $shipment->company->name }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Tipo de Carga</label>
                            <input type="text" class="form-control-plaintext" value="{{ ucfirst($shipment->load_type) }}" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Peso Estimado</label>
                            <input type="text" class="form-control-plaintext" value="{{ $shipment->weight }} kg" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Descrição da Carga</label>
                            <textarea class="form-control-plaintext" rows="2" readonly>{{ $shipment->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-map-marked-alt me-2"></i>Endereços</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_address" class="form-label fw-bold">Origem (Coleta)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input id="start_address" name="start_address" type="text" class="form-control" required placeholder="Digite o endereço completo de coleta">
                            </div>
                            <small class="text-muted">Ex: Rua, número, bairro, cidade - Estado</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="destination_address" class="form-label fw-bold">Destino (Entrega)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-flag-checkered"></i></span>
                                <input id="destination_address" name="destination_address" type="text" class="form-control" required placeholder="Digite o endereço completo de entrega">
                            </div>
                            <small class="text-muted">Ex: Rua, número, bairro, cidade - Estado</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Configuração do Frete</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="truck_type" class="form-label fw-bold">Tipo de Veículo</label>
                            <select id="truck_type" name="truck_type" class="form-select" required>
                                <option value="" disabled selected>Selecione o tipo de veículo</option>
                                <option value="pequeno">Pequeno (Até 3 toneladas)</option>
                                <option value="medio">Médio (3 a 8 toneladas)</option>
                                <option value="grande">Grande (Acima de 8 toneladas)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="freight_value" class="form-label fw-bold">Valor do Frete (R$)</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" id="freight_value" name="freight_value" class="form-control" step="0.01" min="0" required>
                                <button type="button" id="resetFreightValue" class="btn btn-outline-secondary" title="Reverter para valor calculado" disabled>
                                    <i class="fas fa-undo"></i>
                                </button>
                            </div>
                            <small class="text-muted">Valor calculado: <span id="calculated_value">R$ 0,00</span></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="notes" class="form-label fw-bold">Observações</label>
                            <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Informações adicionais sobre o frete"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" id="current_position" name="current_position">
            <input type="hidden" id="current_lat" name="current_lat">
            <input type="hidden" id="current_lng" name="current_lng">
            <input type="hidden" id="start_lat" name="start_lat">
            <input type="hidden" id="start_lng" name="start_lng">
            <input type="hidden" id="destination_lat" name="destination_lat">
            <input type="hidden" id="destination_lng" name="destination_lng">

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-route me-2"></i>Rota e Detalhes</h5>
                    <button type="button" id="calculateRouteBtn" class="btn btn-light btn-sm">
                        <i class="fas fa-route me-1"></i> Calcular Rota
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning" id="routeAlert" style="display: none;">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span id="routeAlertText">Por favor, informe os endereços de origem e destino para calcular a rota.</span>
                    </div>
                    
                    <div id="map" style="height: 400px; width: 100%; margin-bottom: 20px;"></div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-road me-1"></i>Distância</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p id="distance" class="h4 mb-0">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-clock me-1"></i>Tempo Estimado</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p id="duration" class="h4 mb-0">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-dollar-sign me-1"></i>Valor Sugerido</h6>
                                </div>
                                <div class="card-body text-center">
                                    <p id="suggested_value" class="h4 mb-0">R$ 0,00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
                <button type="button" class="btn btn-secondary me-md-2" onclick="window.history.back()">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-check me-1"></i> Confirmar Frete
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initMap" async defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

    <script>
        let map, directionsService, directionsRenderer, autocompleteStart, autocompleteDestination;
        let calculatedFreightValue = 0;
        let routeCalculated = false;

        function initMap() {
            const mapDiv = document.getElementById('map');
            if (!mapDiv) return;

            map = new google.maps.Map(mapDiv, {
                center: { lat: -15.7801, lng: -47.9292 }, // Centro do Brasil
                zoom: 5,
                gestureHandling: "cooperative",
                mapTypeControl: true,
                streetViewControl: false,
                fullscreenControl: true
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: false,
                polylineOptions: {
                    strokeColor: '#4285F4',
                    strokeOpacity: 1.0,
                    strokeWeight: 5
                },
                markerOptions: {
                    icon: {
                        url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png"
                    }
                },
                suppressInfoWindows: true
            });
            directionsRenderer.setMap(map);

            initAutocomplete();
        }

        function initAutocomplete() {
            const startAddressInput = document.getElementById('start_address');
            const destinationAddressInput = document.getElementById('destination_address');

            if (!startAddressInput || !destinationAddressInput) return;

            autocompleteStart = new google.maps.places.Autocomplete(startAddressInput, {
                fields: ['address_components', 'geometry', 'name'],
                componentRestrictions: { country: 'br' },
                types: ['address']
            });

            autocompleteDestination = new google.maps.places.Autocomplete(destinationAddressInput, {
                fields: ['address_components', 'geometry', 'name'],
                componentRestrictions: { country: 'br' },
                types: ['address']
            });

            // Adiciona um marcador para a origem
            const startMarker = new google.maps.Marker({
                map: map,
                anchorPoint: new google.maps.Point(0, -29),
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png"
                }
            });

            // Adiciona um marcador para o destino
            const destMarker = new google.maps.Marker({
                map: map,
                anchorPoint: new google.maps.Point(0, -29),
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                }
            });

            autocompleteStart.addListener('place_changed', function() {
                const place = autocompleteStart.getPlace();
                if (!place.geometry) {
                    window.alert("Endereço não encontrado: '" + place.name + "'");
                    return;
                }
                
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                
                startMarker.setPosition(place.geometry.location);
                startMarker.setVisible(true);
                
                $('#routeAlert').hide();
            });

            autocompleteDestination.addListener('place_changed', function() {
                const place = autocompleteDestination.getPlace();
                if (!place.geometry) {
                    window.alert("Endereço não encontrado: '" + place.name + "'");
                    return;
                }
                
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                
                destMarker.setPosition(place.geometry.location);
                destMarker.setVisible(true);
                
                $('#routeAlert').hide();
            });
        }

        function calculateFreightValue(distanceInKm, truckType) {
            const rates = {
                'pequeno': 3.20,
                'medio': 4.80,
                'grande': 6.50
            };
            
            if (!rates[truckType]) return 0;
            
            let value = distanceInKm * rates[truckType];
            
            // Adicionar taxa mínima
            value = Math.max(value, 50);
            
            // Arredondar para múltiplo de 5
            value = Math.ceil(value / 5) * 5;
            
            return value;
        }

        function formatCurrency(value) {
            return value.toLocaleString('pt-BR', { 
                style: 'currency', 
                currency: 'BRL',
                minimumFractionDigits: 2
            });
        }

        function showRouteAlert(message, type = 'warning') {
            const alert = $('#routeAlert');
            alert.removeClass('alert-warning alert-danger alert-success').addClass(`alert-${type}`);
            $('#routeAlertText').html(`<i class="fas fa-exclamation-circle me-2"></i>${message}`);
            alert.show();
        }

        function calculateRoute() {
            const startPlace = autocompleteStart.getPlace();
            const destinationPlace = autocompleteDestination.getPlace();
            const truckType = $('#truck_type').val();

            if (!startPlace?.geometry || !destinationPlace?.geometry) {
                showRouteAlert('Por favor, selecione endereços válidos para origem e destino.');
                return;
            }

            if (!truckType) {
                showRouteAlert('Por favor, selecione o tipo de veículo.');
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

            showRouteAlert('Calculando rota...', 'info');

            const request = {
                origin: startLatLng,
                destination: destinationLatLng,
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                provideRouteAlternatives: false,
                avoidHighways: false,
                avoidTolls: false
            };

            directionsService.route(request, (response, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(response);
                    const route = response.routes[0].legs[0];
                    routeCalculated = true;

                    // Atualizar informações de distância e tempo
                    $('#distance').text(route.distance.text);
                    $('#duration').text(route.duration.text);

                    // Calcular valor do frete
                    const distanceText = route.distance.text.replace(' km', '').replace(',', '.');
                    const distanceInKm = parseFloat(distanceText);
                    calculatedFreightValue = calculateFreightValue(distanceInKm, truckType);

                    // Atualizar campos de valor
                    $('#calculated_value').text(formatCurrency(calculatedFreightValue));
                    $('#suggested_value').text(formatCurrency(calculatedFreightValue));
                    
                    // Se o campo de valor estiver vazio ou com valor padrão, atualize
                    if (!$('#freight_value').val() || $('#freight_value').val() == '0') {
                        $('#freight_value').val(calculatedFreightValue.toFixed(2));
                    }

                    // Habilitar botão de reset
                    $('#resetFreightValue').prop('disabled', false);

                    showRouteAlert('Rota calculada com sucesso!', 'success');
                    setTimeout(() => $('#routeAlert').fadeOut(), 3000);
                } else {
                    routeCalculated = false;
                    showRouteAlert('Não foi possível calcular o trajeto. Verifique os endereços.', 'danger');
                }
            });
        }

        $(document).ready(function() {
            // Calcular rota quando o botão for clicado
            $('#calculateRouteBtn').click(calculateRoute);

            // Recalcular valor sugerido quando mudar o tipo de caminhão
            $('#truck_type').change(function() {
                if (routeCalculated) {
                    const distanceText = $('#distance').text().replace(' km', '').replace(',', '.');
                    const distanceInKm = parseFloat(distanceText);
                    const truckType = $(this).val();
                    calculatedFreightValue = calculateFreightValue(distanceInKm, truckType);
                    
                    $('#calculated_value').text(formatCurrency(calculatedFreightValue));
                    $('#suggested_value').text(formatCurrency(calculatedFreightValue));
                }
            });

            // Botão para reverter para o valor calculado
            $('#resetFreightValue').click(function() {
                if (calculatedFreightValue > 0) {
                    $('#freight_value').val(calculatedFreightValue.toFixed(2));
                    $(this).prop('disabled', true);
                }
            });

            // Formatar valor manualmente inserido
            $('#freight_value').on('change', function() {
                let value = parseFloat($(this).val());
                if (isNaN(value) || value < 0) {
                    value = 0;
                }
                $(this).val(value.toFixed(2));
                
                // Habilitar/desabilitar botão de reset
                const currentValue = parseFloat($(this).val());
                $('#resetFreightValue').prop('disabled', 
                    currentValue.toFixed(2) === calculatedFreightValue.toFixed(2) || !routeCalculated
                );
            });

            // Validação antes do envio
            $('#freightRequestForm').on('submit', function(e) {
                e.preventDefault();

                if (!$('#start_address').val() || !$('#destination_address').val()) {
                    showRouteAlert('Por favor, informe os endereços de origem e destino.', 'danger');
                    return;
                }

                if (!$('#truck_type').val()) {
                    showRouteAlert('Por favor, selecione o tipo de veículo.', 'danger');
                    return;
                }

                if (!routeCalculated) {
                    showRouteAlert('Por favor, calcule a rota antes de enviar.', 'danger');
                    return;
                }

                const freightValue = parseFloat($('#freight_value').val());
                if (isNaN(freightValue) || freightValue <= 0) {
                    showRouteAlert('Por favor, insira um valor válido para o frete.', 'danger');
                    return;
                }

                // Desativar botão para evitar múltiplos envios
                $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processando...');

                // Coletar todos os dados do formulário
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
                    freight_value: freightValue,
                    status_id: $('#status_id').val(),
                    distance: $('#distance').text(),
                    duration: $('#duration').text(),
                    suggested_value: calculatedFreightValue,
                    notes: $('#notes').val()
                };

                // Enviar via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = "{{ route('freights.index') }}";
                        } else {
                            showRouteAlert(response.message || 'Erro ao processar o frete.', 'danger');
                            $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check me-1"></i> Confirmar Frete');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Erro ao enviar o formulário';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg += ': ' + xhr.responseJSON.message;
                        } else if (xhr.statusText) {
                            errorMsg += ' (' + xhr.statusText + ')';
                        }
                        showRouteAlert(errorMsg, 'danger');
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check me-1"></i> Confirmar Frete');
                        console.error(xhr);
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
            border: 1px solid #ddd;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            font-family: Arial, sans-serif;
        }
        .pac-item {
            padding: 8px 10px;
            font-size: 14px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        .pac-item:hover {
            background-color: #f5f5f5;
        }
        .pac-item-query {
            font-size: 14px;
            color: #333;
        }
        #map {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            padding: 1rem 1.5rem;
        }
        #freight_value {
            font-weight: bold;
            text-align: right;
        }
        #resetFreightValue {
            transition: all 0.3s;
        }
        #resetFreightValue:not(:disabled):hover {
            background-color: #e9ecef;
            transform: rotate(-90deg);
        }
        .alert {
            border-radius: 8px;
            border-left: 5px solid;
        }
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        .form-control-plaintext {
            padding: 0.375rem 0;
        }
        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>
@endsection