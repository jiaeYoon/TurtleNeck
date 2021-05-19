<?php
  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
  
  /* 세션에 저장해둔 사용자 아이디(login_id)값 가져오기 */
  $id = $_SESSION['userId'];
  print_r($_SESSION);

  /* challenge 테이블에 사용자의 id값, 날짜 삽입 */
  $sql = "insert into challenge(id, c_date) values((SELECT user_info.id from user_info where user_info.login_id='{$id}'), now());";
  $result = mysqli_query($conn, $sql);

  if($result === false){
    echo mysqli_error($conn);
  }
?>

<Doctype html>
<head></head>
  <body>
    <h1> good job! </h1>
  </body>
</html>