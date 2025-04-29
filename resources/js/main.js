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