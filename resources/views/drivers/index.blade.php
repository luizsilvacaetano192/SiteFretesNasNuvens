@extends('layouts.app')

@section('styles')
<style>
    /* Estilos completos */
    .driver-table {
        font-size: 0.875rem;
    }
    
    .driver-table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .driver-actions .btn {
        transition: all 0.2s ease;
    }
    
    .driver-actions .btn:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    #driversLocationModal .modal-dialog {
        max-width: 95%;
        height: 90vh;
        margin: 1rem auto;
    }

    #driversLocationModal .modal-content {
        height: 100%;
    }

    #driversMap {
        width: 100%;
        height: calc(100% - 60px);
        min-height: 500px;
        background: #f8f9fa;
    }

    .leaflet-popup-content {
        font-size: 0.875rem;
    }

    .leaflet-popup-content b {
        color: #0d6efd;
    }

    @media (max-width: 768px) {
        #driversLocationModal .modal-dialog {
            max-width: 100%;
            height: 100vh;
            margin: 0;
        }

        #driversMap {
            height: calc(100vh - 120px);
        }
    }

    .btn:hover {
        transform: scale(1.05);
        transition: 0.2s ease;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.2);
    }

    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .table {
        font-size: 0.875rem;
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom-width: 1px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }

    td.dt-control {
        position: relative;
    }

    td.dt-control::before {
        content: "+";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        font-size: 1rem;
        color: #198754;
        display: inline-block;
        text-align: center;
        width: 20px;
        cursor: pointer;
    }

    tr.shown td.dt-control::before {
        content: "-";
        color: #dc3545;
    }

    .driver-details-img {
        max-height: 150px;
        object-fit: contain;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-buildings me-2"></i>Gestão de motoristas</h2>
        <button class="btn btn-primary" id="showDriversLocationBtn">
            <i class="fas fa-map-marked-alt me-2"></i>Ver Localizações
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="drivers-table" class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40"></th>
                            <th>Motorista</th>
                            <th>Contato</th>
                            <th>Documentos</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th class="text-end" width="220">Ações</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Localização -->
<div class="modal fade" id="driversLocationModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-map-marked-alt me-2"></i>Localização dos Motoristas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="driversMap"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Imagem -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" class="img-fluid w-100" style="max-height:90vh; object-fit:contain;">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Bloqueio -->
<div class="modal fade" id="blockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-lock me-2"></i>Bloqueio de Motorista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>Escolha o tipo de bloqueio e informe o motivo.
                </div>
                <div class="mb-3">
                    <label for="blockReason" class="form-label">Motivo do Bloqueio</label>
                    <textarea class="form-control" id="blockReason" rows="3" placeholder="Descreva o motivo do bloqueio..."></textarea>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-danger" id="blockUserBtn">
                        <i class="fas fa-user-slash me-2"></i>Bloquear Usuário
                    </button>
                    <button class="btn btn-warning" id="blockTransferBtn">
                        <i class="fas fa-exchange-alt me-2"></i>Bloquear Transferências
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Saldo -->
<div class="modal fade" id="balanceModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-wallet me-2"></i>Saldo e Transferências</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-0 bg-light">
                            <div class="card-body text-center">
                                <h6 class="card-title text-muted">ID Conta Asaas</h6>
                                <p class="card-text h5" id="asaasIdentifier">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-0 bg-success text-white">
                            <div class="card-body text-center">
                                <h6 class="card-title">Saldo Total</h6>
                                <p class="card-text h4" id="totalBalance">R$ 0,00</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-0 bg-warning">
                            <div class="card-body text-center">
                                <h6 class="card-title">Saldo Bloqueado</h6>
                                <p class="card-text h4" id="blockedBalance">R$ 0,00</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 border-0 bg-info text-white">
                            <div class="card-body text-center">
                                <h6 class="card-title">Saldo Disponível</h6>
                                <p class="card-text h4" id="availableBalance">R$ 0,00</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Histórico de Transferências</h5>
                    <button type="button" class="btn btn-success" id="newTransferBtn">
                        <i class="fas fa-plus me-2"></i>Nova Transferência
                    </button>
                </div>
                <div class="table-responsive">
                    <table id="transfersTable" class="table table-striped table-hover" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Descrição</th>
                                <th>Data</th>
                                <th>ID Asaas</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Transferência -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>Realizar Transferência</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <form id="transferForm">
                            <input type="hidden" id="transferDriverId">
                            <input type="hidden" id="selectedFreightValue">
                            <div class="mb-3">
                                <label for="transferType" class="form-label">Tipo de Transferência</label>
                                <select class="form-select" id="transferType" required>
                                    <option value="">Selecione...</option>
                                    <option value="available_balance">Liberar valor</option>
                                    <option value="blocked_balance">Enviar valor bloqueado</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="transferAmount" class="form-label">Valor (R$)</label>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="transferAmount" required>
                            </div>
                            <div class="mb-3">
                                <label for="transferDescription" class="form-label">Descrição</label>
                                <textarea class="form-control" id="transferDescription" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-8">
                        <div class="card border-0 h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-truck me-2"></i>Fretes disponíveis (opcional)</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 400px;">
                                    <table id="transferFreightsTable" class="table table-sm table-hover mb-0" style="width:100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="40"></th>
                                                <th>ID</th>
                                                <th>Empresa</th>
                                                <th>Tipo de Carga</th>
                                                <th>Valor</th>
                                                <th>Data</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="submitTransfer">
                    <i class="fas fa-paper-plane me-2"></i>Enviar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Fretes -->
<div class="modal fade" id="freightsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-truck me-2"></i>Fretes do Motorista</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="freightsTable" class="table table-striped table-hover" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAllFreights">
                                </th>
                                <th>ID Frete</th>
                                <th>Empresa</th>
                                <th>Tipo de Carga</th>
                                <th>Data do Frete</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Caminhões -->
<div class="modal fade" id="trucksModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-truck me-2"></i>Caminhões e Implementos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="trucksTable" class="table table-striped table-hover" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th width="40"></th>
                                <th>Placa</th>
                                <th>Marca/Modelo</th>
                                <th>Ano</th>
                                <th>Tipo</th>
                                <th>Data</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
// Constantes
const DEFAULT_MAP_CENTER = [-15.7889, -47.8799];
const DEFAULT_MAP_ZOOM = 4;
const MARKER_ZOOM = 12;
const MAX_MAP_INIT_ATTEMPTS = 10;
const MAP_INIT_RETRY_DELAY = 300;

// Variáveis globais
let driversMap = null;
let mapInitializationAttempts = 0;
let driversLocationModal = null;
let mapMarkers = [];
let selectedDriverId = null;

// Funções utilitárias
function maskPhone(value) {
    if (!value) return '';
    return value.replace(/\D/g, '').replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
}

function maskCPF(cpf) {
    return cpf?.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4") || '';
}

function formatDateBR(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('pt-BR');
}

function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value || 0);
}

function getStatusLabel(status) {
    const labels = {
        'create': ['Aguardando Ativação', 'warning'],
        'active': ['Ativo', 'success'],
        'block': ['Bloqueado', 'danger'],
        'transfer_block': ['Transferências Bloqueadas', 'danger'],
    };
    return labels[status] || ['Desconhecido', 'secondary'];
}

function openImageModal(src) {
    $('#modalImage').attr('src', src);
    new bootstrap.Modal('#imageModal').show();
}

// Funções do mapa
function showDriversLocation() {
    mapInitializationAttempts = 0;
    
    if (!driversLocationModal) {
        driversLocationModal = new bootstrap.Modal('#driversLocationModal');
        
        $('#driversLocationModal').on('shown.bs.modal', function() {
            initializeMapWithRetry();
        });
        
        $('#driversLocationModal').on('hidden.bs.modal', function() {
            clearMap();
        });
    }
    
    driversLocationModal.show();
}

function initializeMapWithRetry() {
    if (mapInitializationAttempts >= MAX_MAP_INIT_ATTEMPTS) {
        toastr.error('Não foi possível carregar o mapa após várias tentativas');
        return;
    }

    mapInitializationAttempts++;
    
    const mapContainer = document.getElementById('driversMap');
    if (!mapContainer || mapContainer.offsetWidth === 0 || mapContainer.offsetHeight === 0) {
        setTimeout(initializeMapWithRetry, MAP_INIT_RETRY_DELAY);
        return;
    }

    try {
        if (driversMap) {
            driversMap.remove();
            driversMap = null;
        }

        driversMap = L.map('driversMap', {
            preferCanvas: true,
            zoomControl: true
        }).setView(DEFAULT_MAP_CENTER, DEFAULT_MAP_ZOOM);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(driversMap);

        setTimeout(() => {
            driversMap.invalidateSize(true);
            loadDriverLocations();
        }, 100);

    } catch (error) {
        console.error('Erro na inicialização do mapa:', error);
        setTimeout(initializeMapWithRetry, MAP_INIT_RETRY_DELAY);
    }
}

function loadDriverLocations() {
    if (!driversMap) return;

    clearMarkers();

    $.ajax({
        url: '/drivers/locations',
        method: 'GET',
        success: function(data) {
            if (!data || data.length === 0) {
                showNoLocationsMessage();
                return;
            }
            
            const bounds = L.latLngBounds();
            let hasValidLocations = false;
            
            data.forEach(driver => {
                if (driver.latitude && driver.longitude) {
                    const latLng = L.latLng(driver.latitude, driver.longitude);
                    const marker = L.marker(latLng).addTo(driversMap);
                    
                    marker.bindPopup(`
                        <b>${driver.name}</b><br>
                        ${driver.address ? `Endereço: ${driver.address}<br>` : ''}
                        ${driver.phone ? `Tel: ${maskPhone(driver.phone)}<br>` : ''}
                        Status: ${getStatusLabel(driver.status)[0]}
                    `);
                    
                    mapMarkers.push(marker);
                    bounds.extend(latLng);
                    hasValidLocations = true;
                }
            });
            
            if (hasValidLocations) {
                if (mapMarkers.length === 1) {
                    driversMap.setView(mapMarkers[0].getLatLng(), MARKER_ZOOM);
                } else {
                    driversMap.fitBounds(bounds.pad(0.2));
                }
            } else {
                showNoLocationsMessage();
            }
        },
        error: function() {
            showMapError('Erro ao carregar localizações');
        }
    });
}

function clearMarkers() {
    mapMarkers.forEach(marker => marker.remove());
    mapMarkers = [];
}

function clearMap() {
    clearMarkers();
    if (driversMap) {
        driversMap.remove();
        driversMap = null;
    }
}

function showNoLocationsMessage() {
    if (!driversMap) return;
    
    driversMap.setView(DEFAULT_MAP_CENTER, DEFAULT_MAP_ZOOM);
    L.popup()
        .setLatLng(driversMap.getCenter())
        .setContent('Nenhuma localização disponível')
        .openOn(driversMap);
}

function showMapError(message) {
    if (!driversMap) return;
    
    L.popup()
        .setLatLng(driversMap.getCenter())
        .setContent(message)
        .openOn(driversMap);
}

// Funções de gestão de motoristas
function updateDriverStatus(id, status) {
    const reason = $('#blockReason').val().trim();

    if ((status === 'block' || status === 'transfer_block') && !reason) {
        toastr.warning('Por favor, informe o motivo do bloqueio.');
        return;
    }

    $.ajax({
        url: `/drivers/${id}/update-status`,
        type: 'POST',
        data: {
            status,
            reason,
            _token: '{{ csrf_token() }}'
        },
        success: function() {
            $('#drivers-table').DataTable().ajax.reload(null, false);
            bootstrap.Modal.getInstance(document.getElementById('blockModal'))?.hide();
            toastr.success(`Status atualizado para ${getStatusLabel(status)[0]}`);
            $('#blockReason').val('');
        },
        error: function() {
            toastr.error("Erro ao atualizar status.");
        }
    });
}

function activateDriver(id, status) {
    if (status === 'active') {
        selectedDriverId = id;
        new bootstrap.Modal('#blockModal').show();
    } else if (status === 'create') {
        $.get(`/drivers/${id}`, function(driverData) {
            const apiData = {
                driver_id: id,
                name: driverData.name,
                cpfCnpj: driverData.cpf.replace(/\D/g, ''),
                phone: driverData.phone
            };

            toastr.info('Criando conta Asaas para o motorista...', 'Aguarde', {timeOut: 0});
           
            sendCreateAsaasAccount(apiData, id);
            
        }).fail(function() {
            toastr.error('Não foi possível obter os dados do motorista');
        });
    } else {
        updateDriverStatus(id, 'active');
    }
}

async function sendCreateAsaasAccount(apiData, id) {
    try {
        const response = await fetch('/api/create-asaas-account', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(apiData)
        });

        const result = await response.json();

        toastr.clear();

        if (result.success) {
            toastr.success('Conta Asaas criada com sucesso! Ativando motorista...');
            updateDriverStatus(id, 'active');
            sendSms(apiData);
        } else {
            toastr.error('Não foi possível criar a conta Asaas: ' + (result.message || 'Erro desconhecido'));
        }
    } catch (error) {
        toastr.clear();
        toastr.error('Erro ao conectar com o serviço de pagamentos');
    }
}

async function sendSms(apiData) {
    try {
        const body = {
            phone: apiData.phone,
            message: `Sr(a) ${apiData.name} seu cadastro de motorista Fretes em nuvem foi ativado. Já pode logar e usar para realizar fretes.`
        };

        const response = await fetch('/SendSms', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(body)
        });

        const result = await response.json();

        toastr.clear();

        if (result.success) {
            toastr.success('Enviado aviso de ativação por SMS para o motorista');
        } else {
            toastr.error('Não foi possível enviar SMS: ' + (result.message || 'Erro desconhecido'));
        }

    } catch (error) {
        toastr.clear();
        toastr.error('Erro ao conectar com o serviço de SMS');
    }
}

function togglePassword(id, password) {
    const span = document.getElementById(`password-${id}`);
    if (span.innerText === '••••••••') {
        span.innerText = password;
    } else {
        span.innerText = '••••••••';
    }
}

function openWhatsApp(phone) {
    if (!phone) return alert("Número de telefone não disponível.");
    const formatted = phone.replace(/\D/g, '');
    window.open(`https://wa.me/55${formatted}`, '_blank');
}

function deleteDriver(id) {
    if (confirm('Tem certeza que deseja deletar este motorista?')) {
        $.ajax({
            url: `/drivers/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function() {
                toastr.success('Motorista deletado com sucesso');
                $('#drivers-table').DataTable().ajax.reload(null, false);
            },
            error: function(xhr) {
                try {
                    const error = JSON.parse(xhr.responseText);
                    toastr.error(error.message || 'Erro ao deletar motorista');
                } catch {
                    toastr.error('Erro ao deletar motorista');
                }
            }
        });
    }
}

// Funções de fretes
function showFreightsModal(driverId) {
    const modal = new bootstrap.Modal('#freightsModal');
    
    if ($.fn.DataTable.isDataTable('#freightsTable')) {
        $('#freightsTable').DataTable().destroy();
        $('#freightsTable tbody').empty();
    }
    
    modal.show();
    
    const freightsTable = $('#freightsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
            zeroRecords: "Nenhum frete disponível para este motorista"
        },
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50],
        ajax: {
            url: `/drivers/${driverId}/freights`,
            dataSrc: 'freights'
        },
        columns: [
            { 
                data: null,
                orderable: false,
                render: function() {
                    return '<input type="checkbox" class="freightCheckbox">';
                }
            },
            { data: 'id' },
            { data: 'company.name' },
            { data: 'cargo_type' },
            { 
                data: 'freight_date',
                render: function(data) {
                    return data ? formatDateBR(data) : '';
                }
            },
            { 
                data: 'status',
                render: function(data) {
                    const statusClasses = {
                        'pending': 'warning',
                        'in_progress': 'primary',
                        'completed': 'success',
                        'canceled': 'danger'
                    };
                    return `<span class="badge bg-${statusClasses[data] || 'secondary'}">${data}</span>`;
                }
            }
        ]
    });

    $('#selectAllFreights').click(function() {
        $('.freightCheckbox').prop('checked', this.checked);
    });
}

// Funções de transferência
function openTransferModal(driverId) {
    $('#transferDriverId').val(driverId);
    $('#transferForm')[0].reset();
    $('#selectedFreightValue').val('');
    
    if ($.fn.DataTable.isDataTable('#transferFreightsTable')) {
        $('#transferFreightsTable').DataTable().destroy();
    }
    
    const modal = new bootstrap.Modal('#transferModal');
    modal.show();
    
    const table = $('#transferFreightsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
            zeroRecords: "Nenhum frete disponível"
        },
        pageLength: 5,
        lengthMenu: [5, 10, 15],
        ajax: {
            url: `/drivers/${driverId}/freights`,
            dataSrc: 'freights'
        },
        columns: [
            { 
                data: null,
                orderable: false,
                width: '3%',
                render: function() {
                    return '<input type="radio" name="freightRadio" class="freightRadio">';
                }
            },
            { 
                data: 'id',
                width: '8%' 
            },
            { 
                data: 'company.name',
                width: '20%' 
            },
            { 
                data: 'cargo_type',
                width: '20%' 
            },
            { 
                data: 'value',
                width: '15%',
                render: function(data) {
                    return data ? formatCurrency(data) : '';
                }
            },
            { 
                data: 'freight_date',
                width: '15%',
                render: function(data) {
                    return data ? formatDateBR(data) : '';
                }
            },
            { 
                data: 'status',
                width: '19%',
                render: function(data) {
                    const statusClasses = {
                        'pending': 'warning',
                        'in_progress': 'primary',
                        'completed': 'success',
                        'canceled': 'danger'
                    };
                    return `<span class="badge bg-${statusClasses[data] || 'secondary'}">${data}</span>`;
                }
            }
        ],
        scrollX: true,
        autoWidth: false,
        fixedColumns: true
    });
    
    $('#transferFreightsTable tbody').on('click', '.freightRadio', function() {
        const rowData = table.row($(this).closest('tr')).data();
        if (rowData) {
            $('#transferAmount').val(rowData.value ? rowData.value.toFixed(2) : '');
            $('#selectedFreightValue').val(rowData.value || '');
            $('.freightRadio').not(this).prop('checked', false);
        }
    });
}

function submitTransfer() {
    const driverId = $('#transferDriverId').val();
    const type = $('#transferType').val();
    const amount = parseFloat($('#transferAmount').val());
    const description = $('#transferDescription').val();
    const freightValue = $('#selectedFreightValue').val();

    if (!type || !amount || amount <= 0) {
        toastr.warning('Preencha todos os campos corretamente');
        return;
    }

    $('#submitTransfer').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Enviando...');

    const requestData = {
        type,
        amount,
        description,
        _token: '{{ csrf_token() }}'
    };

    if (freightValue) {
        requestData.freight_value = freightValue;
    }

    $.ajax({
        url: `/transfer/${driverId}`,
        type: 'POST',
        data: requestData,
        success: function(response) {
            toastr.success('Transferência realizada com sucesso!');
            $('#transferModal').modal('hide');
            showBalanceModal(driverId);
        },
        error: function(xhr) {
            try {
                const error = JSON.parse(xhr.responseText);
                toastr.error(error.message || 'Erro ao realizar transferência');
            } catch {
                toastr.error('Erro ao realizar transferência');
            }
        },
        complete: function() {
            $('#submitTransfer').prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Enviar');
        }
    });
}

// Funções de saldo
function showBalanceModal(driverId) {
    const modal = new bootstrap.Modal('#balanceModal');
    selectedDriverId = driverId;
    
    if ($.fn.DataTable.isDataTable('#transfersTable')) {
        $('#transfersTable').DataTable().destroy();
    }
    
    $('#balanceModal .modal-body').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Carregando informações financeiras...</p>
        </div>
    `);
    
    modal.show();
    
    $.ajax({
        url: `/drivers/${driverId}/balance-data`,
        method: 'GET',
        success: function(data) {
            $('#asaasIdentifier').text(data.account.asaas_identifier || 'Não informado');
            $('#totalBalance').text(formatCurrency(data.account.total_balance));
            $('#blockedBalance').text(formatCurrency(data.account.blocked_balance));
            $('#availableBalance').text(formatCurrency(data.account.available_balance));
            
            $('#transfersTable').DataTable({
                data: data.transfers,
                columns: [
                    { 
                        data: 'type', 
                        render: type => {
                            const types = {
                                'PIX': '<span class="badge bg-success">PIX</span>',
                                'TED': '<span class="badge bg-primary">TED</span>',
                                'DOC': '<span class="badge bg-info">DOC</span>',
                                'INTERNAL': '<span class="badge bg-secondary">Interna</span>',
                                'available_balance': '<span class="badge bg-success">Liberação de Saldo</span>',
                                'blocked_balance': '<span class="badge bg-warning">Bloqueio de Saldo</span>',
                                'debited_balance': '<span class="badge bg-danger">Transferência PIX</span>'
                            };
                            return types[type] || type;
                        }
                    },
                    { 
                        data: 'amount', 
                        render: amount => formatCurrency(amount) 
                    },
                    { 
                        data: 'description',
                        render: (description, type, row) => {
                            if (description) return description;
                            
                            const descriptions = {
                                'available_balance': 'Transferência de liberação de saldo',
                                'blocked_balance': 'Transferência de saldo bloqueado',
                                'debited_balance': 'Transferência PIX feita pelo motorista'
                            };
                            
                            return descriptions[row.type] || 'Transferência bancária';
                        }
                    },
                    { 
                        data: 'transfer_date', 
                        render: date => formatDateBR(date) 
                    },
                    { 
                        data: 'asaas_identifier' 
                    }
                ],
                order: [[3, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                }
            });
            
            $('#newTransferBtn').off('click').on('click', function() {
                openTransferModal(driverId);
            });
        },
        error: function() {
            $('#balanceModal .modal-body').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Erro ao carregar informações financeiras. Tente novamente mais tarde.
                </div>
            `);
        }
    });
}

// Funções de caminhões
function showTrucksModal(driverId) {
    const modal = new bootstrap.Modal('#trucksModal');
    
    if ($.fn.DataTable.isDataTable('#trucksTable')) {
        $('#trucksTable').DataTable().destroy();
        $('#trucksTable tbody').empty();
    }
    
    modal.show();
    
    const trucksTable = $('#trucksTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
            zeroRecords: "Nenhum caminhão cadastrado para este motorista"
        },
        pageLength: 5,
        lengthMenu: [5, 10, 25],
        ajax: {
            url: `/trucks?driver_id=${driverId}`,
            dataSrc: function(response) {
                try {
                    const parsedBody = JSON.parse(response.body);
                    return parsedBody.success ? parsedBody.trucks : [];
                } catch (e) {
                    console.error('Erro ao processar resposta:', e);
                    toastr.error('Erro ao carregar dados dos caminhões');
                    return [];
                }
            }
        },
        order: [[5, 'desc']],
        columns: [
            { 
                className: 'dt-control',
                orderable: true,
                data: null,
                defaultContent: ''
            },
            { 
                data: 'license_plate',
                render: (data) => data || 'Não informado'
            },
            { 
                data: null,
                render: (data) => `${data.brand || ''} ${data.model || ''}`.trim() || 'Não informado'
            },
            { 
                data: 'manufacture_year',
                render: (data) => data || 'Não informado'
            },
            { 
                data: 'vehicle_type',
                render: (data) => data || 'N/A'
            },
            { 
                data: 'created_at',
                render: (data) => formatDateBR(data) || 'Não informado'
            },
            { 
                data: 'active',
                render: (data, type, row) => `
                    ${data ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-secondary">Inativo</span>'}
                    <button onclick="toggleTruckStatus(${row.id}, ${data})" class="btn btn-sm ${data ? 'btn-outline-danger ms-2' : 'btn-outline-success ms-2'}">
                        <i class="fas ${data ? 'fa-times' : 'fa-check'}"></i>
                    </button>
                `
            }
        ]
    });

    $('#trucksTable tbody').on('click', 'td.dt-control', function () {
        const tr = $(this).closest('tr');
        const row = trucksTable.row(tr);
        
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(formatTruckDetails(row.data())).show();
            tr.addClass('shown');
        }
    });
}

function formatTruckDetails(d) {
    if (!d) return '<div class="alert alert-warning">Dados do caminhão não disponíveis</div>';

    return `
        <div class="p-3 bg-light rounded">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Detalhes do Caminhão</h5>
                <button onclick="toggleTruckStatus(${d.id}, ${d.active})" class="btn btn-sm ${d.active ? 'btn-outline-danger' : 'btn-outline-success'}">
                    <i class="fas ${d.active ? 'fa-times-circle' : 'fa-check-circle'} me-1"></i>
                    ${d.active ? 'Desativar' : 'Ativar'}
                </button>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Placa:</strong> ${d.license_plate || 'Não informado'}</p>
                    <p><strong>Marca/Modelo:</strong> ${d.brand || ''} ${d.model || ''}</p>
                    <p><strong>Ano:</strong> ${d.manufacture_year || 'Não informado'}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tipo:</strong> ${d.vehicle_type || 'Não informado'}</p>
                    <p><strong>Status:</strong> ${d.active ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-secondary">Inativo</span>'}</p>
                </div>
            </div>
        </div>
    `;
}

function toggleTruckStatus(truckId, isActive) {
    const action = isActive ? 'deactivate' : 'activate';
    const actionText = isActive ? 'desativação' : 'ativação';
    
    if (!confirm(`Tem certeza que deseja ${actionText} este caminhão?`)) {
        return;
    }

    toastr.info(`Processando ${actionText}...`, 'Aguarde', {timeOut: 0});

    $.ajax({
        url: '/toggle-truck-status',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify({
            truck_id: truckId,
            action: action
        }),
        contentType: 'application/json',
        success: function(response) {
            toastr.clear();
            if (response.success) {
                toastr.success(`Caminhão ${action === 'activate' ? 'ativado' : 'desativado'} com sucesso!`);
                $('#trucksTable').DataTable().ajax.reload(null, false);
            } else {
                toastr.error(response.message || `Erro na ${actionText} do caminhão`);
            }
        },
        error: function(xhr) {
            toastr.clear();
            let errorMsg = `Erro na ${actionText} do caminhão`;
            try {
                const response = JSON.parse(xhr.responseText);
                errorMsg = response.message || errorMsg;
            } catch (e) {}
            toastr.error(errorMsg);
        }
    });
}

// Inicialização principal
$(document).ready(function() {
    // Configuração da tabela de motoristas
    const table = $('#drivers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('drivers.data') }}",
        order: [[4, 'desc']],
        columns: [
            { className: 'dt-control', orderable: false, data: null, defaultContent: '' },
            { 
                data: 'name',
                render: (data, type, row) => `
                    <div class="fw-semibold">${data}</div>
                    <div class="text-muted small">${row.address || 'Endereço não informado'}</div>
                `
            },
            { 
                data: 'phone',
                render: (data) => maskPhone(data)
            },
            { 
                data: 'cpf',
                render: (data) => data ? `CPF: ${maskCPF(data)}` : 'Não informado'
            },
            { 
                data: 'created_at',
                render: formatDateBR
            },
            {
                data: 'status',
                render: status => {
                    const [text, color] = getStatusLabel(status);
                    return `<span class="badge bg-${color}">${text}</span>`;
                }
            },
            {
                data: null,
                className: 'text-end',
                render: (data, type, row) => `
                    <div class="btn-group btn-group-sm" data-driver-id="${row.id}">
                        <button class="btn btn-success btn-sm btn-balance" title="Saldo">
                            <i class="fas fa-wallet"></i>
                        </button>
                        <button class="btn btn-primary btn-sm btn-freights" title="Fretes">
                            <i class="fas fa-truck"></i>
                        </button>
                        <button class="btn btn-dark btn-sm btn-trucks" title="Caminhões">
                            <i class="fas fa-truck-pickup"></i>
                        </button>
                        <button class="btn btn-sm ${row.status === 'active' ? 'btn-danger' : 'btn-warning'} btn-status" 
                            title="${row.status === 'active' ? 'Bloquear' : 'Ativar'}" data-status="${row.status}">
                            <i class="fas ${row.status === 'active' ? 'fa-lock' : 'fa-check'}"></i>
                        </button>
                        <button class="btn btn-info btn-sm btn-analyze" title="Analisar">
                            <i class="fas fa-search"></i>
                        </button>
                        <button class="btn btn-success btn-sm btn-whatsapp" title="WhatsApp" data-phone="${row.phone}">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-delete" title="Deletar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        }
    });

    // Expandir detalhes do motorista
    $('#drivers-table tbody').on('click', 'td.dt-control', function() {
        const tr = $(this).closest('tr');
        const row = table.row(tr);
        
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(formatDriverDetails(row.data())).show();
            tr.addClass('shown');
        }
    });

    // Event listeners
    $('#showDriversLocationBtn').click(showDriversLocation);
    $('#blockUserBtn').click(() => updateDriverStatus(selectedDriverId, 'block'));
    $('#blockTransferBtn').click(() => updateDriverStatus(selectedDriverId, 'transfer_block'));
    $('#submitTransfer').click(submitTransfer);

    // Delegation para botões dinâmicos
    $(document).on('click', '.btn-balance', function() {
        const driverId = $(this).closest('.btn-group').data('driver-id');
        showBalanceModal(driverId);
    });

    $(document).on('click', '.btn-freights', function() {
        const driverId = $(this).closest('.btn-group').data('driver-id');
        showFreightsModal(driverId);
    });

    $(document).on('click', '.btn-trucks', function() {
        const driverId = $(this).closest('.btn-group').data('driver-id');
        showTrucksModal(driverId);
    });

    $(document).on('click', '.btn-status', function() {
        const driverId = $(this).closest('.btn-group').data('driver-id');
        const status = $(this).data('status');
        activateDriver(driverId, status);
    });

    $(document).on('click', '.btn-whatsapp', function() {
        const phone = $(this).data('phone');
        openWhatsApp(phone);
    });

    $(document).on('click', '.btn-delete', function() {
        const driverId = $(this).closest('.btn-group').data('driver-id');
        deleteDriver(driverId);
    });
});

function formatDriverDetails(d) {
    let reason = '';
    if (d.status === 'block' || d.status === 'transfer_block') {
        reason = `<p><strong>Motivo:</strong> ${d.reason || 'Não informado'}</p>`;
    }

    return `
        <div class="p-3 bg-light rounded">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Endereço:</strong> ${d.address || 'Não informado'}</p>
                    <p><strong>Data de Nascimento:</strong> ${formatDateBR(d.birth_date)}</p>
                    <p><strong>CPF:</strong> ${maskCPF(d.cpf)}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>CNH:</strong> ${d.driver_license_number}</p>
                    <p><strong>Validade CNH:</strong> ${formatDateBR(d.driver_license_expiration)}</p>
                    <p><strong>Status:</strong> ${getStatusLabel(d.status)[0]}</p>
                </div>
            </div>
            ${reason}
        </div>
    `;
}
</script>
@endsection