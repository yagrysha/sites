<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 */
class Error{

	function fatal($err){
		echo $err.'<br />';
		exit;
	}
	function e404(){
		Utils::location(PAGE404);
	}
	function e404Unless($val){
		if(!$val)Utils::location(PAGE404);
	}
	function e404If($val){
		if($val)Utils::location(PAGE404);
	}

}
