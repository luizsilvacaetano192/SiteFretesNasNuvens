@extends('layouts.app')

@section('title', 'Register Driver')

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

<form action="{{ route('drivers.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label class="form-label">Driver Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">CPF</label>
        <input type="text" name="cpf" id="cpf" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">License Number</label>
        <input type="text" name="license_number" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">License Category</label>
        <input type="text" name="license_category" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
</form>

<!-- Adicionando máscaras de CPF e telefone -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        $('#cpf').mask('000.000.000-00');
        $('#phone').mask('(00) 00000-0000');
    });
</script>

@endsection
