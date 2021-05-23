<?php
    $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
    $hashedPassword = password_hash($_POST['PW'], PASSWORD_DEFAULT);
    echo $hashedPassword;
    $sql = "
        INSERT INTO user_info
        (login_id, pw, u_name)
        VALUES('{$_POST['ID']}', '{$hashedPassword}', '{$_POST['name']}'
        )";
    echo $sql;
    $result = mysqli_query($conn, $sql);

    if ($result === false) {
        echo "저장에 문제가 생겼습니다..";
        echo mysqli_error($conn);
?>
    <script>
        location.href = "index.html";
    </script>
<?php
    } else {
?>
    <script>
        alert("회원가입이 완료되었습니다");
        location.href = "main.html";
     </script>
 <?php
      }
?>

