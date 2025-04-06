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
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
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
<div class="modal fade" id="analyzeModal" tabindex="-1" aria-labelledby="analyzeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">🕵️ Análise de Motorista com IA</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" id="analysisContent"></div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal WhatsApp Web -->
<div class="modal fade" id="whatsAppModal" tabindex="-1" aria-labelledby="whatsAppModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 1000px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">💬 Conversar no WhatsApp</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body" style="height: 80vh;">
        <iframe id="whatsAppIframe" src="" frameborder="0" style="width:100%; height:100%;" allow="camera; microphone"></iframe>
      </div>
    </div>
  </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<style>
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
.status-label {
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 8px;
    display: inline-block;
}
.status-pending { background-color: #ffc107; color: #000; }
.status-blocked, .status-transfer-blocked { background-color: #dc3545; color: #fff; }
.status-active { background-color: #28a745; color: #fff; }
</style>

<script>
function formatDateBR(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('pt-BR');
}

function maskCPF(cpf) {
    return cpf?.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4") || '';
}

function maskRG(rg) {
    return rg?.replace(/^(\d{2})(\d{3})(\d{3})(\d{1})$/, "$1.$2.$3-$4") || '';
}

function maskPhone(phone) {
    return phone?.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3") || '';
}

function getStatusLabel(status) {
    switch (status) {
        case null:
        case '':
        case 'create': return '<span class="status-label status-pending">Aguardando ativação</span>';
        case 'active': return '<span class="status-label status-active">Ativo</span>';
        case 'block': return '<span class="status-label status-blocked">Bloqueado</span>';
        case 'transfer_block': return '<span class="status-label status-transfer-blocked">Transferências Bloqueadas</span>';
        default: return status;
    }
}

function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function openWhatsAppModal(phone) {
    if (!phone) return alert("Telefone não disponível.");
    const cleaned = phone.replace(/\D/g, '');
    const full = `55${cleaned.slice(-11)}`;
    const url = `https://web.whatsapp.com/send?phone=${full}&text=Ol%C3%A1%2C%20gostaria%20de%20falar%20com%20voc%C3%AA.`;
    $('#whatsAppIframe').attr('src', url);
    new bootstrap.Modal(document.getElementById('whatsAppModal')).show();
}

function renderImageColumn(title, src) {
    return `
        <div class="col-md-3 text-center mb-3">
            <p><strong>${title}</strong></p>
            <img src="${src}" class="img-fluid rounded" style="max-height:150px;"
                 onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';"/>
            <br>
            <a href="${src}" download class="btn btn-sm btn-outline-primary mt-2">⬇ Baixar</a>
            <button class="btn btn-sm btn-outline-secondary mt-2" onclick="openImageModal('${src}')">🔍 Ampliar</button>
        </div>
    `;
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
            <p><strong>Status:</strong> ${getStatusLabel(d.status)}</p>
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

function activateDriver(driverId) {
    alert(`Ativar motorista ID: ${driverId}`);
}

function analyzeDriver(driverId) {
    const row = $('#drivers-table').DataTable().row(function (idx, data) {
        return data.id === driverId;
    }).data();

    if (!row) return alert('Motorista não encontrado!');

    $('#analysisContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Analisando...</span>
            </div>
            <p class="mt-2">Aguarde enquanto a inteligência artificial realiza a análise...</p>
        </div>
    `);
    new bootstrap.Modal(document.getElementById('analyzeModal')).show();

    $.ajax({
        url: '/api/analyze-driver',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(row),
        success: function (result) {
            $('#analysisContent').html(`
                <div class="alert alert-info">
                    <h5>🧠 Resultado da Análise via IA:</h5>
                    <p>${result.message.replace(/\n/g, "<br>")}</p>
                </div>
                <hr>
                <div class="row">
                    ${renderImageColumn('Frente CNH', row.driver_license_front)}
                    ${renderImageColumn('Comprovante de Endereço', row.address_proof)}
                    ${renderImageColumn('Foto do Rosto', row.face_photo)}
                </div>
            `);
        },
        error: function () {
            $('#analysisContent').html(`<div class="alert alert-danger">❌ Erro na análise com IA.</div>`);
        }
    });
}

$(document).ready(function() {
    const table = $('#drivers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('drivers.data') }}",
        columns: [
            { className: 'dt-control', orderable: false, data: null, defaultContent: '' },
            { data: 'name', name: 'name' },
            { data: 'address', name: 'address' },
            { data: 'identity_card', name: 'identity_card', render: maskRG },
            { data: 'phone', name: 'phone', render: maskPhone },
            { data: 'status', name: 'status', render: getStatusLabel },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="/drivers/${row.id}/balance" class="btn btn-outline-success">💰 Saldo</a>
                            <a href="/drivers/${row.id}/freights" class="btn btn-outline-primary">🚚 Ver Fretes</a>
                            <button onclick="activateDriver(${row.id})" class="btn btn-outline-warning">✅ Ativar</button>
                            <button onclick="analyzeDriver(${row.id})" class="btn btn-outline-dark">🕵️ Analisar</button>
                            <button onclick="openWhatsAppModal('${row.phone}')" class="btn btn-outline-success">💬 Conversar</button>
                        </div>
                    `;
                }
            }
        ],
        order: [[1, 'asc']]
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
});
</script>
@endsection
