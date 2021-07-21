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


/* 시작 날짜 */
const start_date = '2021/07/13';    // 시작 날짜 : DB에서 받아온 값으로 대체

// 시작 날짜 표시(html)
const start_from1 = document.querySelector('.graph__start .graph__sday');
const start_from2 = document.querySelector('.graph__start2 .graph__sday');

start_from1.textContent = start_date;
start_from2.textContent = start_date;


/* 보상 획득까지 남은 날짜 */
let dday_date = 21;       // 21일 채우면 성공
const cumulative_days = 21;  // 누적 날짜 : DB에서 받아온 값으로 대체

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
  pie_gauge.style.background = `conic-gradient(#4a8bcc 0deg ${gauge_degree}deg, #dddddd ${gauge_degree}deg)`;
  bar_basic_gauge.style.width = `${gauge_percentage}%`;
}
else {                              // 추가 보상
  pie_gauge.style.background = `conic-gradient(#4A8BCC 0deg 252deg, #F47575 252deg ${gauge_degree}deg, #DDDDDD ${gauge_degree}deg)`;
  bar_basic_gauge.style.width = "70%";
  bar_extra_gauge.style.width = `${gauge_percentage}%`;
  
  if (cumulative_days === 30) {   // 추가 보상 획득
    bar_extra_gauge.style.borderRadius = "0px 20px 20px 0px";
  }
}


/* 도장 찍기 */
const calendar = document.querySelector('.calendar');

// 스탬프 몇 줄 찍어야 될 지 저장
const rows = Math.ceil(cumulative_days / 6);
let stamp_row = 0;

// 도장 찍기
for (let i = 0; i < calendar.childNodes.length; i++) {
  const calendar_row = calendar.childNodes[i];  // text, .calendar__part(줄 단위)로 구성

  // nodeType이 element(1)인 경우 도장 찍기
  if (calendar_row.nodeType === 1 && stamp_row < rows) {
    const calendar_days = calendar_row.childNodes;  // text, .calendar__circle(날짜)로 구성
    let stamp_day = 0;
    
    // 마지막 줄에서 줄 전체를 채우지 않는 경우
    if (stamp_row === rows - 1 && (cumulative_days % 6) !== 0 ) {
      for (let j = 0; j < calendar_days.length; j++) {
        const calendar_day = calendar_days[j];
        if (calendar_day.nodeType === 1 && stamp_day < cumulative_days % 6) {
          calendar_day.style.backgroundColor = "rgba(74, 139, 204, 0.5)";
          stamp_day++;
        }
      }
    }

    // 그 외
    else {
      for (let j = 0; j < calendar_days.length; j++) {
        const calendar_day = calendar_days[j];
        if (calendar_day.nodeType === 1 && stamp_day < 6) {
          calendar_day.style.backgroundColor = "rgba(74, 139, 204, 0.5)";
          stamp_day++;
        }
      }
    }
    stamp_row++;
  }
}


/* 초기화 */
// 공백 기간 구하기
const date = new Date();                  // 현재 날짜
const last_date = new Date('2021/07/17'); // 마지막으로 한 날짜 : DB에서 받아온 값으로 대체(날짜 부분만)

// 공백 기간
const blank_term = Math.floor((date.getTime() - last_date.getTime()) / (1000 * 60 * 60 * 24));

// 누적 날짜가 0이상이고, blank_term이 5이상이면 초기화(공백 기간이 5일이면 초기화)
if (blank_term > 5 && cumulative_days > 0) {
  reset_calendar();
}

// 시작 날짜로부터 30일이 지났으면 초기화
const first_date = new Date(`${start_date}`);         // 시작 날짜
const success_term = Math.floor((date.getTime() - first_date.getTime()) / (1000 * 60 * 60 * 24));

// 현재 날짜 - 시작 날짜가 30이면 초기화
if (success_term == 30) {
  reset_calendar();
}

// 챌린지 성공 여부를 저장할 것인가?

function reset_calendar() {
  // 시작 날짜를 현재 날짜로 저장하는 php로 이동
  location.href = "init.php";
}