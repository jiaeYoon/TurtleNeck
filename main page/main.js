'use strict';

const main = document.querySelector('.main');
const login_bg = document.querySelector('.login');
const login = document.querySelector('.login__box');

login_bg.addEventListener("click", () => {
  login_bg.style.display = 'none';
  login.style.display = 'none';
});

main.addEventListener("click", () => {
  login_bg.style.display = 'block';
  login.style.display = 'block';
});