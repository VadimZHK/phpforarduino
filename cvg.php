<?php
include "check.php";  //Был ли введен пароль
include "Connect.php";//Откроем базу
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
['X','дом','улица','чердак','бытовка']
,[0,-4.2,-5.2,-3.3,-4.7]
,[1,-3.8,-5.0,-3.2,-4.6]
,[2,-4.1,-5.1,-3.2,-4.6]
,[3,-4.4,-5.4,-3.5,-4.9]
,[4,-4.3,-5.4,-3.5,-4.9]
,[5,-4.3,-5.4,-3.5,-4.8]
,[6,-4.2,-5.3,-3.4,-4.8]
,[7,-4.2,-5.3,-3.4,-4.8]]);
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
<div id='chart_div' style='width:900px;height:500px;'>
</div>
<!--/form-->
</body>
</html>