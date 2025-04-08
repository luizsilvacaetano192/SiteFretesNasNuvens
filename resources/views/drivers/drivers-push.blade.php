@extends('layouts.app')

@push('styles')
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        table.dataTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Remover faixas cinzas das linhas ímpares */
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: transparent;
        }

        /* Destaque visual para linha selecionada */
        tr.selected-row {
            background-color: #dbeafe !important;
            transition: background-color 0.3s ease;
        }

        .token-wrapper {
            position: relative;
            max-height: 40px;
            overflow: hidden;
            word-break: break-word;
            transition: max-height 0.3s ease;
            background: #f8f9fa;
            padding: 5px;
            border-radius: 5px;
            font-family: monospace;
        }

        .token-wrapper.expanded {
            max-height: 500px;
        }

        .toggle-token {
            background: none;
            border: none;
            font-weight: 600;
            font-size: 0.85rem;
            color: #0d6efd;
            cursor: pointer;
            padding-top: 5px;
        }

        #driversTable th, #driversTable td {
            vertical-align: middle;
        }
    </style>
@endpush

@section('content')
<div class="container">
    <h2 class="mb-4">Enviar push para os motoristas</h2>

    <div id="feedback" style="display: none;" class="alert mt-3"></div>
    <ul id="feedback-list" class="mt-2"></ul>

    <form id="pushForm">
        @csrf

        <div class="mb-3">
            <input type="text" name="title" id="title" class="form-control" placeholder="Título da mensagem" required>
        </div>

        <div class="mb-3">
            <select name="screen" id="screen" class="form-control" required>
                <option value="">Abrir qual tela?</option>
                <option value="Menu">Menu - Tela inicial</option>
                <option value="ListScreen">ListScreen - Tela de lista de carga</option>
                <option value="SaldoScreen">SaldoScreen - Tela de saldos</option>
                <option value="PerfilScreen">PerfilScreen - Tela de Perfil</option>
                <option value="MeusFretesScreen">MeusFretesScreen - Tela de Meus Fretes</option>
            </select>
        </div>

        <div class="mb-3">
            <textarea name="message" id="message" class="form-control" rows="3" placeholder="Escreva a sua mensagem..." required></textarea>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <select id="filterCidade" class="form-control">
                    <option value="">Filtrar por cidade</option>
                    @foreach ($cidades as $cidade)
                        <option value="{{ $cidade }}">{{ $cidade }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <select id="filterEstado" class="form-control">
                    <option value="">Filtrar por estado</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}">{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <table id="driversTable" class="table table-bordered table-hover table-striped align-middle">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Token Push</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($drivers as $driver)
                <tr>
                    <td><input type="checkbox" name="drivers[]" value="{{ $driver->id }}" class="driver-checkbox"></td>
                    <td>{{ $driver->name }}</td>
                    <td class="address-cell">{{ $driver->address }}</td>
                    <td>
                        <div class="token-wrapper" id="token-{{ $driver->id }}">
                            <div class="token-text">{{ $driver->token_push }}</div>
                            <button type="button" class="toggle-token" onclick="toggleToken({{ $driver->id }})">Ver mais</button>
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        const table = $('#driversTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            paging: true,
            searching: true,
            ordering: true,
            order: [[1, 'desc']],
            columnDefs: [{ orderable: false, targets: 0 }]
        });

        function updateRowHighlight() {
            $('.driver-checkbox').each(function () {
                const row = $(this).closest('tr');
                if (this.checked) {
                    row.addClass('selected-row');
                } else {
                    row.removeClass('selected-row');
                }
            });
        }

        $('#selectAll').on('change', function () {
            $('.driver-checkbox').prop('checked', this.checked).trigger('change');
        });

        $(document).on('change', '.driver-checkbox', function () {
            updateRowHighlight();
        });

        function filterByCidadeEstado() {
            const cidade = $('#filterCidade').val().toLowerCase();
            const estado = $('#filterEstado').val().toLowerCase();

            table.rows().every(function () {
                const row = $(this.node());
                const address = row.find('.address-cell').text().toLowerCase();
                const matchCidade = !cidade || address.includes(cidade);
                const matchEstado = !estado || address.includes(estado);
                row.toggle(matchCidade && matchEstado);
            });
        }

        $('#filterCidade, #filterEstado').on('change', filterByCidadeEstado);

        $('#pushForm').on('submit', async function (e) {
            e.preventDefault();

            const title = $('#title').val();
            const message = $('#message').val();
            const screen = $('#screen').val();
            const selectedDrivers = $('.driver-checkbox:checked').map(function () {
                return this.value;
            }).get();

            if (!title || !message || !screen || selectedDrivers.length === 0) {
                alert("Preencha todos os campos e selecione pelo menos um motorista.");
                return;
            }

            const csrfToken = $('input[name="_token"]').val();

            const response = await fetch("{{ route('drivers.sendPush') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    title,
                    message,
                    screen,
                    drivers: selectedDrivers
                })
            });

            const result = await response.json();
            const feedback = $('#feedback');
            const feedbackList = $('#feedback-list');

            if (response.ok) {
                feedback.removeClass().addClass('alert alert-success').text(result.message || "Mensagem enviada com sucesso!").show();
                feedbackList.html('');
                result.resultados.forEach(msg => {
                    feedbackList.append(`<li>${msg}</li>`);
                });
                $('#pushForm')[0].reset();
                $('.driver-checkbox').prop('checked', false).trigger('change');
                $('#selectAll').prop('checked', false);
            } else {
                feedback.removeClass().addClass('alert alert-danger').text(result.message || "Erro ao enviar mensagem.").show();
                feedbackList.html('');
            }
        });
    });

    function toggleToken(id) {
        const wrapper = document.getElementById(`token-${id}`);
        const btn = wrapper.querySelector('.toggle-token');
        wrapper.classList.toggle('expanded');
        btn.innerText = wrapper.classList.contains('expanded') ? 'Ver menos' : 'Ver mais';
    }
</script>
@endpush
