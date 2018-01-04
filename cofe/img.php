<?php  
$text = "";
//$im = imagecreatefromgif("../images/1.gif"); 
$im=imagecreate(80,25);
if (!$im) {
	echo "no gif";
}
imagecolorallocate($im, 240, 240, 240);

$temp = array();
$temp['text'] = "";
for ($i=0; $i<=4; $i++){
	$temp['num'] = rand(0,9);
	$temp['ygol'] = rand(-20,20);  
	$temp['y'] = 15 + rand(0,10);
	$temp['x'] = 8+$i*13; 	  $temp['size'] = 14 ; 
	
	for ($j=1; $j<=3; $j++){
		$temp['color_'.$j] = rand(0,180); 
	}
	$temp['black'] = imagecolorallocate($im, $temp['color_1'], $temp['color_2'], $temp['color_3']); 
	$temp['font'] = rand(1,6);
	
	imagettftext($im, $temp['size'], $temp['ygol'], $temp['x']+1, $temp['y']-1, $temp['black'], "ttf/".$temp['font'].".ttf" ,$temp['num']); 
	$temp['text'] .= $temp['num'];
}
session_start();

$_SESSION['spamcode'] = $temp['text'];

header("Content-type: image/gif"); 

imagegif($im); 
imagedestroy($im);
?>