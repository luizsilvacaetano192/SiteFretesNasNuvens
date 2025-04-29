<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Fretes em Nuvens</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            display: flex;
            height: 100vh;
            background-color: #f5f7fa;
            color: #333;
        }
        
        .login-container {
            width: 40%;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 5%;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
            z-index: 2;
        }
        
        .logo {
            margin-bottom: 1px;
            text-align: center;
        }
        
        .logo img {
            max-width: 250px;
            height: auto;
        }
        
        .login-form h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .login-form p {
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
            background-color: #f9f9f9;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .forgot-password a {
            color: #3498db;
            text-decoration: none;
        }
        
        .login-button {
            width: 100%;
            padding: 14px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
            position: relative;
        }
        
        .login-button:hover {
            background-color: #2980b9;
        }

        .login-button:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
        }

        .spinner {
            display: none;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: translateY(-50%) rotate(360deg); }
        }
        
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .background {
            width: 60%;
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .background-content {
            color: white;
            text-align: center;
            padding: 0 10%;
        }
        
        .background-content h3 {
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .background-content p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
        }
        
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            
            .login-container, .background {
                width: 100%;
            }
            
            .login-container {
                padding: 40px 5%;
            }
            
            .background {
                padding: 40px 5%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="{{ asset('images/logo_fretes_nas_nuvens.png') }}" alt="Fretes em Nuvens">
        </div>
        
        <div class="login-form">
            <h2>Acesse sua conta</h2>
            <p>Digite seu CNPJ e senha para acessar o sistema</p>
            
            <form id="loginForm">
                <div class="form-group">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" id="cnpj" name="cnpj" placeholder="00.000.000/0000-00" required>
                    <div id="cnpj-error" class="error-message"></div>
                </div>
                
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
                    <div id="password-error" class="error-message"></div>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Lembrar-me</label>
                    </div>
                    <div class="forgot-password">
                        <a href="">Esqueceu sua senha?</a>
                    </div>
                </div>
                
                <button type="submit" class="login-button" id="loginButton">
                    <span id="buttonText">Entrar</span>
                    <div class="spinner" id="spinner"></div>
                </button>
            </form>
        </div>
    </div>
    
    <div class="background">
        <div class="background-content">
            <h3>Gestão inteligente de fretes</h3>
            <p>Otimize sua operação logística com nossa plataforma completa para gestão de fretes e transportes.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            const buttonText = document.getElementById('buttonText');
            const spinner = document.getElementById('spinner');
            const cnpjInput = document.getElementById('cnpj');
            const passwordInput = document.getElementById('password');
            const cnpjError = document.getElementById('cnpj-error');
            const passwordError = document.getElementById('password-error');
            const rememberCheckbox = document.getElementById('remember');
            
            let loginInProgress = false;
            let authCheckAbortController = null;

            // Formatação automática do CNPJ
            cnpjInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length > 12) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, '$1.$2.$3/$4-$5');
                } else if (value.length > 8) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4}).*/, '$1.$2.$3/$4');
                } else if (value.length > 5) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3}).*/, '$1.$2.$3');
                } else if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d{3}).*/, '$1.$2');
                }
                
                e.target.value = value;
            });

            function showError(element, message) {
                element.textContent = message;
                element.style.display = 'block';
            }

            function clearErrors() {
                cnpjError.style.display = 'none';
                passwordError.style.display = 'none';
            }

            async function checkInitialAuth() {
                const token = localStorage.getItem('auth_token');
                if (!token) return;
                
                try {
                    authCheckAbortController = new AbortController();
                    
                    const response = await fetch('/api/user', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        signal: authCheckAbortController.signal
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        const redirectTo = data.role === 'admin' ? '/freights' : '/cliente';
                        window.location.href = redirectTo;
                    } else {
                        localStorage.removeItem('auth_token');
                    }
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        localStorage.removeItem('auth_token');
                    }
                }
            }

            async function handleLogin(event) {
                event.preventDefault();
                
                if (loginInProgress) return;
                loginInProgress = true;
                
                buttonText.style.display = 'none';
                spinner.style.display = 'block';
                loginButton.disabled = true;
                clearErrors();
                
                try {
                    const formData = {
                        cnpj: cnpjInput.value.replace(/\D/g, ''),
                        password: passwordInput.value,
                        remember: rememberCheckbox.checked
                    };
                    
                    const response = await fetch('/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(formData)
                    });

                    if (response.status === 429) {
                        const errorData = await response.json();
                        const retryAfter = parseInt(response.headers.get('Retry-After')) || 60;
                        
                        let secondsLeft = retryAfter;
                        showError(cnpjError, `Muitas tentativas. Tente novamente em ${secondsLeft} segundos.`);
                        
                        const countdown = setInterval(() => {
                            secondsLeft--;
                            if (secondsLeft <= 0) {
                                clearInterval(countdown);
                                cnpjError.style.display = 'none';
                            } else {
                                showError(cnpjError, `Muitas tentativas. Tente novamente em ${secondsLeft} segundos.`);
                            }
                        }, 1000);
                        
                        return;
                    }

                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw data;
                    }
                    
                    localStorage.setItem('auth_token', data.access_token);
                    window.location.href = data.redirect_to;
                    
                } catch (error) {
                    if (error.errors) {
                        if (error.errors.cnpj) {
                            showError(cnpjError, error.errors.cnpj[0]);
                        }
                        if (error.errors.password) {
                            showError(passwordError, error.errors.password[0]);
                        }
                    } else {
                        showError(cnpjError, error.message || 'Erro durante o login');
                    }
                } finally {
                    buttonText.style.display = 'block';
                    spinner.style.display = 'none';
                    loginButton.disabled = false;
                    loginInProgress = false;
                }
            }

            loginForm.addEventListener('submit', handleLogin);
            
            // Verificação modificada para evitar requisições desnecessárias
            const token = localStorage.getItem('auth_token');
            if (token) {
                try {
                    const parsed = JSON.parse(atob(token.split('.')[1]));
                    if (parsed && parsed.exp * 1000 > Date.now()) {
                        setTimeout(checkInitialAuth, 500);
                    } else {
                        localStorage.removeItem('auth_token');
                    }
                } catch (e) {
                    localStorage.removeItem('auth_token');
                }
            }


        });
    </script>
</body>
</html>