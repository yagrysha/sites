<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */
class Utils {

	function getmicrotime(){
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	function location($url){
		header('Location: ' . $url);
		exit();
	}

	function redirect($url){
		header('Location: ' . DOMAIN . $url);
		exit();
	}

	function resize_img($from_file, $to_file, $maxx, $maxy, $crop = false){
		list($width, $height, $type, $attr) = getimagesize($from_file);
		if(!in_array($type, array(1, 2, 3, 15, 16))){
			return false;
		}
		if($width > $maxx || $height > $maxy){
			switch ($type){
				case 1:
					$im = @imagecreatefromgif($from_file);
					break;
				case 2:
					$im = @imagecreatefromjpeg($from_file);
					break;
				case 3:
					$im = @imagecreatefrompng($from_file);
					break;
				case 15:
					$im = @imagecreatefromwbmp($from_file);
					break;
				case 16:
					$im = @imagecreatefromxbm($from_file);
					break;
				default:
					return false;
			}
			if($crop){
				$k = ($width < $height)? ($width / $maxx):($height / $maxy);
				$x = $width / $k;
				$y = $height / $k;
				$nim = imagecreatetruecolor($x, $y);
				imagecopyresampled($nim, $im, 0, 0, 0, 0, $x, $y, $width, 
						$height);
				$oim = imagecreatetruecolor($maxx, $maxy);
				imagecopy($oim, $nim, 0, 0, 0, 0, $maxx, $maxy);
				imagejpeg($oim, $to_file);
			}else{
				$k = ($width > $height)? ($width / $maxx):($height / $maxy);
				$x = $width / $k;
				$y = $height / $k;
				$nim = imagecreatetruecolor($x, $y);
				imagecopyresampled($nim, $im, 0, 0, 0, 0, $x, $y, $width, 
						$height);
				imagejpeg($nim, $to_file);
			}
		}else{
			copy($from_file, $to_file);
		}
		return true;
	}

	function tocsv($list, $file){
		$fp = fopen(DATA_DIR . $file, 'w');
		foreach($list as $line){
			Utils::fputcsv($fp, $line);
		}
		fclose($fp);
	}

	function fputcsv(&$handle, $fields = array(), $delimiter = ';', $enclosure = '"'){
		$str = '';
		$escape_char = '\\';
		foreach($fields as $value){
			if(strpos($value, $delimiter) !== false || strpos($value, 
					$enclosure) !== false || strpos($value, "\n") !== false ||
					 strpos($value, "\r") !== false || strpos($value, "\t") !==
					 false || strpos($value, ' ') !== false){
						$str2 = $enclosure;
				$escaped = 0;
				$len = strlen($value);
				for($i = 0; $i < $len; $i++){
					if($value[$i] == $escape_char){
						$escaped = 1;
					}else if(!$escaped && $value[$i] == $enclosure){
						$str2 .= $enclosure;
					}else{
						$escaped = 0;
					}
					$str2 .= $value[$i];
				}
				$str2 .= $enclosure;
				$str .= $str2 . $delimiter;
			}else{
				$str .= $value . $delimiter;
			}
		}
		$str = substr($str, 0, -1);
		$str .= "\n";
		return fwrite($handle, $str);
	}

	function getPages($count, $npage, $onpage = 20, $nn = 8){
		$lastpage = ceil($count / $onpage);
		$end = ceil($npage / $nn) * $nn;
		$start = $end - ($nn - 1);
		$end = ($end > $lastpage)? $lastpage:$end;
		$pages = array();
		if($start > 1) $pages[$start - 1] = '...';
		for($i = $start; $i <= $end; $i++){
			$pages[$i] = $i;
		}
		if($end < $lastpage) $pages[$end + 1] = '...';
		return $pages;
	}

	function post_request($params, $url){
		$post_params = array();
		foreach($params as $key=>$val){
			if(is_array($val)) $params[$key] = implode(',', $val);
			$post_params[] = $key . '=' . urlencode($params[$key]);
		}
		$post_string = implode('&', $post_params);
		if(function_exists('curl_init')){
			// Use CURL if installed...
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'php (curl) ' . phpversion());
			$result = curl_exec($ch);
			curl_close($ch);
		}else{
			// Non-CURL based version...
			$context = array(		'http'=>array(
					'method'=>'POST',
					'header'=>'Content-type: application/x-www-form-urlencoded'."\r\n".'User-Agent: php (non-curl) '.
					phpversion() .		"\r\n" .	'Content-length: ' .strlen($post_string),'content'=>$post_string));
			$contextid = stream_context_create($context);
			$sock = fopen($url, 'r', false, $contextid);
			if($sock){
				$result = '';
				while(!feof($sock))
					$result .= fgets($sock, 4096);
				fclose($sock);
			}
		}
		return $result;
	}
}