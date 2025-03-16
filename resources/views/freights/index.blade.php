@extends('layouts.app')

@section('title', 'Lista de Fretes')

@section('content')
    <div class="container">
        <h1>Lista de Fretes</h1>
        <button id="delete-all-freights" class="btn btn-danger mb-3">Excluir Todos</button>

        <table id="freights-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Empresa</th>
                    <th>Endereço de Início</th>
                    <th>Endereço de Destino</th>
                    <th>Motorista</th>
                    <th>Posição Atual</th>
                    <th>Duração</th>
                    <th>Distância</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data carregada via AJAX pelo DataTables -->
            </tbody>
        </table>
    </div>

    <!-- Modal para visualização do mapa e histórico -->
    <div class="modal fade" id="freightMapModal" tabindex="-1" aria-labelledby="freightMapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="freightMapModalLabel">Detalhes do Frete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex">
                    <!-- Mapa -->
                    <div style="width: 70%;">
                        <div id="location-info" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px; background-color: #f8f9fa; border-bottom: 2px solid #ddd;">
                            📍 Localização Atual: <span id="user-location">Obtendo localização...</span>
                        </div>
                        <div id="map" style="width: 100%; height: 350px;"></div>
                        <div style="margin-top: 15px; text-align: center; font-size: 16px;">
                            <strong>🚚 Endereço de Partida:</strong> <span id="start-address">Carregando...</span><br>
                            <strong>🏁 Endereço de Destino:</strong> <span id="destination-address">Carregando...</span>
                        </div>
                        <p><strong>Distância:</strong> <span id="distance"></span></p>
                        <p><strong>Tempo Estimado:</strong> <span id="duration"></span></p>
                    </div>

                    <!-- Histórico de Localização -->
                    <div style="width: 30%; padding-left: 15px;">
                        <h5>📜 Histórico</h5>
                        <ul id="location-history" class="list-group" style="max-height: 350px; overflow-y: auto;">
                            <li class="list-group-item">Carregando...</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
   
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&callback=initMap&libraries=places,directions"></script>

    <script>
        let map, truckMarker, directionsService, directionsRenderer, trackingInterval;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 7,
                center: { lat: -15.7801, lng: -47.9292 }
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({ map: map });
        }

        $(document).ready(function() {
            $('#freights-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('freights.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'company.name', name: 'company.name' },
                    { data: 'start_address', name: 'start_address' },
                    { data: 'destination_address', name: 'destination_address' },
                    { data: 'driver.name', name: 'driver.name' },
                    { data: 'current_position', name: 'current_position' },
                    { data: 'duration', name: 'duration' },
                    { data: 'distance', name: 'distance' },
                    { data: 'status.name', name: 'status.name' },
                    {
                        data: 'id',
                        name: 'actions',
                        render: function(data) {
                            return `
                                <button class="btn btn-info btn-sm view-shipment" data-id="${data}">Ver</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteFreight(${data})">Excluir</button>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        $(document).on("click", ".view-shipment", function() {
            var shipmentId = $(this).data("id");

            $.get(`/freights/${shipmentId}`, function(data) {
                $('#start-address').text(data.start_address);
                $('#destination-address').text(data.destination_address);
                $('#user-location').text(data.current_position);

                loadMap(data.start_lat, data.start_lng, data.destination_lat, data.destination_lng, data.current_lat, data.current_lng, shipmentId);
                loadFreightHistory(shipmentId);
                $('#freightMapModal').modal('show');
            });
        });

        function loadMap(startLat, startLng, destinationLat, destinationLng, currentLat, currentLng, freightId) {
            if (!map) {
                initMap();
            }

            directionsService.route({
                origin: { lat: parseFloat(startLat), lng: parseFloat(startLng) },
                destination: { lat: parseFloat(destinationLat), lng: parseFloat(destinationLng) },
                travelMode: google.maps.TravelMode.DRIVING
            }, (response, status) => {
                if (status === "OK") {
                    directionsRenderer.setDirections(response);
                    let route = response.routes[0].legs[0];

                    document.getElementById("distance").innerText = route.distance.text;
                    document.getElementById("duration").innerText = route.duration.text;
                }
            });

            updateTruckPosition(freightId);
        }

        function updateTruckPosition(freightId) {
            if (!freightId) return;

            if (trackingInterval) clearInterval(trackingInterval);

            function fetchPosition() {
                $.get(`/freights/${freightId}/position`, function(data) {
                    if (data.success && data.current_lat && data.current_lng) {
                        let truckLatLng = new google.maps.LatLng(parseFloat(data.current_lat), parseFloat(data.current_lng));

                        if (!truckMarker) {
                            truckMarker = new google.maps.Marker({
                                position: truckLatLng,
                                map: map,
                                icon: { url: "https://img.icons8.com/ios-filled/50/000000/truck.png", scaledSize: new google.maps.Size(40, 40) }
                            });
                        } else {
                            truckMarker.setPosition(truckLatLng);
                        }

                        document.getElementById("user-location").innerText = data.position;

                        loadFreightHistory(freightId);
                    }
                });
            }

            fetchPosition();
            trackingInterval = setInterval(fetchPosition, 10000);
        }

        function loadFreightHistory(freightId) {
            $.get(`/freights/${freightId}/history`, function(data) {
                let historyList = $("#location-history");
                historyList.empty();

                console.log('dados retornados historioc', data)

                if (data.length === 0) {
                    historyList.append('<li class="list-group-item">Nenhum histórico encontrado.</li>');
                    return;
                }

                data.forEach(entry => {
                    historyList.append(`<li class="list-group-item">📍 ${entry.address} - ${entry.date} ${entry.time}</li>`);
                });
            });
        }
    </script>
@endpush
