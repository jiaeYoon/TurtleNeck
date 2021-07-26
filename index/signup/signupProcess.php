<?php
    $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
    
    // 한글 깨짐 방지
    mysqli_query($conn, "set session character_set_connection=utf8;");
    mysqli_query($conn, "set session character_set_results=utf8;");
    mysqli_query($conn, "set session character_set_client=utf8;");
    
    $hashedPassword = password_hash($_POST['PW'], PASSWORD_DEFAULT);
    
    $ID = $_POST['ID'];
    $name =$_POST['name'];
    $confirm_p1 = $_POST['PW'];
    $confirm_p2 = $_POST['PW_R'];
    
    if ($ID && $name && $confirm_p1)
    {
      $sql = "SELECT id FROM user_info WHERE login_id='{$ID}'";
      $result = mysqli_query($conn, $sql);
      //$sql = "SELECT id FROM user_info WHERE login_id='qw'";

      $count = mysqli_num_rows($result);
      //echo $count;
      //$row = mysqli_fetch_array($result);
      //print_r($row);
      //echo $row;
      if($count)
      {
?>
<script>
    alert("사용할 수 없는 아이디입니다.")
    location.href = "signup.php";
</script>
<?php
      }

      if ($confirm_p1 != $confirm_p2)
      {
?>
<script>
        alert("비밀번호가 일치하지 않습니다.")
        location.href = "signup.php";
</script>
<?php
        }
        else{
            $sql = "
                INSERT INTO user_info
                (login_id, pw, u_name)
                VALUES('{$ID}', '{$hashedPassword}', '{$name}'
                )";
            $result = mysqli_query($conn, $sql);

            if ($result === false) {
                echo "저장에 문제가 생겼습니다..";
                echo mysqli_error($conn);
?>
    <script>
            location.href = "../index.html";
    </script>
<?php
            } else {
?>
    <script>
                alert("회원가입이 완료되었습니다");
                location.href = "../selfcheck/SignUp_SelfCheck.php";
     </script>
 <?php
            }
        }
    }
    else
    {
?>
<script>
        alert("모든 값을 입력해주세요.")
        location.href ="signup.html";
</script>
<?php
    }
?>