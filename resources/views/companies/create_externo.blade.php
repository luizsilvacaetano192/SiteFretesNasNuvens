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
            height: 100vh;
            overflow-x: hidden;
        }
        .left-column {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            color: white;
        }
        .left-content {
            max-width: 600px;
            margin: 0 auto;
        }
        .left-column h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .left-column p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
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
        .right-column {
            padding: 2rem;
            height: 100vh;
            overflow-y: auto;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 2rem;
        }
        @media (max-width: 992px) {
            .left-column {
                height: auto;
                padding: 2rem 1rem;
            }
            .left-content {
                text-align: center;
                padding-bottom: 2rem;
            }
            .right-column {
                height: auto;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid g-0">
    <div class="row g-0">
        <!-- Coluna Esquerda com Imagem -->
        <div class="col-lg-6 d-none d-lg-block">
            <div class="left-column">
                <div class="left-content">
                    <img src="{{ asset('images/mascote-fretes-em-nuvens.png') }}" alt="Logo" class="logo">
                    <h1>Cadastre sua empresa</h1>
                    <p>Junte-se a milhares de empresas que já utilizam nossa plataforma para gerenciar seus negócios de forma eficiente e moderna.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check-circle me-2"></i> Gestão completa de clientes</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2"></i> Relatórios detalhados</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2"></i> Suporte 24/7</li>
                        <li class="mb-2"><i class="fas fa-check-circle me-2"></i> Integração com principais plataformas</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Coluna Direita com Formulário -->
        <div class="col-lg-6">
            <div class="right-column">
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

                                    <!-- Seções do formulário permanecem iguais -->
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

                                    <!-- Demais seções do formulário (contatos, endereço, segurança, etc.) -->
                                    <!-- ... (manter todas as outras seções do formulário originais) ... -->

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