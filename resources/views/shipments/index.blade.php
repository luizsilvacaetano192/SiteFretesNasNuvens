@extends('layouts.app')

@section('title', 'Shipments List')

@section('content')
    <div class="container">
        <h1>Lista de Cargas</h1>

        <div class="legend-container">
            <span class="legend-item">
                <span class="legend-box red"></span> Carga sem frete
            </span>
            <span class="legend-item">
                <span class="legend-box yellow"></span> Frete solicitado
            </span>
        </div>

        <a href="{{ route('shipments.create') }}" class="btn btn-primary mt-3">Nova Carga</a>

        <table id="shipments-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Empresas</th>
                    <th>Peso</th>
                    <th>Tipo de Carga</th>
                    <th>Dimensão</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shipments as $shipment)
                    <tr>
                        <td>{{ $shipment->id }}</td>
                        <td>{{ $shipment->company->name }}</td>
                        <td>{{ $shipment->weight }}</td>
                        <td>{{ $shipment->cargo_type }}</td>
                        <td>{{ $shipment->dimensions }}</td>
                        <th>
                        <a href="{{ route('shipments.requestFreight', $shipment->id) }}" class="btn btn-success">Solicitar Frete</a>
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>

        $(document).ready(function() {
            // Inicializar o DataTable
            $('#shipments-table').DataTable();
        });
    </script>
@endpush

@section('styles')
    <style>
        .legend-container {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .legend-item {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .legend-box {
            width: 15px;
            height: 15px;
            border-radius: 3px;
            display: inline-block;
            margin-right: 5px;
        }

        .red {
            background-color: red;
        }

        .yellow {
            background-color: yellow;
        }

        /* Estilo para o dropdown de sugestões do Autocomplete */
        .pac-container {
            z-index: 1051 !important; /* Garante que o dropdown apareça acima do modal */
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        }

        .pac-item {
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
        }

        .pac-item:hover {
            background-color: #f5f5f5;
        }
    </style>
@endsection