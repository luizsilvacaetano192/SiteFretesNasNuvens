@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista de Motoristas</h2>
        <a href="{{ route('drivers.create') }}" class="btn btn-success">➕ Adicionar Motorista</a>
    </div>

    <table id="drivers-table" class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>RG</th>
                <th>Telefone</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal de Imagem Ampliada -->
<div class="modal fade" id="imageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-dark">
      <div class="modal-body text-center p-0">
        <img id="modalImage" src="" class="img-fluid w-100" style="max-height:90vh; object-fit:contain;">
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Análise por IA -->
<div class="modal fade" id="analyzeModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">🕵️ Análise de Motorista com IA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
      <div class="modal-header">
        <h5 class="modal-title">🔒 Bloqueio de Motorista</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-center">Escolha o tipo de bloqueio e informe o motivo.</p>

        <div class="mb-3">
          <label for="blockReason" class="form-label">📝 Motivo do Bloqueio</label>
          <textarea class="form-control" id="blockReason" rows="3" placeholder="Descreva o motivo do bloqueio..."></textarea>
        </div>

        <div class="d-grid gap-2">
          <button class="btn btn-danger" id="blockUserBtn">🚫 Bloquear Usuário</button>
          <button class="btn btn-warning" id="blockTransferBtn">📵 Bloquear Transferências</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Saldo e Transferências -->
<div class="modal fade" id="balanceModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">💰 Saldo e Transferências</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-4">
          <div class="col-md-3">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <h6 class="card-title">ID Conta Asaas</h6>
                <p class="card-text" id="asaasIdentifier">-</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-success text-white">
              <div class="card-body">
                <h6 class="card-title">Saldo Total</h6>
                <p class="card-text" id="totalBalance">R$ 0,00</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-warning text-dark">
              <div class="card-body">
                <h6 class="card-title">Saldo Bloqueado</h6>
                <p class="card-text" id="blockedBalance">R$ 0,00</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-info text-white">
              <div class="card-body">
                <h6 class="card-title">Saldo Disponível</h6>
                <p class="card-text" id="availableBalance">R$ 0,00</p>
              </div>
            </div>
          </div>
        </div>
        
        <h5 class="mb-3">Histórico de Transferências</h5>
        <table id="transfersTable" class="table table-striped" style="width:100%">
          <thead>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Transferência -->
<div class="modal fade" id="transferModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">💸 Realizar Transferência</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4"> <!-- Reduzido para 4 colunas -->
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
          <div class="col-md-8"> <!-- Expandido para 8 colunas -->
            <h6 class="mb-3">Fretes disponíveis (opcional)</h6>
            <div class="table-responsive" style="min-height: 150px;">
              <table id="transferFreightsTable" class="table table-sm table-striped" style="width:100%">
                <thead>
                  <tr>
                    <th width="3%"></th>
                    <th width="8%">ID</th>
                    <th width="20%">Empresa</th>
                    <th width="20%">Tipo de Carga</th>
                    <th width="15%">Valor</th>
                    <th width="15%">Data</th>
                    <th width="19%">Status</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="submitTransfer">Enviar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Fretes do Motorista -->
<div class="modal fade" id="freightsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">🚚 Fretes do Motorista</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table id="freightsTable" class="table table-striped" style="width:100%">
          <thead>
            <tr>
              <th width="5%">
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Estilos e Scripts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
/* Estilos para o modal de transferência */
#transferModal .modal-dialog {
    max-width: 1300px;
    width: 95%;
}

#transferFreightsTable {
    font-size: 0.85rem;
    table-layout: fixed;
}

#transferFreightsTable th, 
#transferFreightsTable td {
    padding: 0.5rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

#transferFreightsTable thead th {
    position: sticky;
    top: 0;
    background-color: #f8f9fa;
    z-index: 10;
}

#transferModal .modal-body {
    max-height: 70vh;
    overflow-y: auto;
    padding: 20px;
}

.dataTables_scrollBody {
    overflow-x: auto !important;
}

/* Melhorias na responsividade */
@media (max-width: 1400px) {
    #transferModal .modal-dialog {
        max-width: 95%;
    }
}

@media (max-width: 768px) {
    #transferModal .row {
        flex-direction: column;
    }
    #transferModal .col-md-4, 
    #transferModal .col-md-8 {
        width: 100%;
        max-width: 100%;
    }
}    
td.dt-control::before {
    content: "+";
    font-weight: bold;
    font-size: 18px;
    color: #198754;
    display: inline-block;
    text-align: center;
    width: 20px;
    cursor: pointer;
}
tr.shown td.dt-control::before {
    content: "−";
    color: #dc3545;
}
.password-hidden {
    font-family: 'monospace';
    letter-spacing: 2px;
}
.card-title {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}
.card-text {
    font-size: 1.1rem;
    font-weight: bold;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
#newTransferBtn {
    margin-right: 10px;
}
#freightsTable th:first-child, 
#freightsTable td:first-child {
    text-align: center;
    vertical-align: middle;
}
.freightCheckbox, .freightRadio {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
#selectAllFreights {
    width: 18px;
    height: 18px;
    cursor: pointer;
}
#transferFreightsTable {
    min-height: 100px;
    width: 100%;
    font-size: 0.9rem;
}
#transferFreightsTable th, 
#transferFreightsTable td {
    padding: 0.3rem 0.5rem;
}
.dataTables_empty {
    padding: 20px !important;
}
#transferModal .modal-body {
    max-height: 70vh;
    overflow-y: auto;
}
</style>

<script>
let selectedDriverId = null;

function maskRG(value) {
    if (!value) return '';
    return value.replace(/^(\d{1,2})(\d{3})(\d{3})([\dxX])?$/, (_, p1, p2, p3, p4) => `${p1}.${p2}.${p3}${p4 ? '-' + p4 : ''}`);
}

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

function openImageModal(src) {
    $('#modalImage').attr('src', src);
    new bootstrap.Modal('#imageModal').show();
}

function renderImageColumn(title, src) {
    return `
        <div class="col-md-3 text-center mb-3">
            <p><strong>${title}</strong></p>
            <img src="${src}" class="img-fluid rounded" style="max-height:150px;" onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';"/>
            <br>
            <a href="${src}" download class="btn btn-sm btn-outline-primary mt-2">⬇ Baixar</a>
            <button class="btn btn-sm btn-outline-secondary mt-2" onclick="openImageModal('${src}')">🔍 Ampliar</button>
        </div>
    `;
}

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
    } else {
        updateDriverStatus(id, 'active');
    }
}

function analyzeDriver(driverId) {
    const modal = new bootstrap.Modal('#analyzeModal');
    $('#analysisContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Aguarde enquanto a inteligência artificial realiza a análise...</p>
        </div>
    `);
    modal.show();

    $.get(`/drivers/${driverId}/analyze`, result => {
        $('#analysisContent').html(`
            <div class="alert alert-info">
                <h5>🧠 Resultado da Análise via IA:</h5>
                <p>${result.message.replace(/\n/g, "<br>")}</p>
            </div>
            <div class="row">
                ${renderImageColumn('Frente CNH', result.driver_license_front)}
                ${renderImageColumn('Comprovante de Endereço', result.address_proof)}
                ${renderImageColumn('Foto do Rosto', result.face_photo)}
            </div>
        `);
    }).fail(() => {
        $('#analysisContent').html(`<div class="alert alert-danger">❌ Erro na análise com IA.</div>`);
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

function getStatusLabel(status) {
    const labels = {
        'create': ['Aguardando Ativação', 'warning'],
        'active': ['Ativo', 'success'],
        'block': ['Bloqueado', 'danger'],
        'transfer_block': ['Transferências Bloqueadas', 'danger'],
    };
    return labels[status] || ['Desconhecido', 'secondary'];
}

function openWhatsApp(phone) {
    if (!phone) return alert("Número de telefone não disponível.");
    const formatted = phone.replace(/\D/g, '');
    window.open(`https://wa.me/55${formatted}`, '_blank');
}

function showFreightsModal(driverId) {
    const modal = new bootstrap.Modal('#freightsModal');
    
    // Limpa a tabela antes de recarregar
    if ($.fn.DataTable.isDataTable('#freightsTable')) {
        $('#freightsTable').DataTable().destroy();
        $('#freightsTable tbody').empty();
    }
    
    // Mostra o modal imediatamente
    modal.show();
    
    // Inicializa a tabela com dados vazios primeiro
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
        // Força a exibição da mensagem de "Nenhum dado disponível" quando a tabela estiver vazia
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

    // Configura o checkbox "Selecionar todos"
    $('#selectAllFreights').click(function() {
        $('.freightCheckbox').prop('checked', this.checked);
    });

    // Carrega os dados via AJAX
    $.get(`/drivers/${driverId}/freights`, function(data) {
        if (data.freights && data.freights.length > 0) {
            freightsTable.clear().rows.add(data.freights).draw();
        } else {
            freightsTable.clear().draw(); // Isso mostrará a mensagem de zeroRecords
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

    $.post(`/drivers/${driverId}/transfer`, requestData, function(response) {
        toastr.success('Transferência realizada com sucesso!');
        $('#transferModal').modal('hide');
        showBalanceModal(driverId);
    }).fail(function(error) {
        toastr.error(error.responseJSON?.message || 'Erro ao realizar transferência');
    }).always(function() {
        $('#submitTransfer').prop('disabled', false).html('Enviar');
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
    
    $('#balanceModal .modal-header').html(`
        <h5 class="modal-title">💰 Saldo e Transferências</h5>
        <button type="button" class="btn btn-success" id="newTransferBtn">
            ➕ Nova Transferência
        </button>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    `);
    
    modal.show();
    
    $.get(`/drivers/${driverId}/balance-data`, function(data) {
        $('#asaasIdentifier').text(data.account.asaas_identifier || 'Não informado');
        $('#totalBalance').text(formatCurrency(data.account.total_balance));
        $('#blockedBalance').text(formatCurrency(data.account.blocked_balance));
        $('#availableBalance').text(formatCurrency(data.account.available_balance));
        
        $('#balanceModal .modal-body').html(`
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">ID Conta Asaas</h6>
                            <p class="card-text" id="asaasIdentifier">${data.account.asaas_identifier || '-'}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Saldo Total</h6>
                            <p class="card-text" id="totalBalance">${formatCurrency(data.account.total_balance)}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h6 class="card-title">Saldo Bloqueado</h6>
                            <p class="card-text" id="blockedBalance">${formatCurrency(data.account.blocked_balance)}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Saldo Disponível</h6>
                            <p class="card-text" id="availableBalance">${formatCurrency(data.account.available_balance)}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <h5 class="mb-3">Histórico de Transferências</h5>
            <table id="transfersTable" class="table table-striped" style="width:100%">
                <thead>
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
                Erro ao carregar informações financeiras. Tente novamente mais tarde.
            </div>
        `);
    });
}

function format(d) {
    let reason = '';
    if (d.status === 'block' || d.status === 'transfer_block') {
        reason = `<p><strong>Motivo:</strong> ${d.reason || 'Não informado'}</p>`;
    }

    return `
        <div class="p-3 bg-light rounded">
            <p><strong>Data de Nascimento:</strong> ${formatDateBR(d.birth_date)}</p>
            <p><strong>Estado Civil:</strong> ${d.marital_status}</p>
            <p><strong>CPF:</strong> ${maskCPF(d.cpf)}</p>
            <p><strong>CNH:</strong> ${d.driver_license_number}</p>
            <p><strong>Categoria CNH:</strong> ${d.driver_license_category}</p>
            <p><strong>Validade CNH:</strong> ${formatDateBR(d.driver_license_expiration)}</p>
            <p><strong>Status:</strong> ${getStatusLabel(d.status)[0]}</p>
            <p><strong>Senha:</strong> 
                <span id="password-${d.id}" class="password-hidden">••••••••</span>
                <button class="btn btn-sm btn-outline-secondary" onclick="togglePassword('${d.id}', '${d.password}')">👁️</button>
            </p>
            ${reason}
            <div class="row">
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
            { data: 'name' },
            { data: 'address' },
            { data: 'identity_card', render: maskRG },
            { data: 'phone', render: maskPhone },
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
                render: (data, type, row) => `
                    <div class="btn-group btn-group-sm">
                        <button onclick="showBalanceModal(${row.id})" class="btn btn-outline-success">💰 Saldo</button>
                        <button onclick="showFreightsModal(${row.id})" class="btn btn-outline-primary">🚚 Fretes</button>
                        <button onclick="activateDriver(${row.id}, '${row.status}')" class="btn btn-outline-${row.status === 'active' ? 'danger' : 'warning'}">
                            ${row.status === 'active' ? '🚫 Bloquear' : '✅ Ativar'}
                        </button>
                        <button class="btn btn-sm btn-info" onclick="analyzeDriver(${row.id})">🔍 Analisar</button>
                        <button class="btn btn-sm btn-success" onclick="openWhatsApp('${row.phone}')">💬 WhatsApp</button>
                    </div>
                `
            }
        ]
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