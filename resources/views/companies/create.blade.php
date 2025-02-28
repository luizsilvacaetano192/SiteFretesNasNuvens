@extends('layouts.app')

@section('title', 'Register Company')

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

<form action="{{ route('companies.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">Company Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">CNPJ</label>
        <input type="text" name="cnpj" id="cnpj" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="address">Endereço</label>
        <input id="address" name="address" type="text" class="form-control" required>
    </div>


    <button type="submit" class="btn btn-primary">Save</button>
</form>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initAutocomplete" async defer></script>

<!-- Adicionando máscaras de CNPJ e telefone -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        $('#cnpj').mask('00.000.000/0000-00');
        $('#phone').mask('(00) 00000-0000');
    });

    let autocomplete;

    function initAutocomplete() {
        const input = document.getElementById('address');
        autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.setFields(['address_components', 'geometry']);
        
        // Quando o usuário selecionar um endereço
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            
            if (!place.geometry) {
                // Caso não encontre um endereço válido
                console.log("Endereço inválido ou não encontrado");
                return;
            }
            
            // Você pode pegar as coordenadas e outros dados aqui se precisar
            console.log("Endereço selecionado:", place.formatted_address);
        });
    }
</script>

@endsection

<script>

   
</script>

