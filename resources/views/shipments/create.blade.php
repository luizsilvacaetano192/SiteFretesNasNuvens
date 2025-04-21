<form action="{{ route('shipments.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Seção Empresa -->
    <div class="mb-3">
        <label class="form-label">Empresa*</label>
        <select name="company_id" class="form-control" required>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Seção Carga -->
    <div class="card mb-3">
        <div class="card-header">Informações da Carga</div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Peso (kg)*</label>
                <input type="number" step="0.01" name="weight" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Carga*</label>
                <input type="text" name="cargo_type" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição Detalhada</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Dimensões (LxAxC em cm)*</label>
                <input type="text" name="dimensions" class="form-control" placeholder="Ex: 120x80x60" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Número de Volumes*</label>
                <input type="number" name="number_of_packages" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Valor da Carga (R$)</label>
                <input type="number" step="0.01" name="cargo_value" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Características Especiais</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_dangerous" id="is_dangerous">
                    <label class="form-check-label" for="is_dangerous">Carga Perigosa</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_refrigerated" id="is_refrigerated">
                    <label class="form-check-label" for="is_refrigerated">Carga Refrigerada</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_fragile" id="is_fragile">
                    <label class="form-check-label" for="is_fragile">Carga Frágil</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção Transporte -->
    <div class="card mb-3">
        <div class="card-header">Informações de Transporte</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Origem*</label>
                    <input type="text" name="origin" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Destino*</label>
                    <input type="text" name="destination" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Data/Hora de Coleta*</label>
                    <input type="datetime-local" name="pickup_time" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Data/Hora de Entrega*</label>
                    <input type="datetime-local" name="delivery_time" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo de Veículo Requerido</label>
                <select name="vehicle_type" class="form-control">
                    <option value="">Qualquer</option>
                    <option value="truck">Caminhão Baú</option>
                    <option value="truck_with_platform">Caminhão Plataforma</option>
                    <option value="van">Van</option>
                    <option value="refrigerated_truck">Caminhão Frigorífico</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Seção Documentação -->
    <div class="card mb-3">
        <div class="card-header">Documentação</div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Número do Pedido/NF</label>
                <input type="text" name="order_number" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Documentos Anexos</label>
                <input type="file" name="attachments[]" class="form-control" multiple>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Cadastrar</button>
</form>