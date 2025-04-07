@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@section('content')
<div class="container">
    <h2 class="mb-4">Envia push para os motoristas</h2>

    <div id="feedback" style="display: none;" class="alert alert-success"></div>

    <form id="pushForm">
        @csrf

        <div class="mb-3">
            <textarea name="message" id="message" class="form-control" rows="3" placeholder="Escreva a sua mensagem..." required></textarea>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>

        <table id="driversTable" class="table table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Nome</th>
                    <th>Celular</th>
                    <th>Endereço</th>
                    <th>Token Push</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($drivers as $driver)
                <tr>
                    <td><input type="checkbox" name="drivers[]" value="{{ $driver->id }}" class="driver-checkbox"></td>
                    <td>{{ $driver->name }}</td>
                    <td>{{ $driver->phone }}</td>
                    <td>{{ $driver->address }}</td>
                    <td style="word-break: break-all;">{{ $driver->token_push }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#driversTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                },
                paging: true,
                searching: true,
                ordering: true,
                columnDefs: [
                    { orderable: false, targets: 0 } // checkbox não ordenável
                ]
            });

            // Checkbox "selecionar todos"
            const selectAll = document.getElementById('selectAll');
            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    const checkboxes = document.querySelectorAll('.driver-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });
            }

            // Submissão do formulário
            const pushForm = document.getElementById('pushForm');
            if (pushForm) {
                pushForm.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const message = document.getElementById('message').value;
                    const checkboxes = document.querySelectorAll('.driver-checkbox:checked');
                    const drivers = Array.from(checkboxes).map(cb => cb.value);

                    if (!message || drivers.length === 0) {
                        alert("Preencha a mensagem e selecione pelo menos um motorista.");
                        return;
                    }

                    const csrfToken = document.querySelector('input[name="_token"]').value;

                    const response = await fetch("{{ route('drivers.sendPush') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        body: JSON.stringify({
                            message: message,
                            drivers: drivers
                        })
                    });

                    const result = await response.json();

                    const feedback = document.getElementById('feedback');
                    if (response.ok) {
                        feedback.innerText = result.message || "Mensagem enviada com sucesso!";
                        feedback.className = "alert alert-success";
                        feedback.style.display = 'block';
                        document.getElementById('pushForm').reset();
                        document.querySelectorAll('.driver-checkbox').forEach(cb => cb.checked = false);
                        document.getElementById('selectAll').checked = false;
                    } else {
                        feedback.innerText = result.message || "Erro ao enviar mensagem.";
                        feedback.className = "alert alert-danger";
                        feedback.style.display = 'block';
                    }
                });
            }
        });
    </script>
@endpush
