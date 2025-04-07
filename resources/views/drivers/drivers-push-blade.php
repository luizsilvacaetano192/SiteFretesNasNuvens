@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Enviar Mensagem para Motoristas</h2>

    <form id="pushForm" method="POST" action="{{ route('admin.motoristas.sendPush') }}">
        @csrf

        <div class="mb-3">
            <textarea name="message" class="form-control" rows="3" placeholder="Digite sua mensagem..." required></textarea>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Nome</th>
                    <th>Celular</th>
                    <th>Endereço</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($motoristas as $motorista)
                <tr>
                    <td><input type="checkbox" name="motoristas[]" value="{{ $motorista->id }}" class="motorista-checkbox"></td>
                    <td>{{ $motorista->nome }}</td>
                    <td>{{ $motorista->celular }}</td>
                    <td>{{ $motorista->endereco }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>

<script>
    // Selecionar todos
    document.getElementById('selectAll').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('.motorista-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection
