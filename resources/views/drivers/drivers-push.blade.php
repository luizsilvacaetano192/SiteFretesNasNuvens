@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        background-color: #f8f9fa;
    }

    .fade-alert {
        animation: fadeInOut 6s ease forwards;
    }

    @keyframes fadeInOut {
        0% { opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { opacity: 0; display: none; }
    }

    tr.selected-row {
        background-color: #d0ebff !important;
        font-weight: 500;
    }

    .token-button-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .token-content {
        font-family: monospace;
        background: #f1f3f5;
        border-radius: 5px;
        padding: 5px;
        word-break: break-word;
    }
</style>
@endpush

@section('content')

<div class="container py-2">
    <h2 class="mb-4"><i class="fa-solid fa-paper-plane"></i> Enviar Push para Motoristas</h2>

    <div id="feedback" style="display: none;" class="alert fade-alert mt-3"></div>
    <ul id="feedback-list" class="mt-2"></ul>

    <form id="pushForm">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label"><i class="fa-solid fa-heading"></i> Título</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Título da mensagem" required>
        </div>

        <div class="mb-3">
            <label for="screen" class="form-label"><i class="fa-solid fa-tv"></i> Tela a abrir</label>
            <select name="screen" id="screen" class="form-select" required>
                <option value="">Abrir qual tela?</option>
                <option value="Menu">Menu - Tela inicial</option>
                <option value="ListScreen">ListScreen - Tela de lista de carga</option>
                <option value="SaldoScreen">SaldoScreen - Tela de saldos</option>
                <option value="PerfilScreen">PerfilScreen - Tela de Perfil</option>
                <option value="MeusFretesScreen">MeusFretesScreen - Tela de Meus Fretes</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label"><i class="fa-solid fa-message"></i> Mensagem</label>
            <textarea name="message" id="message" class="form-control" rows="3" placeholder="Escreva a sua mensagem..." required></textarea>
        </div>

        <div class="mb-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Enviar</button>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-2">
                <select id="filterCidade" class="form-select">
                    <option value="">Filtrar por cidade</option>
                    @foreach ($cidades as $cidade)
                        <option value="{{ $cidade }}">{{ $cidade }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-2">
                <select id="filterEstado" class="form-select">
                    <option value="">Filtrar por estado</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado }}">{{ $estado }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="driversTableContainer" class="table-responsive mb-5">
            <table id="driversTable" class="table table-striped table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th><i class="fa-solid fa-user"></i> Nome</th>
                        <th><i class="fa-solid fa-map-location-dot"></i> Endereço</th>
                        <th><i class="fa-solid fa-key"></i> Token Push</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($drivers as $driver)
                    <tr>
                        <td><input type="checkbox" name="drivers[]" value="{{ $driver->id }}" class="driver-checkbox"></td>
                        <td>{{ $driver->name }}</td>
                        <td class="address-cell">{{ $driver->address }}</td>
                        <td>
                            @if ($driver->token_push)
                            <div class="token-button-container">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleToken(this)">Mostrar</button>
                                <div class="token-content mt-2 d-none">{{ $driver->token_push }}</div>
                            </div>
                            @else
                                <span class="text-muted">Sem token</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
            responsive: true,
            ordering: true,
            order: [[1, 'asc']],
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

        $('.driver-checkbox').on('change', updateRowHighlight);

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

                // Scroll para DataTable se nenhum motorista estiver selecionado
                if (selectedDrivers.length === 0) {
                    document.getElementById("driversTableContainer").scrollIntoView({ behavior: "smooth" });
                }
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

            window.scrollTo({ top: 0, behavior: 'smooth' });

            if (response.ok) {
                feedback.removeClass().addClass('alert alert-success fade-alert').text(result.message || "Mensagem enviada com sucesso!").show();
                feedbackList.html('');
                result.resultados.forEach(msg => {
                    feedbackList.append(`<li>${msg}</li>`);
                });
                $('#pushForm')[0].reset();
                $('.driver-checkbox').prop('checked', false).trigger('change');
                $('#selectAll').prop('checked', false);

                setTimeout(() => {
                    window.location.href = "{{ route('messages-push.index') }}";
                }, 2000);
            } else {
                feedback.removeClass().addClass('alert alert-danger fade-alert').text(result.message || "Erro ao enviar mensagem.").show();
                feedbackList.html('');
            }
        });
    });

    function toggleToken(button) {
        const container = button.closest('.token-button-container');
        const token = container.querySelector('.token-content');

        const isVisible = !token.classList.contains('d-none');
        token.classList.toggle('d-none', isVisible);
        button.textContent = isVisible ? 'Mostrar' : 'Ocultar';
    }
</script>
@endpush
