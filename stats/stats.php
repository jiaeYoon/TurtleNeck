<?php
	// session_start();
	// if (isset($_SESSION['userId']))
	// {
	// $id = $_SESSION['userId'];
	// }
	// else
	// {
	// ? >
	// <script>
	// alert("세션이 만료되어있거나 비회원입니다.");
	// location.href = "../index.html";
	// </script>
	// <?php
	// }  

	$conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");

	/* 세션 값 가져오기 */
	session_start();
	$id = $_SESSION['userId'];

	/* 각 sql문 결과를 저장할 배열 */
	/* data[0]:    누적 운동 횟수
	   data[1]:    사용자 평균 운동 횟수
	   data[2]:    이번달 운동 횟수
	   data[2~13]: 최근 12개월의 운동량 */
	$data = array();
	$i = 0;
	
	/* 누적 운동 횟수 */
	$sql = "SELECT COUNT(*) FROM challenge WHERE id='{$id}'";
	//$sql = "SELECT COUNT(*) FROM challenge WHERE id=104";
	$result = mysqli_query($conn, $sql);
	$row=mysqli_fetch_row($result);
	$data[$i] = $row[0];
	$i++;

	/* 평균 사용자 운동 횟수 */
	$sql = "SELECT AVG(result.c) 
			FROM (SELECT count(*) AS c 
			FROM challenge 
			WHERE c_date 
			GROUP BY id) result";
	$result = mysqli_query($conn, $sql);
	$row=mysqli_fetch_row($result);
	$data[$i] = $row[0];
	$i++;

	/* 이번 달 운동 횟수 */
	$sql = "SELECT COUNT(*) FROM challenge WHERE id='{$id}' AND c_date > date_format(now(), '%Y-%m')";
	//$sql = "SELECT COUNT(*) FROM challenge WHERE id=104 AND c_date > date_format(now(), '%Y-%m')";
	$result = mysqli_query($conn, $sql);
	$row=mysqli_fetch_row($result);
	$data[$i] = $row[0];
	$i++;

	/* 월별 운동 횟수*/
	$sql = "SELECT DATE_FORMAT(c_date,'%Y-%m') m, COUNT(*) 
					FROM challenge 
					WHERE id='{$id}'
					GROUP BY m
					ORDER BY m DESC	/*이번 달 기준 */
					LIMIT 12";

	// $sql = "SELECT DATE_FORMAT(c_date,'%Y-%m') m, COUNT(*) 
	// 				FROM challenge 
	// 				WHERE id=1
	// 				GROUP BY m
	// 				ORDER BY m DESC	/*이번 달 기준 */
	// 				LIMIT 12";

	$result = mysqli_query($conn, $sql);	
	
	$monthly = array();
	$monthly_count = array();
	$dates = array();
	
	$t_date = date("Y-m", time());		
	
	$i = 0;
	while($i < 12) {
		if((substr($t_date, -2, -1)) == '-' || (substr($t_date, -2, ) == 01)) {
			//연도는 작년으로 고정. 달만 바뀜
			$year = date("Y", time())-1;
			while($i < 12){
				$month = $month - 1;
				if($month <= 0)
					$month += 12;
				else if($month < 10) 
				$month = substr_replace($month,"0", 0, 0);
				
				$t_date = $year."-".$month;
				//echo $t_date."<br>";
				$i++;

				array_push($monthly, substr($t_date, 5, ));
				array_push($monthly_count, 0);
				array_push($dates, $t_date);
			}			
		}
			
		else{
			$year = date("Y", time());
			$month = date("m", time()) - $i;
			if($month < 10) 
				$month = substr_replace($month,"0", 0, 0);
			$t_date = $year."-".$month;
			//echo $t_date."<br>";

		array_push($monthly, substr($t_date, 5, ));
		array_push($monthly_count, 0);
		array_push($dates, $t_date);

		$i++;
		}
	}

	$rows = array();
	while($row = mysqli_fetch_array($result)){
		array_push($rows, $row[0], $row[1]);
	}

	for($i=0 ; $i<count($rows) ; $i+=2){
		for($j=0; $j < 12 ; $j++){
			if($dates[$j] == $rows[$i])
				$monthly_count[array_search($rows[$i], $dates)] = $rows[$i+1];
		}
	}

	// for($i=0;$i<12;$i++){
	// 	echo "month:".$monthly[$i]."<br>";
	// 	echo "count:".$monthly_count[$i]."<br>";
	// }

	$sql = "SELECT * FROM challenge WHERE id='{$id}' ORDER BY c_date";
	$result = mysqli_query($conn, $sql);
	
	$dates = array();
	$i = 0;
	while($row=mysqli_fetch_row($result)) {
			$date = $row[1];
			$dates[$i] = $date;
			$i++;
	}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" 
  integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
	<link rel="stylesheet" href="stats.css">
	<link rel="stylesheet" href="../challenge/style.css">
	
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>통계</title>
  
</head>
<body>
<body>
  
  <!-- header -->
  <header>
    <img id="wave" src="../img/sea.svg" alt="wave">
    <nav class="navbar">
      <a href="../main/main.php"><img id="logo" src="../img/turtleneck_logo.svg" alt="logo"></a>
      <ul class="navbar__menu">
        <li><a href="../challenge/challenge.php">30일 챌린지</a></li>
        <li class="navbar__review"><a href="../review/hospital.php">리뷰</a>
          <ul class="navbar__submenu">
            <li><a href="../review/hospital.php">병원 리뷰</a></li>
            <li><a href="../review/item.php">제품 리뷰</a></li>
          </ul>
        </li>
        <li><a href="../stats/stats.php">통계</a></li>
        <li><a href="../ranking/ranking.php">랭킹</a></li>
      </ul>
      <div class="navbar__btn">
        <i class="fas fa-user-circle" id="profile"></i>
        <!-- toggle menu -->
        <i class="fas fa-bars" id="toggleBtn"></i>
      </div>
    </nav>
    <!-- tooltip -->
    <div class="tooltip">
      <a href="../stats/stats.php">통계</a>
      <a href="../modify/auth.php">정보 수정</a>
      <hr/>
      <a href="../signup/logoutProcess.php">로그아웃</a>
    </div>
  </header>
	
  <!-- hideen background -->
  <div class="hidden__bg"></div>

	<!-- title -->
	<div class="title">
		<div id="title__line" style="width:50px;"></div>
		<div class="title__text">통계</div>
	</div>
	

	<!-- 차트 그릴 캔버스 -->
	<table>
		<tr>
			<td>
				<div class="graph_title">누적 운동량</div>
				<canvas id="TotalStamp"> </canvas> 
			</td>
			<td>
				<div class="graph_title">이번달 운동량</div>
				<canvas id="ThisMonthStamp"> </canvas>
			</td>
			<td rowspan="2" id="td_frame">
				<div class="graph_title">운동 히스토리</div>
				<iframe src="history.php"></iframe>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="graph_title">월 별 운동량</div>
				<canvas id="MonthlyStamp" style="float:right;"> </canvas>
			</td>
		</tr>
	</table>

	<script>
		/* header buttons(profile, menu bar) */
		const toggleBtn = document.querySelector('#toggleBtn');
    const profileBtn = document.querySelector('#profile');

    const menu = document.querySelector('.navbar__menu');
    const hidden_box = document.querySelector('.hidden__bg');
    const tooltip = document.querySelector('.tooltip');

    toggleBtn.addEventListener('click', () => {
      menu.classList.toggle('active');
      hidden_box.classList.toggle('active');
      toggleBtn.classList.toggle('active');
      profileBtn.classList.toggle('active');
    });

    profileBtn.addEventListener('click', () => {
      tooltip.classList.toggle('active');
    });
		
		// DB에서 date 값 받아오기
		const data = <?php echo json_encode($data)?>;	
		let today = new Date();
		
		/* 차트 그리기 */
		/* 누적 운동량 도넛 그래프 */
		var TotalCanvas = document.getElementById('TotalStamp').getContext('2d');

		var TotalStamp = new Chart(TotalCanvas, {
			type: 'doughnut',
				data: {
					labels:['나의 누적 운동량',
									'사용자 평균 누적 운동량'],
						datasets: [{
								label: 'Chart of TotalStamp',
								data: [data[0], data[1]],
								backgroundColor: [
									'rgb(255, 205, 86)',
									'gray'
								],
								hoverOffset: 4}]
			},
			options: {
				/* 브라우저 사이즈가 바뀌어도 < style >태그 안에 고정시킨 사이즈 대로 고정됨 */
				responsive: false
			}
		});

		/* 이번달 운동량 도넛 그래프 */
		var ThisMonthCanvas = document.getElementById('ThisMonthStamp').getContext('2d');

		//이번달의 마지막 날
		let lastday = new Date(today.getYear(), today.getMonth()+1, 0).getDate();

		var ThisMonthStamp = new Chart(ThisMonthCanvas, {
			type: 'doughnut',
			data: {
					labels:['운동한 날',
									'운동 안한 날'],
						datasets: [{
								label: 'My First Dataset',
								data: [data[2], lastday-data[2]],  //운동O day, 운동X day 순서
								backgroundColor: [
									'rgb(255, 205, 86)',
									'gray'
								],
								hoverOffset: 4}]
			},
			options: {
				responsive: false,
				pieceLabel: { mode:"label", position:"outside", fontSize: 11, fontStyle: 'bold' }
			}
		});

		/* 월별 운동량 막대그래프 */
		var ctx = document.getElementById('MonthlyStamp').getContext('2d');
		let thisMonth = today.getMonth()+1;
		//console.log(thisMonth);

		// DB에서 date 값 받아오기
		const m_monthly = <?php echo json_encode($monthly)?>;	
		const d_monthly = <?php echo json_encode($monthly_count)?>;	

		var MonthlyStamp = new Chart(ctx, {
				type: 'bar',
				data: {
						labels: [m_monthly[11], m_monthly[10], m_monthly[9], m_monthly[8], 
										m_monthly[7], m_monthly[6], m_monthly[5], m_monthly[4], 
										m_monthly[3], m_monthly[2], m_monthly[1], m_monthly[0]],
										
						datasets: [{
								label: '# of Exercise',
								data: [d_monthly[11], d_monthly[10], d_monthly[9], d_monthly[8], 
										d_monthly[7], d_monthly[6], d_monthly[5], d_monthly[4], 
										d_monthly[3], d_monthly[2], d_monthly[1], d_monthly[0] ],
								backgroundColor: [
										'rgba(255, 99, 132, 0.2)',
										'rgba(54, 162, 235, 0.2)',
										'rgba(255, 206, 86, 0.2)',
										'rgba(75, 192, 192, 0.2)',
										'rgba(153, 102, 255, 0.2)',
										'rgba(255, 159, 64, 0.2)'
								],
								borderColor: [
										'rgba(255, 99, 132, 1)',
										'rgba(54, 162, 235, 1)',
										'rgba(255, 206, 86, 1)',
										'rgba(75, 192, 192, 1)',
										'rgba(153, 102, 255, 1)',
										'rgba(255, 159, 64, 1)'
								],
								borderWidth: 1
						}]
				},
				options: {
						responsive: false,
						scales: {
								y: {
										beginAtZero: true,
										min: 0,
										max: 31,
										stepSize : 5,
								}
						}
				}
		});
	</script>
  </body>
</html>