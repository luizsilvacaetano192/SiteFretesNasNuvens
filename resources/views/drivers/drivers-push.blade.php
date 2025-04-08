@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@section('content')
@php
    $estados = $drivers->map(function ($d) {
        $partes = explode(',', $d->address);
        return trim(end($partes));
    })->filter()->unique()->sort();

    $cidades = $drivers->map(function ($d) {
        $partes = explode(',', $d->address);
        return count($partes) >= 3 ? trim($partes[count($partes) - 2]) : null;
    })->filter()->unique()->sort();
@endphp

<div class="container">
    <h2 class="mb-4">Envia push para os motoristas</h2>

    <div id="feedback" style="display: none;" class="alert mt-3"></div>
    <ul id="feedback-list" class="mt-2"></ul>

    <form id="pushForm">
        @csrf

        <div class="mb-3">
            <input type="text" name="title" id="title" class="form-control" placeholder="Título da mensagem" required>
        </div>

        <div class="mb-3">
            <textarea name="message" id="message" class="form-control" rows="3" placeholder="Escreva a sua mensagem..." required></textarea>
        </div>

        <div class="mb-3">
            <label for="screen">Abrir qual tela?</label>
            <select name="screen" id="screen" class="form-control" required>
                <option value="">Selecione uma tela</option>
                <option value="Menu">Menu (Tela principal)</option>
                <option value="ListScreen">ListScreen - Tela de lista de carga</option>
                <option value="SaldoScreen">SaldoScreen - Tela de saldos</option>
                <option value="PerfilScreen">PerfilScreen - Tela de Perfil</option>
                <option value="MeusFretesScreen">MeusFretesScreen - Tela de Meus Fretes</option>
            </select>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>

        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="filterState">Filtrar por Estado</label>
                <select id="filterState" class="form-control">
                    <option value="">Todos os Estados</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}">{{ $estado }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="filterCity">Filtrar por Cidade</label>
                <select id="filterCity" class="form-control">
                    <option value="">Todas as Cidades</option>
                    @foreach ($cidades as $cidade)
                        <option value="{{ $cidade }}">{{ $cidade }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <table id="driversTable" class="table table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Nome</th>
                    <th>Estado</th>
                    <th>Cidade</th>
                    <th>Endereço</th>
                    <th>Token Push</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($drivers as $driver)
                    @php
                        $partes = explode(',', $driver->address);
                        $estado = trim(end($partes));
                        $cidade = count($partes) >= 3 ? trim($partes[count($partes) - 2]) : '';
                    @endphp
                    <tr>
                        <td><input type="checkbox" name="drivers[]" value="{{ $driver->id }}" class="driver-checkbox"></td>
                        <td>{{ $driver->name }}</td>
                        <td>{{ $estado }}</td>
                        <td>{{ $cidade }}</td>
                        <td>{{ $driver->address }}</td>
                        <td>
                            <div class="token-wrapper" style="max-height: 40px; overflow: hidden; position: relative;">
                                <div class="token-text" style="word-break: break-all;">{{ $driver->token_push }}</div>
                                <button type="button" class="btn btn-sm btn-link toggle-token" style="padding: 0;">Ver mais</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            const table = $('#driversTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                },
                paging: true,
                searching: true,
                ordering: true,
                columnDefs: [
                    { orderable: false, targets: 0 } // checkbox
                ]
            });

            // Filtro por estado
            $('#filterState').on('change', function () {
                table.column(2).search(this.value).draw();
            });

            // Filtro por cidade
            $('#filterCity').on('change', function () {
                table.column(3).search(this.value).draw();
            });

            // Checkbox "selecionar todos"
            $('#selectAll').on('change', function () {
                $('.driver-checkbox').prop('checked', this.checked);
            });

            // Mostrar/ocultar token
            $(document).on('click', '.toggle-token', function () {
                const wrapper = $(this).closest('.token-wrapper');
                const expanded = wrapper.hasClass('expanded');

                if (expanded) {
                    wrapper.css('max-height', '40px');
                    $(this).text('Ver mais');
                } else {
                    wrapper.css('max-height', 'none');
                    $(this).text('Ver menos');
                }

                wrapper.toggleClass('expanded');
            });

            // Envio do formulário
            $('#pushForm').on('submit', async function (e) {
                e.preventDefault();

                const title = $('#title').val();
                const message = $('#message').val();
                const screen = $('#screen').val();
                const drivers = $('.driver-checkbox:checked').map(function () {
                    return this.value;
                }).get();

                if (!title || !message || !screen || drivers.length === 0) {
                    alert("Preencha todos os campos e selecione ao menos um motorista.");
                    return;
                }

                const csrfToken = $('input[name="_token"]').val();

                const response = await fetch("{{ route('drivers.sendPush') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({ title, message, screen, drivers })
                });

                const result = await response.json();
                const feedback = $('#feedback');
                const feedbackList = $('#feedback-list');

                if (response.ok) {
                    feedback.text(result.message || "Mensagem enviada com sucesso!");
                    feedback.removeClass().addClass('alert alert-success').show();

                    feedbackList.empty();
                    if (Array.isArray(result.resultados)) {
                        result.resultados.forEach(msg => {
                            feedbackList.append(`<li>${msg}</li>`);
                        });
                    }

                    $('#pushForm')[0].reset();
                    $('.driver-checkbox').prop('checked', false);
                    $('#selectAll').prop('checked', false);
                } else {
                    feedback.text(result.message || "Erro ao enviar mensagem.");
                    feedback.removeClass().addClass('alert alert-danger').show();
                    feedbackList.empty();
                }
            });
        });
    </script>
@endpush
