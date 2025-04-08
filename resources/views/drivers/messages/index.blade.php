@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Mensagens Push</h2>

    <table id="messages-table" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Motorista</th>
                <th>Título</th>
                <th>Enviado?</th>
                <th>Data</th>
                <th>Tela</th>
                <th>Ações</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
    const table = $('#messages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('mensagens-push.list') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'driver', name: 'driver' },
            { data: 'titulo', name: 'titulo' },
            { data: 'send_label', name: 'send' },
            { data: 'data', name: 'created_at' },
            { data: 'screen', name: 'screen' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
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
@endsection
