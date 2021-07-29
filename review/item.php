<?php
  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
  
  // 한글 깨짐 방지
  mysqli_query($conn, "set session character_set_connection=utf8;");
  mysqli_query($conn, "set session character_set_results=utf8;");
  mysqli_query($conn, "set session character_set_client=utf8;");

  /* 세션에 저장해둔 사용자 id값 가져오기 */
  session_start();
  if (isset($_SESSION['userId']))
  {
    $id = $_SESSION['userId'];
  }
  else
  {
  ?>
  <script>
    alert("세션이 만료되어있거나 비회원입니다.");
    location.href = "../index.html";
  </script>
  <?php
    // header('Location: /index.html');
  }   

  /* db에서 login_id, name값 가져옴 */
  $sql = "SELECT login_id, u_name,profileNum FROM user_info WHERE id='{$id}'";
  
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_row($result);

  $profile_img = isset($row[2]) ? '../img/profile/'.$row[2].'.png' : '../img/profile/1.png';
  $profile_name = $row[0];

  /* db에서 item_review 값 가져옴 */
  $item = "SELECT item_review.id, u_name, item_name, item_rating, item_comments, dates, profileNum 
  FROM item_review 
  JOIN user_info 
  WHERE item_review.id = user_info.id;";
  $item_result = mysqli_query($conn, $item);
  $item_row = mysqli_fetch_row($item_result);

  $this_ids = array();
  $u_names = array();
  $item_names = array();
  $item_ratings = array();
  $item_comments = array();
  $dates = array();
  $profileNums = array();

  $i = 0;
  while ($item_row = mysqli_fetch_row($item_result)) {
    $this_id = $item_row[0];
    $u_name = $item_row[1];
    $item_name = $item_row[2];
    $item_rating = $item_row[3];
    $item_comment = $item_row[4];
    $date = $item_row[5];
    $profileNum = $item_row[6];

    $this_ids[$i] = $this_id;
    $u_names[$i] = $u_name;
    $item_names[$i] = $item_name;
    $item_ratings[$i] = $item_rating;
    $item_comments[$i] = $item_comment;
    $dates[$i] = $date;
    $profileNums[$i] = $profileNum;
    $i++;
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="review.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" 
    integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <title>제품 리뷰</title>

  </head>
  <body>
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


    <div id="main">
      <!-- title -->
      <div class="title">
        <div class="title__line"></div>
        <div class="title__text">제품 리뷰</div>
      </div>
      
      <div class="container">
        <div class="comment__title">작성하기</div>
        <form name="item_review" id="item_review" action="itemProcess.php" method="POST">  
          <div id="item">
            제품 이름
            <input type="text" id="item_name" name="item_name">
          </div>
          <div class="rating-group">
            평점 &nbsp;
            <input disabled checked class="rating__input rating__input--none" name="rating" id="rating" value="0" type="radio">
            <label aria-label="1 star" class="rating__label" for="rating-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
            <input class="rating__input" name="rating" id="rating-1" value="1" type="radio">
            <label aria-label="2 stars" class="rating__label" for="rating-2"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
            <input class="rating__input" name="rating" id="rating-2" value="2" type="radio">
            <label aria-label="3 stars" class="rating__label" for="rating-3"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
            <input class="rating__input" name="rating" id="rating-3" value="3" type="radio">
            <label aria-label="4 stars" class="rating__label" for="rating-4"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
            <input class="rating__input" name="rating" id="rating-4" value="4" type="radio">
            <label aria-label="5 stars" class="rating__label" for="rating-5"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
            <input class="rating__input" name="rating" id="rating-5" value="5" type="radio">
          </div>
          <div class="comment">
            <textarea id="new-comment" name="new_comment" row=5 placeholder="리뷰를 작성해주세요."></textarea>
            <button id="upload" type="button" onclick="formCheck()">업로드</button>
          </div>
		    </form>
        
        <select name="sort">
          <option value="">정렬</option>
          <option value="rank">평점순</option>
          <option value="new">최신순</option>
        </select>

        <div id="field"></div>
      </div>
        
    </div>
    
  </body>
  <script>
    'use strict'

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

    /* 리스트 출력 */
    const this_id = <?php echo json_encode($this_ids)?>;
    const u_name = <?php echo json_encode($u_names)?>;
    const item_names = <?php echo json_encode($item_names)?>;
    const item_rating = <?php echo json_encode($item_ratings)?>;
    const item_comment = <?php echo json_encode($item_comments)?>;
    const item_date = <?php echo json_encode($dates)?>;
    const profileNum = <?php echo json_encode($profileNums)?>;
    
    const listCount = this_id.length;
    for (let i=0; i<listCount; i++) {

      // 사용자 프로필
      var icon_profile = document.createElement('img');
      icon_profile.classList.add('icon_profile');
      icon_profile.setAttribute("src", "../img/profile/"+profileNum[i]+".png");

      var icon = document.createElement('div');
      icon.classList.add('icon')
      icon.appendChild(icon_profile);

      // 사용자 이름
      var user_name = document.createElement('div');
      user_name.classList.add('review__user');
      user_name.innerText = u_name[i];

      // 제품 이름
      var item_name = document.createElement('div');
      item_name.classList.add('review__product');
      item_name.innerText = "제품 이름 : " + item_names[i];
        
      // 평점
      var stars = document.createElement('div');
      stars.classList.add('review__rate');
      const rating = item_rating[i];
        if (rating == 0) {
          stars.innerText = "☆☆☆☆☆";
        }
        if (rating == 1) {
          stars.innerText = "★☆☆☆☆";
        }
        if (rating == 2) {
          stars.innerText = "★★☆☆☆";
        }
        if (rating == 3) {
          stars.innerText = "★★★☆☆";
        }
        if (rating == 4) {
          stars.innerText = "★★★★☆";
        }
        if (rating == 5) {
          stars.innerText = "★★★★★";
        }

      // 업로드 날짜
      var dates = document.createElement('div');
      dates.classList.add('review__date');
      dates.innerText = item_date[i];

      // 리뷰 내용
      var rev_content = document.createElement('div');
      rev_content.classList.add("review__content");
      rev_content.innerText = "리뷰 내용 : " + item_comment[i];

      // 펼치기 버튼
      var review_more = document.createElement('div');
      review_more.classList.add("review__more");
      review_more.innerText = "펼치기";
      review_more.onclick = function(){
        openContent(i);
      }

      var review_meta = document.createElement('div');
      review_meta.classList.add('review__meta');
      review_meta.appendChild(stars);
      review_meta.appendChild(dates);

      var total = document.createElement('div');
      total.classList.add('review__list');
      total.appendChild(icon);
      total.appendChild(user_name);
      total.appendChild(review_meta);
      total.appendChild(item_name);
      total.appendChild(rev_content);
      total.appendChild(review_more);
      
      document.getElementById('field').appendChild(total);
    }

    /* 입력 체크 */
    function formCheck(){
      var item_check = document.getElementById('item_name').value;
      var con_check = document.getElementById('new-comment').value;
      const starNodeList = document.getElementsByName('rating');

      if (!item_check || !con_check) { // 내용 미기재
        alert("내용을 입력해주세요.");
      }
      else {
        starNodeList.forEach((node) => {
          if(node.checked) {
            if (node.value == 0) { // 평점을 체크 안 한건지 0점을 주는 건지 확인
              if (confirm('정말로 별점 0을 주시겠습니까?')) {
                item_review.submit();
              } else {
                alert("평점을 입력해주세요.");
              }
            } else item_review.submit(); // 평점이 0점이 아닌 경우
          }
        })
      }
    }

    /* 펼치기/접기 버튼 */
    function openContent(idx) {
      const review_content = document.getElementsByClassName("review__content")[idx];
      if (review_content.style.display != "block") {
        review_content.style.display = "block";
        document.getElementsByClassName("review__more")[idx].innerText = "접기";
      }
      else {
        review_content.style.display = "-webkit-box";
        document.getElementsByClassName("review__more")[idx].innerText = "펼치기";
      }
    }

  </script>
</html>