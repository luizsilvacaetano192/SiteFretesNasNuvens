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
                                               value="{{ $settings->cloud_percentage ?? old('cloud_percentage', 10) }}" required>
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
                                               value="{{ $settings->advance_percentage ?? old('advance_percentage', 30) }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Percentual do frete que o motorista recebe antecipado</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="minimum_freight_value" class="form-label">
                                        <i class="fas fa-dollar-sign me-1"></i> Valor Mínimo do Frete
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" id="minimum_freight_value" 
                                               name="minimum_freight_value" step="0.01" min="0"
                                               value="{{ $settings->minimum_freight_value ?? old('minimum_freight_value', 150.00) }}" required>
                                    </div>
                                    <small class="text-muted">Valor mínimo para qualquer frete</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="weight_surcharge_3000" class="form-label">
                                        <i class="fas fa-weight me-1"></i> Acréscimo 3-5 Ton
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="weight_surcharge_3000" 
                                               name="weight_surcharge_3000" step="0.01" min="0" max="100"
                                               value="{{ $settings->weight_surcharge_3000 ?? old('weight_surcharge_3000', 10) }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Acréscimo para cargas entre 3-5 toneladas</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="weight_surcharge_5000" class="form-label">
                                        <i class="fas fa-weight-hanging me-1"></i> Acréscimo +5 Ton
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="weight_surcharge_5000" 
                                               name="weight_surcharge_5000" step="0.01" min="0" max="100"
                                               value="{{ $settings->weight_surcharge_5000 ?? old('weight_surcharge_5000', 15) }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Acréscimo para cargas acima de 5 toneladas</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fragile_surcharge" class="form-label">
                                        <i class="fas fa-glass-cheers me-1"></i> Acréscimo Frágil
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="fragile_surcharge" 
                                               name="fragile_surcharge" step="0.01" min="0" max="100"
                                               value="{{ $settings->fragile_surcharge ?? old('fragile_surcharge', 20) }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Acréscimo para cargas frágeis</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="hazardous_surcharge" class="form-label">
                                        <i class="fas fa-radiation me-1"></i> Acréscimo Perigoso
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="hazardous_surcharge" 
                                               name="hazardous_surcharge" step="0.01" min="0" max="100"
                                               value="{{ $settings->hazardous_surcharge ?? old('hazardous_surcharge', 30) }}" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="text-muted">Acréscimo para cargas perigosas</small>
                                </div>
                            </div>
                        </div>

                        <div class="divider mb-4">
                            <div class="divider-text">
                                <i class="fas fa-truck-moving"></i> Valores por KM Sugeridos
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
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
                                                       value="{{ $settings->small_truck_price ?? old('small_truck_price', 2.50) }}" required>
                                            </div>
                                            <small class="text-muted">Ex: Veículos até 3.5 toneladas</small>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="small_refrigerated_rate" class="form-label">Refrigerado</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="small_refrigerated_rate" 
                                                       name="small_refrigerated_rate" step="0.01" min="0"
                                                       value="{{ $settings->small_refrigerated_rate ?? old('small_refrigerated_rate', 3.00) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="small_tanker_rate" class="form-label">Tanque</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="small_tanker_rate" 
                                                       name="small_tanker_rate" step="0.01" min="0"
                                                       value="{{ $settings->small_tanker_rate ?? old('small_tanker_rate', 3.20) }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
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
                                                       value="{{ $settings->medium_truck_price ?? old('medium_truck_price', 3.20) }}" required>
                                            </div>
                                            <small class="text-muted">Ex: Veículos de 3.5 a 8 toneladas</small>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="medium_refrigerated_rate" class="form-label">Refrigerado</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="medium_refrigerated_rate" 
                                                       name="medium_refrigerated_rate" step="0.01" min="0"
                                                       value="{{ $settings->medium_refrigerated_rate ?? old('medium_refrigerated_rate', 3.70) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="medium_tanker_rate" class="form-label">Tanque</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="medium_tanker_rate" 
                                                       name="medium_tanker_rate" step="0.01" min="0"
                                                       value="{{ $settings->medium_tanker_rate ?? old('medium_tanker_rate', 4.00) }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
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
                                                       value="{{ $settings->large_truck_price ?? old('large_truck_price', 4.50) }}" required>
                                            </div>
                                            <small class="text-muted">Ex: Veículos acima de 8 toneladas</small>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="large_refrigerated_rate" class="form-label">Refrigerado</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="large_refrigerated_rate" 
                                                       name="large_refrigerated_rate" step="0.01" min="0"
                                                       value="{{ $settings->large_refrigerated_rate ?? old('large_refrigerated_rate', 5.20) }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="large_tanker_rate" class="form-label">Tanque</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="large_tanker_rate" 
                                                       name="large_tanker_rate" step="0.01" min="0"
                                                       value="{{ $settings->large_tanker_rate ?? old('large_tanker_rate', 5.50) }}" required>
                                            </div>
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
                            <li>Acréscimos são aplicados sobre o valor base do frete</li>
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Configuração do Toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 5000,
        extendedTimeOut: 2000,
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        tapToDismiss: false
    };

    // Validação de percentuais
    $('input[type="number"]').on('blur', function() {
        if ($(this).attr('id').includes('percentage') || $(this).attr('id').includes('surcharge')) {
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
                // Percentuais
                $('#cloud_percentage').val(10);
                $('#advance_percentage').val(30);
                
                // Valores por KM
                $('#small_truck_price').val(2.50);
                $('#medium_truck_price').val(3.20);
                $('#large_truck_price').val(4.50);
                
                // Veículos especiais
                $('#small_refrigerated_rate').val(3.00);
                $('#medium_refrigerated_rate').val(3.70);
                $('#large_refrigerated_rate').val(5.20);
                $('#small_tanker_rate').val(3.20);
                $('#medium_tanker_rate').val(4.00);
                $('#large_tanker_rate').val(5.50);
                
                // Outros valores
                $('#minimum_freight_value').val(150.00);
                $('#weight_surcharge_3000').val(10);
                $('#weight_surcharge_5000').val(15);
                $('#fragile_surcharge').val(20);
                $('#hazardous_surcharge').val(30);
                
                toastr.success('Valores redefinidos para os padrões do sistema');
            }
        });
    });
    
    // Envio do formulário
    $('#settings-form').on('submit', function(e) {
        e.preventDefault();
        
        const btn = $('#save-btn');
        const originalText = btn.html();
        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Salvando...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Configurações salvas com sucesso!');
                } else {
                    toastr.error(response.message || 'Erro ao salvar configurações');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Erro ao salvar configurações';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                toastr.error(errorMessage);
            },
            complete: function() {
                btn.html(originalText);
                btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endpush