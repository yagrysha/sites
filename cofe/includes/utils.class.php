<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 17.12.2007
 * 
 */
class Utils{
	function parseUrl(){
		$path = parse_url($_SERVER['REQUEST_URI']);
		$path['path'] = preg_replace('/^\//','',$path['path']);
		$path['path'] = preg_replace('/\/$/','',$path['path']);
		$path['path'] = str_replace('.html','',$path['path']);
		$path['path'] = str_replace('.aspx','',$path['path']);
		$ret = (!empty($path['path']))?explode('/',$path['path']):array();
		if(defined('PATHFLDR')) {
			$ret = array_slice($ret, PATHFLDR);
		}
		return $ret;
	}
	function getfirstPathNode(){
		//php5 self::
		$patharr = Utils::parseUrl();
		return (@$patharr)?$patharr[0]:false;
	}

	function getmicrotime()	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	function  resize_img($from_file, $to_file, $maxx, $maxy){
		list($width, $height, $type, $attr) =getimagesize($from_file);
		if($width>$maxx || $height>$maxy){
			$k = ($width>$height)?($width/$maxx):($height/$maxy);
			$x=$width/$k;
			$y=$height/$k;
			switch ($type){
				case 1: $im = @imagecreatefromgif($from_file);
				break;
				case 2:	$im = @imagecreatefromjpeg($from_file);
				break;
				case 3: $im = @imagecreatefrompng($from_file);
				break;
				case 15: $im = @imagecreatefromwbmp($from_file);
				break;
				case 16: $im = @imagecreatefromxbm($from_file);
				break;
				default: return false;
			}
			
			$nim = imagecreatetruecolor($x,$y);
			imagecopyresampled($nim,$im,0,0,0,0,$x,$y,$width,$height);
			//imagecopyresized($nim,$im,0,0,0,0,$x,$y,$width,$height);
			imagejpeg($nim,$to_file);
		}
	}
	
	function tocsv($list){
		$fp = fopen(PREFIX.'/file.csv', 'w');
		foreach ($list as $line) {
    	Utils::fputcsv($fp, $line);
		}
		fclose($fp);
	}

	function fputcsv(&$handle, $fields = array(), $delimiter = ';', $enclosure = '"') {
		$str = '';
		$escape_char = '\\';
		foreach ($fields as $value) {
			if (strpos($value, $delimiter) !== false ||
			strpos($value, $enclosure) !== false ||
			strpos($value, "\n") !== false ||
			strpos($value, "\r") !== false ||
			strpos($value, "\t") !== false ||
			strpos($value, ' ') !== false) {
				$str2 = $enclosure;
				$escaped = 0;
				$len = strlen($value);
				for ($i=0;$i<$len;$i++) {
					if ($value[$i] == $escape_char) {
						$escaped = 1;
					} else if (!$escaped && $value[$i] == $enclosure) {
						$str2 .= $enclosure;
					} else {
						$escaped = 0;
					}
					$str2 .= $value[$i];
				}
				$str2 .= $enclosure;
				$str .= $str2.$delimiter;
			} else {
				$str .= $value.$delimiter;
			}
		}
		$str = substr($str,0,-1);
		$str .= "\n";
		return fwrite($handle, $str);
	}	
}
