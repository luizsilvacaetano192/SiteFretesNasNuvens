@extends('layouts.app')

@section('title', 'Create Shipment')

@section('content')

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
        <div class="card-header">
            <h5>Informações da Empresa</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Empresa Contratante*</label>
                <select name="company_id" class="form-control" required>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Informações da Carga</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de Carga*</label>
                    <select name="cargo_type" class="form-control" required>
                        <option value="">Selecione...</option>
                        <option value="Secos">Secos</option>
                        <option value="Frios">Frios</option>
                        <option value="Granel">Granel</option>
                        <option value="Perigosos">Perigosos</option>
                        <option value="Fragil">Fragil</option>
                        <option value="Outros">Outros</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Peso (kg)*</label>
                    <input type="number" name="weight" class="form-control" step="0.01" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Dimensões (L x A x C em cm)*</label>
                    <input type="text" name="dimensions" class="form-control" placeholder="Ex: 120x80x60" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Volume (m³)</label>
                    <input type="number" name="volume" class="form-control" step="0.01">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição da Carga</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3 form-check">
                <input type="hidden" name="is_fragile" value="0">
                <input type="checkbox" name="is_fragile" class="form-check-input" id="fragileCheck" value="1">
                <label class="form-check-label" for="fragileCheck">Carga frágil</label>
            </div>

            <div class="mb-3 form-check">
                <input type="hidden" name="is_hazardous" value="0">
                <input type="checkbox" name="is_hazardous" class="form-check-input" id="hazardousCheck" value="1">
                <label class="form-check-label" for="hazardousCheck">Material perigoso</label>
            </div>

            <div class="mb-3 form-check">
                <input type="hidden" name="requires_temperature_control" value="0">
                <input type="checkbox" name="requires_temperature_control" class="form-check-input" id="tempCheck" value="1">
                <label class="form-check-label" for="tempCheck">Controle de temperatura necessário</label>
            </div>

            <div id="temperatureFields" style="display: none;">
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6>Especificações de Temperatura</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Temperatura mínima (°C)*</label>
                                <input type="number" name="min_temperature" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Temperatura máxima (°C)*</label>
                                <input type="number" name="max_temperature" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tolerância (±°C)</label>
                                <input type="number" name="temperature_tolerance" class="form-control" value="1" min="0.1" step="0.1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipo de Controle</label>
                                <select name="temperature_control_type" class="form-control">
                                    <option value="refrigeration">Refrigeração</option>
                                    <option value="freezing">Congelamento</option>
                                    <option value="climate_controlled">Climatizado</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Unidade de Medida</label>
                                <select name="temperature_unit" class="form-control">
                                    <option value="celsius">Celsius (°C)</option>
                                    <option value="fahrenheit">Fahrenheit (°F)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea name="temperature_notes" class="form-control" rows="2" placeholder="Informações adicionais sobre o controle de temperatura"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" class="btn btn-primary me-md-2">Salvar Carga</button>
        <button type="button" class="btn btn-secondary">Cancelar</button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const tempCheck = document.getElementById('tempCheck');
        const temperatureFields = document.getElementById('temperatureFields');

        // Função para atualizar a exibição dos campos de temperatura
        function updateTemperatureFields() {
            if(tempCheck.checked) {
                temperatureFields.style.display = 'block';
                // Torna os campos obrigatórios
                document.querySelectorAll('[name="min_temperature"], [name="max_temperature"]').forEach(field => {
                    field.required = true;
                });
            } else {
                temperatureFields.style.display = 'none';
                // Remove a obrigatoriedade
                document.querySelectorAll('[name="min_temperature"], [name="max_temperature"]').forEach(field => {
                    field.required = false;
                });
            }
        }

        // Atualiza ao carregar a página (caso de edição)
        updateTemperatureFields();
        
        // Atualiza quando o checkbox é alterado
        tempCheck.addEventListener('change', updateTemperatureFields);
    });
</script>
@endpush

@endsection