<?php
    session_start();
    $id = $_SESSION;
    $id = implode("", $id);

    $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
    $sql = "SELECT * FROM challenge WHERE id='{$id}' ORDER BY c_date";
    $result = mysqli_query($conn, $sql);
    
    $dates = array();
    $i = 0;
    while($row=mysqli_fetch_row($result)) {
        $date = $row[1];
        $date = substr($date, 8, 2); 
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
      <!-- month -->
  <div class="month">
    <div class="m_num"></div>
    <div class="m_eng"></div>
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
  }

  for (let i = 0; i < f_day; i++){
    week[0].childNodes[i].textContent = "";
  }

  // 오늘 날짜 색 바꾸기
  const todayDate = (index, day, i) => {
    const todayCell = week[index].childNodes[day];
    if (i == today.getDate()) {
      todayCell.style.backgroundColor = "#F2BE8A";
    }
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
    if (dates.indexOf(`${i}`) != -1) stampImg.style.visibility = 'visible';
    else stampImg.style.visibility = 'hidden';
    
    stampNode.appendChild(stampImg);
    stampNode.setAttribute('class', 'day');

    return stampNode;
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
</script>
</body>
</html>