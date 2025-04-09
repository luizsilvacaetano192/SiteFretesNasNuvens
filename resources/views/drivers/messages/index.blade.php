@extends('layouts.app')

@push('styles')
<!-- Bootstrap 5 + DataTables -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
    table.dataTable td {
        vertical-align: middle;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.75rem;
        margin: 0 2px;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📩 Mensagens Push</h2>

    {{-- Filtros automáticos --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="filter-send" class="form-label">Enviado?</label>
            <select id="filter-send" class="form-select">
                <option value="">Todos</option>
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="filter-error" class="form-label">Com Erro?</label>
            <select id="filter-error" class="form-select">
                <option value="">Todos</option>
                <option value="1">Sim</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="filter-date" class="form-label">Data</label>
            <input type="date" id="filter-date" class="form-control">
        </div>
    </div>

    <div class="table-responsive">
        <table id="messages-table" class="table table-striped table-hover align-middle">
            <thead class="table-light">
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
                    <th>Erro</th> <!-- NOVA COLUNA -->
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<!-- Bootstrap 5 + jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $.fn.dataTable.ext.errMode = 'throw';

    const table = $('#messages-table').DataTable({
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
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
            {
                data: 'texto',
                name: 'texto',
                render: function (data) {
                    return `
                        <div>
                            <button class="btn btn-outline-primary btn-sm toggle-text">Mostrar</button>
                            <div class="text-content mt-2 d-none">${data}</div>
                        </div>`;
                }
            },
            { data: 'send_label', name: 'send' },
            { data: 'data', name: 'created_at' },
            { data: 'screen', name: 'screen' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            },
            {
                data: 'token',
                name: 'token',
                render: function (data) {
                    return `
                        <div>
                            <button class="btn btn-outline-secondary btn-sm toggle-token">Mostrar</button>
                            <div class="token-content mt-2 d-none">${data}</div>
                        </div>`;
                }
            },
            {
                data: 'error',
                name: 'error',
                render: function (data) {
                    return data ? `<span class="text-danger">${data}</span>` : '';
                }
            }
        ],
        createdRow: function (row, data, dataIndex) {
            if (data.error && data.error !== '') {
                $(row).addClass('table-danger');
            }
        },
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        }
    });

    $('#filter-send, #filter-error').on('change', function () {
        table.ajax.reload();
    });

    $('#filter-date').on('input', function () {
        table.ajax.reload();
    });

    $(document).on('click', '.toggle-text', function () {
        const content = $(this).siblings('.text-content');
        const isVisible = !content.hasClass('d-none');
        content.toggleClass('d-none');
        $(this).text(isVisible ? 'Mostrar' : 'Ocultar');
    });

    $(document).on('click', '.toggle-token', function () {
        const content = $(this).siblings('.token-content');
        const isVisible = !content.hasClass('d-none');
        content.toggleClass('d-none');
        $(this).text(isVisible ? 'Mostrar' : 'Ocultar');
    });

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
