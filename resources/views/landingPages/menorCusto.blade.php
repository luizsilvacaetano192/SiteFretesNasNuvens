<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Meta Tags Essenciais -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fretes em Nuvens | Plataforma de Fretes Online - Economize até 30% nos seus fretes</title>
    <meta name="description" content="Plataforma digital que conecta empresas a motoristas autônomos. Solução completa para transporte de cargas com economia de até 30% comparado a FreteBras e CargoX. Cadastre-se grátis!">
    <meta name="keywords" content="plataforma de fretes, frete online, frete caminhão, contratar frete, frete para empresas, motorista autônomo, frete barato, frete rápido, FreteBras, CargoX, TruckPad">
    <meta name="author" content="Fretes em Nuvens">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.fretesemnuvens.com.br/">
    <meta property="og:title" content="Fretes em Nuvens | Economize até 30% nos seus fretes">
    <meta property="og:description" content="Conectamos empresas que precisam de fretes com motoristas qualificados. Solução mais eficiente e econômica que FreteBras e CargoX. Experimente grátis!">
    <meta property="og:image" content="https://www.fretesemnuvens.com.br/images/og-fretes-em-nuvens.jpg">

    <!-- Favicon -->
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Inline -->
    <style>
        :root {
          --primary: #2563EB;
          --primary-dark: #1D4ED8;
          --secondary: #10B981;
          --dark: #1F2937;
          --darker: #111827;
          --light: #F9FAFB;
          --gray: #6B7280;
          --light-gray: #E5E7EB;
          --white: #FFFFFF;
          --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
          --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
          --transition: all 0.3s ease;
        }

        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

        html {
          scroll-behavior: smooth;
        }

        body {
          font-family: 'Montserrat', sans-serif;
          line-height: 1.6;
          color: var(--dark);
          background-color: var(--white);
          overflow-x: hidden;
        }

        .container {
          width: 100%;
          max-width: 1200px;
          margin: 0 auto;
          padding: 0 20px;
        }

        img {
          max-width: 100%;
          height: auto;
        }

        a {
          text-decoration: none;
          color: inherit;
        }

        ul {
          list-style: none;
        }

        /* Tipografia */
        h1, h2, h3, h4 {
          font-weight: 700;
          line-height: 1.2;
          margin-bottom: 1rem;
        }

        h1 {
          font-size: 2.5rem;
        }

        h2 {
          font-size: 2rem;
        }

        h3 {
          font-size: 1.5rem;
        }

        p {
          margin-bottom: 1rem;
          color: var(--gray);
        }

        .lead {
          font-size: 1.25rem;
          font-weight: 500;
        }

        .text-primary {
          color: var(--primary);
        }

        .text-center {
          text-align: center;
        }

        /* Botões */
        .btn {
          display: inline-block;
          padding: 12px 24px;
          border-radius: 6px;
          font-weight: 600;
          text-align: center;
          transition: var(--transition);
          cursor: pointer;
          border: none;
        }

        .btn-primary {
          background-color: var(--primary);
          color: var(--white);
        }

        .btn-primary:hover {
          background-color: var(--primary-dark);
          transform: translateY(-2px);
          box-shadow: var(--shadow);
        }

        .btn-secondary {
          background-color: var(--secondary);
          color: var(--white);
        }

        .btn-secondary:hover {
          opacity: 0.9;
          transform: translateY(-2px);
          box-shadow: var(--shadow);
        }

        .btn-large {
          padding: 15px 30px;
          font-size: 1.1rem;
        }

        /* Header */
        .main-header {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          background-color: var(--white);
          box-shadow: var(--shadow);
          z-index: 1000;
          padding: 15px 0;
        }

        .main-header .container {
          display: flex;
          justify-content: space-between;
          align-items: center;
        }

        .logo img {
          height: 40px;
        }

        .main-nav ul {
          display: flex;
          align-items: center;
          gap: 20px;
        }

        .main-nav a {
          font-weight: 600;
          transition: var(--transition);
          font-size: 0.95rem;
        }

        .main-nav a:hover {
          color: var(--primary);
        }

        /* Hero Section */
        .hero {
          padding: 180px 0 80px;
          background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
          position: relative;
          overflow: hidden;
        }

        .hero .container {
          display: flex;
          align-items: center;
          gap: 50px;
          position: relative;
          z-index: 2;
        }

        .hero-content {
          flex: 1;
        }

        .hero-image {
          flex: 1;
          position: relative;
        }

        .hero-image img {
          border-radius: 8px;
          box-shadow: var(--shadow-lg);
        }

        .cta-buttons {
          display: flex;
          gap: 15px;
          margin-top: 30px;
        }

        .hero-bg-pattern {
          position: absolute;
          top: 0;
          right: 0;
          width: 50%;
          height: 100%;
          background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBvcGFjaXR5PSIwLjEiPjxwYXRoIGQ9Ik0wIDIwQzAgOC45NSA4Ljk1IDAgMjAgMEMzMS4wNSAwIDQwIDguOTUgNDAgMjBDNDAgMzEuMDUgMzEuMDUgNDAgMjAgNDBDOC45NSA0MCAwIDMxLjA1IDAgMjBaIiBmaWxsPSIjMjU2M0VCIi8+PHBhdGggZD0iTTIwIDBDOC45NSAwIDAgOC45NSAwIDIwQzAgMzEuMDUgOC45NSA0MCAyMCA0MEMzMS4wNSA0MCA0MCAzMS4wNSA0MCAyMEM0MCA4Ljk1IDMxLjA1IDAgMjAgMFoiIGZpbGw9IiMxMEI5ODEiLz48L2c+PC9zdmc+');
          background-size: 40px 40px;
          opacity: 0.3;
          z-index: 1;
        }

        /* Highlights */
        .highlights {
          padding: 60px 0;
          background-color: var(--white);
        }

        .highlights-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 30px;
          margin-top: 40px;
        }

        .highlight-card {
          background-color: var(--light);
          padding: 30px;
          border-radius: 8px;
          text-align: center;
          transition: var(--transition);
          border: 1px solid var(--light-gray);
        }

        .highlight-card:hover {
          transform: translateY(-5px);
          box-shadow: var(--shadow);
        }

        .highlight-card img {
          height: 60px;
          margin-bottom: 20px;
        }

        .highlight-card h3 {
          color: var(--primary);
        }

        /* How It Works */
        .how-it-works {
          padding: 80px 0;
          background-color: var(--light);
        }

        .section-header {
          text-align: center;
          margin-bottom: 50px;
        }

        .steps {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 30px;
        }

        .step {
          background-color: var(--white);
          padding: 30px;
          border-radius: 8px;
          box-shadow: var(--shadow);
          transition: var(--transition);
          position: relative;
          overflow: hidden;
          border: 1px solid var(--light-gray);
        }

        .step:hover {
          transform: translateY(-10px);
          box-shadow: var(--shadow-lg);
        }

        .step-number {
          display: inline-block;
          width: 40px;
          height: 40px;
          background-color: var(--primary);
          color: var(--white);
          border-radius: 50%;
          font-weight: 700;
          line-height: 40px;
          text-align: center;
          margin-bottom: 20px;
        }

        /* Comparison Section */
        .comparison {
          padding: 80px 0;
          background-color: var(--white);
        }

        .comparison-table {
          width: 100%;
          border-collapse: collapse;
          margin-top: 40px;
          box-shadow: var(--shadow);
          border-radius: 8px;
          overflow: hidden;
        }

        .comparison-table th, 
        .comparison-table td {
          padding: 15px;
          text-align: left;
          border: 1px solid var(--light-gray);
        }

        .comparison-table th {
          background-color: var(--primary);
          color: var(--white);
          font-weight: 600;
        }

        .comparison-table tr:nth-child(even) {
          background-color: var(--light);
        }

        .check-icon {
          color: var(--secondary);
          font-weight: bold;
        }

        .x-icon {
          color: #EF4444;
        }

        /* CTA Section */
        .cta-section {
          padding: 80px 0;
          background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
          color: var(--white);
          text-align: center;
        }

        .cta-section h2 {
          margin-bottom: 20px;
        }

        .cta-section p {
          color: var(--light-gray);
          max-width: 700px;
          margin: 0 auto 30px;
        }

        /* Form Section */
        .form-section {
          padding: 80px 0;
          background-color: var(--light);
        }

        .form-container {
          max-width: 600px;
          margin: 0 auto;
          background-color: var(--white);
          padding: 40px;
          border-radius: 8px;
          box-shadow: var(--shadow);
        }

        .form-group {
          margin-bottom: 20px;
        }

        .form-group label {
          display: block;
          margin-bottom: 8px;
          font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
          width: 100%;
          padding: 12px 15px;
          border: 1px solid var(--light-gray);
          border-radius: 6px;
          font-family: 'Montserrat', sans-serif;
          transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
          outline: none;
          border-color: var(--primary);
          box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* WhatsApp Widget */
        .whatsapp-widget {
          position: fixed;
          bottom: 30px;
          right: 30px;
          z-index: 999;
          animation: pulse 2s infinite;
        }

        .whatsapp-link {
          display: flex;
          align-items: center;
          justify-content: center;
          width: 60px;
          height: 60px;
          background-color: #25D366;
          border-radius: 50%;
          box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
          transition: all 0.3s ease;
        }

        .whatsapp-link:hover {
          background-color: #128C7E;
          transform: scale(1.1);
        }

        .whatsapp-link img {
          width: 36px;
          height: 36px;
        }

        @keyframes pulse {
          0% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
          }
          70% {
            box-shadow: 0 0 0 12px rgba(37, 211, 102, 0);
          }
          100% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
          }
        }

        /* Responsividade */
        @media (max-width: 992px) {
          .hero .container {
            flex-direction: column;
            text-align: center;
          }
          
          .hero-content, 
          .hero-image {
            flex: none;
            width: 100%;
          }
          
          .hero {
            padding: 150px 0 60px;
          }
          
          .cta-buttons {
            justify-content: center;
          }
        }

        @media (max-width: 768px) {
          h1 {
            font-size: 2rem;
          }
          
          h2 {
            font-size: 1.75rem;
          }
          
          .main-nav ul {
            display: none;
          }
          
          .mobile-menu-btn {
            display: block;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
          }
          
          .cta-buttons {
            flex-direction: column;
            gap: 10px;
          }
          
          .btn {
            width: 100%;
          }

          .whatsapp-widget {
            bottom: 20px;
            right: 20px;
          }
        }

        /* Animations */
        @keyframes fadeIn {
          from { opacity: 0; transform: translateY(20px); }
          to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
          animation: fadeIn 0.8s ease forwards;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <a href="/">
                    <img src="images/logo_fretes_em_nuvens3.png" 
                    alt="Logo Fretes em Nuvens - Plataforma de fretes online para empresas e motoristas" 
                    width="180">
                </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="#como-funciona">Como Funciona</a></li>
                    <li><a href="#vantagens">Vantagens</a></li>
                    <li><a href="#contato">Contato</a></li>
                    <li><a href="/login" class="btn btn-outline">Entrar</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg-pattern"></div>
        <div class="container">
            <div class="hero-content">
                <h1>Economize até <span class="text-primary">30% nos seus fretes</span> com nossa plataforma</h1>
                <p class="lead">Conectamos sua empresa a mais de 5.000 motoristas verificados em todo Brasil. Agende fretes em minutos, sem taxas abusivas e com acompanhamento em tempo real.</p>
                <div class="cta-buttons">
                    <a href="#formulario" class="btn btn-primary btn-large">Solicitar Demonstração</a>
                    <a href="#como-funciona" class="btn btn-secondary btn-large">Como Funciona</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="images/dashboard-fretes.png" loading="lazy" alt="Plataforma de fretes online Fretes em Nuvens">
            </div>
        </div>
    </section>

    <!-- Seção de Destaques -->
    <section class="highlights">
        <div class="container">
            <div class="section-header">
                <h2>Por que escolher a Fretes em Nuvens?</h2>
                <p class="lead">A solução mais completa e econômica para seu transporte de cargas</p>
            </div>
            
            <div class="highlights-grid">
                <div class="highlight-card">
                    <img src="images/icon-economia.png" alt="Ícone de economia" width="60">
                    <h3>Economia de até 30%</h3>
                    <p>Reduza seus custos com fretes comparado a FreteBras e CargoX</p>
                </div>
                <div class="highlight-card">
                    <img src="images/icon-motoristas.png" alt="Ícone de motoristas" width="60">
                    <h3>+5.000 Motoristas</h3>
                    <p>Cadastrados e verificados em nossa plataforma</p>
                </div>
                <div class="highlight-card">
                    <img src="images/icon-rastreamento.png" alt="Ícone de rastreamento" width="60">
                    <h3>Rastreamento em Tempo Real</h3>
                    <p>Acompanhe sua carga do início ao destino final</p>
                </div>
                <div class="highlight-card">
                    <img src="images/icon-suporte.png" alt="Ícone de suporte" width="60">
                    <h3>Suporte Dedicado</h3>
                    <p>Equipe especializada para te ajudar quando precisar</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Como Funciona -->
    <section id="como-funciona" class="how-it-works">
        <div class="container">
            <div class="section-header">
                <h2>Como funciona nossa plataforma</h2>
                <p class="lead">Um processo simples em poucos passos</p>
            </div>
            
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Cadastro Rápido</h3>
                    <p>Cadastre sua empresa em menos de 5 minutos</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Publicação do Frete</h3>
                    <p>Informe origem, destino, tipo de carga e veículo necessário</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Receba Propostas</h3>
                    <p>Motoristas qualificados enviam propostas competitivas</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <h3>Escolha e Acompanhe</h3>
                    <p>Selecione o melhor motorista e acompanhe em tempo real</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparação com Concorrentes -->
    <section id="vantagens" class="comparison">
        <div class="container">
            <div class="section-header">
                <h2>Compare com outras soluções</h2>
                <p class="lead">Veja como somos a melhor opção para seu negócio</p>
            </div>
            
            <div class="table-responsive">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Recurso</th>
                            <th>Fretes em Nuvens</th>
                            <th>FreteBras</th>
                            <th>CargoX</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Taxa por frete</td>
                            <td class="check-icon">0%</td>
                            <td class="x-icon">5-15%</td>
                            <td class="x-icon">7-12%</td>
                        </tr>
                        <tr>
                            <td>Cadastro de motoristas</td>
                            <td class="check-icon">+5.000 verificados</td>
                            <td class="check-icon">+10.000</td>
                            <td class="check-icon">+8.000</td>
                        </tr>
                        <tr>
                            <td>Tempo de resposta</td>
                            <td class="check-icon">15 minutos (média)</td>
                            <td>45 minutos</td>
                            <td>1 hora</td>
                        </tr>
                        <tr>
                            <td>Rastreamento</td>
                            <td class="check-icon">Tempo real</td>
                            <td class="check-icon">Tempo real</td>
                            <td>Atualizações periódicas</td>
                        </tr>
                        <tr>
                            <td>Suporte dedicado</td>
                            <td class="check-icon">24/7</td>
                            <td>Horário comercial</td>
                            <td>Horário comercial</td>
                        </tr>
                        <tr>
                            <td>Preço médio</td>
                            <td class="check-icon">Até 30% mais barato</td>
                            <td>Preço de mercado</td>
                            <td>Preço de mercado</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Depoimentos -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2>O que nossos clientes dizem</h2>
                <p class="lead">Empresas que já economizam com nossa plataforma</p>
            </div>
            
            <div class="highlights-grid">
                <div class="highlight-card">
                    <img src="images/quote.png" alt="Aspas" width="40" style="margin-bottom: 10px;">
                    <p>"Reduzimos nossos custos com fretes em 28% no primeiro mês usando a plataforma. O suporte é excelente!"</p>
                    <div style="margin-top: 20px; display: flex; align-items: center;">
                        <img src="images/client1.png" alt="Cliente" width="50" style="border-radius: 50%; margin-right: 15px;">
                        <div>
                            <h4 style="margin-bottom: 5px; font-size: 1.1rem;">Carlos Mendes</h4>
                            <p style="font-size: 0.9rem;">Gerente de Logística</p>
                        </div>
                    </div>
                </div>
                
                <div class="highlight-card">
                    <img src="images/quote.png" alt="Aspas" width="40" style="margin-bottom: 10px;">
                    <p>"A plataforma é intuitiva e encontramos motoristas confiáveis rapidamente. Recomendo para qualquer empresa."</p>
                    <div style="margin-top: 20px; display: flex; align-items: center;">
                        <img src="images/client2.png" alt="Cliente" width="50" style="border-radius: 50%; margin-right: 15px;">
                        <div>
                            <h4 style="margin-bottom: 5px; font-size: 1.1rem;">Ana Souza</h4>
                            <p style="font-size: 0.9rem;">Diretora Comercial</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Pronto para reduzir seus custos com fretes?</h2>
            <p>Cadastre-se agora e comece a economizar hoje mesmo</p>
            <a href="#formulario" class="btn btn-secondary btn-large">Solicitar Demonstração Grátis</a>
        </div>
    </section>

    <!-- Form Section -->
    <section id="formulario" class="form-section">
        <div class="container">
            <div class="form-container">
                <h2 class="text-center">Fale conosco</h2>
                <p class="text-center" style="margin-bottom: 30px;">Preencha o formulário e nossa equipe entrará em contato em até 2 horas</p>
                
                <form id="contact-form">
                    <div class="form-group">
                        <label for="name">Nome Completo</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="company">Empresa</label>
                        <input type="text" id="company" name="company" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Telefone</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fretes">Quantos fretes você realiza por mês?</label>
                        <select id="fretes" name="fretes" required>
                            <option value="">Selecione...</option>
                            <option value="1-10">1-10</option>
                            <option value="11-50">11-50</option>
                            <option value="51-100">51-100</option>
                            <option value="100+">100+</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Como podemos te ajudar?</label>
                        <textarea id="message" name="message" rows="4"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </section>

    <!-- WhatsApp Widget -->
    <div class="whatsapp-widget">
      <a href="https://wa.me/5541996077879?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20a%20plataforma%20Fretes%20em%20Nuvens" 
         class="whatsapp-link" 
         target="_blank"
         aria-label="Conversar pelo WhatsApp">
        <img src="images/icone-whatsapp.png" alt="WhatsApp Fretes em Nuvens">
      </a>
    </div>

    <!-- Rodapé -->
    <footer class="main-footer" style="background-color: var(--darker); color: var(--white); padding: 40px 0;">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <div class="logo">
                    <img src="images/logo_fretes_em_nuvens3_white.png" alt="Fretes em Nuvens" width="150">
                </div>
                <div style="display: flex; gap: 15px;">
                    <a href="#"><img src="images/icon-facebook.png" alt="Facebook" width="24"></a>
                    <a href="#"><img src="images/icon-instagram.png" alt="Instagram" width="24"></a>
                    <a href="#"><img src="images/icon-linkedin.png" alt="LinkedIn" width="24"></a>
                </div>
            </div>
            
            <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
                <div>
                    <p>© 2023 Fretes em Nuvens. Todos os direitos reservados.</p>
                </div>
                <div style="display: flex; gap: 20px;">
                    <a href="termos.html" style="color: var(--light-gray);">Termos de Uso</a>
                    <a href="privacidade.html" style="color: var(--light-gray);">Política de Privacidade</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Inline -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
          // Mobile Menu Toggle
          const mobileMenuBtn = document.createElement('button');
          mobileMenuBtn.className = 'mobile-menu-btn';
          mobileMenuBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>';
          
          const header = document.querySelector('.main-header .container');
          header.appendChild(mobileMenuBtn);
          
          const nav = document.querySelector('.main-nav ul');
          
          mobileMenuBtn.addEventListener('click', function() {
            nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
          });
          
          // Form submission
          const contactForm = document.getElementById('contact-form');
          if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
              e.preventDefault();
              
              // Simulate form submission
              const submitBtn = contactForm.querySelector('button[type="submit"]');
              submitBtn.disabled = true;
              submitBtn.textContent = 'Enviando...';
              
              setTimeout(function() {
                submitBtn.textContent = 'Mensagem Enviada!';
                submitBtn.style.backgroundColor = var(--secondary);
                
                // Reset form
                setTimeout(function() {
                  contactForm.reset();
                  submitBtn.disabled = false;
                  submitBtn.textContent = 'Enviar Mensagem';
                  submitBtn.style.backgroundColor = var(--primary);
                  
                  // Show thank you message (could be a modal in a real implementation)
                  alert('Obrigado por seu interesse! Nossa equipe entrará em contato em breve.');
                }, 2000);
              }, 1500);
            });
          }
          
          // Smooth scrolling for anchor links
          document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
              e.preventDefault();
              
              const targetId = this.getAttribute('href');
              if (targetId === '#') return;
              
              const targetElement = document.querySelector(targetId);
              if (targetElement) {
                const headerHeight = document.querySelector('.main-header').offsetHeight;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                
                window.scrollTo({
                  top: targetPosition,
                  behavior: 'smooth'
                });
              }
            });
          });
          
          // Animation on scroll
          const animateOnScroll = function() {
            const elements = document.querySelectorAll('.highlight-card, .step, .comparison-table');
            
            elements.forEach(element => {
              const elementPosition = element.getBoundingClientRect().top;
              const screenPosition = window.innerHeight / 1.3;
              
              if (elementPosition < screenPosition) {
                element.classList.add('fade-in');
              }
            });
          };
          
          window.addEventListener('scroll', animateOnScroll);
          animateOnScroll(); // Run once on load
          
          // Update year in footer
          const yearSpan = document.getElementById('current-year');
          if (yearSpan) {
            yearSpan.textContent = new Date().getFullYear();
          }
        });

        // Schema Markup
        const schemaScript = document.createElement('script');
        schemaScript.type = 'application/ld+json';
        schemaScript.text = JSON.stringify({
          "@context": "https://schema.org",
          "@type": "Organization",
          "name": "Fretes em Nuvens",
          "url": "https://www.fretesemnuvens.com.br",
          "logo": "https://www.fretesemnuvens.com.br/images/logo_fretes_em_nuvens3.png",
          "description": "Plataforma digital que conecta empresas que precisam de fretes com motoristas qualificados",
          "sameAs": [
            "https://www.facebook.com/fretesemnuvens",
            "https://www.instagram.com/fretesemnuvens",
            "https://www.linkedin.com/company/fretes-em-nuvens"
          ],
          "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+5541996077879",
            "contactType": "customer service",
            "email": "contato@fretesemnuvens.com.br",
            "availableLanguage": "Portuguese"
          }
        });
        document.head.appendChild(schemaScript);
    </script>
</body>
</html>