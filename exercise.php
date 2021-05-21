<?php 
  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
  
  // 한글 깨짐 방지
  mysqli_query($conn, "set session character_set_connection=utf8;");
  mysqli_query($conn, "set session character_set_results=utf8;");
  mysqli_query($conn, "set session character_set_client=utf8;");

  /* 세션에 저장해둔 사용자 id값 가져오기 */
  session_start();
  $id = $_SESSION;
  $id = implode("", $id);
  
  // shoulder, neck_shoulder, arm_shoulder, pelvic
  $e_name = 'pelvic'; // 사용자의 선택을 받아올 수 있나?
  $sql = "SELECT * FROM exercise WHERE e_name = '$e_name' ORDER BY e_id";
  $result = mysqli_query($conn, $sql);

  $s_names = array();
  $s_nos = array();
  $s_times = array();
  $s_expls = array();
  
  // 확인용
  $i = 0;
  while ($row = mysqli_fetch_row($result)) {
    $s_name = $row[2];
    $s_no = $row[3];
    $s_time = $row[4];
    $s_expl = nl2br($row[5]);

    $s_names[$i] = $s_name;
    $s_nos[$i] = $s_no;
    $s_times[$i] = $s_time;
    $s_expls[$i] = $s_expl;
    $i++;
  }
?>
  
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" 
    integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="exercise.css">
    <title>운동하기</title>
  </head>
  <body>
    <!-- 헤더 -->
    <header>
      <img id="wave" src="sea.svg" alt="wave">
      <!-- 로고 이미지를 클릭하면 마이페이지로 이동 -->
      <a href="challenge.html"><img id="logo" src="turtleneck_logo.svg" alt="logo"></a> <!--아직 마이페이지 없으니까 calender로 대체할게 -->
      <!-- 로그아웃 -->
      <i class="fas fa-user-circle" id="profile"></i>
      <!-- 말풍선 -->
      <div class="tooltip">
        <!-- 나중에 회원정보 추가하기 -->
        <a href="challenge.php">마이 페이지</a>
        <hr/>
        <a href="logoutProcess.php">로그아웃</a>
      </div>
    </header>

    <!-- 본문 -->
    <main>
      <!-- tutorial -->
      <div class="tutorial">
        <div class="book1 image">
          <div class="page image">
            <!-- image -->
            <img id="ex_img" src="" alt="exercise1">
          </div>
        </div>

        <div class="book2"></div>

        <div class="book1">
          <div class="page info">
            <!-- exercise information -->
            <div id="ex_name"></div>
            <div id="ex_expl"></div>

            <!-- timer & buttons -->
            <div class="additional">
                <!-- timer -->
                <div class="timer">
                  <i class="fas fa-stopwatch"></i>
                  <div><span id="ex_sec">10</span>s</div>
                </div>
                
                <!-- bar -->
                <div id="out_bar">
                  <div id="in_bar"></div>
                </div>
                
                <!-- buttons -->
                <div class="btn">
                  <i id="prev" class="fas fa-chevron-circle-left"></i>
                  <i id="pause" class="fas fa-pause-circle"></i>
                  <i id="next" class="fas fa-chevron-circle-right"></i>
                </div>
            </div>
          </div>
        </div>
      </div>

      <!-- overview -->
      <div class="overview"></div>
    </main>
  </body>
  <script>
    'use strict';

    /* 운동 관련 db값 받아오기 */
    const exercise_name = <?php echo json_encode($e_name)?>;
    const ex_stepNames = <?php echo json_encode($s_names)?>;
    const ex_stepNums = <?php echo json_encode($s_nos)?>;
    const ex_stepTimes = <?php echo json_encode($s_times)?>;
    const ex_stepExpls = <?php echo json_encode($s_expls)?>;

    // tooltip
    const profile = document.querySelector('#profile');
    const tooltip = document.querySelector('.tooltip');
    profile.addEventListener("click", () => {
      tooltip.classList.toggle('active');
    });

    /* exercise & overview */
    // variables
    // timer & bar
    const timer = document.querySelector('#ex_sec');
    const bar = document.querySelector('#in_bar');

    // exercise steps
    const stepCount = ex_stepNames.length;

    // exercise time
    let time = 0;
    let sec = 0;
    //let ex_time = 10;
    let ex_time = ex_stepTimes[0];
    timer.textContent = ex_time;
    let play = true;

    // exercise image
    const ex_image = document.querySelector('#ex_img');
    ex_image.setAttribute('src', `${exercise_name}${ex_stepNums[0]}.png`);
    console.log(ex_image.getAttribute('src'));
    let next_img;
    let index = 0;
    let done = 0;   // 동작 수행 여부 저장

    // exercise text
    const ex_name = document.querySelector('#ex_name');
    const ex_expl = document.querySelector('#ex_expl');
    ex_name.textContent = ex_stepNames[0];
    ex_expl.innerHTML = ex_stepExpls[0];
    
    // overview에 노드 추가
    const overview = document.querySelector('.overview');
    for (let i = 0; i < stepCount; i++) {
      const overviewNode = document.createElement('div');
      const overviewImg = document.createElement('img');
      overviewImg.setAttribute('src', `${exercise_name}${ex_stepNums[i]}.png`);
      overviewNode.appendChild(overviewImg);
      overviewNode.setAttribute('class', 'circle');
      overview.appendChild(overviewNode);
    }

    // overview 자식 노드 list
    const imgList = overview.children;
    
    // functions
    // timer 실행
    window.onload = () => {
      clearInterval(time);
      time = setInterval("Timer();", 1000);
    }

    function sleep(ms) {
      const wakeUpTime = Date.now() + ms;
      while (Date.now() < wakeUpTime) {}
    }

    /* 운동이 모두 완료되면 새 창이 띄워지는 함수 */
    function go_complete() {
      window.open("complete.php", "complete", 'width=500, height=1000, scrollbars=no');
    }

    function Timer() {
      if (timer.textContent >= 0 && index < stepCount) {
        if (timer.textContent > 0) {
          timer.textContent--;
        }
        sec++;
        bar.style.width = `${(sec / ex_time) * 100}%`;
        bar.style['transition-duration'] = '1s';
        bar.style['transition-timing-function'] = 'linear';

        // 일시정지 및 재시작
        pauseBtn.addEventListener('click', pauseplay);
      }
      
      if (sec == Number(ex_time) + 1 && index < stepCount) {
        sleep(2000);
        if (index < stepCount - 1) {
          next_img = `${exercise_name}${ex_stepNums[index + 1]}.png`;
          ex_image.setAttribute('src', next_img);
        }
        
        // 초기화
        if (index == stepCount - 1) {
          timer.textContent = 0;
        }
        else {
          timer.textContent = ex_stepTimes[index + 1];
          sec = 0;
          bar.style.width = '0%';
        }
        
        // overview 색 바꾸기
        imgList[index].style.backgroundColor = '#4A8BCC';
        index++; done++;

        // 텍스트 바꾸기
        ex_name.textContent = ex_stepNames[index];
        ex_expl.innerHTML = ex_stepExpls[index];
        ex_time = ex_stepTimes[index];
      }
      
      // 운동 종료 시
      if (index == stepCount) {
        if (done == stepCount) bar.style.width = '100%';
        else bar.style.width = `${(sec / ex_time) * 100}%`;
        ex_name.textContent = ex_stepNames[stepCount - 1];
        ex_expl.innerHTML = ex_stepExpls[stepCount - 1];
        clearInterval(time);
      }

      if (done == stepCount) go_complete();
    }



    /* buttons */
    const prevBtn = document.querySelector('#prev');
    const pauseBtn = document.querySelector('#pause');
    const nextBtn = document.querySelector('#next');

    // pause & play button
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

    // previous & next button
    // 끝까지 다 넘겨서 파란색으로 바뀌고 다시 앞으로 돌아가면 안 됨
    prevBtn.addEventListener("click", () => {
      if (index > 0) {
        const p_img = `${exercise_name}${ex_stepNums[--index]}.png`;
        ex_image.setAttribute('src', p_img);
        ex_time = ex_stepTimes[index];
        timer.textContent = ex_time;
        sec = 0;
        bar.style.width = '0%';
        ex_name.textContent = ex_stepNames[index];
        ex_expl.innerHTML = ex_stepExpls[index];
      }
    });

    nextBtn.addEventListener("click", () => {
      if (index < stepCount - 1) {
        const n_img = `${exercise_name}${ex_stepNums[++index]}.png`;
        ex_image.setAttribute('src', n_img);
        ex_time = ex_stepTimes[index];
        timer.textContent = ex_time;
        sec = 0;
        bar.style.width = '0%';
        ex_name.textContent = ex_stepNames[index];
        ex_expl.innerHTML = ex_stepExpls[index];
      }
    });
  </script>
</html>