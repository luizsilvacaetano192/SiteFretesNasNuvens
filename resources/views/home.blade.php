<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }
        
        .login-button:hover {
            background-color: #2980b9;
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
            <!-- Substitua pelo caminho real do logo da sua empresa -->
            <img src="images/logo_fretes_nas_nuvens.png" alt="Fretes em Nuvens">
        </div>
        
        <div class="login-form">
            <h2>Acesse sua conta</h2>
            <p>Digite seu CNPJ e senha para acessar o sistema</p>
            
            <form action="#" method="post">
                <div class="form-group">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" id="cnpj" name="cnpj" placeholder="00.000.000/0000-00" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Lembrar-me</label>
                    </div>
                    <div class="forgot-password">
                        <a href="#">Esqueceu sua senha?</a>
                    </div>
                </div>
                
                <button type="submit" class="login-button">Entrar</button>
            </form>
        </div>
    </div>
    
    <div class="background">
        <div class="background-content">
            <h3>Gestão inteligente de fretes</h3>
            <p>Otimize sua operação logística com nossa plataforma completa para gestão de fretes e transportes.</p>
        </div>
    </div>
</body>
</html>