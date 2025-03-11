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

            <div class="mb-3">
                <label class="form-label">Empresa</label>
                <input type="text" class="form-control" value="{{ $shipment->company->name }}" readonly>
            </div>

            <div class="mb-3">
                <label for="start_address">Endereço de Início</label>
                <input id="start_address" name="start_address" type="text" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="destination_address">Endereço de Destino</label>
                <input id="destination_address" name="destination_address" type="text" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Caminhão</label>
                <select id="truck_type" name="truck_type" class="form-control">
                    <option value="pequeno">Pequeno</option>
                    <option value="medio">Médio</option>
                    <option value="grande">Grande</option>
                </select>
            </div>

            <!-- Campos ocultos para coordenadas e status -->
            <input type="hidden" id="current_position" name="current_position">
            <input type="hidden" id="current_lat" name="current_lat">
            <input type="hidden" id="current_lng" name="current_lng">
            <input type="hidden" id="start_lat" name="start_lat">
            <input type="hidden" id="start_lng" name="start_lng">
            <input type="hidden" id="destination_lat" name="destination_lat">
            <input type="hidden" id="destination_lng" name="destination_lng">

            <!-- Mapa para visualização -->
            <div id="map" style="height: 400px; width: 100%; margin-top: 20px;"></div>

            <!-- Exibir Distância e Tempo -->
            <div id="result" style="margin-top: 20px;">
                <h4>Distância e Tempo</h4>
                <p id="distance">Distância: Calculando...</p>
                <p id="duration">Tempo: Calculando...</p>
            </div>

            <button type="submit" class="btn btn-success mt-3">Confirmar Frete</button>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- Carregar jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Carregar a API do Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initMap" async defer></script>

    <script>
        let map, directionsService, directionsRenderer, autocompleteStart, autocompleteDestination;

        // Função para inicializar o mapa
        function initMap() {
            // Verificar se o elemento do mapa existe
            const mapDiv = document.getElementById('map');
            if (!mapDiv) {
                console.error('Elemento do mapa não encontrado.');
                return;
            }

            // Inicializar o mapa
            map = new google.maps.Map(mapDiv, {
                center: { lat: -23.5505, lng: -46.6333 }, // Centro de São Paulo (exemplo)
                zoom: 13
            });

            // Inicializar o serviço de direções
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            // Inicializar o Autocomplete
            initAutocomplete();
        }

        // Função para inicializar o Autocomplete
        function initAutocomplete() {
            const startAddressInput = document.getElementById('start_address');
            const destinationAddressInput = document.getElementById('destination_address');

            if (!startAddressInput || !destinationAddressInput) {
                console.error('Campos de endereço não encontrados.');
                return;
            }

            autocompleteStart = new google.maps.places.Autocomplete(startAddressInput);
            autocompleteDestination = new google.maps.places.Autocomplete(destinationAddressInput);

            autocompleteStart.setFields(['address_components', 'geometry']);
            autocompleteDestination.setFields(['address_components', 'geometry']);

            // Escutar alterações nos campos de endereço
            autocompleteStart.addListener('place_changed', calculateRoute);
            autocompleteDestination.addListener('place_changed', calculateRoute);
        }

        // Função para calcular a rota
        function calculateRoute() {
            const startPlace = autocompleteStart.getPlace();
            const destinationPlace = autocompleteDestination.getPlace();

            if (!startPlace || !startPlace.geometry || !destinationPlace || !destinationPlace.geometry) {
                alert('Por favor, selecione endereços válidos para início e destino.');
                return;
            }

            const startLatLng = startPlace.geometry.location;
            const destinationLatLng = destinationPlace.geometry.location;

            // Preencher os campos ocultos com as coordenadas
            $('#current_position').val($('#start_address').val());
            $('#current_lat').val(startLatLng.lat());
            $('#current_lng').val(startLatLng.lng());
            $('#start_lat').val(startLatLng.lat());
            $('#start_lng').val(startLatLng.lng());
            $('#destination_lat').val(destinationLatLng.lat());
            $('#destination_lng').val(destinationLatLng.lng());

            // Solicitar direções entre os dois pontos
            const request = {
                origin: startLatLng,
                destination: destinationLatLng,
                travelMode: google.maps.TravelMode.DRIVING
            };

            directionsService.route(request, function(response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(response);

                    // Exibir distância e tempo no resultado
                    const route = response.routes[0].legs[0];
                    document.getElementById('distance').textContent = 'Distância: ' + route.distance.text;
                    document.getElementById('duration').textContent = 'Tempo: ' + route.duration.text;
                } else {
                    alert('Não foi possível calcular o trajeto');
                }
            });
        }

        // Submissão do formulário
        $(document).ready(function() {
            $('#freightRequestForm').on('submit', function(e) {
                e.preventDefault();

                // Obter os dados do formulário
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
                    status_id: $('#status_id').val(),
                    distance: $('#distance').text().replace('Distância: ', ''),
                    duration: $('#duration').text().replace('Tempo: ', ''),
                    directions: 'ver depois esta muito grante'
                };

                // Enviar os dados via AJAX
                $.ajax({
                    url: "{{ route('freights.store') }}",
                    method: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        window.location.href = "{{ route('freights') }}";
                    },
                    error: function(xhr) {
                        alert('Erro ao salvar o frete: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endpush

@section('styles')
    <style>
        /* Estilo para o dropdown de sugestões do Autocomplete */
        .pac-container {
            z-index: 1051 !important; /* Garante que o dropdown apareça acima do modal */
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
    </style>
@endsection