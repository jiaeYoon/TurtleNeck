'use strict';

const toggleBtn = document.querySelector('#toggleBtn');
const profileBtn = document.querySelector('#profile');

const menu = document.querySelector('.navbar__menu');
const hidden_box = document.querySelector('.hidden__bg');
const tooltip = document.querySelector('.tooltip');

toggleBtn.addEventListener('click', () => {
  menu.classList.toggle('active');
  hidden_box.classList.toggle('active');
});

profileBtn.addEventListener('click', () => {
  tooltip.classList.toggle('active');
});