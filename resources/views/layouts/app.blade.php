<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestão de Fretes')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            background: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e2e8f0;
        }

        .sidebar-logo {
            padding: 10px;
            text-align: center;
            background: #1a252f;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo img {
            max-height: 35px;
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
            background: var(--primary);
            display: flex;
            align-items: center;
            padding: 0 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            z-index: 10;
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

        /* Conteúdo Principal - Correção do Branco */
        .main-content {
            grid-area: content;
            padding: var(--content-padding);
            overflow-y: auto;
            background-color: #ffffff; /* Fundo branco definido explicitamente */
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
                background-color: "blue"; /* Fundo quase opaco */
                color: #fff;
                font-weight: 600;
                font-size: 14px;
            }

        }
    </style>
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo_fretes_nas_nuvens.png') }}" alt="Logo">
        </div>
        
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-link">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="/freights" class="sidebar-link">
                <i class="fas fa-truck"></i>
                <span>Fretes</span>
            </a>
            <a href="/shipments" class="sidebar-link">
                <i class="fas fa-box"></i>
                <span>Cargas</span>
            </a>
            <a href="/companies" class="sidebar-link">
                <i class="fas fa-building"></i>
                <span>Empresas</span>
            </a>
            <a href="/drivers" class="sidebar-link">
                <i class="fas fa-id-card"></i>
                <span>Motoristas</span>
            </a>
            <div class="sidebar-divider"></div>
            <a href="/drivers/send-push" class="sidebar-link">
                <i class="fas fa-paper-plane"></i>
                <span>Enviar Push</span>
            </a>
            <a href="/messages-push" class="sidebar-link">
                <i class="fas fa-bell"></i>
                <span>Mensagens Push</span>
            </a>
            <div class="sidebar-divider"></div>
            <a href="/freight-statuses" class="sidebar-link">
                <i class="fas fa-tasks"></i>
                <span>Status</span>
            </a>
            <a href="/transfers" class="sidebar-link">
                <i class="fas fa-exchange-alt"></i>
                <span>Transferências</span>
            </a>
            <a href="/settings" class="sidebar-link">
                <i class="fas fa-cog"></i>
                <span>Configurações</span>
            </a>
        </nav>
    </aside>

    <!-- Navbar -->
    <nav class="navbar">
        <button class="navbar-toggler">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand" href="#">
            <i class="fas fa-truck"></i>
            <span>Gestão de Fretes</span>
        </a>


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

        window.addEventListener('load', adjustContentHeight);
        window.addEventListener('resize', adjustContentHeight);
    </script>

    @stack('scripts')
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
            }, 10000); // 10 segundos
        }

        async function checkPendingTasks() {
            fetch('/pending-tasks')
            .then(res => res.json())
            .then(messages => {
                messages.forEach((msg, i) => {
                    setTimeout(() => {
                        // Exibe o toast
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-bottom-right",
                            "timeOut": "4000"
                        };
                        toastr.info(msg.message);

                        // Marca como visto
                        fetch(`/pending-tasks/${msg.id}/seen`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                    }, i * 500); // atraso entre os toasts
                });
            });

        }

        // Verifica ao carregar e a cada 10 minutos
        window.addEventListener('load', () => {
           // checkPendingTasks();
            setInterval(checkPendingTasks, 10 * 60 * 1000); // 10 minutos
        });
</script>

</body>
</html>