'use strict';

/* header buttons(profile, menu bar) */
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


// 구현할 것 목록
// 1. 보상 획득까지 남은 날짜 ------- V
// 1-1. 그래프 구현 ---------------- pie chart style 적용이 안 됨
// 1-2. 추가 보상 획득 구현  -------- V
// 2. 시작 날짜 -------------------- V
// 3. 누적 날짜만큼 도장 찍기
// 3-1. 챌린지 실패 시 초기화
//      -> 10일동안 챌린지를 하지 않으면 챌린지 실패로 간주
// 4. 월 바뀌면 초기화

// DB에서 받아와야 하는 값
// 1. 누적 날짜
// 2. 시작 날짜(date에 저장된 값 중 제일 처음 값?)

// DB에 저장해야 하는 값
// 1. 챌린지 성공 여부()
// 2. 포인트


/* 시작 날짜 */
const start_date = '2021/07/13';    // 시작 날짜 : DB에서 받아온 값으로 대체

// 시작 날짜 표시(html)
const start_from1 = document.querySelector('.graph__start .graph__sday');
const start_from2 = document.querySelector('.graph__start2 .graph__sday');

start_from1.textContent = start_date;
start_from2.textContent = start_date;


/* 보상 획득까지 남은 날짜 */
let dday_date = 21;       // 21일 채우면 성공
const cumulative_days = 30;  // 누적 날짜 : DB에서 받아온 값으로 대체

// 보상 획득까지 남은 날짜 표시(html)
const days_left = document.querySelector('.graph__dday');
const dday_text = document.querySelector('.graph__text1.dday__text');
const graph_text = document.querySelector('.graph__text1.dday__text2');

if (cumulative_days < 21) {          // 기본 보상
  dday_date -= cumulative_days;
}
else if (cumulative_days === 21) {   // 기본 보상 획득
  dday_text.textContent = '기본 보상 획득! 추가 보상 획득까지 도전하세요!'
  graph_text.style.display = 'none';
}
else if (cumulative_days === 30) {   // 추가 보상 획득
  dday_text.textContent = '추가 보상 획득! 30일 챌린지 완주를 축하합니다!'
  graph_text.style.display = 'none';
}
else {                               // 추가 보상
  dday_text.textContent = '추가 보상 획득까지';
  dday_date = 30 - cumulative_days;
}

days_left.textContent = dday_date;


/* 그래프 그리기 */
// 그래프 관련 html 요소
const graph_frame = document.querySelector('.graph__frame');
const pie_gauge = document.querySelector('.graph__pie');        // pie graph
const bar_basic_gauge = document.querySelector('.bar__basic');  // bar graph
const bar_extra_gauge = document.querySelector('.bar__extra');

// 그래프 gauge 계산
const gauge_degree = 12 * cumulative_days;
const gauge_percentage = (cumulative_days <= 21) ?
(70 / 21) * cumulative_days : (30 / 9) * (9 - (30 - cumulative_days));

// 그래프 그리기
if (cumulative_days <= 21) {         // 기본 보상
  pie_gauge.style.background = "conic-gradient(#4A8BCC 0deg 20deg, #DDDDDD 20deg);"
  bar_basic_gauge.style.width = `${gauge_percentage}%`;
}
else {                              // 추가 보상
  pie_gauge.style.background = `conic-gradient(#4A8BCC ${0}deg ${12 * 21}deg, #F47575 ${12 * 21}deg ${gauge_degree}deg, #DDDDDD ${gauge_degree}deg);`
  bar_basic_gauge.style.width = "70%";
  bar_extra_gauge.style.width = `${gauge_percentage}%`;
  
  if (cumulative_days === 30) {   // 추가 보상 획득
    bar_extra_gauge.style.borderRadius = "0px 20px 20px 0px";
  }
}

console.log(bar_basic_gauge.style);
console.log(graph_frame.style);
console.log(pie_gauge.style);