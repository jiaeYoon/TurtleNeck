<?php
    $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
    $hashedPassword = password_hash($_POST['PW'], PASSWORD_DEFAULT);
    $confirm_p1 = $_POST['PW'];
    $confirm_p2 = $_POST['PW_R'];

    if ($confirm_p1 != $confirm_p2)
    {
?>
<script>
    alert("비밀번호가 일치하지 않습니다.")
    location.href = "signup.html";
</script>
<?php
    }
    else{
        $sql = "
            INSERT INTO user_info
            (login_id, pw, u_name)
            VALUES('{$_POST['ID']}', '{$hashedPassword}', '{$_POST['name']}'
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
            location.href = "../selfcheck/SelfCheck.html";
     </script>
 <?php
      }
    }
?>

