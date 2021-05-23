<?php
  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
  
  /* 세션에 저장해둔 사용자 id값 가져오기 */
  session_start();
  $id = $_SESSION;
  $id = implode("", $id);

  /* db에서 name값 가져옴 */
  $sql = "SELECT name FROM user_info WHERE id='{$id}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_row($result);
  $name = $row[0];

  /* db에서 운동횟수 값 가져옴 */
  $sql = "SELECT COUNT(*) FROM challenge WHERE id='{$id}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_row($result);
  $count = $row[0];
?>
        
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" 
  integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
  <link rel="stylesheet" href="profile.css">
  <script src="profile.js" defer></script>
</head>
<body>
  <!-- profile -->
  <div class="profile">
    <div class="character">
      <img style="width:60%; height:60%" src="..\img\turtle.jpg">
    </div> 
    <div class="user_name"><?=$name?></div>
    <div class="ex">
      누적 운동 횟수: <span id="ex_time">
        <?=$count?>
      </span>회
    </div>
    <i id="setting" class="fas fa-cog"></i>
  </div>

  <!-- Slogan -->
  <div class="slogan">
    거북이에서 사람까지,<br/>
    <span class="highlight">TurtleNeck</span>이 함께합니다!
  </div>
</body>
</html>