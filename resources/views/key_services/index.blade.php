@extends('layouts.app')

@section('title', 'Editar Chave de Serviço')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Chave de Serviço</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('service-keys.update', $serviceKey->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Service Name</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $serviceKey->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Service Key</label>
            <textarea class="form-control" name="key" rows="3" required>{{ old('key', $serviceKey->key) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Atualizar
        </button>
        <a href="{{ route('service-keys.index') }}" class="btn btn-secondary ms-2">
            Cancelar
        </a>
    </form>
</div>
@endsection
