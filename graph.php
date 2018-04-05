<?php
include "check.php";  //Был ли введен пароль
include "Connect.php";//Откроем базу
// Задаем входные данные ############################################

// Входные данные - три ряда, содержащие случайные данные.
// Деление на 2 и 3 взято для того чтобы передние ряды не 
// пересекались

// Массив $DATA["x"] содержит подписи по оси "X"

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
// Подсчитаем количество элементов (точек) на графике
	$count = count($DATA["x"]);

/*	
$DATAHEAD=Array();//для заполнения названия мест измерения
*/
// Задаем изменяемые значения #######################################

// Размер изображения

$W=$_SESSION['screen_width'];
$H=$_SESSION['screen_height'];
$W = $W - $W/7;
$H = $H - $H/4;


// Отступы
$MB=20;  // Нижний
$ML=8;   // Левый 
$M=5;    // Верхний и правый отступы.
         // Они меньше, так как там нет текста

// Ширина одного символа
$LW=imagefontwidth(2);

if ($count==0) $count=1;
/*
// Сглаживаем графики ###############################################
if ($_GET['smooth']==1) {

    // Добавим по две точки справа и слева от графиков. Значения в
    // этих точках примем равными крайним. Например, точка если
    // y[0]=16 и y[n]=17, то y[1]=16 и y[-2]=16 и y[n+1]=17 и y[n+2]=17

    // Такое добавление точек необходимо для сглаживания точек
    // в краях графика

    for ($j=0;$j<3;$j++) {
        $DATA[$j][-1]=$DATA[$j][-2]=$DATA[$j][0];
        $DATA[$j][$count]=$DATA[$j][$count+1]=$DATA[$j][$count-1];
        }

    // Сглаживание графики методом усреднения соседних значений

    for ($i=0;$i<$count;$i++) {
        for ($j=0;$j<3;$j++) {
            $DATA[$j][$i]=($DATA[$j][$i-1]+$DATA[$j][$i-2]+
                           $DATA[$j][$i]+$DATA[$j][$i+1]+
                           $DATA[$j][$i+2])/5;
            }
        }
    }
*/

// Подсчитаем максимальное значение
$max=$temphigh;

// Увеличим максимальное значение на 5% (для того, чтобы столбик
// соответствующий максимальному значение не упирался в в границу
// графика
$max=intval(($max+10)/10)*10;

$min=$templow;
$min=intval(($min-10)/10)*10;

// Количество подписей и горизонтальных линий
// сетки по оси Y.

//$county=10;
//шаг 10 градусов - вычислим количество
$county = intval((intval($max/10)*10-intval($min/10)*10)/10);
//print("max ".$max." min ".$min." county ".$county);
//exit;

// Работа с изображением ############################################
//print($W."<br>");
//print($H."<br>");
// Создадим изображение
$im=imagecreate($W,$H);

// Цвет фона (белый)
$bg[0]=imagecolorallocate($im,255,255,255);

// Цвет задней грани графика (светло-серый)
$bg[1]=imagecolorallocate($im,231,231,231);

// Цвет левой грани графика (серый)
$bg[2]=imagecolorallocate($im,212,212,212);

// Цвет сетки (серый, темнее)
$c=imagecolorallocate($im,184,184,184);

// Цвет текста (темно-серый)
$cl_text=imagecolorallocate($im,136,136,136);

// Цвета для линий графиков
$cl_bar[0]=imagecolorallocate($im,161,155,0);
$cl_bar[1]=imagecolorallocate($im,65,170,191);
$cl_bar[2]=imagecolorallocate($im,191,65,170);

$text_width=0;
// Вывод подписей по оси Y
for ($i=1;$i<=$county;$i++) {
    $strl=strlen(($max/$county)*$i)*$LW;
    if ($strl>$text_width) $text_width=$strl;
    }
// Подравняем левую границу с учетом ширины подписей по оси Y
$ML += $text_width;

// Посчитаем реальные размеры графика (за вычетом подписей и
// отступов)
$RW=$W-$ML-$M;
$RH=$H-$MB-$M;

// Посчитаем координаты нуля
$X0=$ML;
$Y0=$H-$MB;

$step=$RH/$county;

// Вывод главной рамки графика
imagefilledrectangle($im, $X0, $Y0-$RH, $X0+$RW, $Y0, $bg[1]);
imagerectangle($im, $X0, $Y0, $X0+$RW, $Y0-$RH, $c);

// Вывод сетки по оси Y
for ($i=1;$i<=$county;$i++) {
    $y=$Y0-$step*$i;
    imageline($im,$X0,$y,$X0+$RW,$y,$c);
    imageline($im,$X0,$y,$X0-($ML-$text_width)/4,$y,$cl_text);
    }

// Вывод сетки по оси X
// Вывод изменяемой сетки
for ($i=0;$i<$count;$i++) {
    imageline($im,$X0+$i*($RW/$count),$Y0,$X0+$i*($RW/$count),$Y0,$c);
    imageline($im,$X0+$i*($RW/$count),$Y0,$X0+$i*($RW/$count),$Y0-$RH,$c);
    }

// Вывод линий графика
$dx=0;//($RW/$count)/2 - $X0;

$pi=$Y0-($RH/$max*$DATA[1][0]);
$po=$Y0-($RH/$max*$DATA[2][0]);
$pu=$Y0-($RH/$max*$DATA[3][0]);
$px=intval($X0+$dx);

for ($i=1;$i<$count;$i++) {
    $x=intval($X0+$i*($RW/$count)+$dx);

    $y=$Y0-($RH/$max*$DATA[1][$i]);
    imageline($im,$px,$pi,$x,$y,$cl_bar[0]);
    $pi=$y;

    $y=$Y0-($RH/$max*$DATA[2][$i]);
    imageline($im,$px,$po,$x,$y,$cl_bar[1]);
    $po=$y;

    $y=$Y0-($RH/$max*$DATA[3][$i]);
    imageline($im,$px,$pu,$x,$y,$cl_bar[2]);
    $pu=$y;
    $px=$x;
    }

// Уменьшение и пересчет координат
$ML-=$text_width;

// Вывод подписей по оси Y
for ($i=1;$i<=$county;$i++) {
    $str=($max/$county)*$i;
    imagestring($im,2, $X0-strlen($str)*$LW-$ML/4-2,$Y0-$step*$i-imagefontheight(2)/2,$str,$cl_text);
                       
    }

// Вывод подписей по оси X
$prev=100000;// непонятнное число
$twidth=$LW*strlen($DATA["x"][0])+6;
$i=$X0+$RW;

while ($i>$X0) {
    if ($prev-$twidth>$i) {
        $drawx=$i-($RW/$count)/2 - $X0;
        if ($drawx>$X0) {
            $str=$DATA["x"][round(($i-$X0)/($RW/$count))-1];//."sec";
            imageline($im,$drawx,$Y0,$i-($RW/$count)/2- $X0,$Y0+5,$cl_text);
            imagestring($im,2,$drawx-(strlen($str)*$LW)/2, $Y0+7,$str,$cl_text);
            }
        $prev=$i;
        }
    $i-=$RW/$count;
    }
	
imagestring($im,2,$H / 2, $H / 2,"text RW $RW W $W X0 $X0 DATA[x] ". $DATA['x'][1],$cl_bar[2]);

header("Content-Type: image/png; charset=utf-8");

// Генерация изображения
ImagePNG($im);

imagedestroy($im);
unset($DATA);
?>