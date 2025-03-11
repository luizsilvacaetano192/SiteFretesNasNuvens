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

    <div class="mb-3">
        <label class="form-label">Company</label>
        <select name="company_id" class="form-control" required>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Weight (kg)</label>
        <input type="text" name="weight" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Cargo Type</label>
        <input type="text" name="cargo_type" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Dimensions (e.g., 2x3x4)</label>
        <input type="text" name="dimensions" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
</form>

@endsection
