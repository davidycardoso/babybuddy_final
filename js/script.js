let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;
const progressBars = document.querySelectorAll('.progress-bar');
let slideInterval;

// Função para mostrar um slide específico
function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.style.display = i === index ? 'block' : 'none';
    });
    progressBars.forEach((bar, i) => {
        bar.classList.toggle('active', i === index);
    });
}

// Função para ir para o próximo slide
function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}

// Função para ir para o slide anterior
function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(currentSlide);
}

// Função para iniciar o autoavançar os slides
function startSlideInterval() {
    slideInterval = setInterval(nextSlide, 3000); // Muda a cada 3 segundos
}

// Função para parar o autoavançar os slides
function stopSlideInterval() {
    clearInterval(slideInterval);
}

// Adiciona os eventos de click para os botões de navegação
document.getElementById('next').addEventListener('click', () => {
    stopSlideInterval(); // Para o autoavançar ao clicar
    nextSlide();
    startSlideInterval(); // Reinicia o autoavançar
});

document.getElementById('prev').addEventListener('click', () => {
    stopSlideInterval(); // Para o autoavançar ao clicar
    prevSlide();
    startSlideInterval(); // Reinicia o autoavançar
});

// Adiciona eventos para pausar ao passar o mouse
document.querySelector('.hero').addEventListener('mouseover', stopSlideInterval);
document.querySelector('.hero').addEventListener('mouseout', startSlideInterval);

// Inicializa o slide e o intervalo
showSlide(currentSlide);
startSlideInterval();


// Adiciona um listener para o evento de scroll no elemento "hero"
document.querySelector('.hero').addEventListener('wheel', (event) => {
    event.preventDefault(); // Impede a rolagem padrão
});
