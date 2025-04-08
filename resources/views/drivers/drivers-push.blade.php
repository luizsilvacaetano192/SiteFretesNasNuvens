<form id="pushForm">
    @csrf

    <div class="mb-3">
        <input type="text" name="title" id="title" class="form-control" placeholder="Título da mensagem" required>
    </div>

    <div class="mb-3">
        <select name="screen" id="screen" class="form-control" required>
            <option value="">Abrir qual tela?</option>
            <option value="Menu">Menu - Tela inicial</option>
            <option value="ListScreen">ListScreen - Tela de lista de carga</option>
            <option value="SaldoScreen">SaldoScreen - Tela de saldos</option>
            <option value="PerfilScreen">PerfilScreen - Tela de Perfil</option>
            <option value="MeusFretesScreen">MeusFretesScreen - Tela de Meus Fretes</option>
        </select>
    </div>

    <div class="mb-3">
        <textarea name="message" id="message" class="form-control" rows="3" placeholder="Escreva a sua mensagem..." required></textarea>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Enviar</button>
    </div>

    {{-- 🔽 Filtros movidos para baixo do botão Enviar --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <select id="filterCidade" class="form-control">
                <option value="">Filtrar por cidade</option>
                @foreach ($cidades as $cidade)
                    <option value="{{ $cidade }}">{{ $cidade }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <select id="filterEstado" class="form-control">
                <option value="">Filtrar por estado</option>
                @foreach ($estados as $estado)
                    <option value="{{ $estado }}">{{ $estado }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <table id="driversTable" class="table table-bordered">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>Nome</th>
                <th>Endereço</th>
                <th>Token Push</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($drivers as $driver)
            <tr>
                <td><input type="checkbox" name="drivers[]" value="{{ $driver->id }}" class="driver-checkbox"></td>
                <td>{{ $driver->name }}</td>
                <td class="address-cell">{{ $driver->address }}</td>
                <td>
                    <div class="token-wrapper" id="token-{{ $driver->id }}">
                        <div class="token-text">{{ $driver->token_push }}</div>
                        <button type="button" class="toggle-token" onclick="toggleToken({{ $driver->id }})">Ver mais</button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>
