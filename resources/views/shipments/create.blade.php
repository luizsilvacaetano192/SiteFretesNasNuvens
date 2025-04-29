@extends('layouts.app')

@section('title', 'Create Shipment')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('shipments.store') }}" method="POST">
        @csrf

        <!-- Seção da Empresa (mantida igual) -->
        
        <!-- Seção da Carga -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Informações da Carga</h5>
            </div>
            <div class="card-body">
                <!-- Outros campos da carga... -->

                <!-- Checkboxes corrigidos -->
                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_fragile" class="form-check-input" id="fragileCheck" value="1" {{ old('is_fragile', 0) == 1 ? 'checked' : '' }}>
                    <input type="hidden" name="is_fragile" value="0">
                    <label class="form-check-label" for="fragileCheck">Carga frágil</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_hazardous" class="form-check-input" id="hazardousCheck" value="1" {{ old('is_hazardous', 0) == 1 ? 'checked' : '' }}>
                    <input type="hidden" name="is_hazardous" value="0">
                    <label class="form-check-label" for="hazardousCheck">Material perigoso</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="requires_temperature_control" class="form-check-input" id="tempCheck" value="1" {{ old('requires_temperature_control', 0) == 1 ? 'checked' : '' }}>
                    <input type="hidden" name="requires_temperature_control" value="0">
                    <label class="form-check-label" for="tempCheck">Controle de temperatura necessário</label>
                </div>

                <!-- Campos de temperatura... -->
            </div>
        </div>

        <!-- Botões de ação -->
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tempCheck = document.getElementById('tempCheck');
        const temperatureFields = document.getElementById('temperatureFields');

        function updateTemperatureFields() {
            if(tempCheck.checked) {
                temperatureFields.style.display = 'block';
                // Adiciona required apenas quando visível
                document.querySelector('[name="min_temperature"]').required = true;
                document.querySelector('[name="max_temperature"]').required = true;
            } else {
                temperatureFields.style.display = 'none';
                // Remove required e limpa valores
                document.querySelector('[name="min_temperature"]').required = false;
                document.querySelector('[name="max_temperature"]').required = false;
                document.querySelector('[name="min_temperature"]').value = '';
                document.querySelector('[name="max_temperature"]').value = '';
            }
        }

        // Inicialização
        updateTemperatureFields();
        
        // Event listener
        tempCheck.addEventListener('change', updateTemperatureFields);
    });
</script>
@endpush

@endsection