'use strict';

/* date */
const today = new Date();

// month
const month = today.getMonth() + 1;

const month_num = document.querySelector('.m_num');
month_num.textContent = month;

const month_eng = document.querySelector('.m_eng');

switch (month) {
  case 1:
    month_eng.textContent = 'JAN';
    break;
  case 2:
    month_eng.textContent = 'FEB';
    break;
  case 3:
    month_eng.textContent = 'MAR';
    break;
  case 4:
    month_eng.textContent = 'APR';
    break;
  case 5:
    month_eng.textContent = 'MAY';
    break;
  case 6:
    month_eng.textContent = 'JUN';
    break;
  case 7:
    month_eng.textContent = 'JUL';
    break;
  case 8:
    month_eng.textContent = 'AUG';
    break;
  case 9:
    month_eng.textContent = 'SEP';
    break;
  case 10:
    month_eng.textContent = 'OCT';
    break;
  case 11:
    month_eng.textContent = 'NOV';
    break;
  case 12:
    month_eng.textContent = 'DEC';
    break;
}

// date & day
const f_date = new Date(today.getFullYear(), today.getMonth(), 1).getDate();  // 첫째 날
const l_date = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();  // 마지막 날
const f_day = new Date(today.getFullYear(), today.getMonth(), 1).getDay();  // 첫째 날의 요일


/* day setting */
// 행 추가
function insertRow() {
  calendar.innerHTML += '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
};

// 1일 전까지 공란 만들기
const calendar = document.querySelector('.calendar > tbody');

// 달력 형태 만들기
const days = Math.ceil((f_day + l_date) / 7);
for (let i = 0; i < days; i++)
  insertRow();

let week = new Array(days);
for (let i = 0; i < days; i++) {
  week[i] = calendar.childNodes[i + 1];
  //console.log(week[i]);
}

for (let i = 0; i < f_day; i++) {
  week[0].childNodes[i].textContent = "";
}

/* stamp */
const makeNode = () => {
  const stampNode = document.createElement('div');
  const stampImg = document.createElement('i');
  stampImg.setAttribute('class', 'far fa-grin');
  stampImg.style.color = '#19bc9c';
  stampNode.appendChild(stampImg);
  stampNode.setAttribute('class', 'day');

  return stampNode;
}

// 오늘 날짜 색 바꾸기
const todayDate = (index, day, i) => {
  const todayCell = week[index].childNodes[day];
  if (i == today.getDate()) {
    todayCell.style.backgroundColor = "#F2BE8A";
  }
}

// 날짜 채우기
let c_day = f_day;  // 요일 값 -> 초기 값 : 첫째 날 요일
let index = 0;

for (let i = 1; i <= l_date; i++) {
  if (c_day >= 7) {
    index++;
    c_day %= 7;
  }
  week[index].childNodes[c_day].textContent = i;
  week[index].childNodes[c_day].insertBefore(makeNode(), null);

  todayDate(index, c_day, i);

  c_day++;
}


/* Change StyleSheet */
// db 연동해서 운동한 날짜 받아와서 변수에 저장
// 그 값이 true면 stamp 이미지가 보이도록(visibility: visible)


// function stamp_on() {
//   //var a = db 연동한 값 date
//   //var b = db 연동한 값 doExercise
//   //var 그 날짜의 스탬프 = 스탬프 고르는 함수( 누적일자에 따라 다른 스탬프);

//   if (a == 날짜 && (그 날짜의 스탬프.style.visibility == "hidden" || 그 날짜의 스탬프.style.visibility = "")
//   {
//     //이미지 변수(stamp??).style.visibility =="visible";
//   }
//   if (a != 날짜 && (그 날짜의 스탬프.style.visibility == "visible"))
//   {
//     스탬프.style.vvisibility = "hidden";
//   }
// }