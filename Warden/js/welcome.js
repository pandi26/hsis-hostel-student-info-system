// JavaScript for opening and closing the mobile menu
document.addEventListener('DOMContentLoaded', function () {
    const hamburgerBtn = document.querySelector('.hamburger-btn');
    const closeBtn = document.querySelector('.close-btn');
    const links = document.querySelector('.links');
    const overlay = document.querySelector('.blur-bg-overlay');

    hamburgerBtn.addEventListener('click', function () {
        links.classList.add('show-menu');
        overlay.classList.add('show');
    });

    closeBtn.addEventListener('click', function () {
        links.classList.remove('show-menu');
        overlay.classList.remove('show');
    });

    overlay.addEventListener('click', function () {
        links.classList.remove('show-menu');
        overlay.classList.remove('show');
    });
});
