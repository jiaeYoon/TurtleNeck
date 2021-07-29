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
  }   

  /* url을 통한 페이지 접근 차단 */
  // $prevPage = $_SERVER["HTTP_REFERER"];
  // if(substr($prevPage, -15, ) != "authProcess.php"){
  //   echo '<script>'; 
  //   echo 'alert("비밀번호 인증없이는 접근할 수 없습니다.")'; 
  //   echo '</script>';
  //   echo "<script>location.href='auth.php'</script>";
  // } 

  /* db에서 login_id, name값 가져옴 */
  $sql = "SELECT login_id, u_name, profileNum FROM user_info WHERE id='{$id}'";
  //$sql = "SELECT login_id, u_name, profileNum FROM user_info WHERE id='194'"; //테스트용
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_row($result);
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../challenge/style.css">
  <link rel="stylesheet" href="modify.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" 
  integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
  <script>
    /* 취소버튼: 뒤로가기 */
	  function goBack(){
		  window.history.go(-1);
	  }

    /* 프로필 선택하면 좌측 사진 변화 */
	  function changeProfile(profileNum){
		  console.log(profileNum);
		 document.getElementById("profile-current").src = "../img/profile/" + profileNum + ".png";
	}
  </script>
  <title>회원정보 수정</title> 
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
		<div class="title__text">회원정보수정</div>
	</div>

  <!-- form -->
  <div id="modify_body">
    <form name = "modify" id="modify-form" action="modifyProcess.php" method="POST">
    <table id="change-icon">
      <tr>
        <td>현재 프로필</td>
        <td>변경할 프로필</td>
      </tr>
      <tr>
        <td>
          <img width =50px src='<?php echo '../img/profile/'.$row[2].'.png'?>' id="profile-current">
        </td>
        <td>
          <label><input type="radio" name="images" value="1" onclick='changeProfile(value);'> <img id ="icon-select1" width="40" class="icons" src="../img/profile/1.png"></img></input></label>
          <label><input type="radio" name="images" value="2" onclick='changeProfile(value);'> <img id ="icon-select2" width="40" class="icons" src="../img/profile/2.png"></img></input></label>
          <label><input type="radio" name="images" value="3" onclick='changeProfile(value);'> <img id ="icon-select3" width="40" class="icons" src="../img/profile/3.png"></img></input></label>
          <label><input type="radio" name="images" value="4" onclick='changeProfile(value);'> <img id ="icon-select4" width="40" class="icons" src="../img/profile/4.png"></img></input></label>
          <label><input type="radio" name="images" value="5"> <img id ="icon-select5" width="40" class="icons" src="../img/profile/2.png"></img></input></label>
          <label><input type="radio" name="images" value="6"> <img id ="icon-select6" width="40" class="icons" src="../img/profile/2.png"></img></input></label>
          <label><input type="radio" name="images" value="7"> <img id ="icon-select7" width="40" class="icons" src="../img/profile/2.png"></img></input></label>
          <label><input type="radio" name="images" value="8"> <img id ="icon-select8" width="40" class="icons" src="../img/profile/2.png"></img></input></label>
        </td>
      </tr>
    </table>

      <table cellpadding="2">
              <tr>
                  <td>아이디</td> 
                  <td><input type="text" id="login_id" value='<?php echo $row[0]?>' disabled/></td>
              </tr>
        <tr>
          <td>비밀번호 변경</td>
          <td><input type = "password" name = "PW" id="password" placeholder="비밀번호"><br></td>
        </tr>
        <tr>
          <td>비밀번호 재입력</td>
          <td><input type = "password" name ="PW_R" id="password-check" placeholder="비밀번호 확인"><br></td>
        </tr>
        <tr>
          <td>이름 변경</td>
          <td><input type = "text" name = "name" value='<?php echo $row[1]?>' id="user_name" placeholder="이름"/><br></td>
        </tr>
      </table>
      <input type="submit" value="수정" class="myButton" id="btn_modify"/>
      <input type="button" value="취소" id="btn_cancel" onclick="goBack();"/>
    </form>
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
    toggleBtn.classList.toggle('active');
    profileBtn.classList.toggle('active');
  });

  profileBtn.addEventListener('click', () => {
    tooltip.classList.toggle('active');
  });
</script>
</html>
