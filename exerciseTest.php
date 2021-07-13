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
  
  /* main.html에서 버튼 값 받아옴 */
  $exercise = $_GET["exercise"];

  // shoulder, neck_shoulder, arm_shoulder, pelvic
  switch($exercise){
      case "ex1":
        $e_name = 'neck_shoulder';
        break;
      case "ex2":
        $e_name = 'shoulder';
        break;
      case "ex3":
        $e_name = 'arm_shoulder';
        break;
      case "ex4":
        $e_name = 'pelvic';
        break;                   
  }

  $sql = "SELECT * FROM exercise WHERE e_name = '$e_name' ORDER BY e_id";
  $result = mysqli_query($conn, $sql);

  $s_names = array();
  $s_nos = array();
  $s_times = array();
  $s_expls = array();
  
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
    <title>운동하기</title>
    <link rel="stylesheet" href="exerciseTest.css">
  </head>
  <body>
    <!-- 헤더 -->
    <header>
      <img id="wave" src="../img/sea.svg" alt="wave">
      <!-- 로고 이미지를 클릭하면 마이페이지로 이동 -->
      <a href="../main/main.html"><img id="logo" src="../img/turtleneck_logo.svg" alt="logo"></a>
      <!-- 로그아웃 -->
      <i class="fas fa-user-circle" id="profile"></i>
      <!-- 말풍선 -->
      <div class="tooltip">
        <!-- 나중에 회원정보 추가하기 -->
        <a href="../main/main.html">마이 페이지</a>
        <hr/>
        <a href="../signup/logoutProcess.php">로그아웃</a>
      </div>
    </header>

    <!-- 본문 -->
    <main>
      <!-- Tutorial -->
      <div class="tutorial">
        <div class="tutorial__title">동작 설명</div>

        <!-- exercise information -->
        <table>
          <tr>
            <td rowspan="2">
              <!-- image -->
              <div class="tutorial__image">
                <img src="" id="tt_img" alt="exercise">
              </div>
            </td>
            <td id="tt_expl"></td>
          </tr>
          <tr>
            <td class="tutorial__timer">
              <i class="fas fa-stopwatch"></i>
              <span id="tt_sec"></span>s
            </td>
          </tr>
        </table>

        <!-- start -->
        <div class="tutorial__start">시작하기</div>

      </div>
      
      <h1>스트레칭 시작</h1>
      <div class="title__line"></div>

      <!-- timer -->
      <div class="timer">
        <i class="fas fa-stopwatch"></i>
        <div><span id="ex_sec">10</span>s</div>
      </div>

      <!-- WebCam -->
      <div id="container">
        <video autoplay="true" id="video"></video>

        <!-- image -->
        <img id="ex_img" src="" alt="exercise">
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

      <!-- overview -->
      <div class="overview"></div>
    </main>
  </body>

  <script>
    'use strict';

    // tooltip
    const profile = document.querySelector('#profile');
    const tooltip = document.querySelector('.tooltip');
    profile.addEventListener("click", () => {
      tooltip.classList.toggle('active');
    });

    /* 웹캠 출력 */
    var video = document.querySelector("#video");

    if (navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
          video.srcObject = stream;
        })
        .catch(function (error) {
          console.log("Error");
        });
    }
    
    /* Tutorial */
    const tutorial = document.querySelector('.tutorial');
    const start = document.querySelector('.tutorial__start');
    
    // 시작하기 버튼 누르면 화면 사라짐
    start.addEventListener("click", () => {
      tutorial.style.display = 'none';
    });

    /* 운동 관련 db값 받아오기 */
    const exercise_name = <?php echo json_encode($e_name)?>;
    const ex_stepNames = <?php echo json_encode($s_names)?>;
    const ex_stepNums = <?php echo json_encode($s_nos)?>;
    const ex_stepTimes = <?php echo json_encode($s_times)?>;
    const ex_stepExpls = <?php echo json_encode($s_expls)?>;

    /* exercise & overview */
    // variables
    // timer & bar
    const timer = document.querySelector('#ex_sec');
    const tt_sec = document.querySelector('#tt_sec');
    const bar = document.querySelector('#in_bar');

    // exercise steps
    const stepCount = ex_stepNames.length;

    // exercise time
    let time = 0;
    let sec = 0;
    let ex_time = ex_stepTimes[0];
    timer.textContent = ex_time;
    tt_sec.textContent = ex_time;
    let play = true;

    // exercise image
    const ex_image = document.querySelector('#ex_img');
    ex_image.setAttribute('src', `ex_image/${exercise_name}${ex_stepNums[0]}.png`);
    const tt_image = document.querySelector('#tt_img');
    tt_image.setAttribute('src', `ex_image/${exercise_name}${ex_stepNums[0]}.png`);
    let next_img;
    let index = 0;
    let done = 0;   // 동작 수행 여부 저장
    
    // exercise text
    const ex_name = document.querySelector('.tutorial__title');
    const tt_expl = document.querySelector('#tt_expl');
    ex_name.textContent = ex_stepNames[0];
    tt_expl.innerHTML = ex_stepExpls[0];

    // overview에 노드 추가
    const overview = document.querySelector('.overview');
    for (let i = 0; i < stepCount; i++) {
      const overviewNode = document.createElement('div');
      const overviewImg = document.createElement('img');
      overviewImg.setAttribute('src', `ex_image/${exercise_name}${ex_stepNums[i]}.png`);
      overviewNode.appendChild(overviewImg);
      overviewNode.setAttribute('class', 'circle');
      overview.appendChild(overviewNode);
    }

    // overview 자식 노드 list
    const imgList = overview.children;
    
    /* functions */
    // 시작하기 버튼 누르면 timer 실행
    start.addEventListener("click", () => {
      clearInterval(time);
      time = setInterval("Timer();", 1000);
    });

    function sleep(ms) {
      const wakeUpTime = Date.now() + ms;
      while (Date.now() < wakeUpTime) {}
    }

    /* 운동이 모두 완료되면 새 창이 띄워지는 함수 */
    function go_complete() {
      location.href = "../complete.php";
    }

    function Timer() {
      if (done !== stepCount) {
        prevBtn.addEventListener("click", prevBtnClick);
        nextBtn.addEventListener("click", nextBtnClick);
      }

      if (timer.textContent >= 0 && index < stepCount && done < stepCount) {
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
      
      // 동작 종료 시 바뀌는 요소들 바꾸기
      if (sec == Number(ex_time) + 1 && index < stepCount) {
        sleep(2000);
        if (index < stepCount - 1) {
          next_img = `ex_image/${exercise_name}${ex_stepNums[index + 1]}.png`;
          ex_image.setAttribute('src', next_img);
          tt_image.setAttribute('src', next_img);
          tutorial.style.visibility = 'visible'; // 동작 설명 띄우기
          clearInterval(time); // 시작하기 버튼 누를 때까지 멈춤
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
        if (imgList[index].style.backgroundColor != 'rgb(74, 139, 204)') done++;
        imgList[index].style.backgroundColor = '#4A8BCC';
        index++;

        // 텍스트 바꾸기
        ex_name.textContent = ex_stepNames[index];
        ex_time = ex_stepTimes[index];
        tt_expl.innerHTML = ex_stepExpls[index];
      }
      
      // 운동 종료 시
      if (index === stepCount) { 
        bar.style.width = '100%';
      }

      if (done === stepCount) {
        go_complete();
        done++;
        clearInterval(time);
      }
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
    function prevBtnClick() {
      if (index === stepCount) index--;
      if (index > 0) {
        const p_img = `ex_image/${exercise_name}${ex_stepNums[--index]}.png`;
        ex_image.setAttribute('src', p_img);
        tt_image.setAttribute('src', p_img);
        ex_time = ex_stepTimes[index];
        timer.textContent = ex_time;
        sec = 0;
        bar.style.width = '0%';
        ex_name.textContent = ex_stepNames[index];
        tt_expl.innerHTML = ex_stepExpls[index];
        tutorial.style.visibility = 'visible';
        clearInterval(time);
      }
    };

    function nextBtnClick() {
      if (index < stepCount - 1) {
        const n_img = `ex_image/${exercise_name}${ex_stepNums[++index]}.png`;
        ex_image.setAttribute('src', n_img);
        tt_image.setAttribute('src', n_img);
        ex_time = ex_stepTimes[index];
        timer.textContent = ex_time;
        sec = 0;
        bar.style.width = '0%';
        ex_name.textContent = ex_stepNames[index];
        tt_expl.innerHTML = ex_stepExpls[index];
        tutorial.style.visibility = 'visible';
        clearInterval(time);
      }
    };
  </script>
</html>