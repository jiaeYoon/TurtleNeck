'use strict'

// tooltip
const profile = document.querySelector('#profile');
const tooltip = document.querySelector('.tooltip');
profile.addEventListener("click", () => {
  tooltip.classList.toggle('active');
});