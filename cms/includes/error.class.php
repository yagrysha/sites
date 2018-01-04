<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */
class Error {

	function fatal($err){
		exit($err);
	}

	function notice($err){
		echo $err . '<br />';
	}

	function e404(){
		Utils::location(PAGE404);
	}

	function e404Unless($val){
		if(!$val) Utils::location(PAGE404);
	}

	function e404If($val){
		if($val) Utils::location(PAGE404);
	}
}
