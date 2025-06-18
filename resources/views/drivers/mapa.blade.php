@extends('layouts.app') {{-- Assumindo que seu layout principal é o código que você forneceu --}}

@section('title', 'Mapa de Motoristas')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">Mapa de Motoristas</h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div id="map" style="height: 600px; width: 100%;"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Carrega a API do Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=SUA_CHAVE_DE_API&callback=initMap&libraries=places&v=weekly" async defer></script>

<script>
    let map;
    let geocoder;
    let markers = [];
    
    function initMap() {
        // Configuração inicial do mapa
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: { lat: -15.788, lng: -47.879 }, // Centro do Brasil
        });
        
        geocoder = new google.maps.Geocoder();
        
        // Busca os motoristas do backend
        fetch('/api/drivers')
            .then(response => response.json())
            .then(drivers => {
                drivers.forEach(driver => {
                    if (driver.address) {
                        geocodeAddress(driver);
                    }
                });
            });
    }
    
    function geocodeAddress(driver) {
        geocoder.geocode({ 'address': driver.address }, (results, status) => {
            if (status === 'OK') {
                const marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    title: driver.name
                });
                
                // Adiciona info window
                const infowindow = new google.maps.InfoWindow({
                    content: `
                        <div style="min-width: 200px">
                            <h5>${driver.name}</h5>
                            <p><strong>Endereço:</strong> ${driver.address}</p>
                            <p><strong>Telefone:</strong> ${driver.phone}</p>
                            <p><strong>CNH:</strong> ${driver.driver_license_number}</p>
                        </div>
                    `
                });
                
                marker.addListener('click', () => {
                    infowindow.open(map, marker);
                });
                
                markers.push(marker);
            } else {
                console.error('Geocode falhou para o motorista ' + driver.name + ': ' + status);
            }
        });
    }
</script>
@endsection