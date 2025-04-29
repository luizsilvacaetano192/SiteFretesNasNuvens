<?php $__env->startSection('title', 'Detalhes do Frete'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <!-- Botão Voltar no Topo -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-truck-moving me-2"></i>Detalhes do Frete #<?php echo e($freight->id); ?>

            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('freights.index')); ?>">Fretes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('freights.index')); ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Voltar para Fretes
            </a>
            <a href="#" class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Imprimir
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Coluna Esquerda -->
        <div class="col-lg-8">
            <!-- Card de Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Status do Frete
                    </h6>
                    <span class="badge bg-<?php echo e($statusBadgeClass); ?>"><?php echo e($freight->freightStatus->name); ?></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Informações Básicas</h6>
                                <p class="mb-1"><strong>Empresa:</strong> <?php echo e($freight->company->name); ?></p>
                                <p class="mb-1"><strong>Criado em:</strong> <?php echo e($freight->created_at->format('d/m/Y H:i')); ?></p>
                                <p class="mb-1"><strong>Valor Total:</strong> R$ <?php echo e(number_format($freight->freight_value, 2, ',', '.')); ?></p>
                                <p class="mb-1"><strong>Valor Motorista:</strong> R$ <?php echo e(number_format($freight->driver_freight_value, 2, ',', '.')); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Datas Importantes</h6>
                                <p class="mb-1"><strong>Coleta:</strong> <?php echo e($freight->pickup_date ? $freight->pickup_date->format('d/m/Y H:i') : 'N/A'); ?></p>
                                <p class="mb-1"><strong>Entrega:</strong> <?php echo e($freight->delivery_date ? $freight->delivery_date->format('d/m/Y H:i') : 'N/A'); ?></p>
                                <p class="mb-1"><strong>Concluído em:</strong> <?php echo e($freight->completed_at ? $freight->completed_at->format('d/m/Y H:i') : 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Rota e Mapa -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-map-marked-alt me-2"></i>Rota e Localização
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div id="map-container" style="position: relative;">
                        <div id="location-info" class="p-3 bg-light border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>📍 Posição atual:</strong> 
                                    <span id="current-position"><?php echo e($freight->current_position ?? 'Não disponível'); ?></span>
                                </div>
                                <div>
                                    <strong>🔄 Atualizado em:</strong> 
                                    <span id="last-update"><?php echo e(now()->format('d/m/Y H:i:s')); ?></span>
                                </div>
                            </div>
                        </div>
                        <div id="map" style="height: 400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Card de Histórico -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Histórico de Atividades
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="history-table">
                            <thead>
                                <tr>
                                    <th width="120">Data/Hora</th>
                                    <th>Evento</th>
                                    <th>Detalhes</th>
                                </tr>
                            </thead>
                            <tbody id="activity-history">
                                <?php $__empty_1 = true; $__currentLoopData = $freight->history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($activity->created_at->format('d/m/Y H:i')); ?></td>
                                    <td><?php echo e($activity->event); ?></td>
                                    <td><?php echo e($activity->details); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Nenhum histórico disponível</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita -->
        <div class="col-lg-4">
            <!-- Card de Carga -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>Detalhes da Carga
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Informações Gerais</h6>
                        <p class="mb-1"><strong>Tipo:</strong> <?php echo e($freight->shipment->cargo_type); ?></p>
                        <p class="mb-1"><strong>Peso:</strong> <?php echo e($freight->shipment->weight); ?> kg</p>
                        <p class="mb-1"><strong>Dimensões:</strong> <?php echo e($freight->shipment->dimensions); ?></p>
                        <p class="mb-1"><strong>Volume:</strong> <?php echo e($freight->shipment->volume); ?></p>
                        <p class="mb-1"><strong>Descrição:</strong> <?php echo e($freight->shipment->description ?? 'N/A'); ?></p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Características</h6>
                        <p class="mb-1"><strong>Frágil:</strong> <?php echo e($freight->shipment->is_fragile ? 'Sim' : 'Não'); ?></p>
                        <p class="mb-1"><strong>Perigosa:</strong> <?php echo e($freight->shipment->is_hazardous ? 'Sim' : 'Não'); ?></p>
                        <p class="mb-1"><strong>Controle de Temperatura:</strong> <?php echo e($freight->shipment->requires_temperature_control ? 'Sim' : 'Não'); ?></p>
                        <?php if($freight->shipment->requires_temperature_control): ?>
                        <p class="mb-1"><strong>Faixa de Temperatura:</strong> 
                            <?php echo e($freight->shipment->min_temperature); ?>°<?php echo e($freight->shipment->temperature_unit); ?> a 
                            <?php echo e($freight->shipment->max_temperature); ?>°<?php echo e($freight->shipment->temperature_unit); ?>

                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Card de Motorista e Veículo -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-truck me-2"></i>Motorista e Veículo
                    </h6>
                </div>
                <div class="card-body">
                    <?php if($freight->driver): ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <img src="<?php echo e($freight->driver->photo_url ?? asset('img/default-driver.png')); ?>" 
                                 class="rounded-circle" width="50" height="50" alt="Foto do Motorista">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0"><?php echo e($freight->driver->name); ?></h6>
                            <small class="text-muted"><?php echo e($freight->driver->phone); ?></small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Informações do Veículo</h6>
                        <p class="mb-1"><strong>Tipo:</strong> <?php echo e($freight->truck_type ? ucwords(str_replace('_', ' ', $freight->truck_type)) : 'N/A'); ?></p>
                        <p class="mb-1"><strong>Placa:</strong> <?php echo e($freight->driver->truck_plate ?? 'N/A'); ?></p>
                        <p class="mb-1"><strong>Capacidade:</strong> <?php echo e($freight->driver->truck_capacity ?? 'N/A'); ?></p>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>Nenhum motorista atribuído a este frete.
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Card de Endereços -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Endereços
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Origem</h6>
                        <p class="mb-1"><strong>Endereço:</strong> <?php echo e($freight->start_address); ?></p>
                        <p class="mb-1"><strong>Instruções:</strong> <?php echo e($freight->loading_instructions ?? 'N/A'); ?></p>
                        <p class="mb-0"><strong>Contato:</strong> <?php echo e($freight->start_contact ?? 'N/A'); ?></p>
                    </div>
                    
                    <div class="mb-0">
                        <h6 class="text-muted mb-2">Destino</h6>
                        <p class="mb-1"><strong>Endereço:</strong> <?php echo e($freight->destination_address); ?></p>
                        <p class="mb-1"><strong>Instruções:</strong> <?php echo e($freight->unloading_instructions ?? 'N/A'); ?></p>
                        <p class="mb-0"><strong>Contato:</strong> <?php echo e($freight->destination_contact ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Card de Pagamento -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>Pagamento
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1"><strong>Status:</strong> 
                            <span class="badge bg-<?php echo e($paymentBadgeClass); ?>"><?php echo e($freight->payment_status); ?></span>
                        </p>
                        <p class="mb-1"><strong>Método:</strong> <?php echo e($freight->payment_method ? strtoupper($freight->payment_method) : 'N/A'); ?></p>
                        <p class="mb-1"><strong>Seguradoras:</strong> 
                            <?php if($freight->insurance_carriers && count($freight->insurance_carriers) > 0): ?>
                                <?php echo e(implode(', ', array_map(function($item) { return ucwords(str_replace('_', ' ', $item)); }, $freight->insurance_carriers))); ?>

                            <?php else: ?>
                                Nenhuma seguradora específica
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <?php if($freight->charge): ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Distância</h6>
                            <p class="h5"><?php echo e($freight->distance ?? 'N/A'); ?></p>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Tempo Estimado</h6>
                            <p class="h5"><?php echo e($freight->duration ?? 'N/A'); ?></p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <?php if($freight->payment_status === 'paid' && $freight->charge->receipt_url): ?>
                        <a href="<?php echo e($freight->charge->receipt_url); ?>" class="btn btn-sm btn-info" target="_blank">
                            <i class="fas fa-file-invoice-dollar me-1"></i>Recibo
                        </a>
                        <?php elseif($freight->charge->charge_url): ?>
                        <a href="<?php echo e($freight->charge->charge_url); ?>" class="btn btn-sm btn-success" target="_blank">
                            <i class="fas fa-credit-card me-1"></i>Pagar
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }
    
    #map-container {
        border-radius: 0.35rem;
        overflow: hidden;
        border: 1px solid #e3e6f0;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    .bg-warning {
        background-color: #f6c23e !important;
    }
    
    .bg-success {
        background-color: #1cc88a !important;
    }
    
    .bg-danger {
        background-color: #e74a3b !important;
    }
    
    .bg-primary {
        background-color: #4e73df !important;
    }
    
    .bg-secondary {
        background-color: #858796 !important;
    }
    
    .text-muted {
        color: #5a5c69 !important;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background-color: white !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #e3e6f0 !important;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
        
        #map {
            height: 300px !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initMap" async defer></script>
<script>
    let map;
    let directionsRenderer;
    let truckMarker;
    let updateInterval;

    function initMap() {
        const mapElement = document.getElementById("map");
        if (!mapElement) return;
        
        const defaultCenter = { lat: -15.7801, lng: -47.9292 };
        
        try {
            map = new google.maps.Map(mapElement, {
                zoom: 7,
                center: defaultCenter,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                map: map,
                polylineOptions: {
                    strokeColor: '#4e73df',
                    strokeOpacity: 0.8,
                    strokeWeight: 4
                }
            });

            initRoute();
            startAutoUpdate();

        } catch (error) {
            console.error("Erro ao inicializar o mapa:", error);
        }
    }

    function initRoute() {
        const directionsService = new google.maps.DirectionsService();

        <?php if($freight->start_lat && $freight->start_lng && $freight->destination_lat && $freight->destination_lng): ?>
            const start = new google.maps.LatLng(<?php echo e($freight->start_lat); ?>, <?php echo e($freight->start_lng); ?>);
            const end = new google.maps.LatLng(<?php echo e($freight->destination_lat); ?>, <?php echo e($freight->destination_lng); ?>);

            directionsService.route({
                origin: start,
                destination: end,
                travelMode: google.maps.TravelMode.DRIVING
            }, (response, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(response);
                    
                    // Marcador de origem
                    new google.maps.Marker({
                        position: start,
                        map: map,
                        icon: {
                            url: "https://maps.google.com/mapfiles/ms/icons/green-dot.png",
                            scaledSize: new google.maps.Size(32, 32)
                        },
                        title: "Ponto de Partida"
                    });

                    // Marcador de destino
                    new google.maps.Marker({
                        position: end,
                        map: map,
                        icon: {
                            url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                            scaledSize: new google.maps.Size(32, 32)
                        },
                        title: "Ponto de Destino"
                    });

                    <?php if($freight->current_lat && $freight->current_lng): ?>
                        updateTruckPosition(<?php echo e($freight->current_lat); ?>, <?php echo e($freight->current_lng); ?>, true);
                    <?php endif; ?>
                }
            });
        <?php endif; ?>
    }

    function updateTruckPosition(lat, lng, position, initialLoad = false) {
        const truckPosition = new google.maps.LatLng(lat, lng);
        
        if (truckMarker) {
            truckMarker.setMap(null);
        }
        
        truckMarker = new google.maps.Marker({
            position: truckPosition,
            map: map,
            icon: {
                url: "https://img.icons8.com/ios-filled/50/000000/truck.png",
                scaledSize: new google.maps.Size(40, 40)
            },
            title: "Posição Atual do Caminhão"
        });
        
        document.getElementById('current-position').textContent = position;
        document.getElementById('last-update').textContent = new Date().toLocaleString();
        
        if (!initialLoad) {
            // Ajusta o zoom para nível 15 (ruas visíveis) e centraliza no caminhão
            map.setCenter(truckPosition);
            map.setZoom(15);
            
            // Efeito de animação
            truckMarker.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => {
                truckMarker.setAnimation(null);
            }, 1500);
        }
    }

    function startAutoUpdate() {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
        
        updateInterval = setInterval(() => {
            fetch(`/freights/<?php echo e($freight->id); ?>/position`)
                .then(response => response.json())
                .then(data => {
                    if (data.current_lat && data.current_lng) {
                        updateTruckPosition(data.current_lat, data.current_lng, data.position);
                    }
                    
                    if (data.history && data.history.length > 0) {
                        updateHistoryTable(data.history);
                    }
                })
                .catch(error => {
                    console.error("Erro ao atualizar posição:", error);
                });
        }, 10000);
    }

    function updateHistoryTable(history) {
        const historyTable = document.getElementById('activity-history');
        historyTable.innerHTML = '';
        
        history.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${new Date(item.created_at).toLocaleString()}</td>
                <td>${item.event}</td>
                <td>${item.details}</td>
            `;
            historyTable.appendChild(row);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof google !== 'undefined') {
            startAutoUpdate();
        }
    });

    window.addEventListener('beforeunload', function() {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/luiz/SiteFretesNasNuvens/resources/views/freights/map.blade.php ENDPATH**/ ?>