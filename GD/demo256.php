<?php

  //
  // demo256.php - пример вывода символов на 256-цветное изображение 
  //
 
  require('phprfont.php'); // включаем функции для отрисовки символов

  // создаем картинку 256 цветов
  $im = @imagecreate(450, 300)
      or die ("Cannot Initialize new GD image stream");

  $back_color = imagecolorallocate ($im, 200, 255, 200); // цвет фона
  $rect_color = imagecolorallocate ($im, 200, 200, 0);   // цвет прямоуг.

  // закрашиваем фон картинки
  imagefilledrectangle($im, 0, 0, ImageSX($im)-1, ImageSY($im)-1, $back_color);

  // рисуем прямоугольник
  imagefilledrectangle($im, 50, 50, 300, 200, $rect_color);
    // выводим картинку
  $src_img = ImageCreateFromJPEG('demojpg.jpg');
  $src_w = ImageSX($src_img);
  $src_h = ImageSY($src_img);
  imagecopy($im, $src_img, 60,40,0,0, $src_w, $src_h);
  

  // загружаем шрифт SIMBOLF размер 23 точки, повернуть на 180 градусов
  //$fid = loadfont('./fonts/SYMBOLF/23', 2);
$fid = loadfont('./fonts/SSERIFE/16', 0);
  // загружаем шрифт SSERIFF размер 22 точки, без поворота
  $fid2 = loadfont('./fonts/SSERIFF/20');

  // загружаем шрифт SSERIFF размер 16 точек, поворот 270 град
  $fid3 = loadfont('./fonts/SSERIFE/16', 1);


  // устанавливаем цвет символов
  // и цвет фона под символами для каждого шрифта
  setfontcolor($fid,  200, 100, 100,   0, 255, 0);
  setfontcolor($fid2,   0, 127,   0,   0, 255, 255);
  setfontcolor($fid3,   0,   0,   0,   255, 255, 255);

  // Рисуем шрифтом $fid 8 строк "ENWDIA" (фон букв - прозрачный).
  // Буквы будут греческие, так как шрифт - SYMBOL
  // Для 256-цветовых изображений последний параметр функции
  // draw_strx всегда должен быть равен 100, можно его просто
  // не указывать и тогда он автоматически будет равен 100
  for ($i=0; $i<8; $i++) {
     draw_strx($im, $fid, 100, 110+$i*20, 'Градусы', 1, 100);
  }
//print('q');  
//exit;
  // Рисуем шрифтом $fid2 6 строк "phpRFont" (фон непрозрачный),
  // для каждой строки промежутки между символами увеличиваем на 1 пиксел.
  // Для 256-цветовых изображений последний параметр функции
  // draw_str всегда должен быть равен 100, можно его просто
  // не указывать (как в этом примере) и тогда он автоматически будет 
  // равен 100

  for ($i=0; $i<6; $i++) {
     draw_str($im, $fid2, 270, 100+$i*30, 'phpДляRFont', $i);
  }
  
  // рисуем шрифтом $fid3 строку с поворотом
  draw_str($im, $fid3, 220, 25, 'миру мир !@#$%^&* ТЕСТ ТЕСТ', 0);

  unloadfont($fid);  // выгружаем шрифт $fid
  unloadfont($fid2); // выгружаем шрифт $fid2
  unloadfont($fid3); // выгружаем шрифт $fid3

  // выводим картинку
  header ("Content-type: image/png");
  imagepng ($im);
?>

