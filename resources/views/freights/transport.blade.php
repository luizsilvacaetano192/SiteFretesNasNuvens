
    <!-- Descrição da localização -->
    <div id="location-info" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px; background-color: #f8f9fa; border-bottom: 2px solid #ddd;">
        📍 Localização Atual: <span id="user-location">Obtendo localização...</span>
    </div>

    <div class="container-fluid p-0">
        
        <div id="map" style="width: 100%; height: 100vh;"></div>
    </div>

    <!-- Endereços de partida e destino -->
    <div style="margin-top: 15px; text-align: center; font-size: 16px;">
        <strong>🚚 Endereço de Partida:</strong> <span id="start-address">Carregando...</span><br>
        <strong>🏁 Endereço de Destino:</strong> <span id="destination-address">Carregando...</span>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places, directions"></script>
    
id    <script>
        let map;
        let directionsService;
        let directionsRenderer;
        let geocoder;
        let freightId = 1;

        function initMap() {
            // Criando o mapa
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 7,
                center: { lat: -23.55052, lng: -46.633308 }, // São Paulo como exemplo
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: true // Impede a criação de marcadores adicionais
            });

            // Carregar a rota
            loadRoute();
        }

        // Carregar a rota a partir dos dados do frete
        function loadRoute() {
            // Recuperando os dados do frete (endereço de origem e destino)
            let startAddress = '{{ $freight->shipment->start_address }}'; // Endereço de início
            let destinationAddress = '{{ $freight->shipment->destination_address }}'; // Endereço de destino


            document.getElementById("start-address").innerText = startAddress;
            document.getElementById("destination-address").innerText = destinationAddress;

            directionsService.route(
                {
                    origin: startAddress,
                    destination: destinationAddress,
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (response, status) => {
                    if (status === "OK") {
                        directionsRenderer.setDirections(response);

                        let route = response.routes[0].legs[0];
                        console.log("Distância:", route.distance.text);
                        console.log("Duração:", route.duration.text);
                        // Adicionar marcador no início da rota
                        new google.maps.Marker({
                            position: route.start_location,
                            map: map,
                            icon: {
                                url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png", // Ícone verde para início
                                scaledSize: new google.maps.Size(40, 40),
                            },
                            title: "Ponto de Partida",
                        });

                        // Adicionar marcador no final da rota
                        new google.maps.Marker({
                            position: route.end_location,
                            map: map,
                            icon: {
                                url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png", // Ícone vermelho para destino
                                scaledSize: new google.maps.Size(40, 40),
                            },
                            title: "Destino Final",
                        });
                          // Iniciar rastreamento da localização do usuário
                        trackUserLocation(map);
                    } else {
                        console.error("Erro ao calcular rota:", status);
                    }
                }
            );
        }

        window.onload = initMap; // Inicia o mapa ao carregar a página



        /// ffdfdfdffdfd

        let userMarker; // Variável para armazenar o marcador do usuário


        function saveCurrentPosition(freightId, address) {
       
        $.ajax({
            url: `/api/freights/${freightId}/update-position`,
            type: "POST",
            data: { current_position: address },
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success: function(response) {
                console.log("Localização salva:", response);
            },
            error: function(error) {
                console.error("Erro ao salvar localização:", error);
            }
        });
    }

function trackUserLocation(map) {
   
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            (position) => {
                let userLatLng = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                if (!userMarker) {
                    // Criar marcador na primeira vez
                    userMarker = new google.maps.Marker({
                        position: userLatLng,
                        map: map,
                        icon: {
                           url: "https://img.icons8.com/ios-filled/50/000000/truck.png", // Ícone azul
                            scaledSize: new google.maps.Size(40, 40),
                        },
                        title: "Sua localização",
                    });
                } else {
                    // Atualizar a posição do marcador
                    userMarker.setPosition(userLatLng);
                }

                // Ajustar o mapa para focar na localização do usuário
                map.setCenter(userLatLng);

         

                getAddressFromCoordinates(userLatLng.lat, userLatLng.lng, "user-location", 
                function(address) {
                      
                        saveCurrentPosition('{{ $freight->id }}', address);
                    });
            },
            (error) => {
                console.error("Erro ao obter localização:", error);
            },
            {
                enableHighAccuracy: true, // Maior precisão possível
                maximumAge: 0, // Não usar posição em cache
                timeout: 5000, // Tempo limite de 5 segundos
            }
        );
    } else {
        alert("Seu navegador não suporta geolocalização.");
    }
}

// Chamar a função ao carregar o mapa
function loadMap(startAddress, destinationAddress) {
    let map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: { lat: -23.55052, lng: -46.633308 }, // Localização inicial genérica (São Paulo)
    });

    let directionsService = new google.maps.DirectionsService();
    let directionsRenderer = new google.maps.DirectionsRenderer({ map: map });

    directionsService.route(
        {
            origin: startAddress,
            destination: destinationAddress,
            travelMode: google.maps.TravelMode.DRIVING,
        },
        (response, status) => {
            if (status === "OK") {
                directionsRenderer.setDirections(response);
                let route = response.routes[0].legs[0];

                document.getElementById("distance").innerText = route.distance.text;
                document.getElementById("duration").innerText = route.duration.text;

                // Adicionar marcador no início da rota
                new google.maps.Marker({
                    position: route.start_location,
                    map: map,
                    icon: {
                        url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png", // Ícone verde
                        scaledSize: new google.maps.Size(40, 40),
                    },
                    title: "Ponto de Partida",
                });

                // Adicionar marcador no final da rota
                new google.maps.Marker({
                    position: route.end_location,
                    map: map,
                    icon: {
                        url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png", // Ícone vermelho
                        scaledSize: new google.maps.Size(40, 40),
                    },
                    title: "Destino Final",
                });

                // Iniciar rastreamento da localização do usuário
                trackUserLocation(map);
            } else {
                console.error("Erro ao calcular rota:", status);
            }
        }
    );
}

// Função para obter endereço a partir das coordenadas
function getAddressFromCoordinates(lat, lng, elementId, callback) {
        if (!geocoder) {
            geocoder = new google.maps.Geocoder();
        }

        let latLng = new google.maps.LatLng(lat, lng);

        geocoder.geocode({ location: latLng }, function (results, status) {
            if (status === "OK" && results[0]) {
                let address = results[0].formatted_address;
                document.getElementById(elementId).innerText = address;
                if (callback) callback(address);
            } else {
                console.error("Erro na geocodificação:", status);
            }
        });
    }

    </script>


