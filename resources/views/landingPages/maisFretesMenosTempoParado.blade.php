<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Meta Tags Essenciais -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fretes em Nuvens para Motoristas | Mais fretes, menos tempo parado</title>
    <meta name="description" content="Baixe o app e tenha acesso a centenas de fretes disponíveis diariamente. Ganhe mais com menos tempo ocioso. App gratuito para motoristas autônomos.">
    <meta name="keywords" content="app para motoristas, fretes para caminhoneiros, cadastro de motoristas, frete online, carga para caminhão, aplicativo de fretes, frete rodoviário">
    <meta name="author" content="Fretes em Nuvens">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://motorista.fretesemnuvens.com.br/">
    <meta property="og:title" content="Fretes em Nuvens para Motoristas | Mais fretes, menos tempo parado">
    <meta property="og:description" content="Baixe o app e tenha acesso a centenas de fretes disponíveis diariamente. Ganhe mais com menos tempo ocioso.">
    <meta property="og:image" content="https://motorista.fretesemnuvens.com.br/images/og-motorista.jpg">

    <!-- Favicon -->
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Inline -->
    <style>
        :root {
          --primary: #2563EB;
          --primary-dark: #1D4ED8;
          --secondary: #F59E0B;
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

        .text-secondary {
          color: var(--secondary);
        }

        .text-center {
          text-align: center;
        }

        /* Botões */
        .btn {
          display: inline-flex;
          align-items: center;
          justify-content: center;
          padding: 12px 24px;
          border-radius: 8px;
          font-weight: 600;
          text-align: center;
          transition: var(--transition);
          cursor: pointer;
          border: none;
          gap: 8px;
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

        .btn-app {
          background-color: var(--dark);
          color: var(--white);
        }

        .btn-app img {
          height: 24px;
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
          flex-wrap: wrap;
        }

        .app-badges {
          display: flex;
          gap: 10px;
          margin-top: 20px;
        }

        .app-badge {
          height: 45px;
        }

        .hero-bg-pattern {
          position: absolute;
          top: 0;
          right: 0;
          width: 50%;
          height: 100%;
          background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBvcGFjaXR5PSIwLjEiPjxwYXRoIGQ9Ik0wIDIwQzAgOC45NSA4Ljk1IDAgMjAgMEMzMS4wNSAwIDQwIDguOTUgNDAgMjBDNDAgMzEuMDUgMzEuMDUgNDAgMjAgNDBDOC45NSA0MCAwIDMxLjA1IDAgMjBaIiBmaWxsPSIjMjU2M0VCIi8+PHBhdGggZD0iTTIwIDBDOC45NSAwIDAgOC45NSAwIDIwQzAgMzEuMDUgOC45NSA0MCAyMCA0MEMzMS4wNSA0MCA0MCAzMS4wNSA0MCAyMEM0MCA4Ljk1IDMxLjA1IDAgMjAgMFoiIGZpbGw9IiNGNTlFMEIiLz48L2c+PC9zdmc+');
          background-size: 40px 40px;
          opacity: 0.3;
          z-index: 1;
        }

        /* Benefits Section */
        .benefits {
          padding: 80px 0;
          background-color: var(--white);
        }

        .section-header {
          text-align: center;
          margin-bottom: 50px;
        }

        .benefits-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 30px;
        }

        .benefit-card {
          background-color: var(--light);
          padding: 30px;
          border-radius: 8px;
          box-shadow: var(--shadow);
          transition: var(--transition);
          border: 1px solid var(--light-gray);
        }

        .benefit-card:hover {
          transform: translateY(-5px);
          box-shadow: var(--shadow-lg);
        }

        .benefit-card img {
          height: 60px;
          margin-bottom: 20px;
        }

        .benefit-card h3 {
          color: var(--primary);
        }

        /* App Showcase */
        .app-showcase {
          padding: 80px 0;
          background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
          color: var(--white);
        }

        .app-showcase .container {
          display: flex;
          align-items: center;
          gap: 50px;
        }

        .app-showcase .content {
          flex: 1;
        }

        .app-showcase .image {
          flex: 1;
          text-align: center;
        }

        .app-showcase .image img {
          max-height: 500px;
        }

        .app-showcase h2,
        .app-showcase p {
          color: var(--white);
        }

        .app-showcase p {
          opacity: 0.9;
        }

        /* Testimonials */
        .testimonials {
          padding: 80px 0;
          background-color: var(--light);
        }

        .testimonials-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 30px;
          margin-top: 40px;
        }

        .testimonial-card {
          background-color: var(--white);
          padding: 30px;
          border-radius: 8px;
          box-shadow: var(--shadow);
          position: relative;
        }

        .testimonial-card:before {
          content: '"';
          position: absolute;
          top: 20px;
          left: 20px;
          font-size: 60px;
          color: var(--light-gray);
          font-family: serif;
          line-height: 1;
        }

        .testimonial-content {
          margin-bottom: 20px;
          padding-left: 30px;
        }

        .testimonial-author {
          display: flex;
          align-items: center;
          gap: 15px;
        }

        .testimonial-author img {
          width: 50px;
          height: 50px;
          border-radius: 50%;
          object-fit: cover;
        }

        .author-info h4 {
          margin-bottom: 5px;
        }

        .author-info p {
          font-size: 0.9rem;
        }

        /* CTA Section */
        .cta-section {
          padding: 80px 0;
          text-align: center;
          background-color: var(--white);
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
        .form-group select {
          width: 100%;
          padding: 12px 15px;
          border: 1px solid var(--light-gray);
          border-radius: 6px;
          font-family: 'Montserrat', sans-serif;
          transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus {
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
          .hero .container,
          .app-showcase .container {
            flex-direction: column;
            text-align: center;
          }
          
          .hero-content, 
          .hero-image,
          .app-showcase .content,
          .app-showcase .image {
            flex: none;
            width: 100%;
          }
          
          .hero {
            padding: 150px 0 60px;
          }
          
          .cta-buttons {
            justify-content: center;
          }

          .app-showcase .image img {
            max-height: 400px;
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

          .app-badges {
            justify-content: center;
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
                    alt="Logo Fretes em Nuvens - Plataforma de fretes para motoristas" 
                    width="180">
                </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="#vantagens">Vantagens</a></li>
                    <li><a href="#depoimentos">Depoimentos</a></li>
                    <li><a href="#app">Conheça o App</a></li>
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
                <h1>Mais <span class="text-primary">fretes</span>, menos tempo <span class="text-secondary">parado</span></h1>
                <p class="lead">Baixe o app e tenha acesso a centenas de fretes disponíveis diariamente. Ganhe mais com menos tempo ocioso.</p>
                <div class="cta-buttons">
                    <a href="#download" class="btn btn-primary btn-large">
                        <img src="images/icon-download.png" alt="Download" width="20">
                        Baixar App Agora
                    </a>
                    <a href="#vantagens" class="btn btn-secondary btn-large">Conheça as Vantagens</a>
                </div>
                <div class="app-badges">
                    <a href="https://play.google.com/store" target="_blank">
                        <img src="images/google-play-badge.png" alt="Disponível no Google Play" class="app-badge">
                    </a>
                    <a href="https://www.apple.com/br/app-store/" target="_blank">
                        <img src="images/app-store-badge.png" alt="Baixar na App Store" class="app-badge">
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="images/app-mockup-hero.png" loading="lazy" alt="App Fretes em Nuvens para motoristas">
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="vantagens" class="benefits">
        <div class="container">
            <div class="section-header">
                <h2>Por que baixar o app Fretes em Nuvens?</h2>
                <p class="lead">Tudo que você precisa para encontrar os melhores fretes</p>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-card">
                    <img src="images/icon-fretes.png" alt="Ícone de fretes disponíveis" width="60">
                    <h3>+300 fretes diários</h3>
                    <p>Tenha acesso a centenas de oportunidades de fretes em todo o Brasil</p>
                </div>
                <div class="benefit-card">
                    <img src="images/icon-dinheiro.png" alt="Ícone de dinheiro" width="60">
                    <h3>Sem taxas escondidas</h3>
                    <p>Você recebe 100% do valor combinado, sem intermediários</p>
                </div>
                <div class="benefit-card">
                    <img src="images/icon-rapido.png" alt="Ícone de rapidez" width="60">
                    <h3>Pagamento rápido</h3>
                    <p>Receba em até 48h após a entrega, direto na sua conta</p>
                </div>
                <div class="benefit-card">
                    <img src="images/icon-avaliacao.png" alt="Ícone de avaliação" width="60">
                    <h3>Avaliações transparentes</h3>
                    <p>Construa sua reputação e seja escolhido pelas melhores empresas</p>
                </div>
                <div class="benefit-card">
                    <img src="images/icon-rastreamento.png" alt="Ícone de rastreamento" width="60">
                    <h3>Rastreamento fácil</h3>
                    <p>Compartilhe sua localização em tempo real com os clientes</p>
                </div>
                <div class="benefit-card">
                    <img src="images/icon-suporte.png" alt="Ícone de suporte" width="60">
                    <h3>Suporte 24/7</h3>
                    <p>Nossa equipe está sempre disponível para te ajudar</p>
                </div>
            </div>
        </div>
    </section>

    <!-- App Showcase -->
    <section id="app" class="app-showcase">
        <div class="container">
            <div class="content">
                <h2>O aplicativo feito para <span class="text-secondary">motoristas</span></h2>
                <p class="lead">Interface simples e intuitiva para você encontrar os melhores fretes em poucos cliques</p>
                
                <ul style="margin: 30px 0 40px; list-style: none;">
                    <li style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <img src="images/icon-check.png" alt="Check" width="20">
                        <span>Visualize fretes próximos a você</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <img src="images/icon-check.png" alt="Check" width="20">
                        <span>Filtre por tipo de carga e veículo</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <img src="images/icon-check.png" alt="Check" width="20">
                        <span>Negocie direto com o contratante</span>
                    </li>
                    <li style="display: flex; align-items: center; gap: 10px;">
                        <img src="images/icon-check.png" alt="Check" width="20">
                        <span>Acompanhe seus ganhos mensais</span>
                    </li>
                </ul>
                
                <div class="cta-buttons">
                    <a href="#download" class="btn btn-secondary btn-large">
                        <img src="images/icon-download-white.png" alt="Download" width="20">
                        Baixar Agora
                    </a>
                </div>
            </div>
            <div class="image">
                <img src="images/app-screens.png" loading="lazy" alt="Telas do app Fretes em Nuvens">
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="depoimentos" class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2>O que dizem os motoristas</h2>
                <p class="lead">Quem já usa o app recomenda</p>
            </div>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>Desde que comecei a usar o app, minha renda aumentou em 40%. Consigo encontrar fretes de volta e reduzir muito meu tempo parado.</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/motorista1.jpg" alt="João Silva">
                        <div class="author-info">
                            <h4>João Silva</h4>
                            <p>Caminhoneiro há 12 anos</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>O aplicativo é muito fácil de usar e o pagamento é rápido. Não preciso mais ficar dependendo de intermediários para conseguir fretes.</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/motorista2.jpg" alt="Carlos Oliveira">
                        <div class="author-info">
                            <h4>Carlos Oliveira</h4>
                            <p>Motorista autônomo</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>O que mais gosto é da transparência. Consigo ver todas as informações do frete antes de aceitar e negociar direto com o cliente.</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="images/motorista3.jpg" alt="Roberto Santos">
                        <div class="author-info">
                            <h4>Roberto Santos</h4>
                            <p>Carreteiro</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="download" class="cta-section">
        <div class="container">
            <h2>Pronto para aumentar seus ganhos?</h2>
            <p class="lead" style="margin-bottom: 30px;">Baixe agora o app Fretes em Nuvens e comece a receber propostas hoje mesmo</p>
            
            <div class="app-badges" style="justify-content: center;">
                <a href="https://play.google.com/store" target="_blank">
                    <img src="images/google-play-badge.png" alt="Disponível no Google Play" class="app-badge">
                </a>
                <a href="https://www.apple.com/br/app-store/" target="_blank">
                    <img src="images/app-store-badge.png" alt="Baixar na App Store" class="app-badge">
                </a>
            </div>
        </div>
    </section>

    <!-- Form Section -->
    <section id="contato" class="form-section">
        <div class="container">
            <div class="form-container">
                <h2 class="text-center">Ainda com dúvidas?</h2>
                <p class="text-center" style="margin-bottom: 30px;">Fale com nossa equipe e vamos te ajudar</p>
                
                <form id="contact-form">
                    <div class="form-group">
                        <label for="name">Nome Completo</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">WhatsApp</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="vehicle">Tipo de Veículo</label>
                        <select id="vehicle" name="vehicle" required>
                            <option value="">Selecione...</option>
                            <option value="vuc">VUC</option>
                            <option value="toco">Toco</option>
                            <option value="truck">Truck</option>
                            <option value="carreta">Carreta</option>
                            <option value="bitrem">Bitrem</option>
                            <option value="rodotrem">Rodotrem</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Sua dúvida (opcional)</label>
                        <textarea id="message" name="message" rows="4"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </section>

    <!-- WhatsApp Widget -->
    <div class="whatsapp-widget">
      <a href="https://wa.me/5541996077879?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20o%20app%20para%20motoristas" 
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
                    <a href="#"><img src="images/icon-facebook-white.png" alt="Facebook" width="24"></a>
                    <a href="#"><img src="images/icon-instagram-white.png" alt="Instagram" width="24"></a>
                    <a href="#"><img src="images/icon-youtube-white.png" alt="YouTube" width="24"></a>
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
                submitBtn.style.backgroundColor = 'var(--secondary)';
                
                // Redirect to thank you page or show message
                setTimeout(function() {
                  window.location.href = 'obrigado.html';
                }, 1500);
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
            const elements = document.querySelectorAll('.benefit-card, .testimonial-card');
            
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
        });

        // Schema Markup
        const schemaScript = document.createElement('script');
        schemaScript.type = 'application/ld+json';
        schemaScript.text = JSON.stringify({
          "@context": "https://schema.org",
          "@type": "MobileApplication",
          "name": "Fretes em Nuvens - Motoristas",
          "url": "https://motorista.fretesemnuvens.com.br",
          "description": "Aplicativo para motoristas encontrarem fretes com facilidade e aumentar seus ganhos",
          "operatingSystem": "Android, iOS",
          "applicationCategory": "BusinessApplication",
          "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "BRL"
          }
        });
        document.head.appendChild(schemaScript);
    </script>
</body>
</html>