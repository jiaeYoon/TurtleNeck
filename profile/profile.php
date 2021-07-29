
<?php
  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
  
  /* 인코딩 설정 */
  mysqli_query("SET session character_set_connection=utf8;");
  mysqli_query("SET session character_set_results=utf8;");
  mysqli_query("SET session character_set_client=utf8;");

  /* 세션에 저장해둔 사용자 id값 가져오기 */
  session_start();
  if (isset($_SESSION['userId']))
  {
    echo "로그인상태입니다.";
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

  /* db에서 name값 가져옴 */
  $sql = "SELECT u_name FROM user_info WHERE id='{$id}'";
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
      <img src="../img/turtle.jpg">
    </div> 
    <div class="ex">
      <div class="user_name"><?=$name?></div>
      누적 운동 횟수: <span id="ex_time"><?=$count?>
      </span>회
    </div>
    <div class="setting">
      <i class="fas fa-cog"></i>
    </div>
  </div>

  <!-- Slogan -->
  <div class="slogan">
    바른 자세 만들기,<br/>
    <span class="highlight">TurtleNeck</span>이 함께합니다!
  </div>
</body>
</html>