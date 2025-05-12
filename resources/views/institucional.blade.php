<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Meta Tags Essenciais -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Conectamos empresas que precisam de fretes com motoristas qualificados através de nossa plataforma digital. Solução rápida, segura e eficiente para seu transporte de cargas.">
    <meta name="keywords" content="fretes, transporte de cargas, motoristas autônomos, logística, plataforma de fretes, agendamento de fretes, frete online">
    <meta name="author" content="Fretes em Nuvens">
    <meta property="og:title" content="Fretes em Nuvens - Plataforma Digital para Fretes">
    <meta property="og:description" content="Solução completa para conectar empresas e motoristas de fretes.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.seusite.com">
    <meta property="og:image" content="https://www.seusite.com/images/og-image.jpg">
    
    <title>Fretes em Nuvens | Plataforma Digital para Fretes</title>
    
    <!-- Favicon -->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="http://www.fretesemnuvens.com.br" />
    
    <!-- CSS Inline -->
    <style>
        /* Reset e Base */
        :root {
          --primary-color: #2A5BDD;
          --secondary-color: #28A745;
          --dark-color: #2D3748;
          --light-color: #F7FAFC;
          --gray-color: #718096;
          --light-gray: #E2E8F0;
          --white: #FFFFFF;
          --black: #000000;
          --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
          color: var(--dark-color);
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
        }

        .lead {
          font-size: 1.25rem;
          font-weight: 500;
        }

        .section-description {
          color: var(--gray-color);
          margin-bottom: 2rem;
          text-align: center;
        }

        /* Botões */
        .btn {
          display: inline-block;
          padding: 12px 24px;
          border-radius: 4px;
          font-weight: 500;
          text-align: center;
          transition: var(--transition);
          cursor: pointer;
        }

        .btn-primary {
          background-color: var(--primary-color);
          color: var(--white);
          border: 2px solid var(--primary-color);
        }

        .btn-primary:hover {
          background-color: transparent;
          color: var(--primary-color);
        }

        .btn-secondary {
          background-color: var(--secondary-color);
          color: var(--white);
          border: 2px solid var(--secondary-color);
        }

        .btn-secondary:hover {
          background-color: transparent;
          color: var(--secondary-color);
        }

        .btn-outline {
          background-color: transparent;
          color: var(--primary-color);
          border: 2px solid var(--primary-color);
        }

        .btn-outline:hover {
          background-color: var(--primary-color);
          color: var(--white);
        }

        .btn-large {
          padding: 15px 30px;
          font-size: 1.1rem;
        }

        /* Header */
        .main-header {
          background-color: #f0f8ff; /* Azul Alice - bem clarinho */
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          background-color: var(--white);
          box-shadow: var(--box-shadow);
          z-index: 1000;
          padding: 15px 0;
        }

        .main-header .container {
          display: flex;
          justify-content: space-between;
          align-items: center;
        }

        .main-nav ul {
          display: flex;
          align-items: center;
          gap: 20px;
        }

        .main-nav a {
          font-weight: 500;
          transition: var(--transition);
        }

        .main-nav a:hover {
          color: var(--primary-color);
        }

        /* Hero Section */
        .hero {
          padding: 250px 0 80px;
          background: linear-gradient(135deg, #F7FAFC 0%, #EDF2F7 100%);
        }

        .hero .container {
          display: flex;
          align-items: center;
          gap: 50px;
        }

        .hero-content {
          flex: 1;
        }

        .hero-image {
          flex: 1;
        }

        .cta-buttons {
          display: flex;
          gap: 15px;
          margin-top: 30px;
        }

        /* Highlights */
        .highlights {
          padding: 60px 0;
          background-color: var(--white);
        }

        .highlights .container {
          display: flex;
          justify-content: space-around;
          text-align: center;
        }

        .highlight-item {
          padding: 0 20px;
        }

        .highlight-item img {
          margin-bottom: 15px;
        }

        /* How It Works */
        .how-it-works {
          padding: 80px 0;
          background-color: var(--light-color);
          text-align: center;
        }

        .steps {
          display: flex;
          justify-content: space-between;
          margin-top: 50px;
          flex-wrap: wrap;
          gap: 20px;
        }

        .step {
          flex: 1;
          min-width: 200px;
          padding: 30px 20px;
          background-color: var(--white);
          border-radius: 8px;
          box-shadow: var(--box-shadow);
          transition: var(--transition);
        }

        .step:hover {
          transform: translateY(-10px);
        }

        .step-number {
          display: inline-block;
          width: 40px;
          height: 40px;
          background-color: var(--primary-color);
          color: var(--white);
          border-radius: 50%;
          font-weight: 700;
          line-height: 40px;
          margin-bottom: 20px;
        }

        /* For Companies & For Drivers */
        .for-companies, .for-drivers {
          padding: 80px 0;
        }

        .for-companies .container, .for-drivers .container {
          display: flex;
          align-items: center;
          gap: 50px;
        }

        .for-companies .content, .for-drivers .content {
          flex: 1;
        }

        .for-companies .image, .for-drivers .image {
          flex: 1;
        }

        .benefits {
          margin: 30px 0;
        }

        .benefits li {
          display: flex;
          align-items: center;
          margin-bottom: 15px;
          gap: 10px;
        }

        /* Testimonials */
        .testimonials {
          padding: 80px 0;
          background-color: var(--light-color);
          text-align: center;
        }

        .testimonial-slider {
          display: flex;
          gap: 30px;
          margin-top: 50px;
          overflow-x: auto;
          padding-bottom: 20px;
          scroll-snap-type: x mandatory;
        }

        .testimonial {
          min-width: 350px;
          background-color: var(--white);
          padding: 30px;
          border-radius: 8px;
          box-shadow: var(--box-shadow);
          scroll-snap-align: start;
        }

        .quote {
          margin-bottom: 20px;
          text-align: left;
        }

        .author {
          display: flex;
          align-items: center;
          gap: 15px;
          text-align: left;
        }

        .author img {
          border-radius: 50%;
        }

        .author .info h4 {
          margin-bottom: 5px;
        }

        /* Final CTA */
        .final-cta {
          padding: 80px 0;
          text-align: center;
          background: linear-gradient(135deg, var(--primary-color) 0%, #1E429F 100%);
          color: var(--white);
        }

        /* Footer */
        .main-footer {
          background-color: var(--dark-color);
          color: var(--white);
          padding: 60px 0 0;
        }

        .footer-links {
          display: flex;
          justify-content: space-between;
          flex-wrap: wrap;
          gap: 30px;
          margin-bottom: 60px;
        }

        .links-column {
          flex: 1;
          min-width: 200px;
        }

        .links-column h4 {
          margin-bottom: 20px;
          font-size: 1.1rem;
        }

        .links-column ul li {
          margin-bottom: 10px;
        }

        .links-column a:hover {
          color: var(--light-gray);
        }

        .social-links {
          display: flex;
          gap: 15px;
          margin-top: 15px;
        }

        .footer-bottom {
          background-color: rgba(0, 0, 0, 0.2);
          padding: 20px 0;
        }

        .footer-bottom .container {
          display: flex;
          justify-content: space-between;
          align-items: center;
        }

        .payment-methods img {
          opacity: 0.8;
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
          .for-companies .container,
          .for-drivers .container {
            flex-direction: column;
          }
          
          .for-drivers .container {
            flex-direction: column-reverse;
          }
          
          .hero-content, 
          .hero-image,
          .for-companies .content,
          .for-companies .image,
          .for-drivers .content,
          .for-drivers .image {
            flex: none;
            width: 100%;
          }
          
          .hero {
            padding: 120px 0 60px;
          }
          
          .main-nav ul {
            gap: 15px;
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
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background-color: var(--white);
            flex-direction: column;
            padding: 20px;
            box-shadow: var(--box-shadow);
          }
          
          .mobile-menu-btn {
            display: block;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
          }
          
          .steps {
            flex-direction: column;
          }
          
          .highlight-item {
            margin-bottom: 30px;
          }
          
          .cta-buttons {
            flex-direction: column;
          }
          
          .btn {
            width: 100%;
          }

          .whatsapp-widget {
            bottom: 20px;
            right: 20px;
          }
          
          .whatsapp-link {
            width: 50px;
            height: 50px;
          }
          
          .whatsapp-link img {
            width: 30px;
            height: 30px;
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
   <header class="main-header" style="background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%); box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
    <div class="container">
        <div class="logo">
            <a href="/">
                <img src="{{ asset('images/logo_fretes2.png') }}" 
                alt="Fretes em Nuvens - Plataforma de Fretes" width="180"
                >
            </a>
        </div>
        <nav class="main-nav">
            <ul>
                <li><a href="#como-funciona" style="color: white;">Como Funciona</a></li>
                <li><a href="#para-empresas" style="color: white;">Para Empresas</a></li>
                <li><a href="#para-motoristas" style="color: white;">Para Motoristas</a></li>
                <li><a href="#contato" style="color: white;">Contato</a></li>
                <li><a href="/login" class="btn btn-outline" style="border-color: white; color: white;">Entrar</a></li>
                <li><a href="/cadastro" class="btn btn-primary" style="background-color: white; color: #3498db; border-color: white;">Cadastre-se</a></li>
            </ul>
        </nav>
    </div>
</header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Conectamos empresas a motoristas de fretes de forma inteligente</h1>
                <p class="lead">Solução digital completa para agilizar seu transporte de cargas com segurança e eficiência.</p>
                <div class="cta-buttons">
                    <a href="#para-empresas" class="btn btn-primary btn-large">Preciso de Frete</a>
                    <a href="#para-motoristas" class="btn btn-secondary btn-large">Sou Motorista</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/mascote-fretes-em-nuvens.png') }}" loading="lazy">
            </div>
        </div>
    </section>

    <!-- Seção de Destaques -->
    <section class="highlights">
        <div class="container">
            <div class="highlight-item">
                <img src="{{ asset('images/icon-motoristas.png') }}" alt="Motoristas cadastrados" width="60">
                <h3>+5.000 Motoristas</h3>
                <p>Cadastrados e verificados em nossa plataforma</p>
            </div>
            <div class="highlight-item">
                <img src="{{ asset('images/icon-empresas.png') }}" alt="Empresas parceiras" width="60">
                <h3>+300 Empresas</h3>
                <p>Utilizando nossos serviços regularmente</p>
            </div>
            <div class="highlight-item">
                <img src="{{ asset('images/icon-fretes.png') }}" alt="Fretes realizados" width="60">
                <h3>+20.000 Fretes</h3>
                <p>Realizados com sucesso através da plataforma</p>
            </div>
        </div>
    </section>

    <!-- Como Funciona -->
    <section id="como-funciona" class="how-it-works">
        <div class="container">
            <h2>Como funciona nossa plataforma</h2>
            <p class="section-description">Um processo simples em poucos passos</p>
            
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                   
                    <h3>Cadastro</h3>
                    <p>Empresas e motoristas se cadastram em nossa plataforma</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                 
                    <h3>Publicação</h3>
                    <p>Empresas publicam suas necessidades de fretes</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                 
                    <h3>Propostas</h3>
                    <p>Motoristas candidatam-se aos fretes disponíveis</p>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                  
                    <h3>Fechamento</h3>
                    <p>A empresa aprova o candidato e o frete é realizado</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Para Empresas -->
    <section id="para-empresas" class="for-companies">
        <div class="container">
            <div class="content">
                <h2>Solução completa para sua empresa</h2>
                <ul class="benefits">
                    <li>
                        <img src="{{ asset('images/icone-check.png') }}" alt="Benefício" width="24">
                        <span>Encontre motoristas confiáveis rapidamente</span>
                    </li>
                    <li>
                        <img src="{{ asset('images/icone-check.png') }}" alt="Benefício" width="24">
                        <span>Escolha o melhor o caminhão perfeito para o transporte</span>
                    </li>
                    <li>
                        <img src="{{ asset('images/icone-check.png') }}" alt="Benefício" width="24">
                        <span>Acompanhe seus fretes em tempo real</span>
                    </li>
                    <li>
                        <img src="{{ asset('images/icone-check.png') }}" alt="Benefício" width="24">
                        <span>Reduza custos com transporte</span>
                    </li>
                    <li>
                        <img src="{{ asset('images/icone-check.png') }}" alt="Benefício" width="24">
                        <span>Gestão centralizada de todos seus fretes</span>
                    </li>
                </ul>
                <a href="cadastro-empresa.html" class="btn btn-primary">Cadastre sua empresa</a>
            </div>
            <div class="image">
                <img src="images/for-companies.png" alt="Solução para empresas" loading="lazy">
            </div>
        </div>
    </section>

    <!-- Para Motoristas -->
    <section id="para-motoristas" class="for-drivers">
        <div class="container">
            <div class="image">
                <img src="images/for-drivers.png" alt="Oportunidades para motoristas" loading="lazy">
            </div>
            <div class="content">
                <h2>Mais oportunidades para motoristas</h2>
                <ul class="benefits">
                    <li>
                        <img src="{{ asset('images/icone-check.png') }}" alt="Benefício" width="24">
                        <span>Acesse fretes de diversas empresas</span>
                    </li>
                    <li>
                        <img src="{{ asset('images/icone-check.png') }}" alt="Benefício" width="24">
                        <span>Escolha o frete que melhor combina com seu caminhão</span>
                    </li>
                    <li>
                        <img src="{{ asset('images/icone-check.png') }}" alt="Benefício" width="24">
                        <span>Receba pagamentos com segurança</span>
                    </li>
                    <li>
                        <img src="{{ asset('images/icone-check.png') }}" alt="Benefício" width="24">
                        <span>Avaliações que aumentam sua reputação</span>
                    </li>
                    <li>
                        <img src="{{ asset('images/icone-check.png') }}" alt="Benefício" width="24">
                        <span>Trabalhe quando e como quiser</span>
                    </li>
                </ul>
                <a href="cadastro-motorista.html" class="btn btn-secondary">Cadastre-se como motorista</a>
            </div>
        </div>
    </section>

    <!-- Depoimentos -->
    <section class="testimonials">
        <div class="container">
            <h2>O que dizem sobre nós</h2>
            
            <div class="testimonial-slider">
                <div class="testimonial">
                    <div class="quote">
                        <img src="{{ asset('images/quote.png') }}" alt="Aspas" width="24">
                        <p>Desde que começamos a usar a plataforma, reduzimos em 30% nossos custos com fretes e ganhamos muito mais agilidade.

                        </p>
                    </div>
                    <div class="author">
                        <img src="{{ asset('images/client1.png') }}" alt="Cliente" width="60">
                        <div class="info">
                            <h4>Carlos Mendes</h4>
                            <p>Gerente de Logística - Empresa ABC</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial">
                    <div class="quote">
                        <img src="{{ asset('images/quote.png') }}" alt="Aspas" width="24">
                        <p>Como motorista autônomo, a plataforma me permite escolher os melhores fretes e ter uma renda mais estável.</p>
                    </div>
                    <div class="author">
                        <img src="{{ asset('images/client1.png') }}" alt="Cliente" width="60">
                        <div class="info">
                            <h4>Roberto Silva</h4>
                            <p>Motorista parceiro há 2 anos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="final-cta">
        <div class="container">
            <h2>Pronto para otimizar seus fretes?</h2>
            <p>Junte-se a centenas de empresas e motoristas que já utilizam nossa plataforma</p>
            <div class="cta-buttons">
                <a href="cadastro-empresa.html" class="btn btn-primary btn-large">Sou Empresa</a>
                <a href="cadastro-motorista.html" class="btn btn-secondary btn-large">Sou Motorista</a>
            </div>
        </div>
    </section>

    <!-- WhatsApp Widget -->
    <div class="whatsapp-widget">
      <a href="https://wa.me/5541996077879?text=Olá,%20gostaria%20de%20mais%20informações%20sobre%20a%20plataforma%20Fretes%20em%20Nuvens" 
         class="whatsapp-link" 
         target="_blank"
         aria-label="Conversar pelo WhatsApp">
        <img src="{{ asset('images/icone-whatsapp.png') }}"></a>
    </div>

    <!-- Rodapé -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-logo">
                <img src="{{ asset('images/logo_fretes_em_nuvens3.png') }}" alt="Fretes em Nuvens" width="150">
                <p>Conectando empresas e motoristas de forma inteligente</p>
            </div>
            
            <div class="footer-links">
                <div class="links-column">
                    <h4>Empresa</h4>
                    <ul>
                        <li><a href="sobre.html">Sobre nós</a></li>
                        <li><a href="blog.html">Blog</a></li>
                        <li><a href="carreiras.html">Carreiras</a></li>
                        <li><a href="imprensa.html">Imprensa</a></li>
                    </ul>
                </div>
                
                <div class="links-column">
                    <h4>Recursos</h4>
                    <ul>
                        <li><a href="como-funciona.html">Como funciona</a></li>
                        <li><a href="seguranca.html">Segurança</a></li>
                        <li><a href="duvidas.html">Dúvidas</a></li>
                    </ul>
                </div>
                
                <div class="links-column">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="termos.html">Termos de uso</a></li>
                        <li><a href="privacidade.html">Política de privacidade</a></li>
                        <li><a href="cookies.html">Cookies</a></li>
                    </ul>
                </div>
                
                <div class="links-column">
                    <h4 id="contato">Contato</h4>
                    <ul>
                        <li><a href="mailto:contato@empresa.com">fretesnasnuvens.carlospugas@gmail.com</a></li>
                        <li><a href="tel:+5541996077879">(41) 99607-7879</a></li>
                        <li>
                            <div class="social-links">
                                <a href="#"><img src="{{ asset('images/icon-facebook.png') }}" alt="Facebook" width="24"></a>
                                <a href="#"><img src="images/icon-instagram.svg" alt="Instagram" width="24"></a>
                                <a href="#"><img src="images/icon-linkedin.svg" alt="LinkedIn" width="24"></a>
                                <a href="#"><img src="images/icon-youtube.svg" alt="YouTube" width="24"></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <span id="current-year">2023</span> Fretes em Nuvens. Todos os direitos reservados.</p>
                <div class="payment-methods">
                    <img src="images/payment-methods.png" alt="Métodos de pagamento" width="200">
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
          
          // Fechar menu ao clicar em um link
          const navLinks = document.querySelectorAll('.main-nav a');
          navLinks.forEach(link => {
            link.addEventListener('click', function() {
              if (window.innerWidth <= 768) {
                nav.style.display = 'none';
              }
            });
          });
          
          // Ajustar menu na mudança de tamanho da tela
          window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
              nav.style.display = 'flex';
            } else {
              nav.style.display = 'none';
            }
          });
          
          // Scroll suave para links internos
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
          
          // Animação de scroll
          const animateOnScroll = function() {
            const elements = document.querySelectorAll('.step, .highlight-item, .testimonial');
            
            elements.forEach(element => {
              const elementPosition = element.getBoundingClientRect().top;
              const screenPosition = window.innerHeight / 1.3;
              
              if (elementPosition < screenPosition) {
                element.classList.add('fade-in');
              }
            });
          };
          
          window.addEventListener('scroll', animateOnScroll);
          animateOnScroll(); // Executar uma vez ao carregar
          
          // Testimonial slider navigation
          const testimonialSlider = document.querySelector('.testimonial-slider');
          if (testimonialSlider) {
            let isDown = false;
            let startX;
            let scrollLeft;
            
            testimonialSlider.addEventListener('mousedown', (e) => {
              isDown = true;
              startX = e.pageX - testimonialSlider.offsetLeft;
              scrollLeft = testimonialSlider.scrollLeft;
            });
            
            testimonialSlider.addEventListener('mouseleave', () => {
              isDown = false;
            });
            
            testimonialSlider.addEventListener('mouseup', () => {
              isDown = false;
            });
            
            testimonialSlider.addEventListener('mousemove', (e) => {
              if (!isDown) return;
              e.preventDefault();
              const x = e.pageX - testimonialSlider.offsetLeft;
              const walk = (x - startX) * 2;
              testimonialSlider.scrollLeft = scrollLeft - walk;
            });
          }
          
          // Formulário de contato (exemplo)
          const contactForm = document.getElementById('contact-form');
          if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
              e.preventDefault();
              
              // Simular envio
              const submitBtn = this.querySelector('button[type="submit"]');
              const originalText = submitBtn.textContent;
              
              submitBtn.disabled = true;
              submitBtn.textContent = 'Enviando...';
              
              // Simular delay de rede
              setTimeout(() => {
                submitBtn.textContent = 'Enviado com sucesso!';
                
                // Resetar após 3 segundos
                setTimeout(() => {
                  submitBtn.textContent = originalText;
                  submitBtn.disabled = false;
                  this.reset();
                }, 3000);
              }, 1500);
            });
          }
          
          // Atualizar ano no footer
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
          "url": "http://www.fretesemnuvens.com.br",
          "logo": "http://www.fretesemnuvens.com.br/images/logo.svg",
          "description": "Plataforma digital que conecta empresas que precisam de fretes com motoristas qualificados",
          "sameAs": [
            "https://www.facebook.com/empresa",
            "https://www.instagram.com/empresa",
            "https://www.linkedin.com/company/empresa"
          ],
          "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+5511999999999",
            "contactType": "customer service",
            "email": "contato@empresa.com",
            "availableLanguage": "Portuguese"
          }
        });
        document.head.appendChild(schemaScript);
    </script>
</body>
</html>