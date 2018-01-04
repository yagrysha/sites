<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 */
include_once INCV_DIR.'view.class.php';

class phpView extends View{
	
	function phpView(&$response){
		parent::View($response);
		$this->template = TMPL_DIR.$response['_module'].'/'.
		((@$response['_template'])?$response['_template']:$response['_action']).'.tpl';
		if (!file_exists($this->template)) Error::fatal($this->template.' template not exist');
	}

	function getView(){
		ob_start();
		extract($this->response);
		error_reporting(E_ALL ^ E_NOTICE);
		if($_layout){
		  include(TMPL_DIR.$_layout.'.tpl');   
		}else{
		  include($this->template);
		}
		return ob_get_clean();
	}
}