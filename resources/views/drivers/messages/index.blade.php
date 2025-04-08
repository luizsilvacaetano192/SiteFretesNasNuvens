@extends('layouts.app')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <h2 class="mb-4">Mensagens Push</h2>

    {{-- Filtros automáticos --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="filter-send">Enviado?</label>
            <select id="filter-send" class="form-control">
                <option value="">Todos</option>
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="filter-error">Com Erro?</label>
            <select id="filter-error" class="form-control">
                <option value="">Todos</option>
                <option value="1">Sim</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="filter-date">Data</label>
            <input type="date" id="filter-date" class="form-control">
        </div>
    </div>

    <table id="messages-table" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Motorista</th>
                <th>Título</th>
                <th>Texto</th>
                <th>Enviado?</th>
                <th>Data</th>
                <th>Tela</th>
                <th>Ações</th>
                <th>Token</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    $.fn.dataTable.ext.errMode = 'throw';

    const table = $('#messages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('messages-push.list') }}',
            data: function (d) {
                d.send = $('#filter-send').val();
                d.error = $('#filter-error').val();
                d.date = $('#filter-date').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'driver', name: 'driver' },
            { data: 'titulo', name: 'titulo' },

            // Texto com botão
            {
                data: 'texto',
                name: 'texto',
                render: function (data, type, row) {
                    return `
                        <div>
                            <button class="btn btn-sm btn-primary toggle-text">Mostrar</button>
                            <div class="text-content mt-2 d-none">${data}</div>
                        </div>`;
                }
            },

            { data: 'send_label', name: 'send' },
            { data: 'data', name: 'created_at' },
            { data: 'screen', name: 'screen' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },

            // Token com botão
            {
                data: 'token',
                name: 'token',
                render: function (data, type, row) {
                    return `
                        <div>
                            <button class="btn btn-sm btn-secondary toggle-token">Mostrar</button>
                            <div class="token-content mt-2 d-none">${data}</div>
                        </div>`;
                }
            }
        ]
    });

    // Filtros
    $('#filter-send, #filter-error').on('change', function () {
        table.ajax.reload();
    });

    $('#filter-date').on('input', function () {
        table.ajax.reload();
    });

    // Mostrar/Ocultar Texto
    $(document).on('click', '.toggle-text', function () {
        const content = $(this).siblings('.text-content');
        const isVisible = !content.hasClass('d-none');
        content.toggleClass('d-none');
        $(this).text(isVisible ? 'Mostrar' : 'Ocultar');
    });

    // Mostrar/Ocultar Token
    $(document).on('click', '.toggle-token', function () {
        const content = $(this).siblings('.token-content');
        const isVisible = !content.hasClass('d-none');
        content.toggleClass('d-none');
        $(this).text(isVisible ? 'Mostrar' : 'Ocultar');
    });

    // Botão de reenviar notificação
    $(document).on('click', '.resend-btn', function () {
        const id = $(this).data('id');
        if (confirm('Deseja reenviar a notificação e limpar o erro?')) {
            $.ajax({
                url: `/messages-push/resend/${id}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (res) {
                    alert(res.message);
                    table.ajax.reload(null, false);
                },
                error: function () {
                    alert('Erro ao tentar reenviar.');
                }
            });
        }
    });
});
</script>
@endpush
