<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 29.11.2007
 * 
 */
include_once INC.'/view.class.php';

class phpView extends View{
	
	function phpView(&$response){
		parent::View($response);
		$this->template = TMPL.'/'.$response['_module'].'/'.
		((@$response['_template'])?$response['_template']:$response['_action']).'.tpl';
		if (!file_exists($this->template)) Error::fatal($this->template.' template not exist');
	}

	//private
	function getView(){
		ob_start();
		extract($this->response);
		if($_layout){
		  include(TMPL.'/layout.tpl');   
		}else{
		  include($this->template);
		}
		return ob_get_clean();
	}
}