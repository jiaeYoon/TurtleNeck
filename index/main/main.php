<?php
  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
    
  /* 인코딩 설정 */
  mysqli_query($conn, "set session character_set_connection=utf8;");
  mysqli_query($conn, "set session character_set_results=utf8;");
  mysqli_query($conn, "set session character_set_client=utf8;");

  /* 세션 값 가져오기 */
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

  /* 운동 목록 가져오기 */
  $sql = "SELECT ex_id, ex_name, ex_sname, ex_info, ex_image, hashtag 
          FROM exercise_info E, classifyex C 
          WHERE E.ex_id = C.index 
          ORDER BY ex_id";
  $result = mysqli_query($conn, $sql);

  $ex_ids = array();
  $ex_names = array();
  $ex_snames = array();
  $ex_infos = array();
  $ex_images = array();
  $ex_hashtags = array();
  
  $i = 0;
  while ($row = mysqli_fetch_row($result)) {
    $ex_id = $row[0];
    $ex_name = $row[1];
    $ex_sname = $row[2];
    $ex_info = $row[3];
    $ex_image = $row[4];
    $ex_hashtag = explode(', ', $row[5]);
    
    $ex_ids[$i] = $ex_id;
    $ex_names[$i] = $ex_name;
    $ex_snames[$i] = $ex_sname;
    $ex_infos[$i] = $ex_info;
    $ex_images[$i] = $ex_image;
    $ex_hashtags[$i] = $ex_hashtag;
    $i++;
  }

  /* 해시태그 검색 */
  $hash = "# ALL";
  if(isset($_GET["ex_part"])) $hash = $_GET["ex_part"];
  
  switch($hash) {
    case '# ALL':
      $hash = '';
      break;
    case '# 어깨':
      $hash = 'shoulder';
      break;
    case '# 목':
      $hash = 'neck';
      break;
    case '# 팔':
      $hash = 'arm';
      break;
    case '# 골반':
      $hash = 'pelvic';
      break;
    default:
      break;
  }

  // 버튼의 해시값으로 해당하는 운동 셀렉
  $sql2 = "SELECT ex_id, ex_name, ex_sname, ex_info, ex_image, hashtag 
  FROM exercise_info E, classifyex C 
  WHERE E.ex_id = C.index
  AND hashtag LIKE '%{$hash}%'";
  $result2 = mysqli_query($conn, $sql2);

  // ename_arr[] : 버튼 클릭한 스트레칭 부위가 있는, 운동 세트의 이름들을 배열로 저장
  $ename_arr = array();
  $i = 0;

  while ($row = mysqli_fetch_row($result2)) {
    $ename_arr[$i] = $row[1];
    $i++;
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
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
        <li class="navbar__review"><a href="">리뷰</a>
          <ul class="navbar__submenu">
            <li><a href="../review/hospital.php">병원 리뷰</a></li>
            <li><a href="../review/item.php">제품 리뷰</a></li>
          </ul>
        </li>
        <li><a href="../stats/stats.php">통계</a></li>
        <li><a href="../modify/modify.php">랭킹</a></li>
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
      <a href="../stats/history.php">지난 챌린지</a>
      <a href="../modify/modify.php">정보 수정</a>
      <hr/>
      <a href="../signup/logoutProcess.php">로그아웃</a>
    </div>
  </header>

  <!-- hideen background -->
  <div class="hidden__bg"></div>

  <!-- title -->
  <div class="title">
    <div class="title__line"></div>
    <div class="title__text">스트레칭 시작하기</div>
  </div>

  <!-- article -->
  <div class="article">
    <div class="exercises">
      <!-- search -->
      <div class="search">
        <form action="main.php" method="GET" class="search__hashtag">
          <div class="hashtag__keyword">
            <input type="submit" name="ex_part" value="# ALL"/>
          </div>
          <div class="hashtag__keyword">
            <input type="submit" name="ex_part" value="# 목"/>
          </div>
          <div class="hashtag__keyword">
            <input type="submit" name="ex_part" value="# 팔"/>
          </div>
          <div class="hashtag__keyword">
            <input type="submit" name="ex_part" value="# 어깨"/>
          </div>
          <div class="hashtag__keyword">
            <input type="submit" name="ex_part" value="# 골반"/>
          </div>
        </form>
        <div class="search__bar">
          <form action="new_main.php" method="post">
            <input type="text" name="keyword" class="search__text" autocomplete="off">
            <button type="submit" class="search__btn">
              <i class="fas fa-search"></i>
            </button>
          </form>
        </div>
      </div>
    
      <!-- contents -->
      <div class="contents"></div>
    </div>
  
    <!-- banner -->
    <div class="section">
      <input type="radio" name="slide" id="slide01" checked>
      <input type="radio" name="slide" id="slide02">
      <input type="radio" name="slide" id="slide03">
      <div class="slidewrap">
        <ul class="slidelist">
          <!-- 슬라이드 영역 -->
          <li class="slideitem">
            <a href="https://map.naver.com/v5/search/%EC%A0%95%ED%98%95%EC%99%B8%EA%B3%BC" target='_blank'>
              <div class="textbox">
                <h3>가까운 병원</h3>
                <p>내 위치와 가까운 정형외과를<br> 안내해 드려요</p>
              </div>
              <img src="https://pbs.twimg.com/media/E1-c21aUYAcBVNL?format=png">
            </a>
          </li>
          <li class="slideitem">
            <a href="../selfcheck/Banner_SelfCheck.php">
              <div class="textbox">
                <h3>자가진단</h3>
                <p>지금 내 상태는 어떨까?</p>
              </div>
              <img src="https://pbs.twimg.com/media/E1-eOLoUcAIKnIL?format=png">
            </a>
          </li>
          <li class="slideitem">
            <a href="http://www.amc.seoul.kr/asan/healthinfo/disease/diseaseDetail.do?contentId=31866" target='_blank'>
              <div class="textbox" target='_blank'>
                <h3>질환 정보</h3>
                <p>거북목 증후군이란?</p>
              </div>
              <img src="../img/bg.png">
            </a>
          </li class="slideitem">
  
        </ul>
        <!-- 페이징 -->
        <ul class="slide-pagelist">
          <li><label for="slide01"></label></li>
          <li><label for="slide02"></label></li>
          <li><label for="slide03"></label></li>
        </ul>
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
      toggleBtn.classList.toggle('active');
      profileBtn.classList.toggle('active');
    });

    profileBtn.addEventListener('click', () => {
      tooltip.classList.toggle('active');
    });


    /* get exercise data */
    const contents = document.querySelector('.contents');

    const exercise_nums = <?php echo json_encode($ex_ids)?>;            // 운동 번호
    const exercise_engs = <?php echo json_encode($ex_names)?>;          // 운동 이름(영어)
    const exercise_names = <?php echo json_encode($ex_snames)?>;        // 운동 이름(한글)
    const exercise_expls = <?php echo json_encode($ex_infos)?>;         // 운동 설명
    const exercise_images = <?php echo json_encode($ex_images)?>;       // 운동 이미지
    const exercise_hashtags = <?php echo json_encode($ex_hashtags)?>;   // 운동 해시태그


    // for문 돌면서 버튼 요소 추가하기
    for (let i = 0; i < exercise_names.length; i++) {
      const exercise_image = `../exercise/ex_image/${exercise_images[i]}`;
      let btnNode = createBtn(exercise_nums[i], exercise_engs[i], exercise_names[i], 
      exercise_expls[i], exercise_image, exercise_hashtags[i]);
      contents.appendChild(btnNode);
    }

    // 요소 추가 함수
    function createBtn(num, eng, name, expl, image, hashtags) {
      const contents_btn = document.createElement('form');
      contents_btn.setAttribute('action', '../exercise/exercise.php');
      contents_btn.setAttribute('method', 'GET');
      contents_btn.setAttribute('class', 'contents__btn');
      contents_btn.setAttribute('id', `${eng}`);

      // input
      const contents_input = document.createElement('input');
      contents_input.setAttribute('type', 'hidden');
      contents_input.setAttribute('name', 'exercise');
      contents_input.setAttribute('value', `ex${num}`);
      contents_btn.appendChild(contents_input);

      // image
      const contents_image = document.createElement('div');
      const image_img = document.createElement('img');
      contents_image.setAttribute('class', 'contents__image');
      image_img.setAttribute('src', `${image}`);
      image_img.setAttribute('alt', `${name}`);
      
      contents_image.appendChild(image_img);
      contents_btn.appendChild(contents_image);


      // text
      const contents_text = document.createElement('div');
      contents_text.setAttribute('class', 'contents__text');

      // title
      const contents_title = document.createElement('div');
      contents_title.setAttribute('class', 'contents__title');
      const title_text = document.createTextNode(`${name}`);
      contents_title.appendChild(title_text);

      // explanation
      const contents_info = document.createElement('div');
      contents_info.setAttribute('class', 'contents__info');
      const info_text = document.createTextNode(`${expl}`);
      contents_info.appendChild(info_text);

      // hashtag
      const contents_hashtag = document.createElement('div');
      contents_hashtag.setAttribute('class', 'contents__hashtag');

      // hashtag keywords
      for (let i = 0; i < hashtags.length; i++) {
        const hashtag_keyword = document.createElement('div');
        hashtag_keyword.setAttribute('class', 'hashtag__keyword');
        
        let keyword;
        switch (hashtags[i]) {
          case 'neck':
            keyword = '목';
            break;
          case 'shoulder':
            keyword = '어깨';
            break;
          case 'pelvic':
            keyword = '골반';
            break;
          case 'arm':
            keyword = '팔';
            break;
          default:
            keyword = '기타';
            break;
        }

        const keyword_text = document.createTextNode(`# ${keyword}`);
        hashtag_keyword.appendChild(keyword_text);
        contents_hashtag.appendChild(hashtag_keyword);
      }

      contents_text.appendChild(contents_title);
      contents_text.appendChild(contents_info);
      contents_text.appendChild(contents_hashtag);

      contents_btn.appendChild(contents_text);

      return contents_btn;
    }


    /* hashtag search */
    const eng_exercises = <?php echo json_encode($ename_arr)?>;

    const btnList = document.querySelectorAll('.contents__btn');
    btnList.forEach(button => {
      let btn_id = button.getAttribute('id');
      if(!eng_exercises.includes(`${btn_id}`)) {
        document.getElementById(`${btn_id}`).style.display = 'none';
      }
    });
  </script>
</body>
</html>