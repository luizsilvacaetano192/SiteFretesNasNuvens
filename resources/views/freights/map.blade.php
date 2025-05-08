@extends('layouts.app')

@section('title', 'Detalhes do Frete')

@section('content')
<div class="container-fluid px-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-truck-moving me-2"></i>Detalhes do Frete #{{ $freight->id }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('freights.index') }}">Fretes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('freights.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Voltar para Fretes
            </a>
            <a href="#" class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Imprimir
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Coluna Esquerda -->
        <div class="col-lg-8">
            <!-- Card de Histórico -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Histórico de Localizações
                    </h6>
                    <button id="refresh-history" class="btn btn-sm btn-primary">
                        <i class="fas fa-sync-alt me-1"></i>Atualizar
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="history-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Data</th>
                                    <th>Hora</th>
                                    <th>Endereço</th>
                                    <th>Status</th>
                                    <th>Lat/Long</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($freight->history()->orderBy('date', 'desc')->get() as $location)
                                <tr>
                                    <td data-order="{{ $location->date }}">
                                        {{ \Carbon\Carbon::parse($location->date)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($location->date)->format('H:i:s') }}
                                    </td>
                                    <td>{{ $location->address }}</td>
                                    <td>
                                        <span class="badge bg-{{ $location->status === 'em_transito' ? 'info' : ($location->status === 'entregue' ? 'success' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $location->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $location->latitude }}, {{ $location->longitude }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">Nenhum registro de localização encontrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita -->
        <div class="col-lg-4">
            <!-- Seus outros cards... -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css"/>
<style>
    /* Estilos para o DataTable */
    #history-table_wrapper {
        padding: 15px;
    }
    
    #history-table thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fc;
        z-index: 10;
        white-space: nowrap;
    }
    
    #history-table .dataTables_filter input {
        border: 1px solid #d1d3e2;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
        margin-left: 0.5rem;
    }
    
    #history-table .dataTables_length select {
        border: 1px solid #d1d3e2;
        border-radius: 0.375rem;
        padding: 0.25rem 0.5rem;
    }
    
    #history-table .dataTables_info,
    #history-table .dataTables_paginate {
        padding: 10px 0;
    }
    
    #history-table .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    #history-table .page-link {
        color: #4e73df;
    }
    
    /* Botão de atualização */
    #refresh-history {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        #history-table_wrapper .dataTables_length,
        #history-table_wrapper .dataTables_filter {
            text-align: left;
            margin-bottom: 0.5rem;
        }
        
        #history-table th, #history-table td {
            white-space: normal;
            font-size: 0.875rem;
        }
    }
</style>
@endpush

@push('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json"></script>

<script>
    $(document).ready(function() {
        // Inicialização do DataTable
        var historyTable = $('#history-table').DataTable({
            dom: '<"top"Bf>rt<"bottom"lip><"clear">',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                }
            ],
            order: [[0, 'desc'], [1, 'desc']], // Ordenação decrescente por data e hora
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
            responsive: true,
            stateSave: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
            },
            columnDefs: [
                { 
                    type: 'date-eu', 
                    targets: 0,
                    render: function(data, type, row) {
                        if (type === 'sort') {
                            return data;
                        }
                        return row[0]; // Exibe o valor original para outros tipos
                    }
                },
                { orderable: false, targets: [4] }, // Desativa ordenação para lat/long
                { visible: false, targets: [4] }   // Oculta a coluna de lat/long
            ],
            initComplete: function() {
                // Adiciona classe aos botões de exportação
                $('.dt-buttons button').removeClass('dt-button');
            }
        });

        // Função para atualizar o histórico
        function updateHistory() {
            $.ajax({
                url: '{{ route("freights.history", $freight->id) }}',
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('#refresh-history').html('<i class="fas fa-spinner fa-spin me-1"></i> Carregando...');
                },
                success: function(data) {
                    historyTable.clear();
                    
                    data.forEach(function(item) {
                        var date = new Date(item.date);
                        var formattedDate = date.toLocaleDateString('pt-BR');
                        var formattedTime = date.toLocaleTimeString('pt-BR');
                        
                        historyTable.row.add([
                            item.date, // Para ordenação
                            formattedTime,
                            item.address,
                            '<span class="badge bg-' + 
                                (item.status === 'em_transito' ? 'info' : 
                                (item.status === 'entregue' ? 'success' : 'warning')) + 
                            '">' + item.status.replace('_', ' ') + '</span>',
                            item.latitude + ', ' + item.longitude
                        ]);
                    });
                    
                    historyTable.draw();
                    $('#refresh-history').html('<i class="fas fa-sync-alt me-1"></i> Atualizar');
                    
                    // Mantém a ordenação decrescente
                    historyTable.order([0, 'desc'], [1, 'desc']).draw();
                },
                error: function(xhr) {
                    console.error('Erro ao carregar histórico:', xhr.responseText);
                    $('#refresh-history').html('<i class="fas fa-sync-alt me-1"></i> Atualizar');
                    alert('Erro ao carregar histórico. Tente novamente.');
                }
            });
        }

        // Evento de clique no botão de atualização
        $('#refresh-history').click(function(e) {
            e.preventDefault();
            updateHistory();
        });

        // Atualização automática a cada 1 minuto
        setInterval(updateHistory, 60000);
    });
</script>
@endpush