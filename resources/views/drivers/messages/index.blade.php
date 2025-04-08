@extends('layouts.app')

@section('title', 'Mensagens Push')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Mensagens Push</h2>

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
$(function() {
    const table = $('#messages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('mensagens-push.list') }}',
            data: function (d) {
                d.send = $('#filter-send').val();
                d.erro = $('#filter-send').val() === '0' ? $('#filter-error').val() : '';
                d.data = $('#filter-date').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'driver', name: 'driver' },
            { data: 'titulo', name: 'titulo' },
            {
                data: 'texto',
                name: 'texto',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<span class="texto-obfuscated" style="display:none">${data}</span>
                                <button class="btn btn-sm btn-outline-primary toggle-texto">Mostrar</button>`;
                    }
                    return data;
                }
            },
            {
                data: 'token',
                name: 'token',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<span class="token-obfuscated" style="display:none">${data}</span>
                                <button class="btn btn-sm btn-outline-secondary toggle-token">Mostrar</button>`;
                    }
                    return data;
                }
            },
            { data: 'data', name: 'data' },
            { data: 'send', name: 'send' },
            { data: 'erro', name: 'erro' },
            { data: 'type', name: 'type' },
            { data: 'screen', name: 'screen' }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
        }
    });

    $('#filter-send').on('change', function () {
        const send = $(this).val();
        if (send === '0') {
            $('#erro-container').show();
        } else {
            $('#erro-container').hide();
            $('#filter-error').val('');
        }
        table.draw();
    });

    $('#filter-error, #filter-date').on('change', function () {
        table.draw();
    });

    // Mostrar/ocultar token
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

    // Mostrar/ocultar texto
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
});
</script>
@endpush
