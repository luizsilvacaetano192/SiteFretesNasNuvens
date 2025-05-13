@extends('layouts.cliente.app')

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

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Informações da Empresa</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Empresa Contratante*</label>
                    <select name="company_id" class="form-control" required>
                        <option value="">Selecione uma empresa</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Informações da Carga</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo de Carga*</label>
                        <select name="cargo_type" class="form-control" required>
                            <option value="">Selecione...</option>
                            <option value="Secos" {{ old('cargo_type') == 'Secos' ? 'selected' : '' }}>Secos</option>
                            <option value="Frios" {{ old('cargo_type') == 'Frios' ? 'selected' : '' }}>Frios</option>
                            <option value="Granel" {{ old('cargo_type') == 'Granel' ? 'selected' : '' }}>Granel</option>
                            <option value="Perigosos" {{ old('cargo_type') == 'Perigosos' ? 'selected' : '' }}>Perigosos</option>
                            <option value="Fragil" {{ old('cargo_type') == 'Fragil' ? 'selected' : '' }}>Fragil</option>
                            <option value="Outros" {{ old('cargo_type') == 'Outros' ? 'selected' : '' }}>Outros</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Peso (kg)*</label>
                        <input type="number" name="weight" class="form-control" step="0.01" value="{{ old('weight') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Dimensões (L x A x C em cm)*</label>
                        <input type="text" name="dimensions" class="form-control" placeholder="Ex: 120x80x60" value="{{ old('dimensions') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Volume (m³)</label>
                        <input type="number" name="volume" class="form-control" step="0.01" value="{{ old('volume') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição da Carga</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_fragile" class="form-check-input" id="fragileCheck" value="1" {{ old('is_fragile') ? 'checked' : '' }}>
                    <label class="form-check-label" for="fragileCheck">Carga frágil</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_hazardous" class="form-check-input" id="hazardousCheck" value="1" {{ old('is_hazardous') ? 'checked' : '' }}>
                    <label class="form-check-label" for="hazardousCheck">Material perigoso</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="requires_temperature_control" class="form-check-input" id="tempCheck" value="1" {{ old('requires_temperature_control') ? 'checked' : '' }}>
                    <label class="form-check-label" for="tempCheck">Controle de temperatura necessário</label>
                </div>

                <div id="temperatureFields" style="display: {{ old('requires_temperature_control') ? 'block' : 'none' }};">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6>Especificações de Temperatura</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Temperatura mínima (°C)</label>
                                    <input type="number" name="min_temperature" class="form-control" value="{{ old('min_temperature') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Temperatura máxima (°C)</label>
                                    <input type="number" name="max_temperature" class="form-control" value="{{ old('max_temperature') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Tolerância (±°C)</label>
                                    <input type="number" name="temperature_tolerance" class="form-control" value="{{ old('temperature_tolerance', 1) }}" min="0.1" step="0.1">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipo de Controle</label>
                                    <select name="temperature_control_type" class="form-control">
                                        <option value="refrigeration" {{ old('temperature_control_type') == 'refrigeration' ? 'selected' : '' }}>Refrigeração</option>
                                        <option value="freezing" {{ old('temperature_control_type') == 'freezing' ? 'selected' : '' }}>Congelamento</option>
                                        <option value="climate_controlled" {{ old('temperature_control_type') == 'climate_controlled' ? 'selected' : '' }}>Climatizado</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Unidade de Medida</label>
                                    <select name="temperature_unit" class="form-control">
                                        <option value="celsius" {{ old('temperature_unit') == 'celsius' ? 'selected' : '' }}>Celsius (°C)</option>
                                        <option value="fahrenheit" {{ old('temperature_unit') == 'fahrenheit' ? 'selected' : '' }}>Fahrenheit (°F)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Observações</label>
                                <textarea name="temperature_notes" class="form-control" rows="2">{{ old('temperature_notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
            <a href="{{ route('shipments.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Carga</button>
        </div>
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
            } else {
                temperatureFields.style.display = 'none';
            }
        }

        updateTemperatureFields();
        tempCheck.addEventListener('change', updateTemperatureFields);
    });
</script>
@endpush

@endsection