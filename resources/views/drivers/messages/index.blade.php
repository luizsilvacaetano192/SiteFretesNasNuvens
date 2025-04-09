@extends('layouts.app')

@push('styles')
<!-- Bootstrap 5 + DataTables -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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
<div class="container py-5">
    <div class="text-center mt-4">
        <a href="{{ route('drivers.pushForm') }}" class="btn btn-primary">
            <i class="fa-solid fa-arrow-left"></i> Voltar para envio de push
        </a>
    </div>
</div>

<div class="container mt-4">
    <h2 class="mb-4">📩 Mensagens Push</h2>

    <div class="mb-2 text-muted">Próxima atualização em: <span id="countdown">10</span>s</div>

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
                    <th>Erro</th>
                    <th>Ações</th>
                    <th>Token</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Toast container -->
<div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1055"></div>
<audio id="success-sound" src="{{ asset('sounds/success.mp3') }}"></audio>
<audio id="error-sound" src="{{ asset('sounds/error.mp3') }}"></audio>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $.fn.dataTable.ext.errMode = 'throw';

    let previousData = {};

    function showToast(title, body, type = 'info') {
        const toastId = 'toast-' + Date.now();
        const animation = type === 'danger' ? 'animate__shakeX' : 'animate__fadeInRight';

        const toastHTML = `
            <div id="${toastId}" class="toast animate__animated ${animation} text-bg-${type} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong><br>${body}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
                </div>
            </div>`;

        $('#toast-container').append(toastHTML);

        const toastElement = new bootstrap.Toast(document.getElementById(toastId), {
            delay: 10000
        });
        toastElement.show();

        const soundId = type === 'danger' ? '#error-sound' : '#success-sound';
        $(soundId)[0].play();

        setTimeout(() => {
            $(`#${toastId}`).remove();
        }, 11000);
    }

    const table = $('#messages-table').DataTable({
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        ajax: {
            url: '{{ route("messages-push.list") }}',
            data: function (d) {
                d.send = $('#filter-send').val();
                d.error = $('#filter-error').val();
                d.date = $('#filter-date').val();
            },
            dataSrc: function (json) {
                json.data.forEach(row => {
                    const prev = previousData[row.id];
                    if (prev) {
                        if (prev.send === 0 && row.send === 1) {
                            showToast('Mensagem Enviada ✅', `Motorista: ${row.driver}<br>Título: ${row.titulo}`, 'succe@extends('layouts.app')

@push('styles')
<!-- Bootstrap 5 + DataTables -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
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

    <div class="mb-2 text-muted">Próxima atualização em: <span id="countdown">10</span>s</div>

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
                    <th>Erro</th>
                    <th>Ações</th>
                    <th>Token</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Toast container -->
<div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1055"></div>
<audio id="success-sound" src="{{ asset('sounds/success.mp3') }}"></audio>
<audio id="error-sound" src="{{ asset('sounds/error.mp3') }}"></audio>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $.fn.dataTable.ext.errMode = 'throw';

    let previousData = {};

    function showToast(title, body, type = 'info') {
        const toastId = 'toast-' + Date.now();
        const animation = type === 'danger' ? 'animate__shakeX' : 'animate__fadeInRight';

        const toastHTML = `
            <div id="${toastId}" class="toast animate__animated ${animation} text-bg-${type} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong><br>${body}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
                </div>
            </div>`;

        $('#toast-container').append(toastHTML);

        const toastElement = new bootstrap.Toast(document.getElementById(toastId), {
            delay: 10000
        });
        toastElement.show();

        const soundId = type === 'danger' ? '#error-sound' : '#success-sound';
        $(soundId)[0].play();

        setTimeout(() => {
            $(`#${toastId}`).remove();
        }, 11000);
    }

    const table = $('#messages-table').DataTable({
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        ajax: {
            url: '{{ route("messages-push.list") }}',
            data: function (d) {
                d.send = $('#filter-send').val();
                d.error = $('#filter-error').val();
                d.date = $('#filter-date').val();
            },
            dataSrc: function (json) {
                json.data.forEach(row => {
                    const prev = previousData[row.id];
                    if (prev) {
                        if (prev.send === 0 && row.send === 1) {
                            showToast('Mensagem Enviada ✅', `Motorista: ${row.driver}<br>Título: ${row.titulo}`, 'success');
                        }
                        if (!prev.erro && row.erro) {
                            showToast('Erro ao Enviar ❌', `Motorista: ${row.driver}<br>Título: ${row.titulo}`, 'danger');
                        }
                    }
                    previousData[row.id] = row;
                });
                return json.data;
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
                data: 'erro',
                name: 'erro',
                render: function (data) {
                    return data ? `<span class="text-danger">${data}</span>` : '';
                }
            },
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
            }
        ],
        createdRow: function (row, data, dataIndex) {
            if (data.erro) {
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

    let seconds = 10;
    setInterval(() => {
        seconds--;
        $('#countdown').text(seconds);
        if (seconds <= 0) {
            seconds = 10;
            table.ajax.reload(null, false);
        }
    }, 1000);
});
</script>
@endpush
