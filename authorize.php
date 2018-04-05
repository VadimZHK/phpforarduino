<?php 
    if	(!session_start()) print("Error session_start");
	include "Connect.php";
    $hpassword = ""; 
    // Имя сервера базы данных, например $dblocation = "mysql28.noweb.ru" 
    // сейчас выставлен сервер локальной машины 
    $dblocation = ""; 
    // Имя базы данных 
    $dbname = ""; 
    // Имя пользователя... Скорее всего будет совпадать с вашим доменным именем 
    // у нас, например $dbuser = "softtime";  
    $dbuser = $_POST['user_name']; 
    // Пароль - в комментариях не нуждается ;-) 
    $dbpasswd = $_POST['user_pass']; 
	//echo(@ibase_timestampformat());
	//echo($_POST['name']."<br>");
    // Соединяемся с сервером базы данных 
/*    $dbcnx = @ibase_connect(); 
    if (!$dbcnx) { 
	  echo(ibase_errmsg ());
      echo( "<P>В настоящий момент сервер базы данных не 
                          доступен, поэтому корректное отображение 
                          страницы невозможно.</P>" ); 
      exit(); 
    } */
    // Формируем и выполняем SQL-запрос для посетителя с 
    // именем $_POST['name'] 
    $query = "SELECT * FROM users WHERE lower(logon)=lower('".$dbuser."')"; 
    /*while ($nme = ibase_query($dbcnx,$query)){
		print($nme->PASSWORD);//alias used 
	}	*/	
	$nme = ibase_query($dbcnx,$query);
//	echo($_POST['user_name']." ".$_POST['user_pass']."<br>");
    if(!$nme) 
    { //echo(ibase_errmsg());
      echo "Ошибка выполнения запроса"; 
      exit(); 
    } 
	
		
	$row = ibase_fetch_row ($nme);
	//echo($row);
	
	//while ($nme){
//	print($row[3]."<br>");	
	$dbpassword = $row[3];
	$name = $row[1];
	
	$hpassword = MD5($dbpasswd);
//	print($hpassword."<br>");
	//}	
    // Если запрос вернул результат - производим дальнейшую обработку 
 //   if(ibase_num_rows($nme) > 0) 
//    { 
       // Получаем пароль 
    //   $password = ibase_result($nme,0); 
       // Сравниваем пароль из базы данных и введённый посетителем 
       if ($dbpassword == $hpassword)  
	   {
		   
		   $_SESSION['pass'] = true;
		   
		   header("Location: main.php"); //echo  "Hello, $name";  
	   }  
       else 
       { 
		$_SESSION['pass'] = false;
         echo "Ошибка идентификации: неправильный пароль/логин"; 
         exit(); 
       } 
//    } 
    // Если в результате запроса не получено ни одной 
    // строки - посетитель с таким именем не зарегистрирован 
/*    else 
    { 
      echo "Ошибка идентификации: посетитель не зарегистрирован"; 
      exit(); 
    }*/ 
?>

