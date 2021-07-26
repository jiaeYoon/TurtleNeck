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
  location.href = "../index.html";
  </script>
  <?php
  // header('Location: /index.html');
  }   
  $id = implode("", $id);

  /* db에서 login_id, name값 가져옴 */
  $sql = "SELECT login_id, u_name FROM user_info WHERE id='{$id}'";
  
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_row($result);

  $profile_img = '../img/profile/'.$row[2].'.png';
  $profile_name = $row[0];

  $sql ="SELECT item_review.id,u_name,item_name, item_ratings, item_comments, dates FROM item_review JOIN user_info WHERE item_review.id = user_info.id;";
  $result = mysqli_query($conn, $sql);

  while($row =mysqli_fetch_row($result))
  {
    $this_id = $row[0];
    $u_name = $row[1];
    $item_name = $row[2];
    $item_ratings = $row[3];
    $item_comments = $row[4];
    $dates = $row[5];
?>
<script>
    add_lists();
</script>
<?php
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
          <textarea id="new-comment" name="new_comment" row=5 placeholder="리뷰를 작성해주세요."></textarea>
          <button id="upload" type="submit" onclick="add_review()">업로드</button>
          </div>
		    </form>
        
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
          <div class="review__product">제품 이름</div>
          <div class="review__content">내용 예시입니다.</div>
          <div class="review__more" onclick="openContent()">펼치기</div>
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

    let idx = 0;
    /* 리뷰 추가 */
    function add_review(){
      var item_check = document.getElementById('item_name').value;
      var con_check = document.getElementById('new-comment').value;
      
      if (item_check && con_check)
      {
        /*사용자 프로필 가져오기 */
        var num = "<? echo $profile_img;?>";
		    var profile_name = "<? echo $profile_name;?>";
        var icon_profile = document.createElement('img');
        icon_profile.classList.add('icon_profile');
        icon_profile.setAttribute("src", "../img/profile/"+num+".png");

        var icon = document.createElement('div');
        icon.classList.add('icon')
        icon.appendChild(icon_profile);

        var user_name = document.createElement('div');
        user_name.classList.add('review__user');
        user_name.innerText = profile_name;

        var hos_name = document.createElement('div');
        hos_name.classList.add('review__product');
        hos_name.innerText = "제품 이름 : " + hos_check;

        // 평점 가져오기
        var stars = document.createElement('div');
        stars.classList.add('review__rate');
        const starNodeList = document.getElementsByName('rating');
        starNodeList.forEach((node) => {
          if(node.checked) {
            if (node.value == 0) {
              if (confirm('정말로 별점 0을 주시겠습니까?'))
                stars.innerText = "☆☆☆☆☆";
              else
                alert("평점을 입력해주세요.");
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
        rev_content.classList.add('review__content');
        rev_content.innerText = "리뷰 내용 : " + con_check;
        
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
        total.appendChild(item_name);
        total.appendChild(rev_content);
        total.appendChild(review_more);
        
        document.getElementById('field').appendChild(total);
        item_check = "";
        con_check = "";
      }
      else
        alert('모두 입력해 주세요.');
    }

    /* 펼치기/접기 버튼 test */
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
    function add_lists(){
      var item_check = <?=$item_name?>;
      var con_check = <?=$item_comments?>;
      if (item_check && con_check)
      {
        /*사용자 프로필 가져오기 */
        var num = 1;
        var icon_profile = document.createElement('img');
        icon_profile.classList.add('icon_profile');
        icon_profile.setAttribute("src", "../img/profile/"+num+".png");

        var icon = document.createElement('div');
        icon.classList.add('icon')
        icon.appendChild(icon_profile);

        var user_name = document.createElement('div');
        user_name.classList.add('review__user');
        user_name.innerText = <?=$u_name?>;

        // 제품 리뷰 
        var item_name = document.createElement('div');
        item_name.classList.add('review__product');
        item_name.innerText = "제품 이름 : " + item_check;
        
        // 평점 가져오기
        var stars = document.createElement('div');
        stars.classList.add('review__rate');
        var starNode = <?=$item_ratings?>;
        if (starNode == 0) {
          if (confirm('정말로 별점 0을 주시겠습니까?'))
            stars.innerText = "☆☆☆☆☆";
          else
            alert("평점을 입력해주세요.")
        }
        if (starNode == 1) {
          stars.innerText = "★☆☆☆☆";
        }
        if (starNode == 2) {
          stars.innerText = "★★☆☆☆";
        }
        if (starNode == 3) {
          stars.innerText = "★★★☆☆";
        }
        if (starNode == 4) {
          stars.innerText = "★★★★☆";
        }
        if (starNode == 5) {
          stars.innerText = "★★★★★";
        }

        var dates = document.createElement('div');
        dates.classList.add('review__date');
        dates.innerText = <?=$dates?>;

        var rev_content = document.createElement('div');
        rev_content.classList.add("review__content");
        rev_content.innerText = "리뷰 내용 : " + con_check;

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
        total.appendChild(item_name);
        total.appendChild(rev_content);
        total.appendChild(review_more);
        
        document.getElementById('field').appendChild(total);
        item_check = "";
        con_check = "";
      }
    }
  </script>
</html>