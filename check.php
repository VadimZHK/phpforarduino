<?php
session_start();
if (!$_SESSION['pass']) header("Location: index.php");
?>