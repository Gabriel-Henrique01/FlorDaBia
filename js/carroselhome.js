let slideIndex = 0;

function moveSlide(step) {
    const slides = document.querySelectorAll('.carousel-inner img');
    const totalSlides = slides.length;
    
    slideIndex += step;

    if (slideIndex >= totalSlides) {
        slideIndex = 0; // Voltar para o início
    } else if (slideIndex < 0) {
        slideIndex = totalSlides - 1; // Ir para o fim
    }

    const offset = -slideIndex * 100;
    document.querySelector('.carousel-inner').style.transform = `translateX(${offset}%)`;
}

function updateSlideWidth() {
    const carouselInner = document.querySelector('.carousel-inner');
    const slides = Array.from(carouselInner.children);
    const slideWidth = slides[0].getBoundingClientRect().width;
    const currentIndex = slideIndex;

    carouselInner.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
}

document.addEventListener('DOMContentLoaded', () => {
    const carouselInner = document.querySelector('.carousel-inner');
    const slides = Array.from(carouselInner.children);
    const slideCount = slides.length;
    let currentIndex = 0;

    // Função para mover o carrossel
    function moveToSlide(index) {
        carouselInner.style.transform = `translateX(-${index * 100}%)`;
        currentIndex = index;
    }

    // Função para avançar slides
    function nextSlide() {
        const nextIndex = (currentIndex + 1) % slideCount;
        moveToSlide(nextIndex);
    }

    // Função para retroceder slides
    function prevSlide() {
        const prevIndex = (currentIndex - 1 + slideCount) % slideCount;
        moveToSlide(prevIndex);
    }

    // Configuração de movimento automático
    let autoSlideInterval = setInterval(nextSlide, 3000); // Altere 3000 para o intervalo desejado em milissegundos

    // Adicione event listeners para os botões
    document.querySelector('.prev').addEventListener('click', () => {
        clearInterval(autoSlideInterval); // Pausa o movimento automático
        prevSlide();
        autoSlideInterval = setInterval(nextSlide, 3000); // Reinicia o intervalo
    });

    document.querySelector('.next').addEventListener('click', () => {
        clearInterval(autoSlideInterval); // Pausa o movimento automático
        nextSlide();
        autoSlideInterval = setInterval(nextSlide, 3000); // Reinicia o intervalo
    });

    // Inicializa o carrossel
    moveToSlide(currentIndex);

    // Atualiza a largura do slide quando a janela é redimensionada
    window.addEventListener('resize', updateSlideWidth);
});