@extends('layouts.app')

@section('title', 'Saldo do Motorista')

@section('content')
<div class="container-fluid px-4">
    <!-- Verificação de existência do driver -->
    @isset($driver)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-wallet me-2"></i>Saldo do Motorista: {{ $driver->name ?? 'N/A' }}
                    </h3>
                    <div>
                        <span class="badge bg-light text-dark fs-6">
                            <i class="fas fa-id-card me-1"></i> {{ $account->asaas_identifier ?? 'N/A' }}
                        </span>
                    </div>
                </div>
                
                <!-- Verificação de existência da conta -->
                @isset($account)
                <div class="card-body">
                    <div class="row">
                        <!-- Cards de Saldo (conteúdo mantido igual) -->
                        ...
                    </div>
                </div>
                @else
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i> Conta não encontrada para este motorista
                    </div>
                </div>
                @endisset
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>Histórico de Transferências
                    </h4>
                </div>
                
                <!-- Verificação de existência de transferências -->
                @isset($transfers)
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="transfersTable" class="table table-hover table-bordered" style="width:100%">
                            <!-- Cabeçalho da tabela -->
                            <thead class="table-dark">
                                <tr>
                                    <th width="120">Data</th>
                                    <th>Tipo</th>
                                    <th width="150">Valor</th>
                                    <th>Descrição</th>
                                    <th width="120">Frete</th>
                                    <th>Cliente</th>
                                    <th width="150">ID Transferência</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transfers as $transfer)
                                <!-- Conteúdo das linhas da tabela -->
                                ...
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle me-2"></i> Nenhuma transferência encontrada
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i> Dados de transferências não disponíveis
                    </div>
                </div>
                @endisset
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i> Motorista não encontrado ou não especificado
            </div>
        </div>
    </div>
    @endisset
</div>
@endsection