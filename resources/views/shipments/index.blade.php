
@extends('layouts.app')

@section('title', 'Shipments List')

@section('content')

    <div class="container">
        <h1>Lista de Cargas</h1>

        <button class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#allShipmentsModal">
            Ver Todas as Cargas no Mapa
        </button>

          <!-- Legenda -->
        <div class="legend-container">
            <span class="legend-item">
                <span class="legend-box red"></span> Carga sem frete
            </span>
            <span class="legend-item">
                <span class="legend-box yellow"></span> Frete solicitado
            </span>
        </div>

        <a href="{{ route('shipments.create') }}" class="btn btn-primary mt-3">Add New Shipment</a>

        <table id="shipments-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company</th>
                    <th>Driver</th>
                    <th>Weight</th>
                    <th>Cargo Type</th>
                    <th>Start Address</th>
                    <th>Destination Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($shipments as $shipment)
                <tr>
                    <td>{{ $shipment->id }}</td>
                    <td style="background-color: {{ !$shipment->freight ? 'rgba(255, 0, 0, 0.2)' : ($shipment->freight->status_id == 2 ? 'rgba(255, 255, 0, 0.3)' : '') }};">
                        {{ $shipment->company->name }}
                    </td>
                    <td>{{ $shipment->driver->name ?? 'N/A' }}</td>
                    <td>{{ $shipment->weight }}</td>
                    <td>{{ $shipment->cargo_type }}</td>
                    <td>{{ $shipment->start_address }}</td>
                    <td>{{ $shipment->destination_address }}</td>
                    <td>
                        <a href="/shipments/{{ $shipment->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

        
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewModalLabel">Shipment Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
       

        <div class="modal-body">
            <div id="shipment-details"></div>
            <button type="button" class="btn btn-primary mt-3" onclick="openFreightModal()">
                Solicitar Frete
            </button>
            <div id="map" style="height: 400px; width: 100%; margin-top: 20px;"></div>
            <p><strong>Distância:</strong> <span id="distance"></span></p>
            <p><strong>Tempo Estimado:</strong> <span id="duration"></span></p>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>


    <!-- Modal para Exibir Todas as Cargas -->
    <div class="modal fade" id="allShipmentsModal" tabindex="-1" aria-labelledby="allShipmentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allShipmentsModalLabel">Todas as Cargas no Mapa</h5>
                   

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="locationSelect">Escolha um Estado ou Cidade:</label>
                        <select id="locationSelect" class="form-control">
                            <option value="">Selecione...</option>
                            <option value="São Paulo, Brazil">São Paulo</option>
                            <option value="Rio de Janeiro, Brazil">Rio de Janeiro</option>
                            <option value="Belo Horizonte, Brazil">Belo Horizonte</option>
                            <option value="Curitiba, Brazil">Curitiba</option>
                            <option value="Porto Alegre, Brazil">Porto Alegre</option>
                        </select>
                    </div>
                  

                    <div id="allShipmentsMap" style="height: 500px;"></div>

                    <div class="legend-container">
                        <span class="legend-item">
                            <span class="legend-circle red"></span> Carga sem frete
                        </span>
                        <span class="legend-item">
                            <span class="legend-circle yellow"></span> Frete solicitado
                        </span>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Solicitação de Frete -->
<div class="modal fade" id="freightRequestModal" tabindex="-1" aria-labelledby="freightRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="freightRequestModalLabel">Solicitar Frete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="freightRequestForm">
                    <input type="hidden" id="shipment_id">

                    <div class="mb-3">
                        <label class="form-label">Empresa</label>
                        <input type="text" id="company_name" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Endereço Inicial</label>
                        <input type="text" id="start_address" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Endereço Final</label>
                        <input type="text" id="destination_address" class="form-control" readonly>
                    </div>

                    <button type="submit" class="btn btn-success">Confirmar Frete</button>
                </form>
            </div>
        </div>
    </div>
</div>




@endsection

@push('scripts')
   <!-- jQuery (deve vir primeiro) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places ,directions&callback=initMap" async defer></script>





<script>
   $(document).ready(function() {

    var allShipmentsMap;

    function updateMapLocation() {
    if (!allShipmentsMap) {
        console.error("Erro: O mapa ainda não foi carregado.");
        return;
    }

    var selectedLocation = document.getElementById("locationSelect").value;
    if (!selectedLocation) return;

    var geocoder = new google.maps.Geocoder();

    geocoder.geocode({ address: selectedLocation }, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            var location = results[0].geometry.location;

            // Centraliza o mapa e aplica zoom
            allShipmentsMap.setCenter(location);
            allShipmentsMap.setZoom(12);

            // Adicionando o círculo de status ao redor do marcador
           

            var circleColor = 'green'; // Default
            if (!shipment.freight) {
                circleColor = 'red'; // Não tem frete associado
            } else if (shipment.freight.status_id == 2) {
                circleColor = 'yellow'; // Frete com status 2
            }

            // Adiciona um marcador
            new google.maps.Marker({
                position: location,
                map: allShipmentsMap,
                title: "Local Selecionado"
            });

            var circle = new google.maps.Circle({
                map: allShipmentsMap,
                radius: 1000, // Raio do círculo em metros
                fillColor: circleColor,
                fillOpacity: 0.35,
                strokeColor: circleColor,
                strokeOpacity: 0.8,
                strokeWeight: 2,
                center: location,
            });

        } else {
            alert("Localização não encontrada: " + status);
        }
    });
}




    // Adicionar evento para mudar o mapa ao selecionar um local
    document.getElementById("locationSelect").addEventListener("change", updateMapLocation); 

    // Evento ao abrir o modal
    $('#allShipmentsModal').on('shown.bs.modal', function() {
        initAllShipmentsMap();
    });

    document.getElementById("freightRequestForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Evita o reload da página

        let shipmentId = document.getElementById("shipment_id").value;
        let startAddress = document.getElementById("start_address").value;
        let destinationAddress = document.getElementById("destination_address").value;

        fetch("/freight/store", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify({
                shipment_id: shipmentId,
                current_position: startAddress,
                status_id: 2
            })
        })
        .then(response => response.json())
        .then(data => {
            window.location.href = '/freights';  
            if (data.success) {
                alert("Frete solicitado com sucesso!");

                document.getElementById("freightRequestForm").reset();
                var freightModal = bootstrap.Modal.getInstance(document.getElementById('freightRequestModal'));
                freightModal.hide();
            } else {
                alert("Erro ao solicitar frete!");
            }
        })
        .catch(error => console.error("Erro:", error));
    });


   

    function initAllShipmentsMap() {
        allShipmentsMap = new google.maps.Map(document.getElementById('allShipmentsMap'), {
            zoom: 5,
            center: { lat: -23.55052, lng: -46.633308 } // Padrão: São Paulo
        });

        // Buscar todas as cargas
        $.ajax({
            url: "/api/shipments",  // Ajuste para sua rota API
            method: "GET",
            success: function(data) {
                data.forEach(shipment => {
                
                    let startLocation = shipment.start_address;

                    let geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ 'address': startLocation }, function(results, status) {
                        if (status === 'OK') {
                            var circleColor = 'green'; // Default
                            if (!shipment.freight) {
                                circleColor = 'red'; // Não tem frete associado
                            } else if (shipment.freight.status_id == 2) {
                                circleColor = 'yellow'; // Frete com status 2
                            }

                            let marker = new google.maps.Marker({
                                position: results[0].geometry.location,
                                map: allShipmentsMap,
                                title: `Carga ID: ${shipment.id}`,
                                icon: {
                                    url: "{{ asset('images/carga.png') }}", // Link do ícone de carga
                                    scaledSize: new google.maps.Size(80, 80)
                                }
                            });

                              // Adicionando o círculo de status ao redor do marcador
                            var circle = new google.maps.Circle({
                                map: allShipmentsMap,
                                radius: 1000, // Raio do círculo em metros
                                fillColor: circleColor,
                                fillOpacity: 0.35,
                                strokeColor: circleColor,
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                center: results[0].geometry.location,
                            });

                            let infoWindow = new google.maps.InfoWindow({
                               
                                content: `<strong>Carga ID:</strong> ${shipment.id} 
                                 <button onclick="openFreightModal('${shipment.id}', '${shipment.start_address}', '${shipment.destination_address}', '${shipment.company.name}')" 
                                    class="btn btn-primary btn-sm" style="float: right;">
                                    Solicitar Frete
                                </button>
                                  <br> <strong>Empresa:</strong> ${shipment.company.name}
                                <br> <strong>Endereço Inicial:</strong> ${shipment.start_address}
                                 <br> <strong>Endereço Final:</strong> ${shipment.destination_address}
                              <div id="mini-map-${shipment.id}" style="width: 600px; height: 150px;"></div>  
                              <p id="route-info-${shipment.id}">Calculando rota...</p>        
                                `
                            });

                            marker.addListener('click', function() {
                                infoWindow.open(allShipmentsMap, marker);

                            
        
                                setTimeout(() => {
                                    initializeMiniMap(shipment.id, shipment.start_address, shipment.destination_address);
                                }, 500); // Aguarde a renderização do InfoWindow
                            });

                        } else {
                            console.log("Geocoding falhou: " + status);
                        }
                    });
                });
            },
            error: function(error) {
                console.log("Erro ao buscar cargas:", error);
            }
        });
    }

    function initializeMiniMap(id, startAddress, destinationAddress) {
    let miniMap = new google.maps.Map(document.getElementById(`mini-map-${id}`), {
        zoom: 12,
        center: { lat: 0, lng: 0 }, // Ajustado dinamicamente depois
        disableDefaultUI: true
    });

    let directionsService = new google.maps.DirectionsService();
    let directionsRenderer = new google.maps.DirectionsRenderer({ suppressMarkers: false });
    directionsRenderer.setMap(miniMap);

    directionsService.route(
        {
            origin: startAddress,
            destination: destinationAddress,
            travelMode: google.maps.TravelMode.DRIVING
        },
        function (response, status) {
            if (status === "OK") {
                directionsRenderer.setDirections(response);
                let route = response.routes[0].legs[0];
                document.getElementById(`route-info-${id}`).innerHTML = 
                    `<strong>Distância:</strong> ${route.distance.text} <br>
                     <strong>Tempo estimado:</strong> ${route.duration.text}`;
            } else {
                document.getElementById(`route-info-${id}`).innerHTML = "Não foi possível calcular a rota.";
            }
        }
    );
}


    $('#shipments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('shipments.data') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'company.name', name: 'company.name' },
            { data: 'driver.name', name: 'driver.name' },
            { data: 'weight', name: 'weight' },
            { data: 'cargo_type', name: 'cargo_type' },
            { data: 'start_address', name: 'start_address' },
            { data: 'destination_address', name: 'destination_address' },
            {
                data: 'id',
                render: function(data) {
                    return `
                        <button class="btn btn-info btn-sm" data-id="${data}" id="view-shipment">View</button>
                        <a href="/shipments/${data}/edit" class="btn btn-warning btn-sm">Edit</a>
                        <form action="/shipments/${data}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    `;
                }
            }

        ],
        createdRow: function (row, data) {
            if (!data.freight) {
                $(row).addClass("bg-red"); // Carga sem frete
            } else if (data.freight.status_id == 2) {
                $(row).addClass("bg-yellow"); // Frete solicitado
            }
        },
        drawCallback: function () {
            $('#shipmentsTable tbody tr').each(function () {
                let rowData = table.row(this).data(); // Obtém os dados da linha

                if (!rowData.freight) {
                    $(this).addClass("bg-red");
                } else if (rowData.freight.status_id == 2) {
                    $(this).addClass("bg-yellow");
                }
            });
        }
    });

    let map;
    let directionsService = new google.maps.DirectionsService();
    let directionsRenderer = new google.maps.DirectionsRenderer();

    // Mostrar o modal de visualização quando clicar no botão "View"
    $(document).on('click', '#view-shipment', function() {
        var shipmentId = $(this).data('id');
        
        $.get('/shipments/' + shipmentId, function(data) {
            // Preencher os dados no modal
            $('#shipment-details').html(`
                <h5><strong>Company:</strong> ${data.company.name}</h5>
                <h5><strong>Driver:</strong> ${data.driver.name}</h5>
                <h5><strong>Weight:</strong> ${data.weight} kg</h5>
                <h5><strong>Cargo Type:</strong> ${data.cargo_type}</h5>
                <h5><strong>Start Address:</strong> ${data.start_address}</h5>
                <h5><strong>Destination Address:</strong> ${data.destination_address}</h5>
                <h5><strong>Expected Start Date:</strong> ${data.expected_start_date}</h5>
                <h5><strong>Expected Delivery Date:</strong> ${data.expected_delivery_date}</h5>
               
            `);

            // Iniciar o mapa com os endereços de início e destino
            initMap(data.start_address, data.destination_address);
            $('#viewModal').modal('show');
        });
    });
});




// Função para inicializar o mapa com os pontos de início e destino
function initMap(startAddress, destinationAddress) {
   
    const geocoder = new google.maps.Geocoder();
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();

    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 7,
        center: { lat: -23.5505, lng: -46.6333 } // Ponto inicial arbitrário
    });

    directionsRenderer.setMap(map);

    // Geocodificar o endereço de início
    geocoder.geocode({ address: startAddress }, function(results, status) {
        if (status === 'OK') {
            const startLatLng = results[0].geometry.location;

            // Geocodificar o endereço de destino
            geocoder.geocode({ address: destinationAddress }, function(results, status) {
                if (status === 'OK') {
                    const destinationLatLng = results[0].geometry.location;

                    // Criar a solicitação de direções
                    const request = {
                        origin: startLatLng,
                        destination: destinationLatLng,
                        travelMode: google.maps.TravelMode.DRIVING
                    };

                    directionsService.route(request, function(response, status) {
                        if (status === google.maps.DirectionsStatus.OK) {
                            directionsRenderer.setDirections(response);
                            const route = response.routes[0].legs[0];
                            document.getElementById('distance').textContent = 'Distância: ' + route.distance.text;
                            document.getElementById('duration').textContent = 'Tempo: ' + route.duration.text;
                        } else {
                            alert('Não foi possível calcular o trajeto.');
                        }
                    });


                    // Exibir distância e tempo no resultado
                    const route = response.routes[0].legs[0];
                    document.getElementById('distance').textContent = 'Distância: ' + route.distance.text;
                    document.getElementById('duration').textContent = 'Tempo: ' + route.duration.text;


                    // Adicionar marcadores com ícones personalizados
                    new google.maps.Marker({
                        position: startLatLng,
                        map: map,
                        icon: 'https://img.icons8.com/ios-filled/50/000000/package.png', // Ícone de carga
                        title: 'Start'
                    });

                    new google.maps.Marker({
                        position: destinationLatLng,
                        map: map,
                        icon: 'https://img.icons8.com/ios-filled/50/000000/package.png',
                        title: 'Destination'
                    });
                } else {
                    alert('Não foi possível encontrar o destino.');
                }
            });
        } else {
           // alert('Não foi possível geocodificar o endereço de início.');
        }
    });
}

function openFreightModal(shipmentId, startAddress, destinationAddress, companyId) {


    // Preencher os campos do modal com os valores do marcador
    document.getElementById("shipment_id").value = shipmentId;
    document.getElementById("start_address").value = startAddress;
    document.getElementById("destination_address").value = destinationAddress;
    document.getElementById("company_name").value = companyId;

    let freightModal = new bootstrap.Modal(document.getElementById('freightRequestModal'));
    freightModal.show();

 

    

    // Abrir o modal
  
}


</script>

@endpush

@section('styles')

<style>
    .legend-container {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .legend-item {
        display: flex;
        align-items: center;
        font-size: 14px;
        font-weight: bold;
        color: #333;
    }

    .legend-box {
        width: 15px;
        height: 15px;
        border-radius: 3px;
        display: inline-block;
        margin-right: 5px;
    }

    .red {
        background-color: red;
    }

    .yellow {
        background-color: yellow;
    }

    /* Cores para as linhas */
    .bg-red {
        background-color: rgba(255, 0, 0, 0.2) !important;
    }

    .bg-yellow {
        background-color: rgba(255, 255, 0, 0.3) !important;
    }
    
    .legend-container {
            display: flex;
            justify-content: start;
            gap: 15px;
            margin-bottom: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .legend-item {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .legend-circle {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .red {
            background-color: red;
        }

        .yellow {
            background-color: yellow;
        }

        .bg-red {
        background-color: rgba(255, 0, 0, 0.2) !important;
        }

        .bg-yellow {
            background-color: rgba(255, 255, 0, 0.3) !important;
        }
</style>

@endsection