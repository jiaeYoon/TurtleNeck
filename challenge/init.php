<?php
  session_start();
  $id = $_SESSION;
  $id = implode("", $id);

  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
  $sql = "SELECT startDate FROM user_info WHERE id = ${id}";
  $result = mysqli_query($conn, $sql);

  $today = strval(date("Y-m-d"));

  $sql2 = "UPDATE user_info SET startDate = '${today}' WHERE id = ${id}";
  $result2 = mysqli_query($conn, $sql2);
  
  $sql3 = "SELECT startDate FROM user_info WHERE id = ${id}";
  $result3 = mysqli_query($conn, $sql3);
  $row = mysqli_fetch_row($result3);
?>

<Doctype html>
<head></head>
  <body>
    <script>
      location.href = "../challenge/challenge.php";
    </script>
  </body>
</html>