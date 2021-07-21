<?php
	$conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");

	// 한글 깨짐 방지
	mysqli_query($conn, "set session character_set_connection=utf8;");
	mysqli_query($conn, "set session character_set_results=utf8;");
	mysqli_query($conn, "set session character_set_client=utf8;");

	/* 세션에 저장해둔 사용자 id값 가져오기 */
	session_start();
	$id = $_SESSION;
	$id = implode("", $id);

	$hos_name = $_POST['hospital_name'];
	$new_comment = $_POST['new-comment'];
	$ratings = $_POST['rating3'];
	$dates = date("Y-m-d");

// DB에 저장되는 값 : 작성자id, 제품명, 평점, 코멘트, 작성 날짜
	if ($ratings)
	{

		$sql = "INSERT INTO hospital_review (id, hos_name, hos_rating, hos_comments, dates) 
				VALUES ($id, $hos_name, $hos_rating, $hos_comment, $dates)";
		$result = mysqli_query($conn, $sql);

		if ($result == false)
		{
			echo "저장에 문제가 생겼습니다.";
			echo mysqli_error($conn);
		
?>
	<script>
		location.href = "../hospital.php";
	</script>
<?php
		}else {
?>
	<script>
		location.href = "hospital.php";
	</script>
<?php
		}
	}else {

?>
	<script>
    alert("평점을 입력해주세요.");
    location.href = "hospital.php";
  </script>
<?php
	}
?>