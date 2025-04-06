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
                <th style="width: 20px;"></th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>RG</th>
                <th>Telefone</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal for enlarged images -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Visualização de Imagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" style="max-height: 70vh;">
            </div>
            <div class="modal-footer">
                <a id="downloadImage" href="#" class="btn btn-primary" download>Baixar Imagem</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    td.dt-control::before {
        content: "+";
        font-weight: bold;
        font-size: 18px;
        color: #198754; /* Verde Bootstrap */
        display: inline-block;
        text-align: center;
        width: 20px;
        cursor: pointer;
    }
    tr.shown td.dt-control::before {
        content: "−";
        color: #dc3545; /* Vermelho Bootstrap */
    }
    /* Estilo personalizado para o botão de detalhes */
    td.dt-control {
        text-align: center;
        cursor: pointer;
    }
    
    td.dt-control:before {
        content: "➕";
        font-size: 1.1em;
        color: #198754;
    }
    
    tr.shown td.dt-control:before {
        content: "➖";
        color: #dc3545;
    }
    
    .img-thumbnail {
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .img-thumbnail:hover {
        transform: scale(1.05);
    }
    
    .image-container {
        position: relative;
        margin-bottom: 15px;
    }
    
    .image-actions {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px;
        display: flex;
        justify-content: space-around;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .image-container:hover .image-actions {
        opacity: 1;
    }
    
    .image-actions a {
        color: white;
        text-decoration: none;
        font-size: 0.8rem;
    }
    
    .image-actions a:hover {
        color: #ddd;
    }
</style>

<script>

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
                <div class="col-md-3 text-center">
                    <p><strong>Frente CNH</strong></p>
                    <img src="${d.driver_license_front}" class="img-fluid rounded" style="max-height:150px;" 
                        onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';">
                    <br>
                    <a href="${d.driver_license_front}" download class="btn btn-sm btn-outline-primary mt-2">⬇ Baixar</a>
                </div>
                <div class="col-md-3 text-center">
                    <p><strong>Verso CNH</strong></p>
                    <img src="${d.driver_license_back}" class="img-fluid rounded" style="max-height:150px;" 
                        onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';">
                    <br>
                    <a href="${d.driver_license_back}" download class="btn btn-sm btn-outline-primary mt-2">⬇ Baixar</a>
                </div>
                <div class="col-md-3 text-center">
                    <p><strong>Foto do Rosto</strong></p>
                    <img src="${d.face_photo}" class="img-fluid rounded-circle" style="max-height:150px;" 
                        onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';">
                    <br>
                    <a href="${d.face_photo}" download class="btn btn-sm btn-outline-primary mt-2">⬇ Baixar</a>
                </div>
                <div class="col-md-3 text-center">
                    <p><strong>Comprovante de Endereço</strong></p>
                    <img src="${d.address_proof}" class="img-fluid rounded" style="max-height:150px;" 
                        onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';">
                    <br>
                    <a href="${d.address_proof}" download class="btn btn-sm btn-outline-primary mt-2">⬇ Baixar</a>
                </div>
            </div>
        </div>
    `;
}


function showImageModal(imageSrc, title) {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('downloadImage').href = imageSrc;
    document.getElementById('downloadImage').download = title.toLowerCase().replace(/ /g, '-') + '.jpg';
    document.getElementById('imageModalLabel').textContent = title;
    modal.show();
}

$(document).ready(function() {
    var table = $('#drivers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('drivers.data') }}",
        columns: [
            {
                className: 'dt-control',
                orderable: false,
                searchable: false,
                data: null,
                defaultContent: '',
                width: '20px'
            },
            { data: 'name', name: 'name' },
            { data: 'address', name: 'address' },
            { data: 'identity_card', name: 'identity_card' },
            { data: 'phone', name: 'phone' },
        ],
        order: [[1, 'asc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        }
    });

    $('#drivers-table tbody').on('click', 'td.dt-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

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