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
      $id = implode("",$id);
    }
    else
    {
    ?>
    <script>
      alert("세션이 만료되어있거나 비회원입니다.");
      location.href = "../index.html"
    </script>
    <?php
    // header('Location: /index.html');
    }   
    $id = implode("", $id);

    /* db에서 login_id, name값 가져옴 */
    //$sql = "SELECT login_id, u_name FROM user_info WHERE id='{$id}'";
    $sql = "SELECT login_id, u_name FROM user_info WHERE id=194";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);

    $profile_img = '../img/profile/'.$row[2].'.png';
    $profile_name = $row[0];
    
    /* db에서 hospital_review 값 가져옴 */
    $hos = "SELECT hospital_review.id, u_name, hos_name, hos_ratings, hos_comments, dates, profileNum 
    FROM hospital_review 
    JOIN user_info 
    WHERE hospital_review.id = user_info.id;";
    $hos_result = mysqli_query($conn, $hos);
    $hos_row = mysqli_fetch_row($hos_result);

    $this_ids = array();
    $u_names = array();
    $hos_names = array();
    $hos_ratings = array();
    $hos_comments = array();
    $dates = array();
    $profileNums = array();

    $i = 0;
    while ($hos_row = mysqli_fetch_row($hos_result)) {
      $this_id = $hos_row[0];
      $u_name = $hos_row[1];
      $hos_name = $hos_row[2];
      $hos_rating = $hos_row[3];
      $hos_comment = $hos_row[4];
      $date = $hos_row[5];
      $profileNum = $hos_row[6];

      $this_ids[$i] = $this_id;
      $u_names[$i] = $u_name;
      $hos_names[$i] = $hos_name;
      $hos_ratings[$i] = $hos_rating;
      $hos_comments[$i] = $hos_comment;
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
    <title>병원 리뷰</title>

  </head>
  <body>
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
          <button id="upload" type="submit" onclick="add_review()">업로드</button>
        </form>
        </div>
        
        
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
          <div class="icon"><img class = "icon_profile" src="../img/profile/1.png"></div>
          <div class="review__user">사용자 이름</div>
          <div class="review__meta">
            <div class="review__rate">★★★☆☆</div>
            <div class="review__date">2021-07-18</div>
          </div>
            <div class="review__title">병원 이름</div>
            <div class="review__address">주소</div>
          <div class="review__content">내용 예시입니다. 세 줄 넘어가면 ... 표시로 바뀝니다<br>
            21일 전력거래소는 이날 최대 전력 수요를 오후 5시 기준 88.93GW(잠정치)로 집계했다. 올여름 들어 가장 높은 수치다. 이전 최고 기록은 지난 15일 88.6GW였다.<br>
            다만 걱정했던 전력 수급 비상사태는 없었다. 전력 사용량이 치솟으면서 이날 전력 예비력은 10.2GW, 공급 예비율은 12.1%까지 내려갔지만 수급 차질을 우려할 만한 수준은 아니었다. 통상 예비력이 10GW 이상이면 전력 공급이 안정됐다고 평가한다. 예비력이 5.5GW 이하로 떨어지면 ‘전력 수급 경보’를 발령해야 한다.
          </div>
          <div class="review__more" onclick="openContent(0)">펼치기</div>
        </div>
        
        <div id="field"></div>
      </div>
        

    </div>
    
  </body>
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
    
    
    /* 리스트 출력 */
    const this_id = <?php echo json_encode($this_ids)?>;
    const u_name = <?php echo json_encode($u_names)?>;
    const hos_names = <?php echo json_encode($hos_names)?>;
    const hos_rating = <?php echo json_encode($hos_ratings)?>;
    const hos_comment = <?php echo json_encode($hos_comments)?>;
    const hos_date = <?php echo json_encode($dates)?>;
    const profileNum = <?php echo json_encode($profileNums)?>;

    const listCount = this_id.length;
    for (let i=0; i<listCount; i++) {
        
        var icon_profile = document.createElement('img');
        icon_profile.classList.add('icon_profile');
        icon_profile.setAttribute("src", "../img/profile/"+profileNum[i]+".png");

        var icon = document.createElement('div');
        icon.classList.add('icon')
        icon.appendChild(icon_profile);

        var user_name = document.createElement('div');
        user_name.classList.add('review__user');
        user_name.innerText = u_name[i];

        var hos_name = document.createElement('div');
        hos_name.classList.add('review__title');
        hos_name.innerText = "병원 이름 : " + hos_names[i];

        var stars = document.createElement('div');
        stars.classList.add('review__rate');
        const rating = hos_rating[i];
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


        var dates = document.createElement('div');
        dates.classList.add('review__date');
        dates.innerText = hos_date[i];

        var rev_content = document.createElement('div');
        rev_content.classList.add("review__content");
        rev_content.innerText = hos_comment[i];
        
        let idx = 0;
        var this_idx = ++idx;
        var review_more = document.createElement('div');
        review_more.classList.add("review__more");
        review_more.innerText = "펼치기";
        review_more.onclick = function(){
          openContent(this_idx);
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
        total.appendChild(hos_name);
        total.appendChild(rev_content);
        total.appendChild(review_more);
      
        
        document.getElementById('field').appendChild(total);
    }
	
    /* 입력 체크 */
	  let idx = 0;
    function add_review(){
      var hos_check = document.getElementById('hospital_name').value;
      var con_check = document.getElementById('new-comment').value;
      // var hos_address = document.getElementById('tags').value;
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
              if (confirm('정말로 별점 0을 주시겠습니까?'))
                stars.innerText = "☆☆☆☆☆";
              else
                alert("평점을 입력해주세요.")
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
        rev_content.classList.add("review__content");
        rev_content.innerText = con_check;
        
        var this_idx = ++idx;
        var review_more = document.createElement('div');
        review_more.classList.add("review__more");
        review_more.innerText = "펼치기";
        review_more.onclick = function(){
          openContent(this_idx);
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
        total.appendChild(hos_name);
        total.appendChild(rev_content);
        total.appendChild(review_more);
      
        
        document.getElementById('field').appendChild(total);
        hos_check = "";
        con_check = "";
      }
      else
        alert('모두 입력해 주세요.');
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