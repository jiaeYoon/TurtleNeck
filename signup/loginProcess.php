<?php
$conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
//아이디 비교와 비밀번호 비교가 필요한 시점이다.
// 1차로 DB에서 비밀번호를 가져온다
// 평문의 비밀번호와 암호화된 비밀번호를 비교해서 검증한다.
$email = $_POST['userid'];
$password = $_POST['userpw'];

// DB 정보 가져오기
$sql = "SELECT * FROM user_info WHERE login_id ='{$email}'";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_array($result);
$hashedPassword = $row['pw'];
$row['id'];

// DB 정보를 가져왔으니
// 비밀번호 검증 로직을 실행하면 된다.
$passwordResult = password_verify($password, $hashedPassword);

if (password_verify($password, $hashedPassword)) {
    // 로그인 성공
    // 세션에 id 저장
    session_start();
    $_SESSION['userId'] = $row['id'];
?>
    <script>
        location.href = "../main/main.html";
    </script>
<?php
} else {
     //로그인 실패
?>
    <script>
        alert("로그인에 실패하였습니다");
        // 실패하면 다시 메인 화면으로
        location.href = "../index.html";
    </script>
<?php
}
?>
