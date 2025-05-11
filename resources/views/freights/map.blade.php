@extends('layouts.app')

@section('title', 'Rota e Histórico do Frete')

@section('content')
<div class="container-fluid px-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-map-marked-alt me-2"></i>Rota do Frete #{{ $freight->id }}
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('freights.index') }}">Fretes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rota</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('freights.show', $freight->id) }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>Voltar para Frete
            </a>
            <button id="export-route" class="btn btn-primary">
                <i class="fas fa-file-pdf me-1"></i>Exportar Rota
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Coluna do Mapa -->
        <div class="col-lg-6">
            <!-- Card do Mapa -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-map me-2"></i>Mapa da Rota
                    </h6>
                    <div class="btn-group">
                        <button id="map-type-road" class="btn btn-sm btn-outline-secondary active">
                            <i class="fas fa-road"></i> Padrão
                        </button>
                        <button id="map-type-satellite" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-satellite"></i> Satélite
                        </button>
                        <button id="map-type-hybrid" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-layer-group"></i> Híbrido
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="map-container" style="position: relative;">
                        <!-- Controles do Mapa -->
                        <div id="map-controls" class="position-absolute top-0 end-0 mt-2 me-2" style="z-index: 1000;">
                            <div class="btn-group-vertical shadow-sm">
                                <button id="track-toggle" class="btn btn-sm btn-primary">
                                    <i class="fas fa-lock"></i> Travar Mapa
                                </button>
                                <button id="zoom-toggle" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search-plus"></i> Zoom
                                </button>
                                <button id="center-route" class="btn btn-sm btn-primary">
                                    <i class="fas fa-expand"></i> Ver Rota
                                </button>
                            </div>
                        </div>
                        
                        <!-- Informações de Localização -->
                        <div id="location-info" class="p-3 bg-light border-bottom">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>📍 Posição atual:</strong> 
                                    <span id="current-position">
                                        @php
                                            $lastLocation = $freight->history()
                                                ->orderBy('created_at', 'desc')
                                                ->first();
                                        @endphp
                                        {{ $lastLocation->address ?? 'Não disponível' }}
                                    </span>
                                    <span id="updating-indicator" class="d-none ms-2">
                                        <i class="fas fa-sync-alt fa-spin"></i> Atualizando...
                                    </span>
                                </div>
                                <div>
                                    <strong>🔄 Atualizado em:</strong> 
                                    <span id="last-update">
                                        @if($lastLocation && $lastLocation->created_at)
                                            {{ $lastLocation->created_at->format('d/m/Y H:i:s') }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mapa Google -->
                        <div id="map" style="height: 550px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna do Histórico -->
        <div class="col-lg-6">
            <!-- Card de Status -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Status do Frete
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-{{ $statusBadgeClass }} p-2">
                                <i class="fas fa-truck me-1"></i> {{ $freight->freightStatus->name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Informações</h6>
                                <p class="mb-1"><strong>Empresa:</strong> {{ $freight->company->name }}</p>
                                <p class="mb-1"><strong>Motorista:</strong> {{ $freight->driver->name ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Veículo:</strong> {{ $freight->truck_plate ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Datas</h6>
                                <p class="mb-1"><strong>Início:</strong> {{ $freight->created_at ? $freight->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                <p class="mb-1"><strong>Coleta:</strong> {{ $freight->pickup_date ? $freight->pickup_date->format('d/m/Y H:i') : 'N/A' }}</p>
                                <p class="mb-1"><strong>Entrega:</strong> {{ $freight->delivery_date ? $freight->delivery_date->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Histórico -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-history me-2"></i>Histórico de Localização
                    </h6>
                    <div>
                        <button id="refresh-history" class="btn btn-sm btn-primary">
                            <i class="fas fa-sync-alt me-1"></i> Atualizar
                        </button>
                    </div>
                </div>
                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="history-table" style="width: 100%;">
                            <thead class="thead-light sticky-top" style="top: 0;">
                                <tr>
                                    <th width="20%">Data/Hora</th>
                                    <th width="70%">Localização</th>
                                    <th width="10%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($freight->history()->orderBy('created_at', 'desc')->get() as $location)
                                <tr data-lat="{{ $location->latitude }}" data-lng="{{ $location->longitude }}">
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small>{{ $location->created_at ? $location->created_at->format('d/m/Y') : 'N/A' }}</small>
                                            <small>{{ $location->created_at ? $location->created_at->format('H:i:s') : 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 350px;" title="{{ $location->address ?? 'N/A' }}">
                                            {{ $location->address ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $location->status === 'em_transito' ? 'info' : ($location->status === 'entregue' ? 'success' : 'warning') }}">
                                            {{ $location->status ? ucfirst(str_replace('_', ' ', $location->status)) : 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">Nenhum registro de localização encontrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-2">
                    <small class="text-muted">Mostrando {{ $freight->history()->count() }} registros</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ... (código JavaScript anterior permanece igual, mas com verificações adicionais)

// Atualizar histórico via AJAX
function updateHistory() {
    $('#refresh-history').html('<i class="fas fa-spinner fa-spin me-1"></i> Atualizando');
    
    $.get('{{ route("freights.history", $freight->id) }}', function(data) {
        historyTable.clear();
        
        // Ordenar por created_at decrescente (mesmo critério do backend)
        data.sort((a, b) => {
            const dateA = a.created_at ? new Date(a.created_at) : 0;
            const dateB = b.created_at ? new Date(b.created_at) : 0;
            return dateB - dateA;
        });
        
        data.forEach(item => {
            const createdAt = item.created_at ? new Date(item.created_at) : null;
            
            historyTable.row.add([
                `<div class="d-flex flex-column">
                    <small>${createdAt ? createdAt.toLocaleDateString('pt-BR') : 'N/A'}</small>
                    <small>${createdAt ? createdAt.toLocaleTimeString('pt-BR') : 'N/A'}</small>
                </div>`,
                `<div class="text-truncate" style="max-width: 350px;" title="${item.address || 'N/A'}">
                    ${item.address || 'N/A'}
                </div>`,
                `<span class="badge bg-${item.status === 'em_transito' ? 'info' : (item.status === 'entregue' ? 'success' : 'warning')}">
                    ${item.status ? item.status.replace('_', ' ') : 'N/A'}
                </span>`
            ]).nodes().to$()
                .attr('data-lat', item.latitude || 0)
                .attr('data-lng', item.longitude || 0)
                .attr('title', item.address || 'N/A');
        });
        
        historyTable.draw();
        $('#refresh-history').html('<i class="fas fa-sync-alt me-1"></i> Atualizar');
        
        // Atualizar última posição com o primeiro item da lista ordenada
        if (data.length > 0) {
            const last = data[0];
            lastPosition = { 
                lat: last.latitude || 0, 
                lng: last.longitude || 0 
            };
            updateCurrentPosition(lastPosition, last.address || 'N/A');
            
            // Atualizar também o texto exibido
            const lastUpdate = last.created_at ? new Date(last.created_at) : null;
            $('#current-position').text(last.address || 'N/A');
            $('#last-update').text(
                lastUpdate ? 
                lastUpdate.toLocaleDateString('pt-BR') + ' ' + lastUpdate.toLocaleTimeString('pt-BR') : 
                'N/A'
            );
        }
    }).fail(() => {
        $('#refresh-history').html('<i class="fas fa-sync-alt me-1"></i> Atualizar');
        alert('Erro ao atualizar histórico');
    });
}
</script>
@endpush