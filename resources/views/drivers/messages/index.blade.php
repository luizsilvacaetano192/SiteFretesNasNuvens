@extends('layouts.app')

@section('title', 'Mensagens Push')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Mensagens Push</h2>

    <table class="table table-bordered" id="messages-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Motorista</th>
                <th>Texto</th>
                <th>Token</th>
                <th>Link</th>
                <th>Data</th>
                <th>Enviada</th>
                <th>Erro</th>
                <th>Tipo</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>
$(function() {
    $('#messages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('mensagens-push.list') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'driver', name: 'driver' },
            { data: 'texto', name: 'texto' },
            { data: 'token', name: 'token' },
            { data: 'link', name: 'link' },
            { data: 'data', name: 'data' },
            { data: 'send', name: 'send' },
            { data: 'erro', name: 'erro' },
            { data: 'type', name: 'type' }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json'
        }
    });
});
</script>
@endpush
