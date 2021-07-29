<?php
    //세션에 있는 id 값 가져오기
  //   session_start();
  //   if (isset($_SESSION['userId']))
  //   {
  //     $id = $_SESSION['userId'];
  //   }
  //   else
  //   {
  // ? >
  // <script>
  //   alert("세션이 만료되어있거나 비회원입니다.");
  //   location.href = "../index.html";
  // </script>
  // <?php

  // }   

  /* 세션 값 가져오기 */
  session_start();
  $id = $_SESSION['userId'];

  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
  // user_info에 저장된 시작 날짜 가져오기
  $sql = "SELECT startDate FROM user_info WHERE id = ${id}";
  $result = mysqli_query($conn, $sql);

  $s_row = mysqli_fetch_row($result);
  // 시작 날짜
  $start_date = $s_row[0];
  
  // 날짜 구하기
  $sql2 = "SELECT c_date FROM challenge WHERE id = ${id} and c_date >= DATE_FORMAT(now(), '${start_date}')";
  $result2 = mysqli_query($conn, $sql2);
      
  $i = 0;
  $dates = array();
  
  while($row = mysqli_fetch_row($result2)){
    if ($row) {
      $date = $row[0];
      $dates[$i] = $date;
      $i++;
    }
  }

  // 누적 날짜
  $cumulative_days = sizeof($dates);
  // 마지막으로 한 날짜
  $last_date = $start_date;
  if ($cumulative_days != 0) $last_date = end($dates);

  // 시작 날짜 이후 누적 날짜가 0이면 초기화 후 처음 운동하는 시점에 시작 날짜 변경
  if ($cumulative_days == 1) {
    $sql3 = "UPDATE user_info SET startDate = '${last_date}' WHERE id = ${id}";
    $result3 = mysqli_query($conn, $sql3);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" 
  integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
  <title>TurtleNeck</title>
</head>
<body>
  <!-- header -->
  <header>
    <img id="wave" src="../img/sea.svg" alt="wave">
    <nav class="navbar">
      <a href="../main/main.php"><img id="logo" src="../img/turtleneck_logo.svg" alt="logo"></a>
      <ul class="navbar__menu">
        <li><a href="../challenge/challenge.php">30일 챌린지</a></li>
        <li class="navbar__review"><a href="../review/hospital.php">리뷰</a>
          <ul class="navbar__submenu">
            <li><a href="../review/hospital.php">병원 리뷰</a></li>
            <li><a href="../review/item.php">제품 리뷰</a></li>
          </ul>
        </li>
        <li><a href="../stats/stats.php">통계</a></li>
        <li><a href="../ranking/ranking.php">랭킹</a></li>
      </ul>
      <div class="navbar__btn">
        <i class="fas fa-user-circle" id="profile"></i>
        <!-- toggle menu -->
        <i class="fas fa-bars" id="toggleBtn"></i>
      </div>
    </nav>
    <!-- tooltip -->
    <div class="tooltip">
      <a href="../stats/stats.php">통계</a>
      <a href="../modify/auth.php">정보 수정</a>
      <hr/>
      <a href="../signup/logoutProcess.php">로그아웃</a>
    </div>
  </header>

  <!-- hideen background -->
  <div class="hidden__bg"></div>

  <!-- title -->
  <div class="title">
    <div class="title__line"></div>
    <div class="title__text">30일 챌린지</div>
  </div>

  <!-- challenge -->
  <div class="challenge">
    <!-- calendar -->
    <div class="calendar">
      <div class="calendar__part">
        <div class="calendar__circle">
          <div class="calendar__num">1</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">2</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">3</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">4</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">5</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">6</div>
        </div>
      </div>
      <div class="calendar__part">
        <div class="calendar__circle">
          <div class="calendar__num">7</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">8</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">9</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">10</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">11</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">12</div>
        </div>
      </div>
      <div class="calendar__part">
        <div class="calendar__circle">
          <div class="calendar__num">13</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">14</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">15</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">16</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">17</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">18</div>
        </div>
      </div>
      <div class="calendar__part">
        <div class="calendar__circle">
          <div class="calendar__num">19</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">20</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">21</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">22</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">23</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">24</div>
        </div>
      </div>
      <div class="calendar__part">
        <div class="calendar__circle">
          <div class="calendar__num">25</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">26</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">27</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">28</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">29</div>
        </div>
        <div class="calendar__circle">
          <div class="calendar__num">30</div>
        </div>
      </div>
    </div>
  
    <!-- graph -->
    <div class="graph">
      <div class="graph__text">
        <div class="graph__text1 dday__text">보상 획득까지</div>
        <div class="graph__text1 dday__text2">
          <span class="graph__dday">21</span>일 남았습니다.
        </div>
      </div>
      <div class="graph__frame">
        <div class="graph__bar">
          <div class="bar__basic"></div>
          <div class="bar__extra"></div>
        </div>
        <div class="graph__pie">
          <div class="pie__background">
            <div class="graph__start">
              <div class="graph__sday"></div>
              <div class="graph__text1">부터 시작</div>
            </div>
          </div>
        </div>
      </div>
      <div class="graph__start2">
        <div class="graph__sday"></div>
        <div class="graph__text1">부터 시작</div>
      </div>
    </div>
  </div>
  <script>
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
    const start_date = <?php echo json_encode($start_date)?>;    // 시작 날짜

    // 시작 날짜 표시(html)
    const start_from1 = document.querySelector('.graph__start .graph__sday');
    const start_from2 = document.querySelector('.graph__start2 .graph__sday');

    start_from1.textContent = start_date;
    start_from2.textContent = start_date;


    /* 보상 획득까지 남은 날짜 */
    let dday_date = 21;       // 21일 채우면 성공
    const cumulative_days = <?php echo json_encode($cumulative_days)?>;  // 누적 날짜

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
    const last_date = new Date(<?php echo json_encode($last_date)?>); // 마지막으로 한 날짜

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

    function reset_calendar() {
      // 시작 날짜를 현재 날짜로 저장하는 php로 이동
      location.href = "init.php";
    }
  </script>
</body>
</html>