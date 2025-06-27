// script.js - Lightbox ajusté avec fond semi-transparent, navigation et bouton de fermeture

document.addEventListener('DOMContentLoaded', () => {
    const images = document.querySelectorAll('.gallery img');
    const sources = Array.from(images).map(img => img.src);

    let currentIndex = 0;
    let overlay;

    const createLightbox = (index) => {
        currentIndex = index;

        overlay = document.createElement('div');
        overlay.className = 'lightbox-overlay';

        const container = document.createElement('div');
        container.className = 'lightbox-container';

        const img = document.createElement('img');
        img.src = sources[currentIndex];
        img.className = 'lightbox-image';

        const btnPrev = document.createElement('button');
        btnPrev.innerHTML = '&#10094;';
        btnPrev.className = 'lightbox-prev';
        btnPrev.onclick = () => navigate(-1);

        const btnNext = document.createElement('button');
        btnNext.innerHTML = '&#10095;';
        btnNext.className = 'lightbox-next';
        btnNext.onclick = () => navigate(1);

        const btnClose = document.createElement('button');
        btnClose.innerHTML = '&times;';
        btnClose.className = 'lightbox-close';
        btnClose.onclick = () => overlay.remove();

        container.appendChild(btnPrev);
        container.appendChild(img);
        container.appendChild(btnNext);
        container.appendChild(btnClose);
        overlay.appendChild(container);
        document.body.appendChild(overlay);
    };

    const navigate = (direction) => {
        currentIndex = (currentIndex + direction + sources.length) % sources.length;
        const img = document.querySelector('.lightbox-image');
        if (img) img.src = sources[currentIndex];
    };

    images.forEach((img, index) => {
        img.addEventListener('click', () => createLightbox(index));
    });
});