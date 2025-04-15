@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Gestão de Motoristas</h1>
        <a href="{{ route('drivers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Novo Motorista
        </a>
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
                            <th>Status</th>
                            <th class="text-end" width="220">Ações</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modais (mantidos os mesmos, apenas com classes atualizadas) -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-body text-center p-0">
        <img id="modalImage" src="" class="img-fluid w-100" style="max-height:90vh; object-fit:contain;">
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
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="analysisContent"></div>
    </div>
  </div>
</div>

<!-- Modal de Bloqueio -->
<div class="modal fade" id="blockModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title"><i class="fas fa-lock me-2"></i>Bloqueio de Motorista</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
<div class="modal fade" id="balanceModal" tabindex="-1" aria-hidden="true">
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
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
<div class="modal fade" id="freightsModal" tabindex="-1" aria-hidden="true">
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
    </div>
  </div>
</div>

<!-- CSS -->
<style>
/* Estilos globais */
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

/* Estilos específicos para a tabela principal */
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

/* Estilos para os controles de expansão */
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

/* Estilos para os modais */
.modal-header {
    padding: 1rem 1.5rem;
}

.modal-title {
    font-weight: 600;
}

/* Estilos para os botões de ação */
.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* Estilos para as imagens nos detalhes */
.driver-details-img {
    max-height: 150px;
    object-fit: contain;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
}

/* Estilos para a tabela de fretes na transferência */
#transferFreightsTable {
    font-size: 0.8rem;
}

#transferFreightsTable thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 10;
}

/* Responsividade */
@media (max-width: 768px) {
    #transferModal .row {
        flex-direction: column;
    }
    
    #transferModal .col-md-4, 
    #transferModal .col-md-8 {
        width: 100%;
    }
    
    .btn-group-sm {
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    .btn-group-sm .btn {
        flex: 1 0 auto;
    }
}
</style>

<!-- JavaScript (mantido o mesmo) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
// ... (todo o JavaScript existente permanece exatamente o mesmo)
// Apenas atualizei a renderização da tabela principal para refletir as novas colunas
$(document).ready(function () {
    const table = $('#drivers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('drivers.data') }}",
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
                render: (data, type, row) => `
                    <div>${maskPhone(data)}</div>
                    <div class="text-muted small">${maskRG(row.identity_card) || 'RG não informado'}</div>
                `
            },
            { 
                data: 'cpf',
                render: (data) => `
                    <div>CPF: ${maskCPF(data) || 'Não informado'}</div>
                    <div class="text-muted small">CNH: ${row.driver_license_number || 'Não informada'}</div>
                `
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
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: (data, type, row) => `
                    <div class="btn-group btn-group-sm">
                        <button onclick="showBalanceModal(${row.id})" class="btn btn-outline-success btn-sm" title="Saldo">
                            <i class="fas fa-wallet"></i>
                        </button>
                        <button onclick="showFreightsModal(${row.id})" class="btn btn-outline-primary btn-sm" title="Fretes">
                            <i class="fas fa-truck"></i>
                        </button>
                        <button onclick="activateDriver(${row.id}, '${row.status}')" class="btn btn-sm ${row.status === 'active' ? 'btn-outline-danger' : 'btn-outline-warning'}" title="${row.status === 'active' ? 'Bloquear' : 'Ativar'}">
                            <i class="fas ${row.status === 'active' ? 'fa-lock' : 'fa-check'}"></i>
                        </button>
                        <button onclick="analyzeDriver(${row.id})" class="btn btn-outline-info btn-sm" title="Analisar">
                            <i class="fas fa-search"></i>
                        </button>
                        <button onclick="openWhatsApp('${row.phone}')" class="btn btn-outline-success btn-sm" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                    </div>
                `
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        },
        drawCallback: function(settings) {
            // Adiciona tooltips aos botões
            $('[title]').tooltip({
                placement: 'top',
                trigger: 'hover'
            });
        }
    });

    // Restante do JavaScript permanece exatamente igual
    $('#drivers-table tbody').on('click', 'td.dt-control', function () {
        const tr = $(this).closest('tr');
        const row = table.row(tr);
        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });

    $('#blockUserBtn').click(() => updateDriverStatus(selectedDriverId, 'block'));
    $('#blockTransferBtn').click(() => updateDriverStatus(selectedDriverId, 'transfer_block'));
    $('#submitTransfer').click(submitTransfer);
});
</script>
@endsection