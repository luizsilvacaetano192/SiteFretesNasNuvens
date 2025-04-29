@extends('layouts.app')

@section('title', 'Solicitar Frete')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-0">Solicitar Frete para Carga #{{ $shipment->id }}</h1>
        </div>
    </div>

    <form id="freightRequestForm" method="POST" action="{{ route('freights.store') }}" novalidate>
        @csrf
        <input type="hidden" name="shipment_id" value="{{ $shipment->id }}">
        <input type="hidden" name="company_id" value="{{ $shipment->company->id }}">
        <input type="hidden" name="status_id" value="3">
        
        <!-- Campos hidden para dados da rota -->
        <input type="hidden" id="driver_freight_value" name="driver_freight_value">
        <input type="hidden" id="distance" name="distance">
        <input type="hidden" id="duration" name="duration">
        <input type="hidden" id="distance_km" name="distance_km">
        <input type="hidden" id="duration_min" name="duration_min">
        <input type="hidden" id="current_position" name="current_position">
        <input type="hidden" id="current_lat" name="current_lat">
        <input type="hidden" id="current_lng" name="current_lng">
        <input type="hidden" id="start_lat" name="start_lat">
        <input type="hidden" id="start_lng" name="start_lng">
        <input type="hidden" id="destination_lat" name="destination_lat">
        <input type="hidden" id="destination_lng" name="destination_lng">
        <input type="hidden" id="status_id" name="status_id" value="3">

        <!-- Informações da Carga -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Detalhes da Carga</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Empresa Contratante</label>
                        <input type="text" class="form-control-plaintext" value="{{ $shipment->company->name }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Tipo de Carga</label>
                        <input type="text" class="form-control-plaintext" value="{{ ucfirst($shipment->cargo_type) }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Peso (kg)</label>
                        <input type="text" class="form-control-plaintext" value="{{ $shipment->weight }}" readonly>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Dimensões (cm)</label>
                        <input type="text" class="form-control-plaintext" value="{{ $shipment->dimensions }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Volume (m³)</label>
                        <input type="text" class="form-control-plaintext" value="{{ $shipment->volume }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Descrição</label>
                        <input type="text" class="form-control-plaintext" value="{{ $shipment->description }}" readonly>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Carga Frágil</label>
                        <input type="text" class="form-control-plaintext" value="{{ $shipment->is_fragile ? 'Sim' : 'Não' }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Carga Perigosa</label>
                        <input type="text" class="form-control-plaintext" value="{{ $shipment->is_hazardous ? 'Sim' : 'Não' }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Controle de Temperatura</label>
                        <input type="text" class="form-control-plaintext" value="{{ $shipment->requires_temperature_control ? 'Sim' : 'Não' }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        @if($shipment->requires_temperature_control)
                        <label class="form-label fw-bold">Faixa de Temperatura</label>
                        <input type="text" class="form-control-plaintext" 
                               value="{{ $shipment->min_temperature }}°{{ $shipment->temperature_unit }} a {{ $shipment->max_temperature }}°{{ $shipment->temperature_unit }}" readonly>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Endereços -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Endereços</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_address" class="form-label fw-bold required-field">Origem</label>
                        <input id="start_address" name="start_address" type="text" class="form-control" required>
                        <div class="invalid-feedback">Por favor, informe o endereço de origem.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="destination_address" class="form-label fw-bold required-field">Destino</label>
                        <input id="destination_address" name="destination_address" type="text" class="form-control" required>
                        <div class="invalid-feedback">Por favor, informe o endereço de destino.</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pickup_date" class="form-label fw-bold required-field">Data de Coleta</label>
                        <input type="datetime-local" id="pickup_date" name="pickup_date" class="form-control" required>
                        <div class="invalid-feedback">Por favor, informe a data de coleta.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="delivery_date" class="form-label fw-bold required-field">Data Estimada de Entrega</label>
                        <input type="datetime-local" id="delivery_date" name="delivery_date" class="form-control" required>
                        <div class="invalid-feedback">Por favor, informe a data estimada de entrega.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuração do Frete -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Configuração do Frete</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="truck_type" class="form-label fw-bold required-field">Tipo de Veículo</label>
                        <select id="truck_type" name="truck_type" class="form-select" required>
                            <option value="" disabled selected>Selecione o tipo</option>
                            <option value="pequeno">Pequeno (até 3 ton)</option>
                            <option value="medio">Médio (3-8 ton)</option>
                            <option value="grande">Grande (8+ ton)</option>
                            @if($shipment->requires_temperature_control)
                            <option value="refrigerado_pequeno">Refrigerado Pequeno</option>
                            <option value="refrigerado_medio">Refrigerado Médio</option>
                            <option value="refrigerado_grande">Refrigerado Grande</option>
                            @endif
                            @if($shipment->is_hazardous)
                            <option value="tanque_pequeno">Tanque Pequeno (produtos perigosos)</option>
                            <option value="tanque_grande">Tanque Grande (produtos perigosos)</option>
                            @endif
                        </select>
                        <div class="invalid-feedback">Por favor, selecione o tipo de veículo.</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Método de Pagamento</label>
                        <input type="text" class="form-control-plaintext" value="PIX" readonly>
                        <input type="hidden" name="payment_method" value="pix">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="freight_value" class="form-label fw-bold required-field">Valor do Frete (R$)</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" id="freight_value" name="freight_value" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="invalid-feedback">Por favor, informe o valor do frete.</div>
                        <small class="text-muted">Valor sugerido: <span id="suggested_value">R$ 0,00</span></small>
                    </div>
                </div>
                
                <!-- Seção de Seguradoras -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Filtro por Seguradoras</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <label class="form-label fw-bold">Selecione as Seguradoras</label>
                                        <div class="form-control" style="height: auto; min-height: 100px; max-height: 200px; overflow-y: auto;">
                                            @foreach([
                                                'Allianz Carga',
                                                'Berkley Seguros',
                                                'Bradesco Carga',
                                                'Chubb Carga',
                                                'Fairfax Seguros',
                                                'HDI Seguros Carga',
                                                'Itaú Seguros Carga',
                                                'J. Malucelli Seguros',
                                                'Liberty Seguros Carga',
                                                'Mapfre Carga',
                                                'Mitsui Sumitomo Seguros',
                                                'Pamcary',
                                                'Porto Seguro Carga',
                                                'Seguradora Líder (DPVAT)',
                                                'Sompo Carga',
                                                'Sura Carga',
                                                'Tokio Marine Carga',
                                                'Yousure Carga',
                                                'Zurich Carga',
                                            ] as $insurer)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="insurance_carriers[]" 
                                                       value="{{ strtolower(str_replace(' ', '_', $insurer)) }}" 
                                                       id="{{ $insurer }}Check">
                                                <label class="form-check-label" for="{{ $insurer }}Check">
                                                    {{ $insurer }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-warning h-100">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-exclamation-triangle fa-2x me-3 mt-1"></i>
                                                <div>
                                                    <h5 class="alert-heading">Atenção</h5>
                                                    <hr>
                                                    <p class="mb-0">
                                                        Ao selecionar uma ou mais seguradoras, somente motoristas cadastrados 
                                                        nas seguradoras selecionadas poderão visualizar e aceitar este frete.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações Adicionais -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informações Adicionais</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="freight_description" class="form-label fw-bold">Descrição do Frete (Opcional)</label>
                        <textarea id="freight_description" name="freight_description" class="form-control" rows="4" 
                                  placeholder="Adicione informações adicionais sobre o frete, como instruções especiais, detalhes de carga/descarga, ou outros requisitos"></textarea>
                        <small class="text-muted">Esta descrição será visível para os motoristas interessados</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="loading_instructions" class="form-label fw-bold">Instruções de Carregamento</label>
                        <input type="text" id="loading_instructions" name="loading_instructions" class="form-control" 
                               placeholder="Ex: Portão 3, doca 5">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="unloading_instructions" class="form-label fw-bold">Instruções de Descarga</label>
                        <input type="text" id="unloading_instructions" name="unloading_instructions" class="form-control" 
                               placeholder="Ex: Falar com João na recepção">
                    </div>
                </div>
            </div>
        </div>

        <!-- Rota e Detalhes -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Rota e Detalhes</h5>
            </div>
            <div class="card-body">
                <div id="map" style="height: 400px; width: 100%; margin-bottom: 20px;"></div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-info">
                            <div class="card-body text-center">
                                <h6 class="mb-2">Distância</h6>
                                <p id="distance_value" class="h4 mb-0">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-info">
                            <div class="card-body text-center">
                                <h6 class="mb-2">Tempo Estimado</h6>
                                <p id="duration_value" class="h4 mb-0">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-success">
                            <div class="card-body text-center">
                                <h6 class="mb-2">Valor Sugerido</h6>
                                <p id="calculated_value" class="h4 mb-0">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Valor Final -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Resumo Financeiro</h6>
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="freight-summary">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="text-muted">Valor Base:</span>
                                                <span id="base_freight_value">R$ 0,00</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="text-muted">Taxa da Plataforma ({{ $settings->cloud_percentage }}%):</span>
                                                <span id="platform_fee_value" class="text-danger">- R$ 0,00</span>
                                            </div>
                                            <hr class="my-3">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="fw-bold">Total a Pagar:</span>
                                                <span id="final_value" class="fw-bold">R$ 0,00</span>
                                            </div>
                                            
                                            <div class="driver-value-container bg-light p-3 rounded">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">Valor para o Motorista</h6>
                                                        <small class="text-muted">Após desconto da plataforma</small>
                                                    </div>
                                                    <span id="driver_value" class="display-6 text-success fw-bold">R$ 0,00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-5">
                                        <div class="alert alert-info h-100">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-info-circle fa-2x me-3 mt-1"></i>
                                                <div>
                                                    <h5 class="alert-heading">Sobre os Valores</h5>
                                                    <hr>
                                                    <ul class="mb-0 ps-3">
                                                        <li>O <strong>Valor Base</strong> é calculado pela distância × taxa por km</li>
                                                        <li>A <strong>Taxa da Plataforma</strong> de {{ $settings->cloud_percentage }}% é descontada do valor total</li>
                                                        <li>O <strong>Motorista recebe</strong> o valor após o desconto</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-5">
            <button type="button" class="btn btn-secondary me-md-2" onclick="window.history.back()">
                Cancelar
            </button>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-qrcode me-2"></i> Confirmar e Pagar via PIX
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places" async defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Variáveis globais
    let map, directionsService, directionsRenderer, autocompleteStart, autocompleteDestination;
    
    // Função para carregar o Google Maps
    function loadGoogleMaps() {
        // Verificar se os elementos necessários existem
        if (!document.getElementById('map') || 
            !document.getElementById('start_address') || 
            !document.getElementById('destination_address')) {
            return;
        }

        // Verificar se a API do Google Maps foi carregada
        if (typeof google === 'object' && typeof google.maps === 'object') {
            initializeMap();
        } else {
            // Tentar novamente após um curto período
            setTimeout(loadGoogleMaps, 200);
        }
    }

    // Função para inicializar o mapa
    function initializeMap() {
        const mapDiv = document.getElementById('map');
        if (!mapDiv) return;

        // Configurações iniciais do mapa
        map = new google.maps.Map(mapDiv, {
            center: { lat: -15.7801, lng: -47.9292 }, // Centro do Brasil
            zoom: 5
        });

        // Inicializar serviços de rota
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: false,
            polylineOptions: {
                strokeColor: '#4285F4',
                strokeOpacity: 1.0,
                strokeWeight: 5
            }
        });
        directionsRenderer.setMap(map);

        // Inicializar autocomplete para os campos de endereço
        initAutocomplete();
    }

    // Função para inicializar autocomplete
    function initAutocomplete() {
        const startAddressInput = document.getElementById('start_address');
        const destinationAddressInput = document.getElementById('destination_address');

        if (!startAddressInput || !destinationAddressInput) return;

        // Configurar autocomplete para origem
        autocompleteStart = new google.maps.places.Autocomplete(startAddressInput, {
            fields: ['address_components', 'geometry'],
            componentRestrictions: { country: 'br' }
        });

        // Configurar autocomplete para destino
        autocompleteDestination = new google.maps.places.Autocomplete(destinationAddressInput, {
            fields: ['address_components', 'geometry'],
            componentRestrictions: { country: 'br' }
        });

        // Adicionar listeners para calcular a rota quando os endereços mudarem
        autocompleteStart.addListener('place_changed', calculateRoute);
        autocompleteDestination.addListener('place_changed', calculateRoute);
    }

    function getSafeSetting(settingName, defaultValue = 0) {
        // Mapear todas as configurações disponíveis
        const settings = {
            small_truck_rate: {{ $settings->small_truck_rate ?? 0 }},
            medium_truck_rate: {{ $settings->medium_truck_rate ?? 0 }},
            large_truck_rate: {{ $settings->large_truck_rate ?? 0 }},
            small_refrigerated_rate: {{ $settings->small_refrigerated_rate ?? 0 }},
            medium_refrigerated_rate: {{ $settings->medium_refrigerated_rate ?? 0 }},
            large_refrigerated_rate: {{ $settings->large_refrigerated_rate ?? 0 }},
            small_tanker_rate: {{ $settings->small_tanker_rate ?? 0 }},
            large_tanker_rate: {{ $settings->large_tanker_rate ?? 0 }},
            cloud_percentage: {{ $settings->cloud_percentage ?? 0 }},
            minimum_freight_value: {{ $settings->minimum_freight_value ?? 0 }},
            weight_surcharge_5000: {{ $settings->weight_surcharge_5000 ?? 1 }},
            weight_surcharge_3000: {{ $settings->weight_surcharge_3000 ?? 1 }},
            fragile_surcharge: {{ $settings->fragile_surcharge ?? 1 }},
            hazardous_surcharge: {{ $settings->hazardous_surcharge ?? 1 }}
        };

        // Obter o valor da configuração ou usar o padrão
        const value = settings[settingName] !== undefined ? settings[settingName] : defaultValue;
        const num = parseFloat(value);
        
        return isNaN(num) ? defaultValue : num;
    }

    function calculateFreightValue(distanceInKm, truckType) {
        const rates = {
            'pequeno': getSafeSetting('small_truck_rate'),
            'medio': getSafeSetting('medium_truck_rate'),
            'grande': getSafeSetting('large_truck_rate'),
            'refrigerado_pequeno': getSafeSetting('small_refrigerated_rate'),
            'refrigerado_medio': getSafeSetting('medium_refrigerated_rate'),
            'refrigerado_grande': getSafeSetting('large_refrigerated_rate'),
            'tanque_pequeno': getSafeSetting('small_tanker_rate'),
            'tanque_grande': getSafeSetting('large_tanker_rate')
        };
        
        const platformPercentage = getSafeSetting('cloud_percentage');
        
        let value = distanceInKm * rates[truckType];
        value = Math.max(value, getSafeSetting('minimum_freight_value'));
        
        // Aplicar fatores adicionais
        const weight = parseFloat("{{ $shipment->weight }}");
        if (weight > 5000) value *= getSafeSetting('weight_surcharge_5000');
        else if (weight > 3000) value *= getSafeSetting('weight_surcharge_3000');
        
        if ("{{ $shipment->is_fragile }}" === "1") value *= getSafeSetting('fragile_surcharge');
        if ("{{ $shipment->is_hazardous }}" === "1") value *= getSafeSetting('hazardous_surcharge');
        
        const platformFee = value * (platformPercentage / 100);
        const driverValue = value - platformFee;
        
        return {
            total: Math.round(value * 100) / 100,
            platformFee: Math.round(platformFee * 100) / 100,
            driverValue: Math.round(driverValue * 100) / 100
        };
    }

    // Função para formatar valores monetários
    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', { 
            style: 'currency', 
            currency: 'BRL',
            minimumFractionDigits: 2
        });
    }

    // Função para converter texto de duração em minutos
    function parseDuration(durationText) {
        let minutes = 0;
        const hoursMatch = durationText.match(/(\d+)\s*h/);
        const minsMatch = durationText.match(/(\d+)\s*m/);
        
        if (hoursMatch) minutes += parseInt(hoursMatch[1]) * 60;
        if (minsMatch) minutes += parseInt(minsMatch[1]);
        
        return minutes;
    }

    // Função para atualizar o resumo financeiro
    function updateFinancialSummary(freightData) {
        $('#base_freight_value').text(formatCurrency(freightData.total));
        $('#platform_fee_value').text('- ' + formatCurrency(freightData.platformFee));
        $('#final_value').text(formatCurrency(freightData.total));
        $('#driver_value').text(formatCurrency(freightData.driverValue));
        $('#freight_value').val(freightData.total.toFixed(2));
        $('#driver_freight_value').val(freightData.driverValue.toFixed(2));
        $('#calculated_value').text(formatCurrency(freightData.total));
        $('#suggested_value').text(formatCurrency(freightData.total));
    }

    // Função principal para calcular a rota
    function calculateRoute() {
        const startPlace = autocompleteStart.getPlace();
        const destinationPlace = autocompleteDestination.getPlace();
        const truckType = $('#truck_type').val();

        // Verificar se todos os dados necessários estão disponíveis
        if (!startPlace || !destinationPlace || !startPlace.geometry || !destinationPlace.geometry || !truckType) {
            return;
        }

        // Configurar requisição para o serviço de rotas
        const request = {
            origin: startPlace.geometry.location,
            destination: destinationPlace.geometry.location,
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC
        };

        // Chamar o serviço de rotas
        directionsService.route(request, (response, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                // Exibir a rota no mapa
                directionsRenderer.setDirections(response);
                const route = response.routes[0].legs[0];
                const distanceKm = parseFloat(route.distance.text.replace(' km', '').replace(',', '.'));
                const durationMin = parseDuration(route.duration.text);

                // Atualizar exibição
                $('#distance_value').text(route.distance.text);
                $('#duration_value').text(route.duration.text);
                
                // Atualizar campos ocultos
                $('#distance').val(route.distance.text);
                $('#duration').val(route.duration.text);
                $('#distance_km').val(distanceKm);
                $('#duration_min').val(durationMin);
                $('#current_position').val($('#start_address').val());
                $('#current_lat').val(startPlace.geometry.location.lat());
                $('#current_lng').val(startPlace.geometry.location.lng());
                $('#start_lat').val(startPlace.geometry.location.lat());
                $('#start_lng').val(startPlace.geometry.location.lng());
                $('#destination_lat').val(destinationPlace.geometry.location.lat());
                $('#destination_lng').val(destinationPlace.geometry.location.lng());

                // Calcular e atualizar valores do frete
                const freightData = calculateFreightValue(distanceKm, truckType);
                updateFinancialSummary(freightData);
                
                // Definir datas sugeridas
                const pickupDate = new Date(new Date().getTime() + 60 * 60 * 1000); // 1 hora a partir de agora
                $('#pickup_date').val(pickupDate.toISOString().slice(0, 16));
                
                const deliveryDate = new Date(pickupDate.getTime() + durationMin * 60 * 1000 * 1.25); // Tempo de rota + 25% de margem
                $('#delivery_date').val(deliveryDate.toISOString().slice(0, 16));
            } else {
                // Exibir erro se a rota não puder ser calculada
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Não foi possível calcular a rota. Verifique os endereços informados.',
                });
            }
        });
    }

    // Função para atualizar o valor do frete quando o usuário modificar manualmente
    $('#freight_value').on('input', function() {
        const manualValue = parseFloat($(this).val()) || 0;
        const platformPercentage = getSafeSetting('cloud_percentage');
        const platformFee = manualValue * (platformPercentage / 100);
        const driverValue = manualValue - platformFee;
        
        // Atualizar os valores financeiros
        $('#base_freight_value').text(formatCurrency(manualValue));
        $('#platform_fee_value').text('- ' + formatCurrency(platformFee));
        $('#final_value').text(formatCurrency(manualValue));
        $('#driver_value').text(formatCurrency(driverValue));
        $('#driver_freight_value').val(driverValue.toFixed(2));
        
        // Atualizar o valor sugerido para refletir a mudança
        $('#suggested_value').text(formatCurrency(manualValue));
        
        // Validar o valor mínimo
        const minimumValue = getSafeSetting('minimum_freight_value');
        if (manualValue < minimumValue) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').text(`O valor mínimo do frete é ${formatCurrency(minimumValue)}`);
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Função para validar todos os campos antes do envio
    function validateForm() {
        let isValid = true;
        
        // Validar campos obrigatórios
        const requiredFields = [
            'start_address', 
            'destination_address',
            'pickup_date',
            'delivery_date',
            'truck_type',
            'freight_value'
        ];

        requiredFields.forEach(field => {
            const element = $(`#${field}`);
            if (!element.val()) {
                element.addClass('is-invalid');
                isValid = false;
            } else {
                element.removeClass('is-invalid');
            }
        });
        
        // Validar valor mínimo do frete
        const freightValue = parseFloat($('#freight_value').val()) || 0;
        const minimumValue = getSafeSetting('minimum_freight_value');
        if (freightValue < minimumValue) {
            $('#freight_value').addClass('is-invalid');
            $('#freight_value').next('.invalid-feedback').text(`O valor mínimo do frete é ${formatCurrency(minimumValue)}`);
            isValid = false;
        }
        
        // Validar se a rota foi calculada
        if (!$('#distance_km').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Rota não calculada',
                text: 'Por favor, calcule a rota antes de enviar o formulário.',
            });
            isValid = false;
        }
        
        return isValid;
    }

    // Quando o documento estiver pronto
    $(document).ready(function() {
        // Inicializar validação do formulário
        (function() {
            'use strict';
            
            const form = document.getElementById('freightRequestForm');
            
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        })();

        // Carregar o Google Maps
        loadGoogleMaps();

        // Listener para mudança no tipo de veículo
        $('#truck_type').change(function() {
            if (autocompleteStart?.getPlace() && autocompleteDestination?.getPlace()) {
                calculateRoute();
            }
        });

        // Listener para envio do formulário
        $('#freightRequestForm').on('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                // Rolar até o primeiro erro
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 500);
                return;
            }

            // Desabilitar botão de envio
            $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...');

            // Preparar dados do formulário
            const formData = new FormData(this);

            // Enviar requisição AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.payment_link) {
                    // Abrir link de pagamento em nova aba
                    const paymentWindow = window.open(data.payment_link, '_blank');
                    
                    if (!paymentWindow) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Popup bloqueado',
                            text: 'Por favor, permita popups para este site para completar o pagamento.',
                        });
                        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-qrcode me-2"></i> Confirmar e Pagar via PIX');
                        return;
                    }
                    
                    // Exibir mensagem de sucesso
                    Swal.fire({
                        icon: 'success',
                        title: 'Frete criado com sucesso!',
                        text: 'Você será redirecionado para a página de fretes.',
                        timer: 3000,
                        timerProgressBar: true,
                        willClose: () => {
                            window.location.href = '{{ route("freights.index") }}';
                        }
                    });
                } else {
                    $('#submitBtn').prop('disabled', false).html('<i class="fas fa-qrcode me-2"></i> Confirmar e Pagar via PIX');
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message || 'Erro ao processar o pagamento',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-qrcode me-2"></i> Confirmar e Pagar via PIX');
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro ao enviar o formulário',
                });
            });
        });

        // Adicionar validação em tempo real para os campos
        $('input, select').on('change input', function() {
            if ($(this).val()) {
                $(this).removeClass('is-invalid');
            }
        });

        // Garantir que o mapa seja recalculado quando os endereços mudarem
        $('#start_address, #destination_address').on('change', function() {
            if (autocompleteStart?.getPlace() && autocompleteDestination?.getPlace()) {
                calculateRoute();
            }
        });
    });
</script>
@endpush

@section('styles')
<style>
    .pac-container {
        z-index: 1051;
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }
    .pac-item {
        padding: 8px 10px;
        font-size: 14px;
        cursor: pointer;
    }
    .pac-item:hover {
        background-color: #f5f5f5;
    }
    #map {
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    #freight_value {
        font-weight: bold;
        text-align: right;
    }
    .form-control-plaintext {
        padding: 0.375rem 0;
        background-color: transparent;
    }
    .display-6 {
        font-size: 1.75rem;
    }
    .form-select {
        cursor: pointer;
    }
    .swal2-popup.swal2-toast {
        padding: 1em 1.5em;
    }
    textarea.form-control-plaintext {
        resize: none;
    }
    .form-check-input:disabled {
        opacity: 1;
    }
    .alert.alert-info {
        background-color: #e7f5ff;
        border-color: #a5d8ff;
        color: #1864ab;
    }
    .alert.alert-warning {
        background-color: #fff3bf;
        border-color: #ffec99;
        color: #5f3f00;
    }
    .alert.alert-info h5,
    .alert.alert-warning h5 {
        font-weight: 600;
    }
    .freight-summary {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    .driver-value-container {
        border: 2px solid #d1e7dd;
        background-color: rgba(209, 231, 221, 0.2) !important;
    }
    .freight-summary hr {
        border-top: 2px dashed #dee2e6;
    }
    .btn-primary {
        background-color: #32BB91;
        border-color: #32BB91;
    }
    .btn-primary:hover {
        background-color: #2AA381;
        border-color: #2AA381;
    }
    .form-control[type="checkbox"] {
        margin-right: 8px;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
    .invalid-feedback {
        color: #dc3545;
        display: none;
        margin-top: 0.25rem;
        font-size: 0.875em;
    }
    .was-validated .form-control:invalid ~ .invalid-feedback,
    .was-validated .form-control:invalid ~ .invalid-tooltip,
    .form-control.is-invalid ~ .invalid-feedback,
    .form-control.is-invalid ~ .invalid-tooltip {
        display: block;
    }
    .required-field::after {
        content: " *";
        color: #dc3545;
    }
</style>
@endsection