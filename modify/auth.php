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
  //$sql = "SELECT login_id FROM user_info WHERE id='{$id}'";
  
  $sql = "SELECT login_id FROM user_info WHERE id='194'"; //테스트용
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_row($result);
  $login_id = $row[0];
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../challenge/style.css">
  <link rel="stylesheet" href="modify.css">
  <link rel="stylesheet" href="auth.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" 
  integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
  <title>회원정보 수정</title>
  
</head>
<body>
	<!-- header -->
	<header>
		<img id="wave" src="../img/sea.svg" alt="wave"> 
		<nav class="navbar">
			<a href="../main/main.html"><img id="logo" src="../img/turtleneck_logo.svg" alt="logo"></a>
			<ul class="navbar__menu">
			<li><a href="">30일 챌린지</a></li>
			<li><a href="">커뮤니티</a></li>
			<li><a href="">마이페이지</a></li>
			<li><a href="">정보 수정</a></li>
			</ul>
			<i class="fas fa-user-circle" id="profile"></i>
		</nav>
		<!-- tooltip -->
		<div class="tooltip">
			<a href="../main/main.html">마이 페이지</a>
			<a href="../main/main.html">정보 수정</a>
			<hr/>
			<a href="../signup/logoutProcess.php">로그아웃</a>
		</div>
	</header>

	<!-- title -->
	<div class="title">
		<div class="title__line"></div>
		<div class="title__text">비밀번호 인증</div>
	</div>

    <div id="auth_body">
        <div id="warning_text">
            정보를 안전하게 보호하기 위해 비밀번호를 다시 한 번 확인합니다.</br>
            비밀번호가 타인에게 노출되지 않도록 항상 주의해주세요.
        </div>
        
        <form name = "auth" id="auth-form" action="authProcess.php" method="POST">	
        <table cellpadding="2">
            <tr>
                <td>아이디</td>
                <td> <input type="text" id="login_id" value='<?php echo $login_id?>' disabled/></td>
            </tr>
            <tr>
                <td>비밀번호 변경</td>
                <td><input type = "password" name = "PW" id="password" placeholder="비밀번호"><br></td>
            </tr>
            <input type="submit" value="확인" class="myButton" id="btn_modify"/>
        </form>
    </div>
</body>
</html>
