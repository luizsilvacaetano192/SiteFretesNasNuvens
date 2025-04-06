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

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
  <div id="toastContainer"></div>
</div>

<!-- Modal de Imagem Ampliada -->
<!-- ... (os modais permanecem iguais, sem mudanças) ... -->

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
let selectedDriverId = null;

function showToast(message, type = 'success') {
    const toastId = `toast-${Date.now()}`;
    const color = type === 'success' ? 'bg-success' : 'bg-danger';
    const icon = type === 'success' ? '✅' : '❌';

    const toastHTML = `
      <div class="toast align-items-center text-white ${color} border-0 show" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">${icon} ${message}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    `;
    $('#toastContainer').append(toastHTML);

    const toastEl = document.getElementById(toastId);
    const bsToast = new bootstrap.Toast(toastEl, { delay: 4000 });
    bsToast.show();
}

function updateDriverStatus(id, status) {
    $.post(`/drivers/${id}/update-status`, { status, _token: '{{ csrf_token() }}' }, () => {
        $('#drivers-table').DataTable().ajax.reload(null, false);
        bootstrap.Modal.getInstance(document.getElementById('blockModal'))?.hide();

        let msg = 'Status atualizado com sucesso!';
        if (status === 'active') msg = 'Motorista ativado!';
        else if (status === 'block') msg = 'Usuário bloqueado!';
        else if (status === 'transfer_block') msg = 'Transferências bloqueadas!';

        showToast(msg, 'success');
    }).fail(() => showToast("Erro ao atualizar status.", 'error'));
}

function activateDriver(id, status) {
    if (status === 'active') {
        selectedDriverId = id;
        new bootstrap.Modal('#blockModal').show();
    } else {
        updateDriverStatus(id, 'active');
    }
}

// ... (demais funções como analyzeDriver, openImageModal, format, etc. permanecem iguais) ...

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
                        <a href="/drivers/${row.id}/balance" class="btn btn-outline-success">💰 Saldo</a>
                        <a href="/drivers/${row.id}/freights" class="btn btn-outline-primary">🚚 Ver Fretes</a>
                        <button onclick="activateDriver(${row.id}, '${row.status}')" class="btn btn-outline-${row.status === 'active' ? 'danger' : 'warning'}">
                            ${row.status === 'active' ? '🚫 Bloquear' : '✅ Ativar'}
                        </button>
                        <button onclick="analyzeDriver(${row.id})" class="btn btn-outline-dark">🕵️ Analisar</button>
                        <button onclick="openWhatsApp('${row.phone}')" class="btn btn-outline-success">💬 Conversar</button>
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
});
</script>
@endsection
