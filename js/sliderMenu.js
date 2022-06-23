const burgerMenu = document.getElementById('menu');
const sliderMenu = document.getElementById('slider-menu');
const closeMenu =document.getElementById('close');

function showMenu(){
    sliderMenu.style.width = '360px';
}

function hideMenu(){
    sliderMenu.style.width = '0px';
}

burgerMenu.addEventListener('click', showMenu);
closeMenu.addEventListener('click', hideMenu);