<?php
include "check.php";  //Был ли введен пароль
include "Connect.php";//Откроем базу

//массив данных
$DATA=Array();
//Возьмем данные для графика из базы

$query = "select * from PR\$GETTEPM('01.05.2017 02:00:00', '02.05.2017')";
$s = "";//для хранения предыдущего значения времени
$i = 0;
$tbl = ibase_query($dbcnx,$query);
//Заполнение первых элементов массива, т.к. в запросе их может не быть
		$DATA[1][0] = "10.00";
		$DATA[2][0] = "10.00";
		$DATA[3][0] = "10.00";
		$DATA[4][0] = "10.00";
		$DATA[5][0] = "10.00";
//--------------------------------------------------------------------		
		
while ($row= ibase_fetch_row($tbl)){
		//echo($row[0]." ".$row[1]." ".$row[2]."<br>");//alias used 
		if ($row[0] == -1) {//отрицательные это минимальные
			$templow = $row[1];
			$datalow = $row[2];
		} else if ($row[0] == -2) {//и максимальные значения
			$temphigh = $row[1];
			$datahigh = $row[2];	
		} else {					//данные
		
			$DATA[$row[0]][]=$row[1];
			if ($s != $row[2]){
			$DATA["x"][]=$row[2];
		//Проверка, все ли даненые пришли. Если нет, то заполним из предыдцщих
		if (empty($DATA[1][$i])) $DATA[1][$i] = $DATA[1][$i-1];
		if (empty($DATA[2][$i])) $DATA[2][$i] = $DATA[2][$i-1];
		if (empty($DATA[3][$i])) $DATA[3][$i] = $DATA[3][$i-1];
		if (empty($DATA[4][$i])) $DATA[4][$i] = $DATA[4][$i-1];
		if (empty($DATA[5][$i])) $DATA[5][$i] = $DATA[5][$i-1];
		//---------------------------------------------------------------------
		$i++;
			}
			$s = $row[2];
		}		
	}	
//print("\r\n".$query."\r\n");	
//var_dump($DATA);	
//exit;

$count = count($DATA[1]);
$W=$_SESSION['screen_width'];
$H=$_SESSION['screen_height'];
$W = $W - ($W/10);
$H = $H - ($H/10);

$WH = "'width:".$W."px;height:".$H."px;'";
//print($WH);
?>
<html>
<head>
<link rel='icon' type='image/ico' href='https://www.arduino.cc/favicon.ico' type='image/x-icon'/>
<meta charset='utf-8'>
<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'>
</script>
<script type='text/javascript'>google.charts.load('current',{packages:['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart(){var data=google.visualization.arrayToDataTable([
['X','дом','улица','чердак','бытовка','парник']
<?php
for ($i=0;$i<$count;$i++)
{
	echo(",[".$i.",".$DATA[1][$i].",".$DATA[2][$i].",".$DATA[3][$i].",".$DATA[4][$i].",".$DATA[5][$i]."]");
}

?>

]);
var options={};
var chart=new google.visualization.LineChart(document.getElementById('chart_div'));
chart.draw(data,options);
}
</script>
</head>
<body>
<!--form action='' method='post'-->
<h1>Страничка графика</h1>
<!--input type='submit' name='main' value='main'>
<input type='submit' name='svg' value='svg'-->
<br>
<div id='chart_div' style=<?php echo($WH);?>>
</div>
<!--/form-->
</body>
</html>