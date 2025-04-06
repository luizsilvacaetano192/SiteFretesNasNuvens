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

<!-- Scripts e Estilos -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<style>
/* Ícone de expansão sempre visível */
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
    const modalImage = document.getElementById('modalImage');
    modalImage.src = src;
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
    return `
        <div class="p-3 bg-light rounded">
            <p><strong>Data de Nascimento:</strong> ${d.birth_date}</p>
            <p><strong>Estado Civil:</strong> ${d.marital_status}</p>
            <p><strong>CPF:</strong> ${d.cpf}</p>
            <p><strong>CNH:</strong> ${d.driver_license_number}</p>
            <p><strong>Categoria CNH:</strong> ${d.driver_license_category}</p>
            <p><strong>Validade CNH:</strong> ${d.driver_license_expiration}</p>
            <p><strong>Senha:</strong> ${d.password}</p>
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
