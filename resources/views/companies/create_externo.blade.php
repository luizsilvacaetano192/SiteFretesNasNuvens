<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Empresa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
        .was-validated .form-control:invalid ~ .invalid-feedback,
        .was-validated .form-control:valid ~ .invalid-feedback {
            display: block;
        }
        .text-primary {
            color: #0d6efd !important;
        }
        .border-bottom {
            border-bottom: 1px solid #dee2e6 !important;
        }
        .input-group-text {
            min-width: 40px;
            justify-content: center;
        }
        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
        }
        .toggle-password {
            cursor: pointer;
        }
        .toggle-password:hover {
            background-color: #e9ecef;
        }
        .container {
            padding: 2rem 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-building me-2"></i>Cadastro de Empresa</h4>
                </div>

                <div class="card-body">
                    <div id="error-alert" class="alert alert-danger alert-dismissible fade show" style="display: none;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Erros encontrados:</strong>
                        <ul id="error-list" class="mb-0"></ul>
                    </div>

                    <form id="company-form" action="{{ route('companies.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2"><i class="fas fa-info-circle me-2"></i>Dados da Empresa</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Razão Social*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-signature"></i></span>
                                        <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}">
                                    </div>
                                    <div class="invalid-feedback">Por favor, informe a razão social.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="trading_name" class="form-label">Nome Fantasia</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        <input type="text" id="trading_name" name="trading_name" class="form-control" value="{{ old('trading_name') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-6">
                                    <label for="cnpj" class="form-label">CNPJ*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" id="cnpj" name="cnpj" class="form-control" required value="{{ old('cnpj') }}">
                                    </div>
                                    <div class="invalid-feedback">Por favor, informe um CNPJ válido.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="state_registration" class="form-label">Inscrição Estadual (IE)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                        <input type="text" id="state_registration" name="state_registration" class="form-control" value="{{ old('state_registration') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2"><i class="fas fa-address-book me-2"></i>Contatos</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="phone" class="form-label">Telefone Fixo*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" id="phone" name="phone" class="form-control" required value="{{ old('phone') }}">
                                    </div>
                                    <div class="invalid-feedback">Por favor, informe um telefone válido.</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="whatsapp" class="form-label">WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                        <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label">E-mail*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}">
                                    </div>
                                    <div class="invalid-feedback">Por favor, informe um e-mail válido.</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2"><i class="fas fa-map-marker-alt me-2"></i>Endereço</h5>
                            
                            <div class="mb-3">
                                <label for="autocomplete" class="form-label">Buscar Endereço*</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" id="autocomplete" class="form-control" placeholder="Digite o endereço...">
                                    <button class="btn btn-outline-secondary" type="button" id="clear-address">
                                        <i class="fas fa-times"></i> Limpar
                                    </button>
                                </div>
                                <small class="text-muted">Comece a digitar e selecione o endereço correto</small>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="address" class="form-label">Endereço*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-road"></i></span>
                                        <input type="text" id="address" name="address" class="form-control bg-light" readonly required value="{{ old('address') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="number" class="form-label">Número*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        <input type="text" id="number" name="number" class="form-control" required value="{{ old('number') }}">
                                    </div>
                                    <div class="invalid-feedback">Por favor, informe o número.</div>
                                </div>
                            </div>
                            
                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <label for="complement" class="form-label">Complemento</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                        <input type="text" id="complement" name="complement" class="form-control" value="{{ old('complement') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="neighborhood" class="form-label">Bairro*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                        <input type="text" id="neighborhood" name="neighborhood" class="form-control bg-light" readonly required value="{{ old('neighborhood') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="city" class="form-label">Cidade*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                        <input type="text" id="city" name="city" class="form-control bg-light" readonly required value="{{ old('city') }}">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label for="state" class="form-label">UF*</label>
                                    <div class="input-group">
                                        <input type="text" id="state" name="state" class="form-control bg-light text-uppercase" readonly required value="{{ old('state') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3 mt-2">
                                <div class="col-md-3">
                                    <label for="zip_code" class="form-label">CEP*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                        <input type="text" id="zip_code" name="zip_code" class="form-control bg-light" readonly required value="{{ old('zip_code') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2"><i class="fas fa-lock me-2"></i>Segurança</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Senha*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" id="password" name="password" class="form-control" required minlength="8">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">A senha deve ter pelo menos 8 caracteres.</div>
                                    <small class="text-muted">Mínimo de 8 caracteres</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirmar Senha*</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">As senhas devem coincidir.</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2"><i class="fas fa-info-circle me-2"></i>Informações Adicionais</h5>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição da Empresa</label>
                                <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="website" class="form-label">Site</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    <input type="url" id="website" name="website" class="form-control" placeholder="https://" value="{{ old('website') }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('companies.index') }}" class="btn btn-secondary px-4 py-2 me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-save me-2"></i>Cadastrar Empresa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery first, then Bootstrap, then outros -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<script>
// Global variable for autocomplete
let autocomplete;

$(document).ready(function(){
    // Apply masks
    $('#cnpj').mask('00.000.000/0000-00');
    $('#phone').mask('(00) 0000-0000');
    $('#whatsapp').mask('(00) 00000-0000');
    $('#zip_code').mask('00000-000');
    
    // Clear address button
    $('#clear-address').click(function() {
        $('#autocomplete').val('');
        $('#address').val('');
        $('#number').val('');
        $('#complement').val('');
        $('#neighborhood').val('');
        $('#city').val('');
        $('#state').val('');
        $('#zip_code').val('');
        $('#autocomplete').focus();
    });

    // Toggle password visibility
    $(document).on('click', '.toggle-password', function() {
        const input = $(this).closest('.input-group').find('input');
        const icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Password confirmation validation
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmation = $(this).val();
        
        if (password !== confirmation) {
            this.setCustomValidity("As senhas não coincidem");
        } else {
            this.setCustomValidity("");
        }
    });

    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                        
                        // Show error messages
                        const errors = [];
                        form.querySelectorAll(':invalid').forEach(function(element) {
                            errors.push(element.validationMessage);
                        });
                        
                        if (errors.length > 0) {
                            const errorList = $('#error-list');
                            errorList.empty();
                            errors.forEach(function(error) {
                                errorList.append(`<li>${error}</li>`);
                            });
                            $('#error-alert').show();
                        }
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })();
});

// Initialize Google Places Autocomplete
function initAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('autocomplete'),
        {
            types: ['address'],
            componentRestrictions: {country: 'br'},
            fields: ['address_components', 'formatted_address', 'geometry']
        }
    );

    autocomplete.addListener('place_changed', fillInAddress);
}

// Fill address fields when place is selected
function fillInAddress() {
    const place = autocomplete.getPlace();
    
    if (!place.geometry) {
        alert("Endereço não encontrado: '" + document.getElementById('autocomplete').value + "'");
        return;
    }

    // Fill complete address
    document.getElementById('address').value = place.formatted_address;

    // Fill other fields automatically
    for (const component of place.address_components) {
        const componentType = component.types[0];

        switch (componentType) {
            case "street_number":
                document.getElementById('number').value = component.long_name;
                break;
            case "route":
                // Already included in formatted_address
                break;
            case "sublocality_level_1":
            case "neighborhood":
                document.getElementById('neighborhood').value = component.long_name;
                break;
            case "administrative_area_level_2":
                document.getElementById('city').value = component.long_name;
                break;
            case "administrative_area_level_1":
                document.getElementById('state').value = component.short_name;
                break;
            case "postal_code":
                document.getElementById('zip_code').value = component.long_name;
                break;
        }
    }
    
    // Focus on number field after autocomplete
    document.getElementById('number').focus();
}

// Make function available globally
window.initAutocomplete = initAutocomplete;
</script>

<!-- Load Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB_yr1wIc9h3Nhabwg4TXxEIbdc1ivQ9kI&libraries=places&callback=initAutocomplete" async defer></script>
</body>
</html>