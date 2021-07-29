<?php
    $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");

    // 한글 깨짐 방지
    mysqli_query($conn, "set session character_set_connection=utf8;");
    mysqli_query($conn, "set session character_set_results=utf8;");
    mysqli_query($conn, "set session character_set_client=utf8;");

    /* 세션에 저장해둔 사용자 id값 가져오기 */
    session_start();
    $id = $_SESSION['userId'];

    $hashedPassword = password_hash($_POST['PW'], PASSWORD_DEFAULT);

    $profile = $_POST['images'];
    $confirm_p1 = $_POST['PW'];
    $confirm_p2 = $_POST['PW_R'];
    $name =$_POST['name'];

    if($profile)
    {
      $sql = "UPDATE user_info SET profileNum ='{$profile}' WHERE id='{$id}'";
      $result = mysqli_query($conn, $sql);
    }

    if($confirm_p1)
    {
      $sql = "SELECT id FROM user_info WHERE id='{$id}'";
      $result = mysqli_query($conn, $sql);

      if ($confirm_p1 != $confirm_p2)
      {
?>
        <script>
        alert("비밀번호가 일치하지 않습니다.")
        location.href = "modify.php";
        </script>
<?php
       }
      else
      {
        $sql = "UPDATE user_info SET pw ='{$hashedPassword}' WHERE id='{$id}'";
        $result = mysqli_query($conn, $sql);

        if ($result === false) {
          echo "저장에 문제가 생겼습니다..";
          echo mysqli_error($conn);
?>
    <script>
          location.href = "../modify.php";
    </script>
<?php
        }
      }
    }

    if ($name)
    {
        $sql = "UPDATE user_info SET u_name='{$name}' WHERE id='{$id}'";
        $result = mysqli_query($conn, $sql);

        if ($result === false) {
            echo "저장에 문제가 생겼습니다..";
            echo mysqli_error($conn);
  ?>
    <script>
            location.href = "../modify.php";
    </script>
  <?php
        } else {
  ?>
    <script>
            alert("정보 수정이 완료되었습니다");
            location.href = "modify.php";
     </script>
   <?php
        }
    }
?>
