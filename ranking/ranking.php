<?php
  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
  /* 인코딩 설정 */
  mysqli_query("SET session character_set_connection=utf8;");
  mysqli_query("SET session character_set_results=utf8;");
  mysqli_query("SET session character_set_client=utf8;");

  /*세션에 있는 id값 가져오기 */
  // 세션값이 있는지 확인
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

  /* 순위권(월별) 정보 배열에 저장하기  */
  $rank_name = array();
  $rank_count = array();
  $i = 0;

  $sql = "SELECT challenge.id, u_name, COUNT(if(MONTH(challenge.c_date) = MONTH(now()), challenge.id, null)) AS cc  
          FROM challenge,user_info 
          WHERE challenge.id = user_info.id 
          GROUP BY challenge.id 
          ORDER BY cc DESC;";
  $result = mysqli_query($conn, $sql);
  while($row = mysqli_fetch_row($result))
  {
    $row = mysqli_fetch_row($result);

    while($i < 10)
    {
      $rank_name[$i] = $row[1];
      $rank_count[$i] = $row[2];
    }
    if ($row[0] == $id)
    {
      $myrank = $i;
      $mycount = $row[2];
    }
    $i++;
  }

  /*사용자 이름과 사용자 랭킹 */
  $sql = "SELECT u_name FROM user_info WHERE id='{$id}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_row($result);
  $myname = $row[0];
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ranking.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="../challenge/style.css">
    <script src="ranking.js" defer></script>
    <title>Ranking</title>
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
      <div class="title__text">Ranking</div>
    </div>

    <!-- 상위권 3위-->
      <div id="toprank">
        <div id="top2" class="rectangles"><?=$rank_name[1]?><br>
          <img class="profiles" src="../img/profile/1.png"><div class="inside">2등</div>
        </div>
        <div id="top1" class="rectangles"><?=$rank_name[0]?><br>
          <img class="profiles" src="../img/profile/1.png"><div class="inside" id="first">1등</div>
        </div>
        <div id="top3" class="rectangles"><?=$rank_name[2]?><br>
          <img class="profiles" src="../img/profile/1.png"><div class="inside">3등</div>
        </div>
      </div>

    <!-- 상위권 10위-->
    <div id="allrank">
      <br>
      <table id="etctable">
        <tr>
          <th><span class="bold">순위</span></th>
          <th><span class="bold">이름</span></th>
          <th><span class="bold">점수</span></th>
        </tr>
        <tr>
          <td>4등</td>
          <td><?=$rank_name[3]?></td>
          <td><?=$rank_count[3]?></td>
        </tr>
        <tr>
          <td>5등</td>
          <td><?=$rank_name[4]?></td>
          <td><?=$rank_count[4]?></td>
        </tr>
        <tr>
          <td>6등</td>
          <td><?=$rank_name[5]?></td>
          <td><?=$rank_count[5]?></td>
        </tr>
        <tr>
          <td>7등</td>
          <td><?=$rank_name[6]?></td>
          <td><?=$rank_count[6]?></td>
        </tr>
        <tr>
          <td>8등</td>
          <td><?=$rank_name[7]?></td>
          <td><?=$rank_count[7]?></td>
        </tr>
        <tr>
          <td>9등</td>
          <td><?=$rank_name[8]?></td>
          <td><?=$rank_count[8]?></td>
        </tr>
        <tr>
          <td>10등</td>
          <td><?=$rank_name[9]?></td>
          <td><?=$rank_count[9]?></td>
        </tr>
        <tr>
          <td colspan='3'>.<br>.<br>.</td>
        </tr>
        <tr style="border:none;" id="me">
          <td style="border-left:none;"><?=$myrank?>등</td>
          <td style="border-left:none;"><?=$myname?></td>
          <td style="border:none;"><?=$mycount?></td>
        </tr>
        <tr>
          <td colspan='3'>&nbsp</td>
        </tr>
      </table>
    </div>
  </body>
</html>