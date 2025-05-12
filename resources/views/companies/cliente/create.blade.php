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
        .form-logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-container {
            display: flex;
            height: 100vh;
        }
        .form-column {
            flex: 1;
            padding: 1rem;
            background: #f8f9fa;
            overflow-y: auto;
        }
        .image-column {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            justify-content: flex-start;
            padding-top: 1rem;
        }
        .logo-container {
            padding: 1rem;
            text-align: center;
            z-index: 2;
        }
        .logo {
            max-width: 120px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }
        .image-content {
            padding: 1rem 2rem;
            color: white;
            z-index: 1;
        }
        .image-column h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        .image-column p {
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        .benefits-list {
            margin-top: 1rem;
        }
        .benefits-list li {
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
       
        .card-header {
           background-color: #001a33; /* Azul muito escuro */
            border-bottom: 1px solid #000d1a; /* Borda mais escura para contraste */
            color: white; /* Texto branco para contraste */
            padding: 1rem 1.5rem;
            border-radius: 15px 15px 0 0 !important;
        }

        .card-header h4 {
            color: white !important; /* Garante que o texto fique branco */
            margin-bottom: 0;
        }

        .form-logo {
            max-height: 250px; /* Ajuste conforme necessário */
            width: auto;
             
        }

        @media (max-width: 768px) {
            .form-logo {
                max-height: 250px; /* Tamanho menor para mobile */
            }
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .input-group-text {
            min-width: 45px;
            justify-content: center;
            background-color: #e9ecef;
        }
        .toggle-password {
            cursor: pointer;
            transition: all 0.3s;
        }
        .toggle-password:hover {
            background-color: #e9ecef;
        }
        .section-title {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            position: relative;
            color: #0d6efd;
        }
        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: #0d6efd;
        }
        @media (max-width: 992px) {
            .main-container {
                flex-direction: column;
                height: auto;
            }
            .image-column {
                order: 2;
                min-height: 400px;
            }
            .form-column {
                order: 1;
            }
            .logo-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="main-container">
   
    <div class="form-column">
        
        <div class="form-container">
           <div class="card shadow-lg">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-building me-2"></i>Cadastro de Empresa</h4>
                    <div class="form-logo-container">
                        <img src="{{ asset('images/logo_fretes2.png') }}" alt="Logo Empresa" class="form-logo">
                    </div>
                </div>

                <div class="card-body p-4">
                    <div id="error-alert" class="alert alert-danger alert-dismissible fade show" style="display: none;">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong>Erros encontrados:</strong>
                        <ul id="error-list" class="mb-0"></ul>
                    </div>


                    <form id="company-form" action="{{ route('companies.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <!-- Dados da Empresa -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Dados da Empresa</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Razão Social*</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-file-signature"></i></span>
                                        <input type="text" id="name" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Ex: Tech Solutions Ltda">
                                    </div>
                                    <div class="invalid-feedback">Por favor, informe a razão social.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="trading_name" class="form-label">Nome Fantasia</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                        <input type="text" id="trading_name" name="trading_name" class="form-control" value="{{ old('trading_name') }}" placeholder="Ex: Tech Solutions">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label for="cnpj" class="form-label">CNPJ*</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" id="cnpj" name="cnpj" class="form-control" required value="{{ old('cnpj') }}" placeholder="00.000.000/0000-00">
                                    </div>
                                    <div class="invalid-feedback">Por favor, informe um CNPJ válido.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="state_registration" class="form-label">Inscrição Estadual (IE)</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                        <input type="text" id="state_registration" name="state_registration" class="form-control" value="{{ old('state_registration') }}" placeholder="Opcional para MEI">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contatos -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-address-book me-2"></i>Contatos</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Telefone Fixo*</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" id="phone" name="phone" class="form-control" required value="{{ old('phone') }}" placeholder="(00) 0000-0000">
                                    </div>
                                    <div class="invalid-feedback">Por favor, informe um telefone válido.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="whatsapp" class="form-label">WhatsApp</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                        <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}" placeholder="(00) 00000-0000">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3 mt-1">
                                <div class="col-12">
                                    <label for="email" class="form-label">E-mail*</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="empresa@exemplo.com">
                                    </div>
                                    <div class="invalid-feedback">Por favor, informe um e-mail válido.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-map-marker-alt me-2"></i>Endereço</h5>
                            
                            <div class="mb-3">
                                <label for="autocomplete" class="form-label">Buscar Endereço*</label>
                                <div class="input-group mb-3">
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
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-road"></i></span>
                                        <input type="text" id="address" name="address" class="form-control bg-light" readonly required value="{{ old('address') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="number" class="form-label">Número*</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        <input type="text" id="number" name="number" class="form-control" required value="{{ old('number') }}" placeholder="Ex: 123">
                                    </div>
                                    <div class="invalid-feedback">Por favor, informe o número.</div>
                                </div>
                            </div>
                            
                            <div class="row g-3 mt-1">
                                <div class="col-md-4">
                                    <label for="complement" class="form-label">Complemento</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                        <input type="text" id="complement" name="complement" class="form-control" value="{{ old('complement') }}" placeholder="Ex: Sala 101">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="neighborhood" class="form-label">Bairro*</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                        <input type="text" id="neighborhood" name="neighborhood" class="form-control bg-light" readonly required value="{{ old('neighborhood') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="city" class="form-label">Cidade*</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                        <input type="text" id="city" name="city" class="form-control bg-light" readonly required value="{{ old('city') }}">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label for="state" class="form-label">UF*</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="state" name="state" class="form-control bg-light text-uppercase" readonly required value="{{ old('state') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-3 mt-1">
                                <div class="col-md-4">
                                    <label for="zip_code" class="form-label">CEP*</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                        <input type="text" id="zip_code" name="zip_code" class="form-control bg-light" readonly required value="{{ old('zip_code') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Segurança -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-lock me-2"></i>Segurança</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Senha*</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" id="password" name="password" class="form-control" required minlength="8" placeholder="Mínimo 8 caracteres">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">A senha deve ter pelo menos 8 caracteres.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirmar Senha*</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Confirme sua senha">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">As senhas devem coincidir.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-info-circle me-2"></i>Informações Adicionais</h5>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Descrição da Empresa</label>
                                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Descreva brevemente sua empresa">{{ old('description') }}</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="website" class="form-label">Site</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    <input type="url" id="website" name="website" class="form-control" placeholder="https://www.suaempresa.com.br" value="{{ old('website') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                            <a href="/" class="btn btn-outline-secondary px-4 py-2 me-md-2">
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

    <!-- Coluna Direita - Imagem com Logo -->
    <div class="image-column">
        <div class="logo-container">
            <img src="{{ asset('images/mascote-fretes-em-nuvens.png') }}" alt="Logo" class="logo">
        </div>
        <div class="image-content">
            <h1>Junte-se a Nós</h1>
            <p>Transforme a gestão da sua empresa com nossa plataforma completa e intuitiva.</p>
            
            <ul class="benefits-list list-unstyled">
                <li><i class="fas fa-check-circle text-success me-2"></i> Gestão integrada de empresa e motorista de frete</li>
                <li><i class="fas fa-check-circle text-success me-2"></i> Acompanhe o seu frete em tempo real</li>
                <li><i class="fas fa-check-circle text-success me-2"></i> Suporte especializado 24 horas</li>
                <li><i class="fas fa-check-circle text-success me-2"></i> Ambiente seguro e em conformidade</li>
                <li><i class="fas fa-check-circle text-success me-2"></i> Acesso multiplataforma</li>
            </ul>
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