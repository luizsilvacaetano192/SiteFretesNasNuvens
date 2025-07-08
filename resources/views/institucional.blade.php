<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Meta Tags Essenciais -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fretes em Nuvens | Plataforma de Fretes Online - Conectamos Empresas e Caminhoneiros</title>
    <meta name="description" content="Precisa de fretes ou é motorista de caminhão? Encontre a melhor solução para transporte de cargas. Plataforma mais barata que FreteBras, CargoX e TruckPad. Agende fretes rápidos em todo Brasil para mudanças, carretos e todos tipos de carga.">
    <meta name="keywords" content="plataforma de fretes, frete online, frete caminhão, contratar frete, frete para empresas, motorista autônomo, carga e descarga, transporte de carga, frete rodoviário, frete nacional, frete grátis, frete sem taxa, frete barato, frete rápido, JSL, Tegma, Braspress, FreteBras, CargoX, TruckPad, frete intermunicipal, frete interestadual, frete urbano, preciso de fretes, motorista de fretes, mudança, carreto, transporte, transportadora, aplicativo de transporte">
    <meta name="author" content="Fretes em Nuvens">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="7 days">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.fretesemnuvens.com.br/"> 
    <meta property="og:title" content="Fretes em Nuvens | A Melhor Plataforma de Fretes Online do Brasil">
    <meta property="og:description" content="Conectamos empresas que precisam de fretes com motoristas qualificados. Solução mais eficiente e econômica que FreteBras e CargoX. Experimente grátis!">
    <meta property="og:image" content="https://www.fretesemnuvens.com.br/images/og-fretes-em-nuvens.jpg"> 
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://www.fretesemnuvens.com.br/"> 
    <meta property="twitter:title" content="Fretes em Nuvens | A Melhor Plataforma de Fretes Online do Brasil">
    <meta property="twitter:description" content="Solução digital completa para fretes rodoviários. Mais barato que FreteBras, mais rápido que CargoX. Cadastre-se gratuitamente!">
    <meta property="twitter:image" content="https://www.fretesemnuvens.com.br/images/twitter-fretes-em-nuvens.jpg"> 
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://www.fretesemnuvens.com.br" />
    
    <!-- Geo Tags -->
    <meta name="geo.region" content="BR" />
    <meta name="geo.placename" content="Curitiba" />
    <meta name="geo.position" content="-25.4284;-49.2733" />
    <meta name="ICBM" content="-25.4284, -49.2733" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('images//favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }} sizes='any' " />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('images/site.webmanifest') }}" />
    
    <!-- Preload e Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="preload" href="{{ asset('images/logo_fretes_em_nuvens3.png') }}" as="image">
    <link rel="preload" href="{{ asset('images/mascote-fretes-em-nuvens.png') }}" as="image">
    
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
          background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
          padding: 12px 0;
          top: 0;
          left: 0;
          width: 100%;
          box-shadow: 0 2px 10px rgba(0,0,0,0.2);
          z-index: 1000;
          padding: 15px 0;
        }
        .main-header .container {
          display: flex;
          justify-content: space-between;
          align-items: center;
        }
       .logo img {
            height: auto;
            max-height: 60px; /* Aumentei de 50px para 60px */
            width: auto;
            max-width: 220px; /* Novo valor */
            min-width: 180px; /* Garante que não fique muito pequeno em mobile */
            transition: var(--transition);
        }
        .main-nav ul {
          display: flex;
          align-items: center;
          gap: 20px;
        }
        .main-nav a {
          font-weight: 500;
          color: white;
          transition: var(--transition);
        }
        .main-nav a:hover {
          color: var(--light-gray);
        }
        
        /* Hero Section */
        .hero {
          padding: 180px 0 80px;
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
        
        /* Nova Seção de Palavras-Chave */
        .keyword-target {
          padding: 60px 0;
          background-color: var(--light-color);
        }
        .keyword-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
          gap: 30px;
          margin-top: 40px;
        }
        .keyword-item {
          background-color: var(--white);
          padding: 30px;
          border-radius: 8px;
          box-shadow: var(--box-shadow);
          text-align: center;
          transition: var(--transition);
        }
        .keyword-item:hover {
          transform: translateY(-5px);
        }
        .keyword-item h3 {
          color: var(--primary-color);
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
          flex-wrap: wrap;
        }
        .highlight-item {
          padding: 0 20px;
          flex: 1;
          min-width: 200px;
          margin-bottom: 30px;
        }
        .highlight-item img {
          margin-bottom: 15px;
          height: 60px;
          width: auto;
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
        
        /* Comparison Section */
        .comparison {
          padding: 80px 0;
          background-color: var(--white);
        }
        .comparison-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 30px;
          margin-top: 40px;
        }
        .comparison-item {
          background-color: var(--light-color);
          padding: 30px;
          border-radius: 8px;
          box-shadow: var(--box-shadow);
          transition: var(--transition);
        }
        .comparison-item:hover {
          transform: translateY(-5px);
        }
        .comparison-item h3 {
          color: var(--primary-color);
        }
        
        /* Coverage Section */
        .coverage {
          padding: 80px 0;
          background-color: var(--light-color);
          text-align: center;
        }
        .cities-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
          gap: 30px;
          margin-top: 40px;
        }
        .city-group {
          background-color: var(--white);
          padding: 20px;
          border-radius: 8px;
          box-shadow: var(--box-shadow);
        }
        .city-group h3 {
          color: var(--primary-color);
          margin-bottom: 15px;
        }
        .city-group ul {
          text-align: left;
        }
        .city-group li {
          margin-bottom: 8px;
          position: relative;
          padding-left: 20px;
        }
        .city-group li:before {
          content: "•";
          color: var(--primary-color);
          position: absolute;
          left: 0;
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
        .benefits img {
          width: 24px;
          height: 24px;
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
        .quote img {
          width: 24px;
          height: auto;
          margin-bottom: 10px;
        }
        .author {
          display: flex;
          align-items: center;
          gap: 15px;
          text-align: left;
        }
        .author img {
          border-radius: 50%;
          width: 60px;
          height: 60px;
          object-fit: cover;
        }
        .author .info h4 {
          margin-bottom: 5px;
        }
        
        /* FAQ Section */
        .faq {
          padding: 80px 0;
          background-color: var(--white);
        }
        .faq .container {
          max-width: 800px;
        }
        .faq h2 {
          text-align: center;
          margin-bottom: 40px;
        }
        .faq h3 {
          color: var(--primary-color);
          margin-bottom: 15px;
          padding-bottom: 10px;
          border-bottom: 1px solid var(--light-gray);
        }
        .faq div[itemscope] {
          margin-bottom: 30px;
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
        .links-column a {
          color: var(--light-gray);
          transition: var(--transition);
        }
        .links-column a:hover {
          color: var(--white);
        }
        .social-links {
          display: flex;
          gap: 15px;
          margin-top: 15px;
        }
        .social-links img {
          width: 24px;
          height: 24px;
          opacity: 0.8;
          transition: var(--transition);
        }
        .social-links img:hover {
          opacity: 1;
        }
        .footer-bottom {
          background-color: rgba(0, 0, 0, 0.2);
          padding: 20px 0;
        }
        .footer-bottom .container {
          display: flex;
          justify-content: space-between;
          align-items: center;
          flex-wrap: wrap;
          gap: 20px;
        }
        .payment-methods img {
          opacity: 0.8;
          max-width: 200px;
          height: auto;
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
        
        /* Otimização de imagens */
        img[loading="lazy"] {
          opacity: 0;
          transition: opacity 0.3s ease;
        }
        img[loading="lazy"].loaded {
          opacity: 1;
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
            padding: 150px 0 60px;
          }
          .main-nav ul {
            gap: 15px;
          }
        }
        @media (max-width: 768px) {
              .logo img {
              max-height: 180px;
              max-width: 180px;
          }
                
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
            color: white;
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
    <header class="main-header">
        <div class="container">
           <div class="logo">
              <a href="/">
                  <img src="{{ asset('images/logo_fretes_em_nuvens3.png') }}" 
                  alt="Logo Fretes em Nuvens - Plataforma de fretes online para empresas e motoristas" 
                  width="220"> <!-- Aumentei de 180 para 220 -->
              </a>
          </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="#como-funciona">Como Funciona</a></li>
                    <li><a href="#para-empresas">Para Empresas</a></li>
                    <li><a href="#para-motoristas">Para Motoristas</a></li>
                    <li><a href="#cobertura">Cobertura</a></li>
                    <li><a href="#contato">Contato</a></li>
                    <li><a href="/login" class="btn btn-outline" style="border-color: white; color: white;">Entrar</a></li>
                    <li><a href="/cadastro" class="btn btn-primary" style="background-color: white; color: #3498db; border-color: white;">Cadastre-se</a></li>
                </ul>
                <button class="mobile-menu-btn">☰</button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>A Plataforma de Fretes Mais Completa do Brasil | Mais Econômica que FreteBras e CargoX</h1>
                <p class="lead">Solução completa para quem precisa de fretes ou quer oferecer serviços de transporte. Conectamos empresas a +5.000 motoristas verificados. Ideal para mudanças, carretos, cargas frigoríficas e todos tipos de transporte rodoviário.</p>
                <div class="cta-buttons">
                    <a href="#para-empresas" class="btn btn-primary btn-large">Preciso de Frete</a>
                    <a href="#para-motoristas" class="btn btn-secondary btn-large">Sou Motorista</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/mascote-fretes-em-nuvens.png') }}" loading="lazy" alt="Plataforma de fretes online Fretes em Nuvens">
            </div>
        </div>
    </section>

    <!-- Nova Seção de Palavras-Chave -->
    <section class="keyword-target">
        <div class="container">
            <h2>Encontre a solução ideal para seu transporte</h2>
            <p class="section-description">Plataforma completa para quem precisa de fretes ou quer oferecer serviços de transporte</p>
            <div class="keyword-grid">
                <div class="keyword-item">
                    <h3>Preciso de Fretes</h3>
                    <p>Encontre motoristas confiáveis para transportar sua carga em todo Brasil. Solução mais rápida e econômica que FreteBras e TruckPad.</p>
                    <a href="#para-empresas" class="btn btn-outline">Solicitar Frete</a>
                </div>
                <div class="keyword-item">
                    <h3>Sou Motorista de Caminhão</h3>
                    <p>Encontre fretes para seu caminhão e aumente sua renda. Plataforma com mais oportunidades que CargoX e FreteBras.</p>
                    <a href="#para-motoristas" class="btn btn-outline">Oferecer Serviços</a>
                </div>
                <div class="keyword-item">
                    <h3>Serviços de Mudança</h3>
                    <p>Precisa de carreto ou mudança? Conectamos você aos melhores profissionais para transporte de móveis e objetos.</p>
                    <a href="#mudancas" class="btn btn-outline">Solicitar Mudança</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Destaques -->
    <section class="highlights">
        <div class="container">
            <div class="highlight-item">
                <img src="{{ asset('images/icon-motoristas.png') }}" alt="Ícone de motoristas cadastrados" width="60">
                <h3>+5.000 Motoristas</h3>
                <p>Cadastrados e verificados em nossa plataforma</p>
            </div>
            <div class="highlight-item">
                <img src="{{ asset('images/icon-empresas.png') }}" alt="Ícone de empresas parceiras" width="60">
                <h3>+300 Empresas</h3>
                <p>Utilizando nossos serviços regularmente</p>
            </div>
            <div class="highlight-item">
                <img src="{{ asset('images/icon-fretes.png') }}" alt="Ícone de fretes realizados" width="60">
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

    <!-- Comparação com Concorrentes -->
    <section class="comparison">
        <div class="container">
            <h2>Por que escolher a Fretes em Nuvens?</h2>
            <p class="section-description">Comparado a outras soluções como FreteBras, CargoX e TruckPad, oferecemos:</p>
            <div class="comparison-grid">
                <div class="comparison-item">
                    <h3>✔ Sem Taxas</h3>
                    <p>O valor oferecido pelo frete vai totalmente pra o seu bolso</p>
                </div>
                <div class="comparison-item">
                    <h3>✔ Cadastro simplificado</h3>
                    <p>Processo mais rápido que em FreteBras e CargoX</p>
                </div>
                <div class="comparison-item">
                    <h3>✔ Suporte dedicado</h3>
                    <p>Atendimento personalizado, diferente das grandes transportadoras</p>
                </div>
                <div class="comparison-item">
                    <h3>✔ Tecnologia avançada</h3>
                    <p>Plataforma mais moderna que Rodonaves e Expresso São Miguel</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Cobertura -->
    <section id="cobertura" class="coverage">
        <div class="container">
            <h2>Atendemos todo o território nacional</h2>
            <div class="cities-grid">
                <div class="city-group">
                    <h3>Região Sul</h3>
                    <ul>
                        <li>Curitiba/PR</li>
                        <li>Porto Alegre/RS</li>
                        <li>Florianópolis/SC</li>
                        <li>Joinville/SC</li>
                        <li>Londrina/PR</li>
                    </ul>
                </div>
                <div class="city-group">
                    <h3>Região Sudeste</h3>
                    <ul>
                        <li>São Paulo/SP</li>
                        <li>Rio de Janeiro/RJ</li>
                        <li>Belo Horizonte/MG</li>
                        <li>Campinas/SP</li>
                        <li>Vitória/ES</li>
                    </ul>
                </div>
                <div class="city-group">
                    <h3>Região Nordeste</h3>
                    <ul>
                        <li>Salvador/BA</li>
                        <li>Recife/PE</li>
                        <li>Fortaleza/CE</li>
                        <li>Maceió/AL</li>
                        <li>Natal/RN</li>
                    </ul>
                </div>
                <div class="city-group">
                    <h3>Região Centro-Oeste</h3>
                    <ul>
                        <li>Brasília/DF</li>
                        <li>Goiânia/GO</li>
                        <li>Campo Grande/MS</li>
                        <li>Cuiabá/MT</li>
                    </ul>
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
                        <span>Escolha o melhor caminhão para o transporte</span>
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
                <img src="images/for-companies.png" alt="Solução para empresas que precisam de fretes" loading="lazy">
            </div>
        </div>
    </section>

    <!-- Para Motoristas -->
    <section id="para-motoristas" class="for-drivers">
        <div class="container">
            <div class="image">
                <img src="images/for-drivers.png" alt="Oportunidades para motoristas de caminhão" loading="lazy">
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
            <div class="testimonial" itemscope itemtype="https://schema.org/Review">
                <meta itemprop="itemReviewed" itemscope itemtype="https://schema.org/Service" />
                <meta itemprop="name" content="Plataforma de Gestão de Fretes" />
                
                <div class="quote" itemprop="reviewBody">
                    <img src="{{ asset('images/quote.png') }}" alt="Aspas" width="24">
                    <p>Desde que começamos a usar a plataforma, reduzimos em 30% nossos custos com fretes e ganhamos muito mais agilidade.</p>
                </div>
                <div class="author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <img src="{{ asset('images/client1.png') }}" alt="Foto de Carlos Mendes" width="60">
                    <div class="info">
                        <h4 itemprop="name">Carlos Mendes</h4>
                        <p itemprop="worksFor">Gerente de Logística - Empresa ABC</p>
                        <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                            <meta itemprop="ratingValue" content="5">
                            <meta itemprop="bestRating" content="5">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Repetir para os demais depoimentos -->
            <div class="testimonial" itemscope itemtype="https://schema.org/Review">
                <meta itemprop="itemReviewed" itemscope itemtype="https://schema.org/Service" />
                <meta itemprop="name" content="Plataforma de Gestão de Fretes" />
                
                <div class="quote" itemprop="reviewBody">
                    <img src="{{ asset('images/quote.png') }}" alt="Aspas" width="24">
                    <p>Como motorista autônomo, a plataforma me permite escolher os melhores fretes e ter uma renda mais estável.</p>
                </div>
                <div class="author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <img src="{{ asset('images/client1.png') }}" alt="Foto de Roberto Silva" width="60">
                    <div class="info">
                        <h4 itemprop="name">Roberto Silva</h4>
                        <p>Motorista parceiro há 2 anos</p>
                        <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                            <meta itemprop="ratingValue" content="5">
                            <meta itemprop="bestRating" content="5">
                        </div>
                    </div>
                </div>
            </div>

            <div class="testimonial" itemscope itemtype="https://schema.org/Review">
                <meta itemprop="itemReviewed" itemscope itemtype="https://schema.org/Service" />
                <meta itemprop="name" content="Plataforma de Gestão de Fretes" />
                
                <div class="quote" itemprop="reviewBody">
                    <img src="{{ asset('images/quote.png') }}" alt="Aspas" width="24">
                    <p>Conseguimos otimizar nossa frota usando a plataforma, reduzindo ociosidade e aumentando a produtividade.</p>
                </div>
                <div class="author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                    <img src="{{ asset('images/client1.png') }}" alt="Foto de Ana Paula Souza" width="60">
                    <div class="info">
                        <h4 itemprop="name">Ana Paula Souza</h4>
                        <p>Diretora de Logística - Transportadora XYZ</p>
                        <div itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                            <meta itemprop="ratingValue" content="5">
                            <meta itemprop="bestRating" content="5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- FAQ -->
    <section class="faq">
        <div class="container">
            <h2>Perguntas Frequentes</h2>
            <div itemscope itemtype="https://schema.org/FAQPage"> 
                <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"> 
                    <h3 itemprop="name">Como a Fretes em Nuvens se compara ao FreteBras?</h3>
                    <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"> 
                        <div itemprop="text">
                            <p>Nossa plataforma oferece taxas mais competitivas que o FreteBras, interface mais intuitiva e suporte mais ágil. Diferente do FreteBras, focamos em atendimento personalizado para cada cliente.</p>
                        </div>
                    </div>
                </div>
                <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"> 
                    <h3 itemprop="name">É melhor que contratar a JSL ou Tegma?</h3>
                    <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"> 
                        <div itemprop="text">
                            <p>Para empresas que buscam agilidade e custo-benefício, somos a melhor opção. Enquanto JSL e Tegma são tradicionais com estruturas grandes, oferecemos tecnologia moderna e preços mais acessíveis para o mesmo serviço de qualidade.</p>
                        </div>
                    </div>
                </div>
                <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"> 
                    <h3 itemprop="name">Como se compara ao TruckPad e CargoX?</h3>
                    <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"> 
                        <div itemprop="text">
                            <p>Temos a tecnologia similar ao TruckPad e CargoX, mas com foco maior na experiência do usuário e suporte personalizado. Nossa plataforma é mais intuitiva para motoristas autônomos e pequenas transportadoras.</p>
                        </div>
                    </div>
                </div>
                <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"> 
                    <h3 itemprop="name">Como funciona para quem precisa de fretes urgentes?</h3>
                    <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"> 
                        <div itemprop="text">
                            <p>Para fretes urgentes, nossa plataforma conecta você diretamente com motoristas disponíveis na região, com tempo médio de resposta de menos de 15 minutos. Solução mais rápida que FreteBras e outras transportadoras tradicionais.</p>
                        </div>
                    </div>
                </div>
                <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"> 
                    <h3 itemprop="name">Quais tipos de frete vocês atendem?</h3>
                    <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"> 
                        <div itemprop="text">
                            <p>Atendemos todos os tipos de fretes rodoviários: cargas gerais, frigoríficas, perigosas, granéis, mudanças, veículos e muito mais. Trabalhamos com caminhões de diversos portes, desde vucs até carretas.</p>
                        </div>
                    </div>
                </div>
                <div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question"> 
                    <h3 itemprop="name">Como é feita a segurança dos fretes?</h3>
                    <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"> 
                        <div itemprop="text">
                            <p>Todos os motoristas passam por verificação de documentos e antecedentes. Além disso, oferecemos rastreamento em tempo real, seguro de carga e sistema de avaliações para garantir a segurança de todas as operações.</p>
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
        <img src="{{ asset('images/icone-whatsapp.png') }}" alt="WhatsApp Fretes em Nuvens">
      </a>
    </div>

    <!-- Rodapé -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-logo">
                <img src="{{ asset('images/logo_fretes_em_nuvens3.png') }}" alt="Fretes em Nuvens - Plataforma de Fretes Online" width="150">
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
                    <h4>Tipos de Fretes</h4>
                    <ul>
                        <li><a href="/fretes-urgentes">Fretes Urgentes</a></li>
                        <li><a href="/fretes-intermunicipais">Intermunicipais</a></li>
                        <li><a href="/fretes-interestaduais">Interestaduais</a></li>
                        <li><a href="/fretes-cargas-frigorificadas">Cargas Frigorificadas</a></li>
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
                        <li><a href="mailto:fretesnasnuvens.carlospugas@gmail.com">fretesnasnuvens.carlospugas@gmail.com</a></li>
                        <li><a href="tel:+5541996077879">(41) 99607-7879</a></li>
                        <li>
                            <div class="social-links">
                                <a href="#"><img src="{{ asset('images/icon-facebook.png') }}" alt="Facebook Fretes em Nuvens" width="24"></a> 
                                <a href="#"><img src="images/icon-instagram.svg" alt="Instagram Fretes em Nuvens" width="24"></a>
                                <a href="#"><img src="images/icon-linkedin.svg" alt="LinkedIn Fretes em Nuvens" width="24"></a>
                                <a href="#"><img src="images/icon-youtube.svg" alt="YouTube Fretes em Nuvens" width="24"></a>
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
                    <img src="images/payment-methods.png" alt="Métodos de pagamento aceitos" width="200">
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Inline -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
          // Mobile Menu Toggle
          const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
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
            const elements = document.querySelectorAll('.step, .highlight-item, .testimonial, .comparison-item, .city-group, .keyword-item');
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
          
          // Atualizar ano no footer
          const yearSpan = document.getElementById('current-year');
          if (yearSpan) {
            yearSpan.textContent = new Date().getFullYear();
          }
          
          // Lazy loading para imagens
          const lazyImages = document.querySelectorAll("img[loading='lazy']");
          
          if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
              entries.forEach(entry => {
                if (entry.isIntersecting) {
                  const img = entry.target;
                  img.src = img.dataset.src || img.src;
                  img.classList.add('loaded');
                  observer.unobserve(img);
                }
              });
            });

            lazyImages.forEach(img => imageObserver.observe(img));
          } else {
            // Fallback para navegadores sem suporte
            lazyImages.forEach(img => {
              img.src = img.dataset.src || img.src;
              img.classList.add('loaded');
            });
          }
          
          // Enviar sitemap para motores de busca
          if (window.location.hostname === 'www.fretesemnuvens.com.br' || window.location.hostname === 'fretesemnuvens.com.br') {
            const sitemapUrl = 'https://'  + window.location.hostname + '/sitemap.xml';
            // Google
            fetch(`https://www.google.com/ping?sitemap=${encodeURIComponent(sitemapUrl)}`)
              .then(response => console.log('Sitemap enviado para Google'))
              .catch(err => console.error('Erro ao enviar para Google:', err));
            // Bing
            fetch(`https://www.bing.com/ping?sitemap=${encodeURIComponent(sitemapUrl)}`)
              .then(response => console.log('Sitemap enviado para Bing'))
              .catch(err => console.error('Erro ao enviar para Bing:', err));
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
            "email": "fretesnasnuvens.carlospugas@gmail.com",
            "availableLanguage": "Portuguese"
          }
        });
        document.head.appendChild(schemaScript);
        
        // LocalBusiness Schema
        const localBusinessSchema = document.createElement('script');
        localBusinessSchema.type = 'application/ld+json';
        localBusinessSchema.text = JSON.stringify({
          "@context": "https://schema.org",
          "@type": "LocalBusiness",
          "name": "Fretes em Nuvens",
          "image": "https://www.fretesemnuvens.com.br/images/logo_fretes_em_nuvens3.png",
          "@id": "https://www.fretesemnuvens.com.br",
          "url": "https://www.fretesemnuvens.com.br",
          "telephone": "+5541996077879",
          "priceRange": "$$",
          "address": {
            "@type": "PostalAddress",
            "streetAddress": "Rua dos Fretes, 100",
            "addressLocality": "Curitiba",
            "addressRegion": "PR",
            "postalCode": "80000-000",
            "addressCountry": "BR"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": -25.4284,
            "longitude": -49.2733
          },
          "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": [
              "Monday",
              "Tuesday",
              "Wednesday",
              "Thursday",
              "Friday",
              "Saturday"
            ],
            "opens": "08:00",
            "closes": "18:00"
          }
        });
        document.head.appendChild(localBusinessSchema);
        
        // WebSite Schema
        const websiteSchema = document.createElement('script');
        websiteSchema.type = 'application/ld+json';
        websiteSchema.text = JSON.stringify({
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "Fretes em Nuvens",
          "url": "https://www.fretesemnuvens.com.br",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "https://www.fretesemnuvens.com.br/busca?q={search_term_string}",
            "query-input": "required name=search_term_string"
          }
        });
        document.head.appendChild(websiteSchema);
        
        // Service Schema
        const serviceSchema = document.createElement('script');
        serviceSchema.type = 'application/ld+json';
        serviceSchema.text = JSON.stringify({
          "@context": "https://schema.org",
          "@type": "Service",
          "serviceType": "Plataforma de Fretes Online",
          "provider": {
            "@type": "Organization",
            "name": "Fretes em Nuvens"
          },
          "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Serviços de Frete",
            "itemListElement": [
              {
                "@type": "OfferCatalog",
                "name": "Fretes Urgentes",
                "itemListElement": [
                  {
                    "@type": "Offer",
                    "itemOffered": {
                      "@type": "Service",
                      "name": "Frete Urgente"
                    }
                  }
                ]
              },
              {
                "@type": "OfferCatalog",
                "name": "Mudanças",
                "itemListElement": [
                  {
                    "@type": "Offer",
                    "itemOffered": {
                      "@type": "Service",
                      "name": "Serviço de Mudança"
                    }
                  }
                ]
              }
            ]
          }
        });
        document.head.appendChild(serviceSchema);
    </script>
</body>
</html>