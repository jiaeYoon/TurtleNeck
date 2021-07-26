<?php
  //세션에 있는 id값 가져오기
  session_start();
  if (isset($_SESSION['userId']))
  {
    $id = $_SESSION['userId'];
    $id = implode("",$id);
  }
  else
  {
  ?>
  <script>
  alert("세션이 만료되어있거나 비회원입니다.");
  location.href = "../index.html";
  </script>
  <?php
  }   

  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
  $sql = "SELECT * FROM challenge WHERE id='{$id}' ORDER BY c_date";
  $result = mysqli_query($conn, $sql);
  
  $dates = array();
  $i = 0;
  while($row=mysqli_fetch_row($result)) {
      $date = $row[1];
      //$date = substr($date, 8, 2); 
      $dates[$i] = $date;
      $i++;
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="challenge.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <title></title>
  </head>
  <body>
    <div class="header">
      <!-- month -->
      <div class="month">
        <div class="m_num"></div>
        <div class="m_eng"></div>
        <div class="year"></div>
      </div>

      <!-- button -->
      <i id="btn_prev" class="fas fa-caret-left"></i>
      <i id="btn_next" class="fas fa-caret-right"></i>
    </div>

    <!-- calendar -->
    <div class="challenge">
      <table class="calendar">
        <thead>
          <tr>
            <th>SUN</th>
            <th>MON</th>
            <th>TUE</th>
            <th>WED</th>
            <th>THU</th>
            <th>FRI</th>
            <th>SAT</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  <script>
    /* date */
    let today = new Date();
  
    // month
    function setMonth(){
      const month = today.getMonth() + 1;
      const year = today.getFullYear();
    
      const month_num = document.querySelector('.m_num');
      month_num.textContent = month;
    
      const month_eng = document.querySelector('.m_eng');
  
      const year_num = document.querySelector('.year');
      year_num.textContent = `${year}`;
  
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
    }
    setMonth();
    
    // 지난 달
    const prevBtn = document.querySelector('#btn_prev');
    prevBtn.addEventListener('click', setPrevMonth);
    let today_year, today_month;

    function setPrevMonth(){
      today_year = today.getFullYear();
      today_month = today.getMonth();
      
      if(today.getMonth() == 0)
      {
        today.setMonth(11);
        today.setFullYear(today_year - 1);
      }
      else
        today.setMonth(today_month - 1);

      setMonth();
      initCalendar();
      makeCalendar();
    }
    
    // 다음 달
    const nextBtn = document.querySelector('#btn_next');
    nextBtn.addEventListener('click', setNextMonth);

    function setNextMonth(){
      today_year = today.getFullYear();
      today_month = today.getMonth();
      
      if(today.getMonth() == 11)
      {
        today.setMonth(0);
        today.setFullYear(today_year + 1);
      }
      else
        today.setMonth(today_month + 1);

      setMonth();
      initCalendar();
      makeCalendar();
    }

    // DB에서 date 값 받아오기
    const dates = <?php echo json_encode($dates)?>;
  
    /* stamp */
    const makeNode = (dates, i) => {
      const stampNode = document.createElement('div');
      const stampImg = document.createElement('i');
      stampImg.setAttribute('class', 'far fa-grin');
      stampImg.style.color = '#19bc9c';
      if (i < 10) i = `0${i}`;
      if (today.getMonth() + 1 < 10) {
          t_month = `0${today.getMonth() + 1}`;
      }
      else t_month = today.getMonth() + 1;

      let compareDate = `${today.getFullYear()}-${t_month}-${i}`;
      if (dates.indexOf(compareDate) != -1) stampImg.style.visibility = 'visible';
      else stampImg.style.visibility = 'hidden';
      
      stampNode.appendChild(stampImg);
      stampNode.setAttribute('class', 'day');
  
      return stampNode;
    }

    function initCalendar(){
      // 행 삭제
      const tbody = document.querySelector('tbody');
      tbody.innerHTML = "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
  	}

    function makeCalendar(){
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
      for (let i = 0; i < days - 1; i++)
        insertRow();

      let week = new Array(days);
      for (let i = 0; i < days; i++) {
        week[i] = calendar.childNodes[i];
      }
    
      for (let i = 0; i < f_day; i++){
        week[0].childNodes[i].textContent = "";
      }

      // 오늘 날짜 색 바꾸기
      const todayDate = (index, day, i) => {
        const todayCell = week[index].childNodes[day];
        const date = new Date();

        if (today.getFullYear() == date.getFullYear() && today.getMonth() == date.getMonth() && i == date.getDate()){
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
        week[index].childNodes[c_day].insertBefore(makeNode(dates, i), null);
        
        todayDate(index, c_day, i);
    
        c_day++;
      }
    }

    initCalendar();
    makeCalendar();
  </script>
  </body>
</html>