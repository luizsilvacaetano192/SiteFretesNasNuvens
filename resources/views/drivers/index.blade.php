@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold mb-0">Gestão de Motoristas</h1>
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

<!-- Modal de Imagem Ampliada -->
<!-- Modal de Imagem Ampliada -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true" style="z-index: 1080;">
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
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Caminhões e Implementos -->
<div class="modal fade" id="trucksModal" tabindex="-1" aria-hidden="true">
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

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
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

<script>
// Funções utilitárias
function maskPhone(value) {
    if (!value) return '';
    return value.replace(/\D/g, '').replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
}

function maskRG(value) {
    if (!value) return '';
    return value.replace(/^(\d{1,2})(\d{3})(\d{3})([\dxX])?$/, (_, p1, p2, p3, p4) => `${p1}.${p2}.${p3}${p4 ? '-' + p4 : ''}`);
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

function openImageModal(src) {
    $('#modalImage').attr('src', src);
    new bootstrap.Modal('#imageModal').show();
}

function renderImageColumn(title, src) {
    if (!src) return `<div class="col-md-3 text-center mb-3"><p><strong>${title}</strong></p><div class="text-danger">Imagem não disponível</div></div>`;
    
    return `
        <div class="col-md-3 text-center mb-3">
            <p><strong>${title}</strong></p>
            <img src="${src}" class="img-fluid rounded driver-details-img" onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';"/>
            <br>
            <a href="${src}" download class="btn btn-sm btn-outline-primary mt-2"><i class="fas fa-download me-1"></i>Baixar</a>
            <button class="btn btn-sm btn-outline-secondary mt-2" onclick="openImageModal('${src}')"><i class="fas fa-search me-1"></i>Ampliar</button>
        </div>
    `;
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

let selectedDriverId = null;

function updateDriverStatus(id, status) {
    const reason = $('#blockReason').val().trim();

    if ((status === 'block' || status === 'transfer_block') && !reason) {
        toastr.warning('Por favor, informe o motivo do bloqueio.');
        return;
    }

    $.post(`/drivers/${id}/update-status`, {
        status,
        reason,
        _token: '{{ csrf_token() }}'
    }, () => {
        $('#drivers-table').DataTable().ajax.reload(null, false);
        bootstrap.Modal.getInstance(document.getElementById('blockModal'))?.hide();
        toastr.success(`Status atualizado para ${status}`);
        $('#blockReason').val('');
    }).fail(() => toastr.error("Erro ao atualizar status."));
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
                cpfCnpj: driverData.cpf.replace(/\D/g, '')
            };

            toastr.info('Criando conta Asaas para o motorista...', 'Aguarde', {timeOut: 0});

            $.ajax({
                url: '/api/create-asaas-account',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(apiData),
                success: function(response) {
                    toastr.clear();
                    if (response.success) {
                        toastr.success('Conta Asaas criada com sucesso! Ativando motorista...');
                        updateDriverStatus(id, 'active');
                    } else {
                        toastr.error('Não foi possível criar a conta Asaas: ' + (response.message || 'Erro desconhecido'));
                    }
                },
                error: function(xhr) {
                    toastr.clear();
                    let errorMsg = 'Erro ao conectar com o serviço de pagamentos';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMsg = response.message || errorMsg;
                    } catch (e) {}
                    toastr.error(errorMsg);
                }
            });
        }).fail(function() {
            toastr.error('Não foi possível obter os dados do motorista');
        });
    } else {
        updateDriverStatus(id, 'active');
    }
}

function analyzeDriver(driverId) {
    const modal = new bootstrap.Modal('#analyzeModal');
    $('#analysisContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Aguarde enquanto a inteligência artificial realiza a análise...</p>
        </div>
    `);
    modal.show();

    $.get(`/drivers/${driverId}/analyze`, result => {
        $('#analysisContent').html(`
            <div class="alert alert-info">
                <h5><i class="fas fa-robot me-2"></i>Resultado da Análise via IA:</h5>
                <p>${result.message.replace(/\n/g, "<br>")}</p>
            </div>
            <div class="row">
                ${renderImageColumn('Frente CNH', result.driver_license_front)}
                ${renderImageColumn('Comprovante de Endereço', result.address_proof)}
                ${renderImageColumn('Foto do Rosto', result.face_photo)}
            </div>
        `);
    }).fail(() => {
        $('#analysisContent').html(`<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Erro na análise com IA.</div>`);
    });
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
                    return data ? new Date(data).toLocaleDateString('pt-BR') : '';
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
        ],
        drawCallback: function(settings) {
            if (this.api().data().length === 0) {
                $(this.api().table().body()).html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="6" class="dataTables_empty">' + 
                    settings.oLanguage.sZeroRecords + 
                    '</td>' +
                    '</tr>'
                );
            }
        }
    });

    $('#selectAllFreights').click(function() {
        $('.freightCheckbox').prop('checked', this.checked);
    });

    $.get(`/drivers/${driverId}/freights`, function(data) {
        if (data.freights && data.freights.length > 0) {
            freightsTable.clear().rows.add(data.freights).draw();
        } else {
            freightsTable.clear().draw();
        }
    }).fail(function() {
        freightsTable.clear().draw();
        toastr.error('Erro ao carregar lista de fretes');
    });
}

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
    
    $.get(`/drivers/${driverId}/freights`, function(data) {
        if (data.freights && data.freights.length > 0) {
            table.clear().rows.add(data.freights).draw();
        } else {
            table.clear().draw();
        }

        $('#transferFreightsTable tbody').on('click', '.freightRadio', function() {
            const rowData = table.row($(this).closest('tr')).data();
            if (rowData) {
                $('#transferAmount').val(rowData.value ? rowData.value.toFixed(2) : '');
                $('#selectedFreightValue').val(rowData.value || '');
                $('.freightRadio').not(this).prop('checked', false);
            }
        });
        
    }).fail(function() {
        console.error('Erro ao carregar fretes');
        table.clear().draw();
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

    $.post(`/transfer/${driverId}`, requestData, function(response) {
        toastr.success('Transferência realizada com sucesso!');
        $('#transferModal').modal('hide');
        showBalanceModal(driverId);
    }).fail(function(error) {
        toastr.error(error.responseJSON?.message || 'Erro ao realizar transferência');
    }).always(function() {
        $('#submitTransfer').prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Enviar');
    });
}

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
    
    $.get(`/drivers/${driverId}/balance-data`, function(data) {
        $('#asaasIdentifier').text(data.account.asaas_identifier || 'Não informado');
        $('#totalBalance').text(formatCurrency(data.account.total_balance));
        $('#blockedBalance').text(formatCurrency(data.account.blocked_balance));
        $('#availableBalance').text(formatCurrency(data.account.available_balance));
        
        $('#balanceModal .modal-body').html(`
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-0 bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title text-muted">ID Conta Asaas</h6>
                            <p class="card-text h5" id="asaasIdentifier">${data.account.asaas_identifier || '-'}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-0 bg-success text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Saldo Total</h6>
                            <p class="card-text h4" id="totalBalance">${formatCurrency(data.account.total_balance)}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-0 bg-warning">
                        <div class="card-body text-center">
                            <h6 class="card-title">Saldo Bloqueado</h6>
                            <p class="card-text h4" id="blockedBalance">${formatCurrency(data.account.blocked_balance)}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-0 bg-info text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Saldo Disponível</h6>
                            <p class="card-text h4" id="availableBalance">${formatCurrency(data.account.available_balance)}</p>
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
        `);
        
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
                    render: date => new Date(date).toLocaleString('pt-BR') 
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
    }).fail(function() {
        $('#balanceModal .modal-body').html(`
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>Erro ao carregar informações financeiras. Tente novamente mais tarde.
            </div>
        `);
    });
}

function formatTruckDetails(d) {
    // Função para construir URL completa do S3
    function getS3Url(path) {
        if (!path) return null;
        // Se já for uma URL completa, retorna como está
        if (path.startsWith('http')) return path;
        // Se for um path do S3, constrói a URL completa
        return `https://${AWS_BUCKET}.s3.amazonaws.com/${path}`;
    }

    // Função auxiliar para renderizar a coluna de foto
    function renderPhotoColumn(photoUrl, title = '') {
        const fullUrl = getS3Url(photoUrl);
        if (!fullUrl) {
            return `
                <div class="text-center">
                    ${title ? `<p><strong>${title}</strong></p>` : ''}
                    <i class="fas fa-image text-muted" style="font-size: 24px;"></i>
                    <div class="text-danger small">Foto ausente</div>
                    <button class="btn btn-sm btn-outline-secondary mt-1" disabled>
                        <i class="fas fa-search me-1"></i>Ampliar
                    </button>
                    <button class="btn btn-sm btn-outline-primary mt-1" disabled>
                        <i class="fas fa-download me-1"></i>Baixar
                    </button>
                </div>
            `;
        }
        
        return `
            <div class="text-center">
                ${title ? `<p><strong>${title}</strong></p>` : ''}
                <img src="${fullUrl}" class="img-fluid rounded" 
                     style="max-height: 80px; object-fit: contain; border: 1px solid #dee2e6; cursor: pointer;" 
                     onclick="openImageModal('${fullUrl}')">
                <br>
                <button class="btn btn-sm btn-outline-secondary mt-1" onclick="openImageModal('${fullUrl}')">
                    <i class="fas fa-search me-1"></i>Ampliar
                </button>
                <a href="${fullUrl}" download class="btn btn-sm btn-outline-primary mt-1">
                    <i class="fas fa-download me-1"></i>Baixar
                </a>
            </div>
        `;
    }

    // Fotos do caminhão
    let truckPhotosHtml = '';
    if (d.photos) {
        truckPhotosHtml = `
        <div class="row mt-3">
            <div class="col-md-12">
                <h6 class="fw-bold">Fotos do Caminhão</h6>
            </div>
            <div class="col-md-4 mb-3">
                ${renderPhotoColumn(d.photos.front, 'Frente')}
            </div>
            <div class="col-md-4 mb-3">
                ${renderPhotoColumn(d.photos.rear, 'Traseira')}
            </div>
            <div class="col-md-4 mb-3">
                ${renderPhotoColumn(d.photos.left_side, 'Lateral Esquerda')}
            </div>
            <div class="col-md-4 mb-3">
                ${renderPhotoColumn(d.photos.right_side, 'Lateral Direita')}
            </div>
            <div class="col-md-4 mb-3">
                ${renderPhotoColumn(d.photos.documents?.crv, 'CRV')}
            </div>
            <div class="col-md-4 mb-3">
                ${renderPhotoColumn(d.photos.documents?.crlv, 'CRLV')}
            </div>
        </div>`;
    }

    // Implementos
    let implementsHtml = '';
    if (d.implements && d.implements.length > 0) {
        implementsHtml = `
        <div class="mt-3">
            <h6 class="fw-bold">Implementos</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Marca/Modelo</th>
                            <th>Placa</th>
                            <th>Ano</th>
                            <th>Capacidade</th>
                            <th width="180">Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${d.implements.map(imp => `
                            <tr>
                                <td>${imp.type}</td>
                                <td>${imp.brand} ${imp.model}</td>
                                <td>${imp.license_plate}</td>
                                <td>${imp.manufacture_year}</td>
                                <td>${imp.capacity}</td>
                                <td>${renderPhotoColumn(imp.photo)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>`;
    }

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
                    <p><strong>Placa:</strong> ${d.license_plate}</p>
                    <p><strong>Marca/Modelo:</strong> ${d.brand} ${d.model}</p>
                    <p><strong>Ano:</strong> ${d.manufacture_year}</p>
                    <p><strong>Chassi:</strong> ${d.chassis_number}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tipo:</strong> ${d.vehicle_type}</p>
                    <p><strong>Capacidade:</strong> ${d.load_capacity}</p>
                    <p><strong>Eixos:</strong> ${d.axles_number}</p>
                    <p><strong>Status:</strong> ${d.active ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-secondary">Inativo</span>'}</p>
                </div>
            </div>
            ${truckPhotosHtml}
            ${implementsHtml}
        </div>
    `;
}

// Adicione estas variáveis no topo do seu arquivo JavaScript
// Substitua com suas configurações reais do AWS S3
const AWS_BUCKET = 'fretes';
const AWS_REGION = ''; // Ex: 'us-east-1'

function toggleTruckStatus(truckId, isActive) {
    const action = isActive ? 'deactivate' : 'activate';
    const actionText = isActive ? 'desativação' : 'ativação';
    
    if (!confirm(`Tem certeza que deseja ${actionText} este caminhão?`)) {
        return;
    }

    toastr.info(`Processando ${actionText}...`, 'Aguarde', {timeOut: 0});

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/toggle-truck-status', // Seu endpoint proxy
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            truck_id: truckId,
            action: action
        }),
        success: function(response) {
            toastr.clear();
            if (response.success) {
                toastr.success(`Caminhão ${action === 'activate' ? 'ativado' : 'desativado'} com sucesso!`);
                
                // Recarrega os dados da tabela
                const trucksTable = $('#trucksTable').DataTable();
                trucksTable.ajax.reload(null, false);
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
                    // Parse o body que é uma string JSON
                    const parsedBody = JSON.parse(response.body);
                    return parsedBody.success ? parsedBody.trucks : [];
                } catch (e) {
                    console.error('Erro ao processar resposta:', e);
                    toastr.error('Erro ao carregar dados dos caminhões');
                    return [];
                }
            }
        },
        columns: [
            { 
                className: 'dt-control',
                orderable: false,
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
                render: (data) => data || 'Não informado'
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
        ],
        drawCallback: function(settings) {
            if (this.api().data().length === 0) {
                $(this.api().table().body()).html(
                    '<tr class="odd">' +
                    '<td valign="top" colspan="6" class="dataTables_empty">' + 
                    settings.oLanguage.sZeroRecords + 
                    '</td>' +
                    '</tr>'
                );
            }
        }
    });

    // Mostrar/ocultar detalhes ao clicar no ícone
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

function format(d) {
    let reason = '';
    if (d.status === 'block' || d.status === 'transfer_block') {
        reason = `<p><strong>Motivo:</strong> ${d.reason || 'Não informado'}</p>`;
    }

    return `
        <div class="p-3 bg-light rounded">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Data de Nascimento:</strong> ${formatDateBR(d.birth_date)}</p>
                    <p><strong>Estado Civil:</strong> ${d.marital_status}</p>
                    <p><strong>CPF:</strong> ${maskCPF(d.cpf)}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>CNH:</strong> ${d.driver_license_number}</p>
                    <p><strong>Categoria CNH:</strong> ${d.driver_license_category}</p>
                    <p><strong>Validade CNH:</strong> ${formatDateBR(d.driver_license_expiration)}</p>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <p><strong>Status:</strong> ${getStatusLabel(d.status)[0]}</p>
                    <p><strong>Seguradora de Carga:</strong> ${d.insurance_company || 'Não informada'}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Senha:</strong> 
                        <span id="password-${d.id}" class="password-hidden">••••••••</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="togglePassword('${d.id}', '${d.password}')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </p>
                </div>
            </div>
            ${reason}
            <div class="row mt-3">
                ${renderImageColumn('Frente CNH', d.driver_license_front)}
                ${renderImageColumn('Verso CNH', d.driver_license_back)}
                ${renderImageColumn('Foto do Rosto', d.face_photo)}
                ${renderImageColumn('Comprovante de Endereço', d.address_proof)}
            </div>
        </div>
    `;
}

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
                render: (data, type, row) => `
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
                        <button onclick="showTrucksModal(${row.id})" class="btn btn-outline-dark btn-sm" title="Caminhões">
                            <i class="fas fa-truck-pickup"></i>
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
            $('[title]').tooltip({
                placement: 'top',
                trigger: 'hover'
            });
        }
    });

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