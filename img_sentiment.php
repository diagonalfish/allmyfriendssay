<?php

header("Content-Type: image/png");

$IMAGE_HEIGHT = 22;
$IMAGE_WIDTH = 200;

$image = imagecreatefrompng("sentimentbar.png");

$value = $_REQUEST['value'];

if ($value == NULL) {
	imagepng($image);
	exit;
}

$value = floatval($value);

$neg = ($value < 0);
$absvalue = abs($value);

imagesetthickness($image, 2);
$black = imagecolorallocate($image, 0, 0, 0);

if ($value == 0) {
	imageline($image, $IMAGE_WIDTH/2, 0, $IMAGE_WIDTH/2, $IMAGE_HEIGHT-1, $black); 
}

else {
	$offset = intval(($absvalue / 4.0) * 100.0);
	$x_pos = $IMAGE_WIDTH/2;
	if ($neg)
		$x_pos -= $offset;
	else
		$x_pos += $offset;
	
	imageline($image, $x_pos, 0, $x_pos, $IMAGE_HEIGHT-1, $black);
}

imagepng($image);