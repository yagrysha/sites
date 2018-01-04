<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.10.2007
 * 
 */
class Error{

	function fatal($err){
		echo $err.'<br />';
		exit;
	}
	function e404(){
		header('Location: '.DOMAIN.'/page/404');
		exit;
	}
	function Location($url){
		header('Location: '.$url);
		exit;
	}
}
function p($out,$ext_flg=false){
	echo'<pre>';
	print_R($out);
	echo'</pre>';
	if($ext_flg) exit;
}
?>