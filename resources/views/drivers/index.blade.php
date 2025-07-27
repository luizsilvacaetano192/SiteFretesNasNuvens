@extends('layouts.app')

@section('title', 'Gestão de Motoristas')

@section('styles')
<style>
    /* Estilos consolidados e organizados */
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
    
    /* Estilo para o mapa */
    #driversMap {
        min-height: 600px;
        width: 100%;
    }

    /* Estilo para os marcadores personalizados */
    .custom-marker {
        background-color: #4e73df;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 5px rgba(0,0,0,0.3);
    }

    /* Estilo para o popup do mapa */
    .map-popup {
        min-width: 200px;
    }

    .map-popup h6 {
        font-size: 1rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .map-popup p {
        margin-bottom: 0.3rem;
        font-size: 0.85rem;
    }

    /* Estilos específicos para modais */
    .modal-driver-map {
        height: 80vh;
        min-height: 500px;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .driver-actions {
            flex-wrap: wrap;
            gap: 0.25rem;
        }

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

    #drivers-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    #drivers-table tbody tr {
        cursor: pointer;
        transition: all 0.2s;
    }

    #drivers-table tbody tr:hover {
        background-color: #f8f9fa;
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

    .modal-header {
        padding: 1rem 1.5rem;
    }

    .modal-title {
        font-weight: 600;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .driver-details-img {
        max-height: 150px;
        object-fit: contain;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    #transferFreightsTable {
        font-size: 0.8rem;
    }

    #transferFreightsTable thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 10;
    }
</style>
@endsection

@section('content')
<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

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

<!-- Modal de Imagem Ampliada -->
<div class="modal fade" id="imageModal" tabindex="-1" style="z-index: 1080;">
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

<!-- Modal de Análise por IA -->
<div class="modal fade" id="analyzeModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-robot me-2"></i>Análise de Motorista com IA</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="analysisContent"></div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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

<!-- Modal de Saldo e Transferências -->
<div class="modal fade" id="balanceModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
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
  <div class="modal-dialog modal-xl modal-dialog-centered">
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

<!-- Modal de Fretes do Motorista -->
<div class="modal fade" id="freightsModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
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

<!-- Modal de Caminhões e Implementos -->
<div class="modal fade" id="trucksModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
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

<!-- Modal de Localização dos Motoristas -->
<div class="modal fade" id="driversLocationModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-map-marked-alt me-2"></i>Localização dos Motoristas</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div id="mapLoading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Carregando...</span>
          </div>
          <p>Carregando mapa...</p>
        </div>
        <div id="driversMap" style="height: 600px; width: 100%; display: none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

<script>
$(document).ready(function() {
    // Configuração básica do Toastr
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": 5000
    };

    // 1. Primeiro inicialize a tabela
    try {
        const table = $('#drivers-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('drivers.data') }}",
                type: "GET",
                error: function(xhr) {
                    toastr.error('Erro ao carregar dados da tabela');
                    console.error('Erro na tabela:', xhr.responseText);
                }
            },
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
                    render: (data) => data ? data.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3') : ''
                },
                { 
                    data: 'cpf',
                    render: (data) => data ? data.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4') : ''
                },
                { 
                    data: 'created_at',
                    render: (data) => data ? new Date(data).toLocaleDateString('pt-BR') : ''
                },
                {
                    data: 'status',
                    render: (data) => {
                        const statusMap = {
                            'active': ['Ativo', 'success'],
                            'block': ['Bloqueado', 'danger'],
                            'create': ['Aguardando', 'warning']
                        };
                        const [text, type] = statusMap[data] || ['Desconhecido', 'secondary'];
                        return `<span class="badge bg-${type}">${text}</span>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: (data, type, row) => `
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-success btn-balance" data-id="${row.id}">
                                <i class="fas fa-wallet"></i>
                            </button>
                            <button class="btn btn-primary btn-freights" data-id="${row.id}">
                                <i class="fas fa-truck"></i>
                            </button>
                            <button class="btn btn-dark btn-trucks" data-id="${row.id}">
                                <i class="fas fa-truck-pickup"></i>
                            </button>
                        </div>
                    `
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            }
        });

        console.log('DataTable inicializado com sucesso');
    } catch (error) {
        console.error('Erro ao inicializar DataTable:', error);
        toastr.error('Erro ao carregar tabela de motoristas');
    }

    // 2. Depois inicialize o mapa (função simplificada)
    function initMap() {
        try {
            // Verifica se o elemento do mapa existe
            if (!document.getElementById('driversMap')) {
                console.error('Elemento do mapa não encontrado');
                return;
            }

            // Cria o mapa básico
            const map = L.map('driversMap').setView([-15.7889, -47.8799], 5);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            console.log('Mapa inicializado com sucesso');
            
            // Adiciona um marcador de teste
            L.marker([-15.7889, -47.8799]).addTo(map)
                .bindPopup('Localização de teste')
                .openPopup();

            return map;
        } catch (error) {
            console.error('Erro ao inicializar mapa:', error);
            toastr.error('Erro ao carregar o mapa');
            return null;
        }
    }

    // 3. Evento para abrir o modal do mapa
    $('#showDriversLocationBtn').click(function() {
        const modal = new bootstrap.Modal('#driversLocationModal');
        modal.show();
        
        // Pequeno delay para garantir que o modal está visível
        setTimeout(() => {
            const map = initMap();
            if (map) {
                // Aqui você pode adicionar a lógica para carregar as localizações reais
                console.log('Mapa pronto para carregar dados');
            }
        }, 300);
    });

    // 4. Eventos básicos para os botões
    $(document).on('click', '.btn-balance', function() {
        toastr.info('Visualizar saldo do motorista: ' + $(this).data('id'));
    });

    $(document).on('click', '.btn-freights', function() {
        toastr.info('Visualizar fretes do motorista: ' + $(this).data('id'));
    });

    $(document).on('click', '.btn-trucks', function() {
        toastr.info('Visualizar caminhões do motorista: ' + $(this).data('id'));
    });

    console.log('Aplicação inicializada com sucesso');
});
</script>
@endsection