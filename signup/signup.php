<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.bundle.min.js" integrity="sha384-BOsAfwzjNJHrJ8cZidOg56tcQWfp6y72vEJ8xQ9w6Quywb24iOsW913URv1IS4GD" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="signup.css">
		<title>회원가입</title>
</head>
<body>
	<header>
		<img id="wave" src="../img/sea.svg" alt="wave">
		<img id="logo" src="../img/turtleneck_logo.svg" alt="logo" href="mainpage.html"><!--메인페이지로 이동하기-->
	  </header>
	<!-- Sign In Box -->
	<div id = "all">
		<div class = "main_box" >
			<form name = "login" action="signupProcess.php" method = "POST" id="signup-form">
				<br>
				<table cellpadding="2">
					<tr>
						<td>아이디</td>
						<td> &nbsp; <input type = "text" name = "ID"><br></td>
					</tr>
					<tr>
						<td>비밀번호 </td>
						<td>&nbsp; <input type = "password" name = "PW" id="password"><br></td>
					</tr>
					<tr>
						<td>다시 입력</td>
						<td>&nbsp; <input type = "password" name ="PW_R" id="password-check"><br></td>
					</tr>
					<tr>
						<td>이름</td>
						<td>&nbsp; <input type = "text" name = "name"><br></td>
					</tr>
				</table>
				<br>
				<input type="submit" value="회원가입" class="myButton" id="signup-button">
			</form>
		</div> <!-- main_box-->
	</div> <!-- all-->
	<!-- Sign In Box -->

</body>
<script>
	const signupForm = document.querySelector("#signup-form");
	const signupButton = document.querySelector("#signup-button");
	const password = document.querySelector("#password");
	const passwordCheck = document.querySelector("#password-check");
	signupButton.addEventListener("click", function(e) {
		if(password.value&& password.value === passwordCheck.value){

		signupForm.submit();
		}else{
			alert("비밀번호가 서로 일치하지 않습니다");
			location.href = "signin.html";
			event.stopImmediatePropagation();
		}
	});
</script>
</html>