@extends('layouts.app')

@section('title', 'Dashboard - Gestão de Fretes')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">📊 Dashboard</h2>

    <div class="row">
        <!-- Card de Cargas -->
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">🚚 Total de Cargas</h5>
                    <p class="card-text fs-2">{{ $totalShipments }}</p>
                    <a href="{{ route('shipments.index') }}" class="btn btn-light">Ver Cargas</a>
                </div>
            </div>
        </div>

        <!-- Card de Fretes -->
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">📦 Total de Fretes</h5>
                    <p class="card-text fs-2">{{ $totalFreights }}</p>
                    <a href="{{ route('freights.index') }}" class="btn btn-light">Ver Fretes</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">📈 Status das Cargas</h5>
                    <canvas id="shipmentsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">📊 Status dos Fretes</h5>
                    <canvas id="freightsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const shipmentsCtx = document.getElementById('shipmentsChart').getContext('2d');
    const freightsCtx = document.getElementById('freightsChart').getContext('2d');

    new Chart(shipmentsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pendentes', 'Em Transporte', 'Entregues'],
            datasets: [{
                data: [{{ $pendingShipments }}, {{ $inTransitShipments }}, {{ $deliveredShipments }}],
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745']
            }]
        }
    });

    new Chart(freightsCtx, {
        type: 'bar',
        data: {
            labels: ['Pendentes', 'Em Transporte', 'Entregues'],
            datasets: [{
                label: 'Status dos Fretes',
                data: [{{ $pendingFreights }}, {{ $inTransitFreights }}, {{ $deliveredFreights }}],
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745']
            }]
        }
    });
</script>
@endsection
