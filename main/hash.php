<?php
  $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");

  mysqli_set_charset($conn, "utf8");

  session_start();
  $id = $_SESSION;
  $id = implode("", $id);

  
  if($_GET["ex_part"]){
		$hash = $_GET["ex_part"];

		
		switch($hash){
			case '#어깨':
			$hash = 'shoulder';
			break;
			
			case '#목':
			$hash = 'neck';
			break;
			case '#팔':
			$hash = 'arm';
			break;
			
			case '#골반':
			$hash = 'pelvic';
			break;

			default:
			break;
		}


		// user_info에 저장된 시작 날짜 가져오기
		$sql = "SELECT e_name FROM classifyex WHERE hashtag LIKE '%{$hash}%'";
		//$sql = "SELECT e_name FROM classifyex WHERE hashtag LIKE '%shoulder%'";
		$result = mysqli_query($conn, $sql);

		//ename_arr[] : 버튼 클릭한 스트레칭 부위가 있는, 운동 세트의 이름들을 배열로 저장
		$ename_arr = array();
		$i = 0;

		while ($row = mysqli_fetch_row($result)) {
			$ename_arr[$j] = $row[0];
		}
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
		<title>해시태그 테스트</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
	</head>

	<body>
		<h2>해시태그 테스트</h2>
		<form action="hash.php" method="GET" name="hashform">
			<input type="submit" name="ex_part" value="#어깨"></button>
			<input type="submit" name="ex_part" value="#목"></button>
			<input type="submit" name="ex_part" value="#골반"></button>
      <input type="submit" name="ex_part" value="#팔"></button>
		</form>

    <div id="blank"> </div>
 
    <div class="exercise__box">
      <div  id="form1">
        <div class="exercise__btn" onClick="document.forms['formone'].submit()"; style="cursor:pointer;">
          <form action="../exercise/exercise.php" method="GET" name="formone">
            <input type="hidden" name="exercise" value="ex1">
              <img src="../exercise/ex_image/neck_shoulder1.png" class="imges">
              <p class="exercise__name">목, 어깨<br/>통증 예방 운동</p>
          </form>
        </div>
      	<div class="exercise__des">
        	<p><span class="des__detail">잘못된 컴퓨터 사용 자세로<br> 생긴 목과 어깨 통증을 예방하는 스트레칭</span><br><br>
          스트레칭 부위: <span class="des__em">목, 어깨</span></p>
      	</div>
    	</div>
  	</div>

  	<div class="exercise__box">
    	<div id="form2">
        <div class="exercise__btn" onClick="document.forms['formtwo'].submit()"; style="cursor:pointer;">
          <form action="../exercise/exercise.php" method="GET" name="formtwo">
            <input type="hidden" name="exercise" value="ex2">
              <img src="../exercise/ex_image/shoulder4.png" class="imges">
              <p class="exercise__name">어깨 스트레칭</p>
          </form>
        </div>
        <div class="exercise__des">
          <p><span class="des__detail">뭉친 어깨를 풀어주는 스트레칭</span><br><br>
          스트레칭 부위: <span class="des__em">어깨</span></p>
        </div>
      </div>
		</div>

		<div class="exercise__box">
  <div id="form3">
          <div class="exercise__btn" onClick="document.forms['formthree'].submit()"; style="cursor:pointer;">
            <form action="../exercise/exercise.php" method="GET" name="formthree">
              <input type="hidden" name="exercise" value="ex3">
              <img src="../exercise/ex_image/arm_shoulder1.png" class="imges">
              <p class="exercise__name">팔, 어깨 스트레칭</p>
            </form>
          </div>
          <div class="exercise__des">
            <p><span class="des__detail">팔과 어깨 근육을 이완시켜줌으로써 통증을 줄여주는 스트레칭</span><br><br>
              스트레칭 부위: <span class="des__em">팔, 어깨</span></p>
          </div>
        </div>
</div>
          
        <div class="exercise__box">
          <div id="form4">
          <div class="exercise__btn" onClick="document.forms['formfour'].submit()"; style="cursor:pointer;">
            <form action="../exercise/exercise.php" method="GET" name="formfour">
              <input type="hidden" name="exercise" value="ex4">
              <img src="../exercise/ex_image/pelvic1.png" class="imges">
              <p class="exercise__name">골반 스트레칭</p>
            </form>
          </div>
          <div class="exercise__des">
            <p><span class="des__detail">장시간 편향적인 자세로 앉아있으면서 틀어진 골반을 바로잡는 스트레칭</span><br><br>
              스트레칭 부위: <span class="des__em">골반</span></p>
          </div>
        </div>
      </div>
    </div>
</div>




    <script>
      const eng_exercises = <?php echo json_encode($ename_arr)?>;
      
      console.log(eng_exercises.includes("shoulder"));
      if(!eng_exercises.includes("shoulder")){
        const name = document.getElementById("form1").style.display = "none";  
      }

      if(!eng_exercises.includes("neck_shoulder")){
        const name = document.getElementById("form2").style.display = "none";  
      }
      
      if(!eng_exercises.includes("arm_shoulder")){
        const name = document.getElementById("form3").style.display = "none";  
      }

      if(!eng_exercises.includes("pelvic")){
        const name = document.getElementById("form4").style.display = "none";  
      }
      
    </script>
  </body>
</html>