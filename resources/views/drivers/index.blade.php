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
      <div class="modal-body" id="analysisContent">
        <div class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Analisando...</span>
          </div>
          <p class="mt-2">Aguarde enquanto a inteligência artificial realiza a análise...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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

function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
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
    const passwordFieldId = `password-${d.id}`;
    const toggleBtnId = `toggle-${d.id}`;

    return `
        <div class="p-3 bg-light rounded">
            <p><strong>Data de Nascimento:</strong> ${formatDateBR(d.birth_date)}</p>
            <p><strong>Estado Civil:</strong> ${d.marital_status}</p>
            <p><strong>CPF:</strong> ${maskCPF(d.cpf)}</p>
            <p><strong>CNH:</strong> ${d.driver_license_number}</p>
            <p><strong>Categoria CNH:</strong> ${d.driver_license_category}</p>
            <p><strong>Validade CNH:</strong> ${formatDateBR(d.driver_license_expiration)}</p>
            <p><strong>Senha:</strong> 
                <span id="${passwordFieldId}">${'•'.repeat(d.password.length)}</span>
                <button class="btn btn-sm btn-outline-secondary" id="${toggleBtnId}" onclick="togglePassword('${d.id}', '${d.password}')">👁 Mostrar</button>
            </p>
            <p><strong>Termos Aceitos:</strong> ${d.terms_accepted ? 'Sim' : 'Não'}</p>
            <div class="row">
                ${renderImageColumn('Frente CNH', d.driver_license_front)}
                ${renderImageColumn('Verso CNH', d.driver_license_back)}
                ${renderImageColumn('Foto do Rosto', d.face_photo)}
                ${renderImageColumn('Comprovante de Endereço', d.address_proof)}
            </div>
        </div>
    `;
}

function togglePassword(id, password) {
    const span = document.getElementById(`password-${id}`);
    const button = document.getElementById(`toggle-${id}`);
    const isHidden = span.innerText.includes('•');

    if (isHidden) {
        span.innerText = password;
        button.innerText = '🙈 Ocultar';
    } else {
        span.innerText = '•'.repeat(password.length);
        button.innerText = '👁 Mostrar';
    }
}

function activateDriver(driverId) {
    alert(`Ativar motorista ID: ${driverId}`);
}

function analyzeDriver(driverId) {
    const row = $('#drivers-table').DataTable().row(function (idx, data) {
        return data.id === driverId;
    }).data();

    if (!row) {
        alert('Motorista não encontrado!');
        return;
    }

    // Mostrar o modal imediatamente
    const modal = new bootstrap.Modal(document.getElementById('analyzeModal'));
    $('#analysisContent').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Analisando...</span>
            </div>
            <p class="mt-2">Aguarde enquanto a inteligência artificial realiza a análise...</p>
        </div>
    `);
    modal.show();

    // Enviar dados via POST
    $.ajax({
        url: '/api/analyze-driver',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            id: row.id,
            name: row.name,
            address: row.address,
            cpf: row.cpf,
            driver_license_number: row.driver_license_number,
            driver_license_front: row.driver_license_front,
            address_proof: row.address_proof,
            face_photo: row.face_photo
        }),
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
            $('#analysisContent').html(`
                <div class="alert alert-danger">
                    ❌ Ocorreu um erro ao realizar a análise com IA.
                </div>
            `);
        }
    });
}

$(document).ready(function() {
    const table = $('#drivers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('drivers.data') }}",
        columns: [
            {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: ''
            },
            { data: 'name', name: 'name' },
            { data: 'address', name: 'address' },
            {
                data: 'identity_card',
                name: 'identity_card',
                render: function(data) {
                    return maskRG(data);
                }
            },
            {
                data: 'phone',
                name: 'phone',
                render: function(data) {
                    return maskPhone(data);
                }
            },
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
