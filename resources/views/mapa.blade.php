<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Rotas com Caminhão</title>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places"></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h2>Buscar Rota</h2>
    <input id="start" type="text" placeholder="Endereço Inicial" size="50">
    <input id="end" type="text" placeholder="Endereço Final" size="50">
    <button onclick="calculateRoute()">Calcular Rota</button>

    <h2>Posição do Caminhão</h2>
    <input id="truckLocation" type="text" placeholder="Definir posição do caminhão" size="50">

    <div id="map"></div>
    <p id="output"></p>

    <script>
        let map, directionsService, directionsRenderer, truckMarker;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -23.55052, lng: -46.633308 },
                zoom: 12
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            const startInput = document.getElementById("start");
            const endInput = document.getElementById("end");
            const truckInput = document.getElementById("truckLocation");

            new google.maps.places.Autocomplete(startInput);
            new google.maps.places.Autocomplete(endInput);
            const truckAutocomplete = new google.maps.places.Autocomplete(truckInput);

            // Criar o marcador do caminhão com ícone personalizado
            truckMarker = new google.maps.Marker({
                map: map,
                icon: {
                    url: "https://img.icons8.com/ios-filled/50/000000/truck.png", // Ícone de caminhão
                    scaledSize: new google.maps.Size(50, 50)
                }
            });

            // Atualizar a posição do caminhão quando um local for selecionado
            truckAutocomplete.addListener("place_changed", function() {
                const place = truckAutocomplete.getPlace();
                if (!place.geometry) {
                    alert("Selecione um local válido.");
                    return;
                }

                truckMarker.setPosition(place.geometry.location);
                map.setCenter(place.geometry.location);
                map.setZoom(14);
            });
        }

        function calculateRoute() {
            const start = document.getElementById("start").value;
            const end = document.getElementById("end").value;

            if (start === "" || end === "") {
                alert("Por favor, insira os endereços.");
                return;
            }

            const request = {
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode.DRIVING
            };

            directionsService.route(request, (result, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                    const route = result.routes[0].legs[0];
                    document.getElementById("output").innerHTML =
                        `<strong>Distância:</strong> ${route.distance.text} <br> 
                         <strong>Tempo Estimado:</strong> ${route.duration.text}`;
                } else {
                    alert("Não foi possível calcular a rota.");
                }
            });
        }

        window.onload = initMap;
    </script>
</body>
</html>
