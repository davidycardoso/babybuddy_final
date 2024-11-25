<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BabyBuddy - Início</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="logo">
                <img src="images/logo.png" alt="Logo">
            </a>
            <ul class="nav-links">
                <li><a href="login.php"><button id="login" class="btn-login">Login</button></a></li>
                <li><a href="register.php"><button id="cadastrar" class="btn-cadastrar">Cadastrar</button></a></li>
                <li><a href="#services">Serviços</a></li>
                <li><a href="#about">Sobre</a></li>
                <li><a href="#contact">Contato</a></li>
            </ul>
        </nav>
    </header>
    
    <section class="hero">
        <div class="slides">
            <div class="slide">
                <img src="images/slide1.jpg" alt="Slide 1">
                <div class="slide-overlay">
                    <div class="hero-content">
                        <h1>Bem-vindo ao Nosso Site</h1>
                        <p>Descubra nossos serviços e ofertas especiais.</p>
                        <a href="#services" class="btn">Saiba Mais</a>
                    </div>
                </div>
            </div>
            <div class="slide">
                <img src="images/slide2.jpg" alt="Slide 2">
                <div class="slide-overlay">
                    <div class="hero-content">
                        <h1>Experiência Incrível</h1>
                        <p>Conheça nossos produtos e serviços de qualidade.</p>
                        <a href="#products" class="btn">Explore</a>
                    </div>
                </div>
            </div>
            <div class="slide">
                <img src="images/slide3.jpg" alt="Slide 3">
                <div class="slide-overlay">
                    <div class="hero-content">
                        <h1>Ofertas Exclusivas</h1>
                        <p>Aproveite nossas promoções e descontos especiais.</p>
                        <a href="#offers" class="btn">Confira</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="progress-container">
            <div class="progress-bar"></div>
            <div class="progress-bar"></div>
            <div class="progress-bar"></div>
        </div>
        <button id="prev" class="nav-btn">&#9664;</button>
        <button id="next" class="nav-btn">&#9654;</button>
    </section>

    <section id="about">
      <div class="about-content">
          <h2>Sobre Nós</h2>
          <p>
              Bem-vindo à nossa empresa! Nós somos uma equipe dedicada a fornecer soluções inovadoras e eficientes para nossos clientes. 
              Combinamos experiência e criatividade para oferecer produtos e serviços que atendem às necessidades e superam as expectativas.
          </p>
          <h3>Nossa Missão</h3>
          <p>
              Nossa missão é transformar ideias em realidade, oferecendo soluções tecnológicas de alta qualidade que ajudam nossos clientes a alcançar seus objetivos.
          </p>
          <h3>Nossos Valores</h3>
          <ul>
              <li>Inovação: Estamos sempre buscando novas ideias e soluções criativas.</li>
              <li>Qualidade: Comprometemo-nos a entregar produtos e serviços de alta qualidade.</li>
              <li>Integridade: Atuamos com transparência e honestidade em todas as nossas interações.</li>
              <li>Colaboração: Trabalhamos juntos para alcançar nossos objetivos comuns e apoiar uns aos outros.</li>
          </ul>
          <h3>Nossa Equipe</h3>
          <div class="team">
              <div class="team-member">
                  <img src="images/david-cardoso.png" alt="David Cardoso">
                  <h4>David Cardoso</h4>
                  <p><strong>Líder, Programador e Analista</strong></p>
                  <p>David é o líder da nossa equipe, responsável pelo desenvolvimento e análise dos nossos projetos. Com ampla experiência em programação, ele garante que nossos produtos sejam eficientes e inovadores.</p>
              </div>
              <div class="team-member">
                  <img src="images/diana-brian.png" alt="Diana Brian">
                  <h4>Diana Brian</h4>
                  <p><strong>Escrevente</strong></p>
                  <p>Diana é nossa especialista em redação e comunicação. Ela cria conteúdos claros e envolventes para garantir que nossa mensagem seja sempre bem transmitida.</p>
              </div>
              <div class="team-member">
                  <img src="images/anna-vitoria.png" alt="Anna Vitória">
                  <h4>Anna Vitória</h4>
                  <p><strong>Design</strong></p>
                  <p>Anna é a responsável pelo design de nossos projetos. Ela cria interfaces atraentes e funcionais que proporcionam uma excelente experiência ao usuário.</p>
              </div>
          </div>
      </div>
  </section>

  <section id="services">
    <div class="services-content">
      <h2>Serviços que Oferecemos</h2>
      <p>Conectamos famílias a babás de confiança, com localização próxima e perfis verificados.</p>
  
      <div class="service-list">
        <div class="service-item">
          <img src="images/geolocalizacao.jpg" alt="Geolocalização de Babás">
          <h3>Geolocalização de Babás</h3>
          <p>Encontre babás perto de você com base na sua localização atual.</p>
        </div>
  
        <div class="service-item">
          <img src="images/chat.jpg" alt="Chat Direto">
          <h3>Chat Direto</h3>
          <p>Converse diretamente com as babás antes de contratar, garantindo confiança e segurança.</p>
        </div>
  
        <div class="service-item">
          <img src="images/profiles.jpg" alt="Perfis Verificados">
          <h3>Perfis Verificados</h3>
          <p>Todos os perfis são verificados e detalhados, mostrando experiência, certificações e avaliações.</p>
        </div>
      </div>
    </div>
  
    <div class="benefits-content">
      <h2>Por que Escolher o BabyBuddy?</h2>
      <ul class="benefits-list">
        <li>Babás próximas a você</li>
        <li>Perfis detalhados e verificados</li>
        <li>Conexão rápida e segura</li>
        <li>Avaliações de outros pais</li>
      </ul>
    </div>
  </section>

  <!-- Seção 5: Depoimentos -->
<section id="testimonials">
  <h2>O que nossos clientes dizem</h2>
  <div class="testimonials-container">
    <div class="testimonial">
      <div class="testimonial-image">
        <img src="images/client1.jpg" alt="Cliente 1">
      </div>
      <div class="testimonial-content">
        <h3>Maria Oliveira</h3>
        <p>"O serviço foi excelente! A babá foi pontual, profissional e extremamente cuidadosa. Recomendo a todos."</p>
      </div>
    </div>

    <div class="testimonial">
      <div class="testimonial-image">
        <img src="images/client2.jpg" alt="Cliente 2">
      </div>
      <div class="testimonial-content">
        <h3>João Santos</h3>
        <p>"Contratar uma babá nunca foi tão fácil. A interface do site é simples e as profissionais são altamente qualificadas."</p>
      </div>
    </div>

    <div class="testimonial">
      <div class="testimonial-image">
        <img src="images/client3.jpg" alt="Cliente 3">
      </div>
      <div class="testimonial-content">
        <h3>Carla Souza</h3>
        <p>"A geolocalização facilitou demais a busca por uma babá próxima à minha casa. Ótimo serviço!"</p>
      </div>
    </div>
  </div>
</section>

<!-- Seção 6: Portfólio ou Projetos -->
<section id="portfolio">
  <h2>Nosso Portfólio</h2>
  <div class="portfolio-container">
    <div class="portfolio-item">
      <img src="images/project1.jpg" alt="Projeto 1">
      <div class="portfolio-info">
        <h3>Contratação de Babá de Emergência</h3>
        <p>Um caso onde ajudamos uma mãe a contratar uma babá em menos de 24 horas para uma emergência. Um processo rápido e eficiente com ótimos resultados.</p>
      </div>
    </div>
    <div class="portfolio-item">
      <img src="images/project2.jpg" alt="Projeto 2">
      <div class="portfolio-info">
        <h3>App de Acompanhamento de Atividades</h3>
        <p>Desenvolvemos um app para que pais possam acompanhar as atividades e cuidados das babás com suas crianças em tempo real.</p>
      </div>
    </div>
  </div>
</section>

<section id="contact">
    <h2>Entre em Contato</h2>
    <form action="send_message.php" method="post">
        <input type="text" name="name" placeholder="Seu Nome" required>
        <input type="email" name="email" placeholder="Seu E-mail" required>
        <textarea name="message" placeholder="Sua Mensagem" required></textarea>
        <button type="submit">Enviar Mensagem</button>
    </form>
</section>

<footer>
    <p>&copy; 2024 BabyBuddy. Todos os direitos reservados.</p>
</footer>

<script src="js/script.js"></script>
</body>
</html>
