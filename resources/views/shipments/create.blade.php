@extends('layouts.app')

@section('title', 'Create Shipment')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('shipments.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">Company</label>
        <select name="company_id" class="form-control">
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Driver</label>
        <select name="driver_id" class="form-control">
            <option value="">Select a driver</option>
            @foreach($drivers as $driver)
                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Weight (kg)</label>
        <input type="text" name="weight" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Cargo Type</label>
        <input type="text" name="cargo_type" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Dimensions</label>
        <input type="text" name="dimensions" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Volume (m³)</label>
        <input type="text" name="volume" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Truck Type</label>
        <input type="text" name="truck_type" class="form-control">
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
        <label class="form-label">Expected Start Date</label>
        <input type="datetime-local" name="expected_start_date" id="expected_start_date" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Expected Delivery Date</label>
        <input type="datetime-local" name="expected_delivery_date" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Deadline (hours or days)</label>
        <input type="number" name="deadline" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Start Time</label>
        <input type="time" name="start_time" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
</form>

 <!-- Mapa para visualização -->
 <div id="map" style="height: 400px; width: 100%; margin-top: 20px;"></div>

<!-- Exibir Distância e Tempo -->
<div id="result" style="margin-top: 20px;">
    <h4>Distância e Tempo</h4>
    <p id="distance">Distância: Calculando...</p>
    <p id="duration">Tempo: Calculando...</p>
</div>


@endsection
<!-- Script do Google Maps API (adicionar ao final do arquivo, antes de </body>) -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places,directions&callback=initMap" async defer></script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initAutocomplete" async defer></script>


<script>
   let map, directionsService, directionsRenderer, autocompleteStart, autocompleteDestination;

function initMap() {
    // Inicializar o mapa centrado em um ponto arbitrário
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: -23.5505, lng: -46.6333 }, // Centro de São Paulo (por exemplo)
        zoom: 13
    });

    // Inicializar o serviço de direções
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    // Inicializar os campos de autocompletamento
    const startAddressInput = document.getElementById('start_address');
    const destinationAddressInput = document.getElementById('destination_address');

    autocompleteStart = new google.maps.places.Autocomplete(startAddressInput);
    autocompleteDestination = new google.maps.places.Autocomplete(destinationAddressInput);

    autocompleteStart.setFields(['address_components', 'geometry']);
    autocompleteDestination.setFields(['address_components', 'geometry']);

    // Escutar alterações nos campos de endereço
    autocompleteStart.addListener('place_changed', calculateRoute);
    autocompleteDestination.addListener('place_changed', calculateRoute);
}

function calculateRoute() {
    // Obter o lugar selecionado para o endereço de início
    const startPlace = autocompleteStart.getPlace();
    const destinationPlace = autocompleteDestination.getPlace();

    if (!startPlace.geometry || !destinationPlace.geometry) {
        return;
    }

    const startLatLng = startPlace.geometry.location;
    const destinationLatLng = destinationPlace.geometry.location;

    // Definir os ícones personalizados para o início e o fim
    const startIcon = {
        url: 'https://img.icons8.com/ios-filled/50/000000/package.png',  // Palete de carga
        scaledSize: new google.maps.Size(40, 40),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(20, 40)
    };
    const destinationIcon = {
        url: 'https://img.icons8.com/ios-filled/50/000000/package.png',
        scaledSize: new google.maps.Size(40, 40),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(20, 40)
    };

    // Colocar marcadores no mapa
    new google.maps.Marker({
        position: startLatLng,
        map: map,
        icon: startIcon,
        title: 'Início'
    });
    new google.maps.Marker({
        position: destinationLatLng,
        map: map,
        icon: destinationIcon,
        title: 'Destino'
    });

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
</script>

