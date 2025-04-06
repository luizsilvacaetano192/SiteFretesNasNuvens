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

<!-- Modal para imagem ampliada -->
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

<!-- Estilos e Scripts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

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
                 onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';">
            <br>
            <a href="${src}" download class="btn btn-sm btn-outline-primary mt-2">⬇ Baixar</a>
            <button class="btn btn-sm btn-outline-secondary mt-2" onclick="openImageModal('${src}')">🔍 Ampliar</button>
        </div>
    `;
}

function format(d) {
    const formatDateBR = (dateStr) => {
        if (!dateStr) return '';
        const [year, month, day] = dateStr.split('-');
        return `${day}/${month}/${year}`;
    };

    const formatCPF = (cpf) => cpf?.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/, "$1.$2.$3-$4") || '';
    const formatRG = (rg) => rg?.replace(/^(\d{2})(\d{3})(\d{3})(\d{1})$/, "$1.$2.$3-$4") || '';
    const formatPhone = (phone) => phone?.replace(/^(\d{2})(\d{5})(\d{4})$/, "($1) $2-$3") || '';

    const passwordId = `pass-${d.id}`;
    const toggleId = `toggle-${d.id}`;

    return `
        <div class="p-3 bg-light rounded">
            <p><strong>Data de Nascimento:</strong> ${formatDateBR(d.birth_date)}</p>
            <p><strong>Estado Civil:</strong> ${d.marital_status}</p>
            <p><strong>CPF:</strong> ${formatCPF(d.cpf)}</p>
            <p><strong>RG:</strong> ${formatRG(d.identity_card)}</p>
            <p><strong>Telefone:</strong> ${formatPhone(d.phone)}</p>
            <p><strong>CNH:</strong> ${d.driver_license_number}</p>
            <p><strong>Categoria CNH:</strong> ${d.driver_license_category}</p>
            <p><strong>Validade CNH:</strong> ${formatDateBR(d.driver_license_expiration)}</p>

            <p>
                <strong>Senha:</strong>
                <input type="password" class="form-control d-inline-block w-auto" id="${passwordId}" value="${d.password}" readonly />
                <button type="button" class="btn btn-sm btn-outline-secondary" id="${toggleId}" onclick="togglePassword('${passwordId}', '${toggleId}')">👁️</button>
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

function togglePassword(inputId, buttonId) {
    const input = document.getElementById(inputId);
    const button = document.getElementById(buttonId);
    if (input.type === "password") {
        input.type = "text";
        button.innerText = "🙈";
    } else {
        input.type = "password";
        button.innerText = "👁️";
    }
}

function activateDriver(driverId) {
    alert(`Ativar motorista ID: ${driverId}`);
    // Aqui você pode fazer uma chamada AJAX para ativar
}

function analyzeDriver(driverId) {
    alert(`Analisar motorista ID: ${driverId}`);
    // Aqui você pode redirecionar ou abrir modal
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
            { data: 'identity_card', name: 'identity_card' },
            { data: 'phone', name: 'phone' },
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
