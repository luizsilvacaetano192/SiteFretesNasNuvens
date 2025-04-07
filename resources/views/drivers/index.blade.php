@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gerenciamento de Motoristas</h2>

    <table id="drivers-table" class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Endereço</th>
                <th>RG</th>
                <th>Telefone</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal: Análise -->
<div class="modal fade" id="analyzeModal" tabindex="-1" aria-labelledby="analyzeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Análise de Documentos do Motorista</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body" id="analyzeModalContent">
                <p>Carregando dados...</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Bloqueio -->
<div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="blockForm">
                <div class="modal-header">
                    <h5 class="modal-title">Bloquear Motorista</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="driver_id" id="block_driver_id">
                    <input type="hidden" name="block_type" id="block_type">

                    <div class="mb-3">
                        <label for="reason" class="form-label">Motivo do bloqueio</label>
                        <textarea name="reason" id="reason" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Confirmar Bloqueio</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- CSS & JS necessários -->
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function () {
    const table = $('#drivers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('drivers.data') }}",
        columns: [
            { data: 'name' },
            { data: 'address' },
            { data: 'identity_card' },
            { data: 'phone' },
            { data: 'status' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data) {
                    const driverId = data.id;
                    const isActive = data.status === 'active';
                    const btnToggle = isActive
                        ? `<button class="btn btn-warning btn-sm" onclick="openBlockModal(${driverId})">Bloquear</button>`
                        : `<button class="btn btn-success btn-sm" onclick="changeStatus(${driverId}, 'active')">Ativar</button>`;

                    return `
                        ${btnToggle}
                        <button class="btn btn-primary btn-sm" onclick="analyzeDriver(${driverId})">Analisar</button>
                    `;
                }
            }
        ]
    });

    window.changeStatus = function (id, status) {
        $.post(`/drivers/${id}/status`, { _token: '{{ csrf_token() }}', status }, function () {
            toastr.success('Status atualizado com sucesso');
            table.ajax.reload();
        }).fail(() => {
            toastr.error('Erro ao atualizar status');
        });
    }

    window.openBlockModal = function (id) {
        $('#block_driver_id').val(id);
        $('#block_type').val('user');
        $('#reason').val('');
        $('#blockModal').modal('show');
    }

    $('#blockForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#block_driver_id').val();
        const reason = $('#reason').val();
        const type = $('#block_type').val();

        $.post(`/drivers/${id}/block`, {
            _token: '{{ csrf_token() }}',
            reason,
            type
        }, function () {
            $('#blockModal').modal('hide');
            toastr.success('Motorista bloqueado com sucesso');
            table.ajax.reload();
        }).fail(() => {
            toastr.error('Erro ao bloquear motorista');
        });
    });

    window.analyzeDriver = function (id) {
        $('#analyzeModalContent').html('<p>Carregando...</p>');
        $('#analyzeModal').modal('show');

        $.get(`/drivers/${id}/analyze`, function (html) {
            $('#analyzeModalContent').html(html);
        }).fail(() => {
            $('#analyzeModalContent').html('<p>Erro ao carregar análise</p>');
        });
    }
});
</script>
@endsection
