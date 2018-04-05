<?php
    //��������������� ������� ��� ����������� �����
    function ImageColor($im, $color_array)
    {
      return ImageColorAllocate(
      $im,
      isset($color_array['r']) ? $color_array['r'] : 0, 
      isset($color_array['g']) ? $color_array['g'] : 0, 
      isset($color_array['b']) ? $color_array['b'] : 0 
      );
    }
 
    //��������� ������ � �������, ������� ���������� ������� � ���� �������.
    $data[] = '60.00';
    $data[] = '58.72';
    $data[] = '60.74';
    $data[] = '54.30';
    $data[] = '57.95';
    $data[] = '61.47';
    $data[] = '63.78';
    $data[] = '56.07';
    $data[] = '52.67';
    $data[] = '47.07';
    $data[] = '45.26';
    $data[] = '47.24';
 
    //��������� �����������  
    $width   = 584; //������
    $height  = 392; //������
    $padding = 20;  //������ �� ���� 
    $step = 2;      //��� ������������ �����
 
    //������� �����������
    $im = ImageCreate ($width, $height) 
      or die ("Cannot Initialize new GD image stream");
 
    //������ �����, ������� ����� �������������� ��� ����������� ��������
    //$bgcolor = imagecolorallocate($im,255, 255, 255);// ImageColor($im, array('r'=>255, 'g'=>255, 'b'=>255)); 
	$bgcolor = ImageColor($im, array('r'=>255, 'g'=>255, 'b'=>255)); 
    //$color = imagecolorallocate($im,225, 255,125) ;//ImageColor($im, array('b'=>125)); 
	$color = ImageColor($im, array('b'=>125)); 
//    $green = imagecolorallocate($im,255,175,175);//ImageColor($im, array('g'=>175)); 
	$green = ImageColor($im, array('g'=>175)); 
    //$gray = imagecolorallocate($im,255, 175, 175);//ImageColor($im, array('r'=>175, 'g'=>125, 'b'=>175)); 
	$gray = ImageColor($im, array('r'=>175, 'g'=>125, 'b'=>175)); 
	
 
    //���������� ������� ����������� �������
    $gwidth  = $width - 2 * $padding; 
    $gheight = $height - 2 * $padding; 
 
    //��������� ����������� � ������������ ��������  
    $min = min($data);
    $min = floor($min/$step) * $step;
    $max = max($data);
    $max = ceil($max/$step) * $step;

    //������ ����� ��������
    for($i = $min; $i < $max + $step; $i += $step)
    {
      $y = $gheight - ($i - $min) * ($gheight) / ($max - $min) + $padding;
      ImageLine($im, $padding, $y, $gwidth + $padding, $y, $gray);
	//array imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )  
    //ImageTTFText($im, 8, 0, $padding + 1, $y - 1, $gray, "verdana", $i);
	//bool imagestring ( resource $image , int $font , int $x , int $y , string $string , int $color )
	imagestring($im,2, $padding + 1, $y - 1,$i." C",$color);
    }
	
    //����������� �������
    $cnt = count($data);
    $x2 = $padding;
    $i  = 0;
 
    //����� ��������, ��� ������ ��������� ��� �������� ��������� 
    //� ����� ������� ����, ��� ���������� ������� ���������� ���������� y
    $y2 = $gheight - ($data[$i] - $min) * ($gheight) / ($max - $min) + $padding;

    for($i = 1; $i < $cnt; $i++)
    {
      $x1 = $x2;
      $x2 = $x1 + (($gwidth) / ($cnt - 1));
      $y1 = $y2;
      $y2 = $gheight - ($data[$i] - $min) * ($gheight) / ($max - $min) + $padding;
 
      //�������� ��� �����, ����� ������� ������ ����� ��������      
      ImageLine($im, $x1, $y1, $x2, $y2, $color);
      ImageLine($im, $x1 + 1, $y1, $x2 + 1, $y2, $color);
    }
 
    //������ ���������� ������ ��������, ����� ��������� �����
    header ("Content-type: image/png; charset=utf-8");	
    ImagePng ($im);
	imagedestroy($im);
?>