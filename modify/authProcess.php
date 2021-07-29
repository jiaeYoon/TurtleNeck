<?php
    $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");

    // 한글 깨짐 방지
    mysqli_query($conn, "set session character_set_connection=utf8;");
    mysqli_query($conn, "set session character_set_results=utf8;");
    mysqli_query($conn, "set session character_set_client=utf8;");

    /* 세션에 저장해둔 사용자 id값 가져오기 */
    session_start();
    $id = $_SESSION['userId'];

    $password = $_POST['PW'];

    // DB 정보 가져오기
    $sql = "SELECT * FROM user_info WHERE id ='{$id}'";
    //$sql = "SELECT * FROM user_info WHERE id =194";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $hashedPassword = $row['pw'];
    
    //비밀번호 검증
    $passwordResult = password_verify($password, $hashedPassword);

    /* ----------- 인증 성공 ------------ */
    if ($passwordResult) {
    ?>
        <script>
            location.href = "modify.php";
        </script>
    <?php
    } 
    /* ----------- 인증 실패 ------------ */
    else {
        
    ?>
        <script>
            alert("비밀번호 인증에 실패하였습니다");
            location.href = "auth.php";
        </script>
    <?php
    }
?>