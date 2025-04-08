@extends('layouts.app')

@section('title', 'Mensagens Push')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Mensagens Push</h2>

    <div id="toast" class="alert alert-info" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999;">
        <span id="toast-message"></span>
    </div>

    <div class="row mb-3">
        <div class="col-md-3">
            <label>Filtrar por envio:</label>
            <select id="filter-send" class="form-control">
                <option value="">Todos</option>
                <option value="1">Enviadas</option>
                <option value="0">Não enviadas</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Com erro:</label>
            <select id="filter-error" class="form-control">
                <option value="">Todos</option>
                <option value="1">Com erro</option>
                <option value="0">Sem erro</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Data:</label>
            <input type="date" id="filter-date" class="form-control">
        </div>
    </div>

    <table class="table table-bordered" id="messages-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Motorista</th>
                <th>Título</th>
                <th>Texto</th>
                <th>Token</th>
                <th>Data</th>
                <th style="display:none;">Send</th>
                <th style="display:none;">Erro Interno</th>
                <th>Enviada</th>
                <th>Erro</th>
                <th>Tipo</th>
                <th>Tela</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>
function showToast(message, type = 'info') {
    $('#toast-message').text(message);
    $('#toast').removeClass().addClass(`alert alert-${type}`).fadeIn();
    setTimeout(() => $('#toast').fadeOut(), 5000);
}

$(function () {
    const table = $('#messages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('mensagens-push.list') }}',
            data: function (d) {
                d.send = $('#filter-send').val();
                d.error = $('#filter-error').val();
                d.date = $('#filter-date').val();
            }
        },
        order: [[0, 'desc']],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'driver', name: 'driver' },
            { data: 'titulo', name: 'titulo' },
            {
                data: 'texto',
                name: 'texto',
                render: function (data, type, row) {
                    return `<span class="texto-obfuscated" style="display:none">${data}</span>
                            <button class="btn btn-sm btn-outline-primary toggle-texto">Mostrar</button>`;
                }
            },
            {
                data: 'token',
                name: 'token',
                render: function (data, type, row) {
                    return `<span class="token-obfuscated" style="display:none">${data}</span>
                            <button class="btn btn-sm btn-outline-secondary toggle-token">Mostrar</button>`;
                }
            },
            { data: 'data', name: 'data' },
            { data: 'send', visible: false, name: 'send' },
            { data: 'reason', visible: false, name: 'reason' },
            { data: 'send_label', name: 'send_label' },
            { data: 'erro', name: 'erro' },
            { data: 'type', name: 'type' },
            { data: 'screen', name: 'screen' },
            {
                data: null,
                name: 'acoes',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    if (row.reason && row.reason.trim() !== '') {
                        return `<button class="btn btn-sm btn-danger reenviar-btn" data-id="${row.id}">Reenviar</button>`;
                    }
                    return '';
                }
            }
        ],
        createdRow: function (row, data, dataIndex) {
            if (data.reason && data.reason.trim() !== '') {
                $(row).addClass('table-danger');
            }
        },
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
        }
    });

    $('#filter-send, #filter-error, #filter-date').on('change', function () {
        table.draw();
    });

    $('#messages-table').on('click', '.toggle-token', function () {
        const btn = $(this);
        const span = btn.siblings('.token-obfuscated');

        span.toggle();
        btn.text(span.is(':visible') ? 'Ocultar' : 'Mostrar');
    });

    $('#messages-table').on('click', '.toggle-texto', function () {
        const btn = $(this);
        const span = btn.siblings('.texto-obfuscated');

        span.toggle();
        btn.text(span.is(':visible') ? 'Ocultar' : 'Mostrar');
    });

    $('#messages-table').on('click', '.reenviar-btn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: `/mensagens-push/${id}/reenviar`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.success) {
                    showToast(`Mensagem #${id} marcada para reenvio. 🚀`);
                    table.ajax.reload(null, false);
                }
            },
            error: function () {
                showToast(`Erro ao reenviar a mensagem #${id}.`, 'danger');
            }
        });
    });
});
</script>
@endpush
