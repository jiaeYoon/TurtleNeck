'use strict';

// timer & bar
const timer = document.querySelector('#ex_sec');
// --test code--
// console.log(timer.textContent);

const bar = document.querySelector('#in_bar');
// --test code--
// bar.style.width = '50%';

let time = 0;
let sec = 0;
let ex_time = 10; // 추후에 운동에 맞는 시간으로 변경
let play = true;

// overview
const overview = document.querySelector('.overview');
const imgList = overview.children;

// 운동 이미지
const ex_image = document.querySelector('#ex_img');
let next_img;
let index = 1;
// --test code--
//ex_image.setAttribute('src', imgList[1].firstChild.nextSibling.getAttribute('src'));

// timer 함수
window.onload = () => {
  clearInterval(time);
  time = setInterval("Timer()", 1000);
}

function Timer() {
  if (timer.textContent > 0) {
    timer.textContent--;
    sec++;
    bar.style.width = `${(sec / ex_time) * 100}%`;
    bar.style['transition-duration'] = '1s';
    bar.style['transition-timing-function'] = 'linear';

    // 일시정지 및 재시작
    pauseBtn.addEventListener('click', pauseplay);
  }

  if (bar.style.width == '100%' && index != imgList.length) {
    next_img = imgList[index].firstChild.nextSibling.getAttribute('src');
    ex_image.setAttribute('src', `${next_img}`);
        
    // 초기화
    timer.textContent = ex_time;
    sec = 0;
    bar.style.width = '0%';
    /* 텍스트 바꾸기 */
    
    // overview 색 바꾸기
    imgList[index - 1].style.backgroundColor = '#4A8BCC';
    
    index++;
  }
}

// button
let prevBtn = document.querySelector('#prev');
let pauseBtn = document.querySelector('#pause');
let nextBtn = document.querySelector('#next');

function pauseplay() {
  if (play) {
    clearInterval(time);
    play = false;
    // 아이콘 변경
    pauseBtn.className = 'fas fa-play-circle';
  }
  else {
    time = setInterval("Timer()", 1000);
    play = true;
    // 아이콘 변경
    pauseBtn.className = 'fas fa-pause-circle';
  }
}