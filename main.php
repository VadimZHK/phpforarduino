<?php
include "check.php";
if(isset($_SESSION['screen_width']) AND isset($_SESSION['screen_height'])){
    echo 'User resolution: ' . $_SESSION['screen_width'] . 'x' . $_SESSION['screen_height'];
} else if(isset($_REQUEST['width']) AND isset($_REQUEST['height'])) {
    $_SESSION['screen_width'] = $_REQUEST['width'];
    $_SESSION['screen_height'] = $_REQUEST['height'];
    header('Location: ' . $_SERVER['PHP_SELF']);
} else {
    echo '<script type="text/javascript">window.location = "' . $_SERVER['PHP_SELF'] . '?width="+screen.width+"&height="+screen.height;</script>';
}
?>
<html>

	<head>
		<meta http-equiv="Content-Type" Content="text/html; Charset=utf-8">
			<title>Вход выполнен</title>
			<!--LINK REL="SHORTCUT ICON" href="UCS.ico"-->
	</head>
	<body>
		<h1>Главная страница</h1>
		<a href="graph.php">График</a></br>
		<a href="svg.php">График от google</a></br>
		<?php

?>
	</body>
</html>