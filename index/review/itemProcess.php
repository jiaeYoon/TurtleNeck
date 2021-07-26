<?php
	$conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");

	// 한글 깨짐 방지
	mysqli_query($conn, "set session character_set_connection=utf8;");
	mysqli_query($conn, "set session character_set_results=utf8;");
	mysqli_query($conn, "set session character_set_client=utf8;");

	/* 세션에 저장해둔 사용자 id값 가져오기 */
	session_start();
	if (isset($_SESSION['userId']))
	{
		$id = $_SESSION['userId'];
		$id = implode("",$id);
	}
	else
	{
	echo "세션이 만료되었거나 비회원입니다.";
?>
<script>
	alert("세션이 만료되어있거나 비회원입니다.");
	location.href = "index.html";
</script>
<?php
	}  

	$item_name = $_POST['item_name'];
	$item_comment = $_POST['new_comment'];
	$item_rating = $_POST['rating'];
	$dates = date("Y-m-d");

// DB에 저장되는 값 : 작성자id, 제품명, 평점, 코멘트, 작성 날짜
	if ($item_rating)
	{

		$sql = "INSERT INTO item_review (id, item_name, item_rating, item_comments, dates) 
				VALUES ('{$id}', '{$item_name}', '{$item_rating}', '{$item_comment}', '{$dates}')";
		$result = mysqli_query($conn, $sql);

		if ($result == false)
		{
			echo "저장에 문제가 생겼습니다.";
			echo mysqli_error($conn);
		
?>
	<script>
		location.href = "item.php";
	</script>
<?php
		}else {
?>
	<script>
		location.href = "item.php";
	</script>
<?php
		}
	}else {

?>
	<script>
    alert("평점을 입력해주세요.");
    location.href = "item.php";
  </script>
<?php
	}
?>