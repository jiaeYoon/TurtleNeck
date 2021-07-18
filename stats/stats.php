<?php
	session_start();
	$id = $_SESSION;
	$id = implode("", $id);

	$conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
	
	/* 각 sql문 결과를 저장할 배열 */
	/* data[0]:    누적 운동 횟수
		 data[1]:    사용자 평균 운동 횟수
		 data[2]:    이번달 운동 횟수
		 data[2~13]: 최근 12개월의 운동량 */
	$data = array();
	$i = 0;
	
	/* 누적 운동 횟수 */
	//$sql = "SELECT COUNT(*) FROM challenge WHERE id='{$id}'";
	$sql = "SELECT COUNT(*) FROM challenge WHERE id=104";
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
	//$sql = "SELECT COUNT(*) FROM challenge WHERE id='{$id}' AND c_date > date_format(now(), '%Y-%m')";
	$sql = "SELECT COUNT(*) FROM challenge WHERE id=104 AND c_date > date_format(now(), '%Y-%m')";
	$result = mysqli_query($conn, $sql);
	$row=mysqli_fetch_row($result);
	$data[$i] = $row[0];
	$i++;

	/* 월별 운동 횟수*/
	// $sql = "SELECT DATE_FORMAT(c_date,'%Y-%m') m, COUNT(*) 
	// 				FROM challenge 
	// 				WHERE id='{$id}'
	// 				GROUP BY m
	// 				ORDER BY m DESC	/*이번 달 기준 */
	// 				LIMIT 12";
	$sql = "SELECT COUNT(*) 
	 				FROM challenge 
					WHERE id=104
	 				GROUP BY DATE_FORMAT(c_date,'%Y-%m')
	 				ORDER BY DATE_FORMAT(c_date,'%Y-%m') DESC	/*이번 달 기준 */
	 				LIMIT 12";
	$result = mysqli_query($conn, $sql);	
	while($row=mysqli_fetch_row($result)) {
			$data[$i] = $row[0];
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
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>통계</title>
  
</head>
<body>

	<!-- 차트 그릴 캔버스 -->
	<canvas id="TotalStamp"> </canvas>
	<canvas id="ThisMonthStamp"> </canvas>
	<canvas id="MonthlyStamp" style="float:right;"> </canvas>

	<script>
	// DB에서 date 값 받아오기
	const data = <?php echo json_encode($data)?>;
	
	let today = new Date();
	
	/* 누적 운동량 도넛 그래프 */
	var TotalCanvas = document.getElementById('TotalStamp').getContext('2d');

	var TotalStamp = new Chart(TotalCanvas, {
		type: 'doughnut',
  	data: {
				labels:['나의 누적 운동량',
								'평균 누적 운동량'],
					datasets: [{
							label: 'My First Dataset',
							data: [data[0], data[1]],
							backgroundColor: [
								'rgb(54, 162, 235)',
								'rgb(255, 205, 86)'
							],
							hoverOffset: 4}]
		},
		options: {
			responsive: false
		}
	});

	/* 이번달 운동량 도넛 그래프 */
	let lastday = new Date(today.getYear(), today.getMonth()+1, 0).getDate();
	var ThisMonthCanvas = document.getElementById('ThisMonthStamp').getContext('2d');

	var ThisMonthStamp = new Chart(ThisMonthCanvas, {
		type: 'doughnut',
  	data: {
				labels:['운동한 날',
								'운동 안한 날'],
					datasets: [{
							label: 'My First Dataset',
							data: [data[2], lastday-data[2]],  //운동O day, 운동X day 순서
							backgroundColor: [
								'rgb(54, 162, 235)',
								'rgb(255, 205, 86)'
							],
							hoverOffset: 4}]
		},
		options: {
			responsive: false
		}
	});

/* 월별 운동량 막대그래프 */
var ctx = document.getElementById('MonthlyStamp').getContext('2d');
	
	
	let thisMonth = today.getMonth()+1;
	//console.log(thisMonth);

	var MonthlyStamp = new Chart(ctx, {
			type: 'bar',
			data: {
					labels: [thisMonth-11, thisMonth-10, thisMonth-9,thisMonth-8,
									 thisMonth-7, thisMonth-6,thisMonth-5,thisMonth-4,
									 thisMonth-3, thisMonth-2, thisMonth-1, thisMonth],
					datasets: [{
							label: '# of Votes',
							data: [data[14], data[13], data[12], data[11], 
										 data[10], data[9], data[8], data[7], 
										 data[6], data[5], data[4], data[3] ],
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
									beginAtZero: true
							}
					}
			}
	});
	</script>
  </body>
</html>