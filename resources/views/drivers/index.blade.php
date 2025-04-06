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
                <th style="width: 20px;"></th> <!-- Adicione um width fixo -->
                <th>Nome</th>
                <th>Endereço</th>
                <th>RG</th>
                <th>Telefone</th>
            </tr>
        </thead>
    </table>
</div>

{{-- Scripts do DataTables --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<style>
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
                    <img src="${d.driver_license_front}" class="img-fluid rounded" style="max-height:150px;" onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';">
                </div>
                <div class="col-md-3 text-center">
                    <p><strong>Verso CNH</strong></p>
                    <img src="${d.driver_license_back}" class="img-fluid rounded" style="max-height:150px;" onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';">
                </div>
                <div class="col-md-3 text-center">
                    <p><strong>Foto do Rosto</strong></p>
                    <img src="${d.face_photo}" class="img-fluid rounded-circle" style="max-height:150px;" onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';">
                </div>
                <div class="col-md-3 text-center">
                    <p><strong>Comprovante de Endereço</strong></p>
                    <img src="${d.address_proof}" class="img-fluid rounded" style="max-height:150px;" onerror="this.onerror=null;this.outerHTML='<div class=\'text-danger\'>Imagem não disponível</div>';">
                </div>
            </div>
        </div>
    `;
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