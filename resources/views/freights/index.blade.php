@extends('layouts.app')

@section('title', 'Gestão de Fretes')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-truck-moving me-2"></i>Gestão de Fretes
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fretes</li>
                </ol>
            </nav>
        </div>
        <div>
            <button id="refresh-table" class="btn btn-outline-primary me-2">
                <i class="fas fa-sync-alt me-1"></i>Atualizar
            </button>
            <button id="export-excel" class="btn btn-success me-2">
                <i class="fas fa-file-excel me-1"></i>Exportar
            </button>
            <button id="delete-all-freights" class="btn btn-danger">
                <i class="fas fa-trash-alt me-1"></i>Limpar Tudo
            </button>
        </div>
    </div>

    <!-- Seção de Estatísticas Dinâmicas -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <div id="freight-stats" class="d-flex gap-4 flex-wrap">
                <!-- Cards serão gerados dinamicamente via JavaScript -->
                <div class="text-center placeholder-glow">
                    <div class="placeholder col-4"></div>
                    <div class="placeholder col-2"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Lista de Fretes
            </h5>
            <div class="input-group" style="width: 300px;">
                <span class="input-group-text bg-transparent">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="freight-search" class="form-control" placeholder="Pesquisar...">
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="freights-table" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th width="50">ID</th>
                            <th>Empresa</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Motorista</th>
                            <th>Status</th>
                            <th>Valor</th>
                            <th width="120">Pagamento</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dados carregados via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    <span id="table-info">Carregando...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div class="modal fade" id="freightModal" tabindex="-1" aria-hidden="true">
    <!-- Conteúdo do modal permanece igual -->
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
:root {
    --primary: #4e73df;
    --secondary: #858796;
    --success: #1cc88a;
    --info: #36b9cc;
    --warning: #f6c23e;
    --danger: #e74a3b;
    --light: #f8f9fc;
    --dark: #5a5c69;
}

body {
    background-color: #f8f9fc;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    margin-bottom: 1.5rem;
}

.stat-card {
    background-color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    min-width: 100px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

#freight-stats {
    gap: 1rem;
    padding: 0.5rem 0;
}

@media (max-width: 768px) {
    .stat-card {
        min-width: calc(50% - 0.5rem);
    }
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
    padding: 1rem 1.35rem;
}

.table thead th {
    vertical-align: middle;
    padding: 1rem;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--secondary);
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.table tbody td {
    vertical-align: middle;
    padding: 1rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.05);
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
}

.btn {
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 0.35rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.85rem;
}

#map-container {
    border-radius: 0.35rem;
    overflow: hidden;
    border: 1px solid #e3e6f0;
}

.modal-xl {
    max-width: 1200px;
}

@media (max-width: 992px) {
    .modal-xl {
        max-width: 95%;
    }
    
    .card-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    #freight-search {
        width: 100% !important;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initMap" async defer></script>

<script>
// Variáveis globais
let map, directionsService, directionsRenderer, truckMarker, trackingInterval;
let freightTable;

// Mapeamento de tradução para os status
const statusTranslations = {
    'active': 'Ativos',
    'pending': 'Pendentes',
    'completed': 'Concluídos',
    'paid': 'Pagamentos',
    'cancelled': 'Cancelados',
    'total': 'Total'
};

$(document).ready(function() {
    // Configuração do Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };

    // Carrega as estatísticas inicialmente
    updateStats();

    // Inicializa a tabela
    initDataTable();

    // Atualiza estatísticas a cada 30 segundos
    setInterval(updateStats, 30000);
});

function initDataTable() {
    freightTable = $('#freights-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('freights.data') }}',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('Erro ao carregar dados:', xhr.responseText);
                toastr.error('Erro ao carregar dados da tabela');
            }
        },
        order: [[0, 'desc']],
        columns: [
            { 
                data: 'id', 
                name: 'id',
                className: 'fw-semibold'
            },
            { 
                data: 'company_name', 
                name: 'company.name',
                render: function(data, type, row) {
                    return data ? `<span class="fw-semibold">${data}</span>` : 'N/A';
                }
            },
            { 
                data: 'start_address', 
                name: 'start_address',
                render: function(data) {
                    return data ? `<span class="text-truncate d-inline-block" style="max-width: 200px;" title="${data}">${data}</span>` : 'N/A';
                }
            },
            { 
                data: 'destination_address', 
                name: 'destination_address',
                render: function(data) {
                    return data ? `<span class="text-truncate d-inline-block" style="max-width: 200px;" title="${data}">${data}</span>` : 'N/A';
                }
            },
            { 
                data: 'driver_name', 
                name: 'driver.name',
                render: function(data) {
                    return data ? data : 'Não atribuído';
                }
            },
            { 
                data: 'status_badge', 
                name: 'status.name',
                orderable: false,
                searchable: false
            },
            { 
                data: 'formatted_value', 
                name: 'freight_value',
                orderable: true,
                searchable: false
            },
            { 
                data: 'payment_button', 
                name: 'payment_button',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
            { 
                data: 'actions', 
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/pt-BR.json'
        },
        dom: '<"top"f>rt<"bottom"lip><"clear">',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i>Exportar',
                className: 'btn btn-success',
                title: 'Fretes'
            }
        ],
        drawCallback: function(settings) {
            updateTableInfo();
        }
    });

    // Pesquisa personalizada
    $('#freight-search').keyup(function() {
        freightTable.search($(this).val()).draw();
    });

    // Botão de atualizar
    $('#refresh-table').click(function() {
        freightTable.ajax.reload(null, false);
        updateStats();
        toastr.success('Tabela atualizada com sucesso!');
    });

    // Botão de exportar
    $('#export-excel').click(function() {
        freightTable.button('.buttons-excel').trigger();
    });

    // Botão de deletar todos
    $('#delete-all-freights').click(function() {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Todos os fretes serão excluídos permanentemente!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir tudo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('freights.deleteAll') }}',
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message);
                            freightTable.ajax.reload();
                            updateStats();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Erro ao excluir fretes: ' + xhr.responseText);
                    }
                });
            }
        });
    });

    // Visualizar frete
    $(document).on('click', '.view-freight', function() {
        const freightId = $(this).data('id');
        loadFreightDetails(freightId);
    });

    // Excluir frete
    $(document).on('click', '.delete-freight', function() {
        const freightId = $(this).data('id');
        
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/freights/${freightId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message);
                            freightTable.ajax.reload();
                            updateStats();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Erro ao excluir frete: ' + xhr.responseText);
                    }
                });
            }
        });
    });
});

// Cria os cards de estatísticas
function createStatsCards(stats) {
    const container = $('#freight-stats');
    container.empty(); // Limpa os cards existentes
    
    // Ordem desejada para exibição dos cards
    const displayOrder = ['total', 'active', 'pending', 'completed', 'paid', 'cancelled'];
    
    displayOrder.forEach(key => {
        if (stats[key]) {
            const stat = stats[key];
            const card = `
                <div class="stat-card" data-status="${key}">
                    <div class="d-flex flex-column align-items-center">
                        <div class="text-xs font-weight-bold text-${stat.color} mb-1">
                            <i class="${stat.icon} me-1"></i>${statusTranslations[key]}
                        </div>
                        <div class="h4 mb-0 text-${stat.color}" id="${key}-count">${stat.count}</div>
                    </div>
                </div>
            `;
            container.append(card);
        }
    });
}

// Atualiza as estatísticas
function updateStats() {
    $.get('{{ route('freights.stats') }}', function(response) {
        if(response.success) {
            createStatsCards(response.data);
        }
    }).fail(function() {
        console.error('Erro ao carregar estatísticas');
        toastr.error('Erro ao carregar estatísticas dos fretes');
    });
}

// Atualiza as informações da tabela
function updateTableInfo() {
    const info = freightTable.page.info();
    $('#table-info').html(
        `Mostrando ${info.start + 1} a ${info.end} de ${info.recordsDisplay} registros`
    );
}

// Inicializa o mapa
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: { lat: -15.7801, lng: -47.9292 },
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: true,
        map: map,
        polylineOptions: {
            strokeColor: '#4e73df',
            strokeOpacity: 0.8,
            strokeWeight: 4
        }
    });
}

// Carrega os detalhes do frete no modal
function loadFreightDetails(freightId) {
    $.get(`/freights/${freightId}`, function(response) {
        // Preenche informações básicas
        $('#modal-title').text(`Frete #${response.id} - ${response.company.name}`);
        $('#company-info').text(response.company.name);
        $('#driver-info').text(response.driver ? response.driver.name : 'Não atribuído');
        $('#cargo-type').text(response.shipment.cargo_type);
        $('#cargo-weight').text(`${response.shipment.weight} kg`);
        $('#start-address').text(response.start_address);
        $('#destination-address').text(response.destination_address);
        $('#current-position').text(response.current_position || 'Não disponível');
        $('#last-update').text(new Date().toLocaleString());

        // Preenche informações de pagamento
        if(response.charge) {
            $('#payment-status').html(response.status ? `<span class="badge ${getStatusBadgeClass(response.status.slug)}">${response.status.name}</span>` : 'N/A');
            $('#payment-value').text(response.freight_value ? 'R$ ' + parseFloat(response.freight_value).toFixed(2).replace('.', ',') : 'N/A');
            
            // Configura os botões de pagamento
            let paymentButtons = '';
            if(response.status && response.status.slug === 'paid' && response.charge.receipt_url) {
                paymentButtons = `
                    <a href="${response.charge.receipt_url}" class="btn btn-sm btn-info mt-2" target="_blank">
                        <i class="fas fa-file-invoice-dollar me-1"></i>Visualizar Recibo
                    </a>
                `;
            } else if(response.charge.charge_url) {
                paymentButtons = `
                    <a href="${response.charge.charge_url}" class="btn btn-sm btn-success mt-2" target="_blank">
                        <i class="fas fa-credit-card me-1"></i>Realizar Pagamento
                    </a>
                `;
            }
            $('#payment-buttons').html(paymentButtons);
        } else {
            $('#payment-status').text('N/A');
            $('#payment-value').text('N/A');
            $('#payment-buttons').html('');
        }

        // Configura o mapa
        if (response.start_lat && response.start_lng && 
            response.destination_lat && response.destination_lng) {
            
            calculateAndDisplayRoute(
                parseFloat(response.start_lat), 
                parseFloat(response.start_lng),
                parseFloat(response.destination_lat), 
                parseFloat(response.destination_lng)
            );
        }

        // Atualiza a posição do caminhão
        if (response.current_lat && response.current_lng) {
            updateTruckPosition(
                parseFloat(response.current_lat), 
                parseFloat(response.current_lng)
            );
        }

        // Carrega o histórico
        loadFreightHistory(freightId);

        // Abre o modal
        $('#freightModal').modal('show');
    }).fail(function() {
        toastr.error('Erro ao carregar detalhes do frete');
    });
}

// Retorna a classe do badge baseado no status
function getStatusBadgeClass(statusSlug) {
    const slug = statusSlug.toLowerCase();
    if(slug === 'pending') return 'bg-warning';
    if(slug === 'active') return 'bg-primary';
    if(slug === 'completed' || slug === 'paid') return 'bg-success';
    if(slug === 'cancelled') return 'bg-danger';
    return 'bg-secondary';
}

// Calcula e exibe a rota no mapa
function calculateAndDisplayRoute(startLat, startLng, destLat, destLng) {
    const start = new google.maps.LatLng(startLat, startLng);
    const end = new google.maps.LatLng(destLat, destLng);

    directionsService.route({
        origin: start,
        destination: end,
        travelMode: google.maps.TravelMode.DRIVING
    }, (response, status) => {
        if (status === 'OK') {
            directionsRenderer.setDirections(response);
            
            const route = response.routes[0].legs[0];
            $('#distance').text(route.distance.text);
            $('#duration').text(route.duration.text);
            
            // Adiciona marcadores personalizados
            new google.maps.Marker({
                position: start,
                map: map,
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png",
                    scaledSize: new google.maps.Size(32, 32)
                },
                title: "Ponto de Partida"
            });

            new google.maps.Marker({
                position: end,
                map: map,
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                    scaledSize: new google.maps.Size(32, 32)
                },
                title: "Ponto de Destino"
            });
        } else {
            toastr.error('Erro ao calcular rota: ' + status);
        }
    });
}

// Atualiza a posição do caminhão no mapa
function updateTruckPosition(lat, lng) {
    const position = new google.maps.LatLng(lat, lng);
    
    if (!truckMarker) {
        truckMarker = new google.maps.Marker({
            position: position,
            map: map,
            icon: {
                url: "https://img.icons8.com/ios-filled/50/000000/truck.png",
                scaledSize: new google.maps.Size(40, 40)
            },
            title: "Posição Atual do Caminhão"
        });
    } else {
        truckMarker.setPosition(position);
    }
    
    // Centraliza o mapa na posição do caminhão
    map.panTo(position);
    map.setZoom(12);
}

// Carrega o histórico de localização
function loadFreightHistory(freightId) {
    $.get(`/freights/${freightId}/history`, function(response) {
        const historyTable = $('#location-history');
        historyTable.empty();

        if (response.length === 0) {
            historyTable.append('<tr><td colspan="3" class="text-center">Nenhum histórico disponível</td></tr>');
            return;
        }

        response.forEach(entry => {
            const date = new Date(entry.created_at);
            historyTable.append(`
                <tr>
                    <td>${date.toLocaleString()}</td>
                    <td>${entry.address || 'N/A'}</td>
                    <td><span class="badge ${getStatusBadgeClass(entry.status || '')}">${entry.status || 'N/A'}</span></td>
                </tr>
            `);
        });
    }).fail(function() {
        toastr.error('Erro ao carregar histórico de localização');
    });
}

// Inicializa o mapa quando a API do Google é carregada
window.initMap = initMap;
</script>
@endpush