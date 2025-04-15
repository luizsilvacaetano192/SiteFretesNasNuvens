@push('scripts')
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Wait for jQuery to be ready
(function($) {
    // Check if jQuery loaded successfully
    if (typeof jQuery === 'undefined') {
        console.error('jQuery failed to load');
        return;
    }

    // Verifica se todas as dependências estão carregadas
    function checkDependencies() {
        const errors = [];
        
        if (typeof toastr === 'undefined') {
            errors.push('toastr não está carregado');
        }
        
        if (typeof Swal === 'undefined') {
            console.warn('SweetAlert2 não está carregado - usando fallback');
        }
        
        if (errors.length > 0) {
            console.error('Erros de dependência:', errors.join(', '));
            return false;
        }
        
        return true;
    }

    // Configuração do Toastr
    function configureToastr() {
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
    }

    // Função segura para mostrar notificações
    function showNotification(type, message) {
        if (typeof toastr === 'undefined') {
            console.log(type.toUpperCase() + ':', message);
            alert(type.toUpperCase() + ': ' + message);
            return;
        }
        
        switch(type) {
            case 'success':
                toastr.success(message);
                break;
            case 'error':
                toastr.error(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            default:
                toastr.info(message);
        }
    }

    // Document ready
    $(function() {
        if (!checkDependencies()) {
            alert('Erro ao carregar recursos necessários. Verifique o console para detalhes.');
            return;
        }
        
        configureToastr();
        
        // Validação de percentuais
        $('input[type="number"]').on('blur', function() {
            if ($(this).attr('id').includes('percentage')) {
                let value = parseFloat($(this).val());
                if (value > 100) {
                    $(this).val(100);
                    showNotification('warning', 'O percentual máximo é 100%');
                }
            }
        });
        
        // Botão de redefinir
        $('#reset-btn').click(function() {
            const resetDefaults = function() {
                $('#cloud_percentage').val(10);
                $('#advance_percentage').val(30);
                $('#small_truck_price').val(2.50);
                $('#medium_truck_price').val(3.20);
                $('#large_truck_price').val(4.50);
                showNotification('success', 'Valores redefinidos para os padrões do sistema');
            };
            
            if (typeof Swal !== 'undefined') {
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
                        resetDefaults();
                    }
                });
            } else {
                if (confirm('Redefinir configurações para os valores padrão?')) {
                    resetDefaults();
                }
            }
        });
        
        // Envio do formulário
        $('#settings-form').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $('#save-btn');
            const originalText = btn.html();
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Salvando...');
            
            console.log('Enviando formulário...'); // Debug
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('Resposta recebida:', response); // Debug
                    
                    if (response && typeof response.success !== 'undefined') {
                        showNotification(
                            response.success ? 'success' : 'error', 
                            response.message || (response.success ? 'Configurações salvas com sucesso!' : 'Erro ao salvar configurações')
                        );
                    } else {
                        showNotification('error', 'Resposta inválida do servidor');
                    }
                },
                error: function(xhr) {
                    console.error('Erro na requisição:', xhr); // Debug
                    
                    let errorMessage = 'Erro ao salvar configurações';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).join('\n');
                        }
                    } else if (xhr.statusText) {
                        errorMessage += ': ' + xhr.statusText;
                    }
                    showNotification('error', errorMessage);
                },
                complete: function() {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }
            });
        });
    });
})(jQuery);
</script>
@endpush