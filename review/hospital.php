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

  /* db에서 login_id, name값 가져옴 */
  $sql = "SELECT login_id, u_name FROM user_info WHERE id='{$id}'";
  
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_row($result);

  $profile_img = '../img/profile/'.$row[2].'.png';
  $profile_name = $row[0];


  $sql = ""
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
    <title>리뷰</title>

  </head>
  <body>
    <header>
      <img id="wave" src="../img/sea.svg" alt="wave"> 
      <nav class="navbar">
        <a href="../main/main.html"><img id="logo" src="../img/turtleneck_logo.svg" alt="logo"></a>
        <ul class="navbar__menu">
          <li><a href="">30일 챌린지</a></li>
          <li class="navbar__review"><a href="">리뷰</a>
            <ul class="navbar__submenu">
              <li><a href="../review/hospital.html">병원 리뷰</a></li>
              <li><a href="../review/item.html">제품 리뷰</a></li>
            </ul>
          </li>
          <li><a href="">마이페이지</a></li>
          <li><a href="">정보 수정</a></li>
        </ul>
        <div class="navbar__btn">
          <i class="fas fa-user-circle" id="profile"></i>
          <!-- toggle menu -->
          <i class="fas fa-bars" id="toggleBtn"></i>
        </div>
      </nav>
      <!-- tooltip -->
      <div class="tooltip">
        <a href="../main/main.html">마이 페이지</a>
        <a href="../main/main.html">정보 수정</a>
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
        <div class="title__text">병원 리뷰</div>
      </div>

      <div class="container">
        <div class="comment__title">작성하기</div>
        <form name="hos_review" id="hos_review" action="hospitalProcess.php" method="POST">
        <div id="hospital">
            병원 이름
            <input type="text" id="hospital_name"> &nbsp; <input type="button" value="검색">
          </div>
          <div class="rating-group">
            평점 &nbsp; <!---->
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
          <textarea id="new-comment" name="new_comment" row=5 placeholder="리뷰를 작성해주세요."></textarea><br>
          <input type="text" id="tags" name="tags" value="태그를 입력하세요(#해시태그)">
          <button id="upload" type="submit" onclick="add_review()">업로드</button>
        </div>
        </form>f
        
        <select name="area" >
          <option value="">지역</option>
          <option value="">강원</option>
          <option value="">경기</option>
          <option value="">경남</option>
          <option value="">경북</option>
          <option value="">광주</option>
          <option value="">대구</option>
          <option value="">대전</option>
          <option value="">부산</option>
          <option value="">서울</option>
          <option value="">세종</option>
          <option value="">울산</option>
          <option value="">인천</option>
          <option value="">전남</option>
          <option value="">전북</option>
          <option value="">제주</option>
          <option value="">충남</option>
          <option value="">충북</option>
        </select>

        <select name="sort">
          <option value="">정렬</option>
          <option value="rank">평점순</option>
          <option value="new">최신순</option>
        </select>

        <div class="review__list">
          <div class="icon"><i class="fas fa-hospital"></i></div>
          <div class="review__info">
            <div class="review__title">병원 이름</div>
            <div class="review__address">주소</div>
            <div class="review__rate">★★★☆☆</div>
            <div class="review__date">2021-07-18</div>
          </div>
          <div class="review__content">내용 예시입니다.</div>
        </div>
        
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
    
    /* 리뷰 추가 */
    function add_review(){
      var hos_check = document.getElementById('hospital_name').value;
      var con_check = document.getElementById('new-comment').value;
      
      if (hos_check && con_check)
      {
		var num = 1;
        var icon_profile = document.createElement('img');
        icon_profile.classList.add('icon_profile');
        icon_profile.setAttribute("src", "../img/profile/"+num+".png");

        var icon = document.createElement('div');
        icon.classList.add('icon')
        icon.appendChild(icon_profile);

        var user_name = document.createElement('div');
        user_name.classList.add('review__user');
        user_name.innerText = "사용자 이름";

        var hos_name = document.createElement('div');
        hos_name.classList.add('review__title');
        hos_name.innerText = "병원 이름 : " + hos_check;

        var stars = document.createElement('div');
        stars.classList.add('review__rate');
        const starNodeList = document.getElementsByName('rating');
        starNodeList.forEach((node) => {
          if(node.checked) {
            if (node.value == 0) {
              stars.innerText = "☆☆☆☆☆";
            }
            if (node.value == 1) {
              stars.innerText = "★☆☆☆☆";
            }
            if (node.value == 2) {
              stars.innerText = "★★☆☆☆";
            }
            if (node.value == 3) {
              stars.innerText = "★★★☆☆";
            }
            if (node.value == 4) {
              stars.innerText = "★★★★☆";
            }
            if (node.value == 5) {
              stars.innerText = "★★★★★";
            }
          }
        })

        var dates = document.createElement('div');
        dates.classList.add('review__date');
        var today = new Date();
        var month = today.getMonth() + 1;
        var day = today.getDate();
        var year = today.getFullYear();
        dates.innerText =  year + "-" + ("0"+month).slice(-2) + "-" + ("0"+day).slice(-2);

        var rev_content = document.createElement('div');
        rev_content.classList.add("review_content");
        rev_content.innerText = "리뷰 내용 : \n" + con_check;
        
        var total = document.createElement('div');
        total.classList.add('review__list');
		total.appendChild(icon);
        total.appendChild(user_name);
        total.appendChild(hos_name);
        total.appendChild(stars);
        total.appendChild(rev_content);
        total.appendChild(dates);
        
        document.getElementById('field').appendChild(total);
        hos_check = "";
        con_check = "";
      }
      else
        alert('모두 입력해 주세요.');
    }
    
    
  </script>
</html>