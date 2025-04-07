@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">🚚 Lista de Motoristas</h2>
    <table class="table table-bordered" id="drivers-table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal de Análise -->
<div class="modal fade" id="analyzeModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">🔎 Análise do Motorista</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row" id="analyzeContent">
        <!-- Conteúdo será preenchido via JS -->
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
        <p>Escolha o tipo de bloqueio e informe o motivo:</p>
        <div class="mb-3">
          <label for="blockReason" class="form-label">Motivo do Bloqueio</label>
          <textarea id="blockReason" class="form-control" rows="3" placeholder="Descreva o motivo do bloqueio..."></textarea>
        </div>
        <div class="d-grid gap-2">
          <button class="btn btn-danger" id="blockUserBtn">🚫 Bloquear Usuário</button>
          <button class="btn btn-warning" id="blockTransferBtn">📵 Bloquear Transferências</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
let selectedDriverId = null;

$(document).ready(function () {
    const table = $('#drivers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("drivers.data") }}',
        columns: [
            { data: 'name' },
            { data: 'cpf' },
            { data: 'status' },
            {
                data: 'id',
                render: function (id, type, row) {
                    let statusBtn = '';

                    if (row.status === 'active') {
                        statusBtn = `<button class="btn btn-sm btn-warning" onclick="openBlockModal(${id})">Bloquear</button>`;
                    } else {
                        statusBtn = `<button class="btn btn-sm btn-success" onclick="updateDriverStatus(${id}, 'active')">Ativar</button>`;
                    }

                    return `
                        ${statusBtn}
                        <button class="btn btn-sm btn-info" onclick="analyzeDriver(${id})">Analisar</button>
                    `;
                }
            }
        ]
    });

    $('#blockUserBtn').click(() => updateDriverStatus(selectedDriverId, 'block'));
    $('#blockTransferBtn').click(() => updateDriverStatus(selectedDriverId, 'transfer_block'));
});

function openBlockModal(id) {
    selectedDriverId = id;
    $('#blockReason').val('');
    new bootstrap.Modal(document.getElementById('blockModal')).show();
}

function updateDriverStatus(id, status) {
    const reason = $('#blockReason').val()?.trim();

    if ((status === 'block' || status === 'transfer_block') && !reason) {
        toastr.warning("Informe o motivo do bloqueio.");
        return;
    }

    $.post(`/drivers/${id}/update-status`, {
        status,
        reason,
        _token: '{{ csrf_token() }}'
    }, () => {
        $('#drivers-table').DataTable().ajax.reload(null, false);
        $('#blockReason').val('');
        bootstrap.Modal.getInstance(document.getElementById('blockModal'))?.hide();
        toastr.success(`Status atualizado para ${status}`);
    }).fail(() => toastr.error("Erro ao atualizar status."));
}

function analyzeDriver(id) {
    $.get(`/drivers/${id}/analyze`, function (data) {
        let html = `
            <div class="col-md-6">
                <h5>📄 Documento (Frente)</h5>
                <img src="${data.document_front}" class="img-fluid mb-3" />
                <h5>📄 Documento (Verso)</h5>
                <img src="${data.document_back}" class="img-fluid mb-3" />
                <h5>📬 Comprovante de Endereço</h5>
                <img src="${data.address_proof}" class="img-fluid mb-3" />
            </div>
            <div class="col-md-6">
                <h5>🧑 Foto do Rosto</h5>
                <img src="${data.face_photo}" class="img-fluid mb-3" />
                <h5>📋 Dados</h5>
                <ul class="list-group">
                    <li class="list-group-item"><strong>Nome:</strong> ${data.name}</li>
                    <li class="list-group-item"><strong>CPF:</strong> ${data.cpf}</li>
                    <li class="list-group-item"><strong>CNH:</strong> ${data.cnh}</li>
                    <li class="list-group-item"><strong>Tipo CNH:</strong> ${data.cnh_type}</li>
                </ul>
                <h5 class="mt-3">🧠 Resultado IA</h5>
                <div class="alert alert-info">
                    ${data.analysis_result}
                </div>
            </div>
        `;
        $('#analyzeContent').html(html);
        new bootstrap.Modal(document.getElementById('analyzeModal')).show();
    }).fail(() => toastr.error("Erro ao carregar dados de análise."));
}
</script>
@endsection
