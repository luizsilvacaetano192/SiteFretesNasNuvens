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

        <!-- Campos ocultos para coordenadas e informações da rota -->
        <input type="hidden" id="current_position" name="current_position">
        <input type="hidden" id="current_lat" name="current_lat">
        <input type="hidden" id="current_lng" name="current_lng">
        <input type="hidden" id="start_lat" name="start_lat">
        <input type="hidden" id="start_lng" name="start_lng">
        <input type="hidden" id="destination_lat" name="destination_lat">
        <input type="hidden" id="destination_lng" name="destination_lng">
        <input type="hidden" id="distance_value" name="distance">
        <input type="hidden" id="duration_value" name="duration">
        <input type="hidden" id="distance_km" name="distance_km">
        <input type="hidden" id="duration_min" name="duration_min">

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
                <i class="fas fa-external-link-alt me-2"></i> Confirmar e Pagar
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initMap" async defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

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

    function parseDuration(durationText) {
        let minutes = 0;
        const hoursMatch = durationText.match(/(\d+)\s*h/);
        const minsMatch = durationText.match(/(\d+)\s*m/);
        
        if (hoursMatch) minutes += parseInt(hoursMatch[1]) * 60;
        if (minsMatch) minutes += parseInt(minsMatch[1]);
        
        return minutes;
    }

    function calculateRoute() {
        const startPlace = autocompleteStart.getPlace();
        const destinationPlace = autocompleteDestination.getPlace();
        const truckType = $('#truck_type').val();

        if (!startPlace?.geometry || !destinationPlace?.geometry || !truckType) {
            return;
        }

        const request = {
            origin: startPlace.geometry.location,
            destination: destinationPlace.geometry.location,
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC
        };

        directionsService.route(request, (response, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(response);
                const route = response.routes[0].legs[0];

                // Atualiza a exibição
                $('#distance').text(route.distance.text);
                $('#duration').text(route.duration.text);
                
                // Calcula valores numéricos
                const distanceKm = parseFloat(route.distance.text.replace(' km', '').replace(',', '.'));
                const durationMin = parseDuration(route.duration.text);
                
                // Preenche os campos ocultos
                document.getElementById('distance_value').value = route.distance.text;
                document.getElementById('duration_value').value = route.duration.text;
                document.getElementById('distance_km').value = distanceKm;
                document.getElementById('duration_min').value = durationMin;
                document.getElementById('current_position').value = $('#start_address').val();
                document.getElementById('current_lat').value = startPlace.geometry.location.lat();
                document.getElementById('current_lng').value = startPlace.geometry.location.lng();
                document.getElementById('start_lat').value = startPlace.geometry.location.lat();
                document.getElementById('start_lng').value = startPlace.geometry.location.lng();
                document.getElementById('destination_lat').value = destinationPlace.geometry.location.lat();
                document.getElementById('destination_lng').value = destinationPlace.geometry.location.lng();

                // Calcular valor do frete
                calculatedFreightValue = calculateFreightValue(distanceKm, truckType);

                // Atualizar campos de valor
                $('#suggested_value').text(formatCurrency(calculatedFreightValue));
                $('#calculated_value').text(formatCurrency(calculatedFreightValue));
                $('#freight_value').val(calculatedFreightValue.toFixed(2));
                $('#final_value').text(formatCurrency(calculatedFreightValue));
            }
        });
    }

    $(document).ready(function() {
        $('#truck_type').change(function() {
            if (autocompleteStart?.getPlace() && autocompleteDestination?.getPlace()) {
                calculateRoute();
            }
        });

        $('#freight_value').on('change', function() {
            const manualValue = parseFloat($(this).val());
            if (!isNaN(manualValue)) {
                $('#final_value').text(formatCurrency(manualValue));
            }
        });

        $('#freightRequestForm').on('submit', function(e) {
            e.preventDefault();
            
            if (!$('#distance_value').val() || !$('#duration_value').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção',
                    text: 'Por favor, calcule a rota antes de enviar o formulário.',
                });
                return;
            }

            $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...');

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('resposta data', data);
                if (data.payment_link) {
                    // Abre o pagamento em nova aba
                    const paymentWindow = window.open(data.payment_link, '_blank');
                    
                    // Verifica se o popup foi bloqueado
                    if (!paymentWindow || paymentWindow.closed || typeof paymentWindow.closed == 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Popup bloqueado',
                            text: 'Por favor, permita popups para este site para completar o pagamento.',
                        });
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-external-link-alt me-2"></i> Confirmar e Pagar');
                        return;
                    }
                    
                    // Redireciona após o pagamento
                    setTimeout(() => {
                        window.location.href = '{{ route("freights") }}';
                    }, 3000);
                } else {
                    $('#submitBtn').prop('disabled', false).html('<i class="fas fa-external-link-alt me-2"></i> Confirmar e Pagar');
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message || 'Erro ao processar o pagamento',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-external-link-alt me-2"></i> Confirmar e Pagar');
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro ao enviar o formulário',
                });
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
    .swal2-popup.swal2-toast {
        padding: 1em 1.5em;
    }
</style>
@endsection