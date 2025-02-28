<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Mapa de Fretes</title>
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        #map {
            height: 100vh;
            width: 100vw;
        }
        .gm-style-iw {
            font-size: 14px; /* Ajusta o tamanho do texto nos pop-ups */
            max-width: 200px; /* Evita que o InfoWindow fique muito grande */
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>
        let map;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -23.55052, lng: -46.633308 }, // Centro inicial (São Paulo)
                zoom: 6,
                disableDefaultUI: true, // Remove botões extras do mapa
                gestureHandling: "greedy" // Facilita navegação em dispositivos móveis
            });

            fetchFreights();
        }

        function fetchFreights() {
            $.get('/api/freights', function(data) {
                let bounds = new google.maps.LatLngBounds();
                
                data.forEach(freight => {
                    geocodeAddress(freight.current_position, function(position) {
                        let marker = new google.maps.Marker({
                            position: position,
                            map: map,
                            icon: {
                                    url: "{{ asset('images/carga.png') }}", // Link do ícone de carga
                                    scaledSize: new google.maps.Size(80, 80)
                            }
                        });

                          // Criar a URL de transporte dinamicamente
                        let transportUrl = `{{ route('freights.transport', ['freightId' => '__ID__']) }}`.replace('__ID__', freight.id);


                        let infoWindow = new google.maps.InfoWindow({
                            content: `<strong>Frete ID:</strong> ${freight.id}<br>
                                      <strong>Motorista:</strong> ${freight.driver ? freight.driver.name : 'Não definido'}<br>
                                      <strong>Status:</strong> ${freight.status.name}
                                      <a href="${transportUrl}" class="btn btn-primary">Transportar Carga</a>
                                      `
                        });

                        marker.addListener('click', function() {
                            infoWindow.open(map, marker);
                        });

                        bounds.extend(position);
                        map.fitBounds(bounds); // Ajusta o zoom para mostrar todos os marcadores
                    });
                });
            });
        }

        function geocodeAddress(address, callback) {
            let geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: address }, function(results, status) {
                if (status === 'OK') {
                    callback(results[0].geometry.location);
                } else {
                    console.error("Erro ao geocodificar endereço:", status);
                }
            });
        }

        window.onload = initMap;
    </script>
</body>
</html>
