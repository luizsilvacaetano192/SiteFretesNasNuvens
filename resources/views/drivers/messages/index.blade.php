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
        <div class="col-md-3" id="erro-container" style="display: none;">
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
        ajax: '{{ route('mensagens-push.list') }}',
        order: [[0, 'desc']], // Ordenar por ID DESC
        columns: [
            { data: 'id' },
            { data: 'driver' },
            { data: 'titulo' },
            {
                data: 'texto',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return `<span class="texto-obfuscated" style="display:none">${data}</span>
                                <button class="btn btn-sm btn-outline-primary toggle-texto">Mostrar</button>`;
                    }
                    return data;
                }
            },
            {
                data: 'token',
                render: function (data, type, row) {
                    if (type === 'display') {
                        return `<span class="token-obfuscated" style="display:none">${data}</span>
                                <button class="btn btn-sm btn-outline-secondary toggle-token">Mostrar</button>`;
                    }
                    return data;
                }
            },
            { data: 'data' },
            { data: 'send' },
            { data: 'erro' },
            { data: 'type' },
            { data: 'screen' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    if (row.erro && row.erro !== '—') {
                        return `<button class="btn btn-sm btn-danger reenviar-btn" data-id="${row.id}">Reenviar</button>`;
                    }
                    return '';
                }
            }
        ],
        createdRow: function (row, data) {
            if (data.erro && data.erro !== '—') {
                $(row).addClass('table-danger');
            }
        },
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
        }
    });

    $('#filter-send').on('change', function () {
        const value = $(this).val();
        table.column(6).search(value).draw(); // coluna "Enviada"

        if (value === '0') {
            $('#erro-container').show();
        } else {
            $('#erro-container').hide();
            $('#filter-error').val('');
            table.column(7).search('').draw();
        }
    });

    $('#filter-error').on('change', function () {
        const value = $(this).val();
        table.column(7).search(value).draw(); // coluna "Erro"
    });

    $('#filter-date').on('change', function () {
        const value = $(this).val();
        table.column(5).search(value).draw(); // coluna "Data"
    });

    $('#messages-table').on('click', '.toggle-token', function () {
        const btn = $(this);
        const span = btn.siblings('.token-obfuscated');

        if (span.is(':visible')) {
            span.hide();
            btn.text('Mostrar');
        } else {
            span.show();
            btn.text('Ocultar');
        }
    });

    $('#messages-table').on('click', '.toggle-texto', function () {
        const btn = $(this);
        const span = btn.siblings('.texto-obfuscated');

        if (span.is(':visible')) {
            span.hide();
            btn.text('Mostrar');
        } else {
            span.show();
            btn.text('Ocultar');
        }
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
