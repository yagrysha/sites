<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.10.2007
 * 
 */
include_once INC.'/view.class.php';
class xmlView extends View{
	
	function xmlView(&$response){
		parent::View($response);
		$this->template = TMPL.'/'.$response['_module'].'/'.
		((@$response['_template'])?$response['_template']:$response['_action']).'.tpl';
		if (!file_exists($this->template)) Error::fatal($this->template.' template not exist');
	}

	//private
	function getView(){
		ob_start();
		extract($this->response);
		include($this->template);
		header('Content-type: text/xml');
		return ob_get_clean();
	}
}
?>