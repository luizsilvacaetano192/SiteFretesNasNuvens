@extends('layouts.app')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #28a745;
    }

    input:checked + .slider:before {
        transform: translateX(22px);
    }
</style>
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

    {{-- Opções para mostrar/ocultar colunas --}}
    <div class="row mb-4 align-items-end">
        <div class="col-md-3">
            <label>Mostrar Texto</label><br>
            <label class="switch">
                <input type="checkbox" class="toggle-column" data-column="3" checked>
                <span class="slider"></span>
            </label>
        </div>
        <div class="col-md-3">
            <label>Mostrar Token</label><br>
            <label class="switch">
                <input type="checkbox" class="toggle-column" data-column="8" checked>
                <span class="slider"></span>
            </label>
        </div>
        <div class="col-md-3">
            <button id="toggle-all-columns" class="btn btn-secondary">Ocultar Todos</button>
        </div>
    </div>

    <table id="messages-table" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>               {{-- 0 --}}
                <th>Motorista</th>        {{-- 1 --}}
                <th>Título</th>           {{-- 2 --}}
                <th>Texto</th>            {{-- 3 --}}
                <th>Enviado?</th>         {{-- 4 --}}
                <th>Data</th>             {{-- 5 --}}
                <th>Tela</th>             {{-- 6 --}}
                <th>Ações</th>            {{-- 7 --}}
                <th>Token</th>            {{-- 8 --}}
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
            { data: 'id', name: 'id' },                        // 0
            { data: 'driver', name: 'driver' },                // 1
            { data: 'titulo', name: 'titulo' },                // 2
            { data: 'texto', name: 'texto' },                  // 3 <- toggle
            { data: 'send_label', name: 'send' },              // 4
            { data: 'data', name: 'created_at' },              // 5
            { data: 'screen', name: 'screen' },                // 6
            { data: 'actions', name: 'actions', orderable: false, searchable: false }, // 7
            { data: 'token', name: 'token' }                   // 8 <- toggle
        ]
    });

    // Atualizar tabela ao mudar filtros
    $('#filter-send, #filter-error').on('change', function () {
        table.ajax.reload();
    });

    $('#filter-date').on('input', function () {
        table.ajax.reload();
    });

    // Alternar visibilidade de colunas individuais
    $('.toggle-column').on('change', function () {
        const columnIdx = $(this).data('column');
        const column = table.column(columnIdx);
        column.visible($(this).is(':checked'));
    });

    // Alternar todas colunas de uma vez
    let allVisible = true;

    $('#toggle-all-columns').on('click', function () {
        allVisible = !allVisible;

        $('.toggle-column').each(function () {
            const columnIdx = $(this).data('column');
            const column = table.column(columnIdx);

            $(this).prop('checked', allVisible);
            column.visible(allVisible);
        });

        $(this).text(allVisible ? 'Ocultar Todos' : 'Mostrar Todos');
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
