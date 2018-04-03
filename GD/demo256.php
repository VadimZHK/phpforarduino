<?php

  //
  // demo256.php - ������ ������ �������� �� 256-������� ����������� 
  //
 
  require('phprfont.php'); // �������� ������� ��� ��������� ��������

  // ������� �������� 256 ������
  $im = @imagecreate(450, 300)
      or die ("Cannot Initialize new GD image stream");

  $back_color = imagecolorallocate ($im, 200, 255, 200); // ���� ����
  $rect_color = imagecolorallocate ($im, 200, 200, 0);   // ���� �������.

  // ����������� ��� ��������
  imagefilledrectangle($im, 0, 0, ImageSX($im)-1, ImageSY($im)-1, $back_color);

  // ������ �������������
  imagefilledrectangle($im, 50, 50, 300, 200, $rect_color);
    // ������� ��������
  $src_img = ImageCreateFromJPEG('demojpg.jpg');
  $src_w = ImageSX($src_img);
  $src_h = ImageSY($src_img);
  imagecopy($im, $src_img, 60,40,0,0, $src_w, $src_h);
  

  // ��������� ����� SIMBOLF ������ 23 �����, ��������� �� 180 ��������
  //$fid = loadfont('./fonts/SYMBOLF/23', 2);
$fid = loadfont('./fonts/SSERIFE/16', 0);
  // ��������� ����� SSERIFF ������ 22 �����, ��� ��������
  $fid2 = loadfont('./fonts/SSERIFF/20');

  // ��������� ����� SSERIFF ������ 16 �����, ������� 270 ����
  $fid3 = loadfont('./fonts/SSERIFE/16', 1);


  // ������������� ���� ��������
  // � ���� ���� ��� ��������� ��� ������� ������
  setfontcolor($fid,  200, 100, 100,   0, 255, 0);
  setfontcolor($fid2,   0, 127,   0,   0, 255, 255);
  setfontcolor($fid3,   0,   0,   0,   255, 255, 255);

  // ������ ������� $fid 8 ����� "ENWDIA" (��� ���� - ����������).
  // ����� ����� ���������, ��� ��� ����� - SYMBOL
  // ��� 256-�������� ����������� ��������� �������� �������
  // draw_strx ������ ������ ���� ����� 100, ����� ��� ������
  // �� ��������� � ����� �� ������������� ����� ����� 100
  for ($i=0; $i<8; $i++) {
     draw_strx($im, $fid, 100, 110+$i*20, '�������', 1, 100);
  }
//print('q');  
//exit;
  // ������ ������� $fid2 6 ����� "phpRFont" (��� ������������),
  // ��� ������ ������ ���������� ����� ��������� ����������� �� 1 ������.
  // ��� 256-�������� ����������� ��������� �������� �������
  // draw_str ������ ������ ���� ����� 100, ����� ��� ������
  // �� ��������� (��� � ���� �������) � ����� �� ������������� ����� 
  // ����� 100

  for ($i=0; $i<6; $i++) {
     draw_str($im, $fid2, 270, 100+$i*30, 'php���RFont', $i);
  }
  
  // ������ ������� $fid3 ������ � ���������
  draw_str($im, $fid3, 220, 25, '���� ��� !@#$%^&* ���� ����', 0);

  unloadfont($fid);  // ��������� ����� $fid
  unloadfont($fid2); // ��������� ����� $fid2
  unloadfont($fid3); // ��������� ����� $fid3

  // ������� ��������
  header ("Content-type: image/png");
  imagepng ($im);
?>

