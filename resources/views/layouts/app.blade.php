<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
@yield('styles')

@stack('scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestão de Fretes')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Navbar */
        .navbar {
            background-color: #007bff;
            height: 60px;
        }

        .navbar-brand {
            font-size: 22px;
            font-weight: bold;
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 70px;
            transition: width 0.3s;
        }

        .sidebar a {
            padding: 12px 20px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 20px;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
            border-left: 4px solid #007bff;
        }

        /* Conteúdo principal */
        .content {
            margin-left: 280px;
            padding: 20px;
            text-align: left; /* Alinhando títulos à esquerda */
        }

        .title-container {
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: left;
            font-size: 32px;
            font-weight: bold;
            color: #333;
            padding-left: 20px;
        }

        /* Botões alinhados à esquerda */
        .btn-left {
            display: flex;
            justify-content: left;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .sidebar a {
                text-align: center;
                font-size: 16px;
            }

            .sidebar a i {
                margin-right: 0;
            }

            .content {
                margin-left: 80px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand mx-auto" href="#">🚛 Gestão de Fretes Nas Nuvens</a>
        </div>
    </nav>

    <!-- Sidebar -->
    <!-- Sidebar -->
<div class="sidebar">
    <a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a>
    <a href="/freights"><i class="fas fa-truck"></i> Gerenciar Fretes</a>
    <a href="/shipments"><i class="fas fa-box"></i> Cargas</a>
    <a href="/companies"><i class="fas fa-building"></i> Empresas</a>
    <a href="/drivers"><i class="fas fa-id-card"></i> Motoristas</a>
    <a href="/drivers/send-push"><i class="fas fa-paper-plane"></i> Enviar Push</a>
    <a href="/messages-push"><i class="fas fa-bell"></i> Mensagens Push</a>
    <a href="/freight-statuses"><i class="fas fa-tasks"></i> Status</a>
    <a href="#"><i class="fas fa-cogs"></i> Configurações</a>
    <a href="#"><i class="fas fa-sign-out-alt"></i> Sair</a>
</div>


    <!-- Conteúdo -->
    <div class="content">
        @yield('content', '<div class="title-container">Bem-vindo ao Gestão de Fretes 🚛</div>')
        
   
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
