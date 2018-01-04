<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.10.2007
 * 
 */

class View{
	
	var $template;
	var $response;
	
	function View(&$response){
		$this->response = $response;
	}
	
	function getView(){
		return @$this->response['text'];
	}
	
	function display(){
		echo $this->getView();
	}
}