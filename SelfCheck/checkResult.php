<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>자가진단</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" 
  integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
  <link rel="stylesheet" href="selfCheck.css">
</head>
<body>
  <!-- 헤더 -->
  <header>
    <img id="wave" src="sea.svg" alt="wave">
    <!--div class="header"-->
      <!-- 로고 이미지를 클릭하면 마이페이지로 이동 -->
      <a href="mypage.html"><img id="logo" src="turtleneck_logo.svg" alt="logo"></a>
    <!-- 로그아웃 -->
    <i class="fas fa-user-circle"></i>
    <!--/div-->
  </header>

  <!-- 본문 -->
  <main class="main">
    <img class="beach" src="beach.svg" alt="main page">
    <img class="turtle" src="turtle.svg" alt="turtle">
    
    <div class="container">
      <h1>자가진단 결과</h1>
      <h4>현재 상태를 체크해보세요.</h4>
      <!-- 자가진단 결과-->
      <?php
        echo $_POST["check"];
      ?>
  </div>
  </main>

</body>
</html>