@extends('layouts.app')

@section('title', 'Lista de Fretes')

@section('content')

    <div class="container">
        <h1>Lista de Fretes</h1>
        <table id="freights-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Motorista</th>
                    <th>Posição Atual</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data carregada via AJAX pelo DataTables -->
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="freightMapModal" tabindex="-1" aria-labelledby="freightMapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="freightMapModalLabel">Detalhes do Frete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                     <!-- Descrição da localização -->
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
        </div>
    </div>
</div>


@endsection

@push('scripts')
    <!-- Adicionar jQuery e DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places,directions"></script>


    <script>
           let truckMarker; // Definindo a variável globalmente
           let map; // Definindo o mapa globalmente

        $(document).ready(function() {

         
           
            $('#freights-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('freights.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'driver.name', name: 'driver.name' },
                    { data: 'current_position', name: 'current_position' },
                    { data: 'status.name', name: 'status.name' },
                    {
                        data: 'id',
                        render: function(data) {
                            return `
                             <button class="btn btn-info btn-sm" data-id="${data}" id="view-shipment">View</button>   
                          
                            <button class="btn btn-danger btn-sm" onclick="deleteFreight(${data})">Excluir</button>
                            `;
                        }
                    }
                ]
            });
        });

         // Mostrar o modal de visualização quando clicar no botão "View"
        $(document).on('click', '#view-shipment', function() {
         
          
            var shipmentId = $(this).data('id');
            
            $.get('/freights/' + shipmentId, function(data) {
                // Preencher os dados no modal
               
                console.log(data);
                setTimeout(() => {
                    loadMap(data.start_address, data.destination_address, data.freight_id);
                }, 500); // Aguarde um pouco para garantir que o modal seja exibido

                // Iniciar o mapa com os endereços de início e destino
               
                $('#freightMapModal').modal('show');
            });
        });

        function deleteFreight(id) {
            if (confirm('Tem certeza que deseja excluir este frete?')) {
                $.ajax({
                    url: `/freights/${id}`,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function(response) {
                        alert('Frete excluído com sucesso!');
                        $('#freights-table').DataTable().ajax.reload();
                    },
                    error: function(error) {
                        alert('Erro ao excluir frete.');
                    }
                });
            }
        }

    

            function loadMap(startAddress, destinationAddress, freight_id) {
             

                document.getElementById("start-address").innerText = startAddress;
                document.getElementById("destination-address").innerText = destinationAddress;

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
                        } else {
                            console.error("Erro ao calcular rota:", status);
                        }
                    }
                );
              
                  // Inicia a atualização da posição do caminhão
                updateTruckPosition(map, freight_id);
                
            }

           

        function updateTruckPosition(map, freightId) {
           
            if (!freightId) return;

            function fetchPosition() {
            $.get(`/freights/${freightId}/position`, function(data) {
                console.log(data);

                if (data.success && data.position) {
                    let address = data.position; // O endereço armazenado no banco

                    
                    document.getElementById("user-location").innerText = address;

                    let geocoder = new google.maps.Geocoder();

                    geocoder.geocode({ address: address }, function(results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            let location = results[0].geometry.location;

                            let truckLatLng = new google.maps.LatLng(location.lat(), location.lng());

                            if (!truckMarker) {
                                // Criar o marcador do caminhão
                                truckMarker = new google.maps.Marker({
                                    position: truckLatLng,
                                    map: map,
                                    icon: {
                                        url: "https://img.icons8.com/ios-filled/50/000000/truck.png", // Ícone de caminhão
                                        scaledSize: new google.maps.Size(40, 40),
                                    },
                                });
                            } else {
                                // Atualizar a posição do caminhão
                                truckMarker.setPosition(truckLatLng);
                            }
                        } else {
                            console.error("Erro ao converter endereço:", status);
                        }
                    });
                }
    });
}

            fetchPosition(); // Buscar a posição inicial
            setInterval(fetchPosition, 10000); // Atualizar a cada 10 segundos
        }


            

    </script>
@endpush
