@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-cog me-2"></i>Configurações do Sistema
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Configurações</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-percentage me-2"></i>Parâmetros de Cálculo
                    </h5>
                </div>
                <div class="card-body">
                    <form id="settings-form" method="POST" action="{{ route('settings.save') }}">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cloud_percentage" class="form-label">
                                        <i class="fas fa-cloud me-1"></i> Percentual Fretes nas Nuvens
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="cloud_percentage" 
                                               name="cloud_percentage" step="0.01" min="0" max="100"
                                               value="{{ $settings->cloud_percentage ?? '' }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Percentual sobre o valor total do frete que fica com a plataforma</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="advance_percentage" class="form-label">
                                        <i class="fas fa-hand-holding-usd me-1"></i> Antecipação para Motorista
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="advance_percentage" 
                                               name="advance_percentage" step="0.01" min="0" max="100"
                                               value="{{ $settings->advance_percentage ?? '' }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Percentual do frete que o motorista recebe antecipado</small>
                                </div>
                            </div>
                        </div>

                        <div class="divider mb-4">
                            <div class="divider-text">
                                <i class="fas fa-truck-moving"></i> Valores por KM Sugeridos
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card border-left-primary h-100">
                                    <div class="card-header bg-primary bg-opacity-10">
                                        <h6 class="mb-0 text-primary">
                                            <i class="fas fa-truck-pickup me-1"></i> Caminhão Pequeno
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="small_truck_price" class="form-label">Valor por KM</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="small_truck_price" 
                                                       name="small_truck_price" step="0.01" min="0"
                                                       value="{{ $settings->small_truck_price ?? '' }}" required>
                                            </div>
                                            <small class="text-muted">Ex: Veículos até 3.5 toneladas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-left-warning h-100">
                                    <div class="card-header bg-warning bg-opacity-10">
                                        <h6 class="mb-0 text-warning">
                                            <i class="fas fa-truck me-1"></i> Caminhão Médio
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="medium_truck_price" class="form-label">Valor por KM</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="medium_truck_price" 
                                                       name="medium_truck_price" step="0.01" min="0"
                                                       value="{{ $settings->medium_truck_price ?? '' }}" required>
                                            </div>
                                            <small class="text-muted">Ex: Veículos de 3.5 a 8 toneladas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-left-danger h-100">
                                    <div class="card-header bg-danger bg-opacity-10">
                                        <h6 class="mb-0 text-danger">
                                            <i class="fas fa-truck-moving me-1"></i> Caminhão Grande
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="large_truck_price" class="form-label">Valor por KM</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="large_truck_price" 
                                                       name="large_truck_price" step="0.01" min="0"
                                                       value="{{ $settings->large_truck_price ?? '' }}" required>
                                            </div>
                                            <small class="text-muted">Ex: Veículos acima de 8 toneladas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" id="reset-btn" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-undo me-1"></i> Redefinir
                            </button>
                            <button type="submit" id="save-btn" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Informações
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Como funciona?</h6>
                        <hr>
                        <ul class="mb-0 ps-3">
                            <li>Os valores configurados aqui serão usados para cálculos automáticos no sistema</li>
                            <li>As sugestões de valores por KM servem como referência inicial para novos fretes</li>
                            <li>Os percentuais afetam diretamente os valores repassados aos motoristas</li>
                        </ul>
                    </div>
                    <div class="alert alert-warning mt-3">
                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Atenção!</h6>
                        <hr>
                        <p class="mb-0">Alterações nestes parâmetros afetam todos os novos fretes cadastrados. Fretes já existentes não serão modificados.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.divider {
    display: flex;
    align-items: center;
    margin: 1rem 0;
    color: #6c757d;
}

.divider::before,
.divider::after {
    flex: 1;
    content: "";
    padding: 1px;
    background-color: #e9ecef;
    margin: 5px;
}

.divider-text {
    padding: 0 1rem;
    font-weight: 500;
    font-size: 0.85rem;
    text-transform: uppercase;
    color: #6c757d;
}

.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    transition: all 0.3s;
}

.card:hover {
    box-shadow: 0 0.5rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.form-label {
    font-weight: 500;
    color: #5a5c69;
}

.input-group-text {
    background-color: #f8f9fc;
}

.alert ul {
    margin-bottom: 0;
    padding-left: 1rem;
}

.alert ul li {
    margin-bottom: 0.25rem;
}

#save-btn {
    min-width: 180px;
}

@media (max-width: 768px) {
    .divider {
        margin: 0.5rem 0;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .col-md-4 {
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Formata os valores percentuais
    $('input[type="number"]').on('blur', function() {
        if ($(this).attr('id').includes('percentage')) {
            let value = parseFloat($(this).val());
            if (value > 100) {
                $(this).val(100);
                toastr.warning('O percentual máximo é 100%');
            }
        }
    });

    // Botão de redefinir
    $('#reset-btn').click(function() {
        Swal.fire({
            title: 'Redefinir configurações?',
            text: "Isso restaurará os valores padrão do sistema",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, redefinir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Aqui você pode implementar a lógica para resetar os valores
                // Por enquanto apenas um exemplo:
                $('#cloud_percentage').val(10);
                $('#advance_percentage').val(30);
                $('#small_truck_price').val(2.50);
                $('#medium_truck_price').val(3.20);
                $('#large_truck_price').val(4.50);
                
                toastr.success('Valores redefinidos para os padrões do sistema');
            }
        });
    });

    // Envio do formulário
    $('#settings-form').submit(function(e) {
        e.preventDefault();
        
        const btn = $('#save-btn');
        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Salvando...');

        // Simulação de envio AJAX
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                toastr.success('Configurações salvas com sucesso!');
                btn.html('<i class="fas fa-save me-1"></i> Salvar Configurações');
                btn.prop('disabled', false);
            },
            error: function(xhr) {
                toastr.error('Erro ao salvar configurações: ' + xhr.responseText);
                btn.html('<i class="fas fa-save me-1"></i> Salvar Configurações');
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endpush