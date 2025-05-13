<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestão de Fretes')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    <style>
        :root {
            --primary: #4e73df;
            --sidebar-width: 200px;
            --navbar-height: 50px;
            --content-padding: 15px;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            display: grid;
            grid-template-areas:
                "sidebar navbar"
                "sidebar content";
            grid-template-columns: var(--sidebar-width) 1fr;
            grid-template-rows: var(--navbar-height) 1fr;
            min-height: 100vh;
        }

        /* Sidebar Estilizado */
        .sidebar {
            grid-area: sidebar;
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
            color: white;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e2e8f0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
        }

        .sidebar-logo {
            padding: 10px;
            text-align: center;
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo img {
            max-height: 150px;
            width: auto;
        }

        .sidebar-nav {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            margin: 2px 10px;
            border-radius: 4px;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            font-size: 0.95rem;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 10px 15px;
        }

        /* Navbar */
        .navbar {
            grid-area: navbar;
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
            display: flex;
            align-items: center;
            padding: 0 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            z-index: 10;
            justify-content: space-between;
        }

        .navbar-brand {
            font-size: 1rem;
            font-weight: 600;
            color: white;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            margin-right: 10px;
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-dropdown-btn {
            background: none;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .user-dropdown-btn:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }

        .user-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
            overflow: hidden;
        }

        .user-dropdown:hover .user-dropdown-content {
            display: block;
        }

        .user-dropdown-item {
            color: #333;
            padding: 10px 16px;
            text-decoration: none;
            display: block;
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }

        .user-dropdown-item:hover {
            background-color: #f5f5f5;
        }

        .user-dropdown-divider {
            height: 1px;
            background-color: #eee;
            margin: 0;
        }

        /* Conteúdo Principal */
        .main-content {
            grid-area: content;
            padding: var(--content-padding);
            overflow-y: auto;
            background-color: #ffffff;
        }

        /* Estilos Específicos para Messages-Push */
        .messages-push-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }

        .message-card {
            border-left: 4px solid var(--primary);
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        /* Menu Mobile */
        .navbar-toggler {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            margin-right: 15px;
        }

        @media (max-width: 768px) {
            body {
                grid-template-areas:
                    "navbar"
                    "content";
                grid-template-columns: 1fr;
                grid-template-rows: var(--navbar-height) 1fr;
            }
            
            .sidebar {
                position: fixed;
                left: -100%;
                top: var(--navbar-height);
                bottom: 0;
                width: 220px;
                z-index: 1000;
                transition: left 0.3s;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .navbar-toggler {
                display: block;
            }
            
            .main-content {
                grid-column: 1;
            }
            #toast-container > .toast {
                background-color: "blue";
                color: #fff;
                font-weight: 600;
                font-size: 14px;
            }
        }
    </style>
    @stack('styles')
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo_fretes_em_nuvens3.png') }}" alt="Logo">
        </div>
        
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-link">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="/freights/cliente" class="sidebar-link">
                <i class="fas fa-truck"></i>
                <span>Fretes</span>
            </a>
            <a href="/shipments/cliente" class="sidebar-link">
                <i class="fas fa-box"></i>
                <span>Cargas</span>
            </a>
        </nav>
    </aside>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-left">
            <button class="navbar-toggler">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="#">
                <i class="fas fa-truck"></i>
                <span>Gestão de Fretes</span>
            </a>
        </div>

        <div class="user-dropdown">
            <button class="user-dropdown-btn">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <span>{{ Auth::user()->name ?? 'Usuário' }}</span>
                <i class="fas fa-chevron-down" style="margin-left: 8px; font-size: 0.8rem;"></i>
            </button>
            <div class="user-dropdown-content">
                <a href="/profile" class="user-dropdown-item">
                    <i class="fas fa-user-circle me-2"></i> Meu Perfil
                </a>
                <a href="/settings" class="user-dropdown-item">
                    <i class="fas fa-cog me-2"></i> Configurações
                </a>
                <div class="user-dropdown-divider"></div>
                <a href="#" class="user-dropdown-item" id="logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i> Sair
                </a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="main-content">
        @yield('content')
    </main>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Controle do menu mobile
        document.querySelector('.navbar-toggler').addEventListener('click', function(e) {
            e.stopPropagation();
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        // Fechar menu ao clicar no conteúdo (mobile)
        document.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            if(window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
        
        // Prevenir fechamento ao clicar no menu
        document.querySelector('.sidebar').addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Garantir que o conteúdo principal tenha altura mínima
        function adjustContentHeight() {
            const navbarHeight = document.querySelector('.navbar').offsetHeight;
            const windowHeight = window.innerHeight;
            document.querySelector('.main-content').style.minHeight = (windowHeight - navbarHeight) + 'px';
        }

        // Logout function
        document.getElementById('logout-btn').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Create a form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout';
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);
            
            // Submit the form
            document.body.appendChild(form);
            form.submit();
        });

        window.addEventListener('load', adjustContentHeight);
        window.addEventListener('resize', adjustContentHeight);
    </script>

    <script>
        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'alert alert-info shadow';
            toast.style.transition = 'opacity 0.5s';
            toast.innerText = message;

            const container = document.getElementById('toast-container');
            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }, 10000);
        }

        async function checkPendingTasks() {
            fetch('/pending-tasks')
            .then(res => res.json())
            .then(messages => {
                messages.forEach((msg, i) => {
                    setTimeout(() => {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-bottom-right",
                            "timeOut": "4000"
                        };
                        toastr.info(msg.message);

                        fetch(`/pending-tasks/${msg.id}/seen`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                    }, i * 500);
                });
            });
        }

        window.addEventListener('load', () => {
            checkPendingTasks();
            setInterval(checkPendingTasks, 10 * 60 * 1000);
        });
    </script>
    @stack('scripts')
</body>
</html>