<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * http://mvc.yagrysha.com/
 */
include_once LIB_DIR . 'smarty/Smarty.class.php';
class smartyView extends View {

	function smartyView(&$response){
		parent::View($response);
		$this->template = TMPL_DIR . $response['_module'] . '/' . ((@$response['_template'])? $response['_template']:$response['_action']) . '.tpl';
		if(!file_exists($this->template)) Error::fatal($this->template . ' template not exist');
	}

	function getView(){
		$smarty = new Smarty();
		$smarty->compile_dir = CACHE_DIR . 'smarty';
		$smarty->assign($this->response);
		$smarty->assign('template', $this->template);
		if($this->response['_layout']){
			return $smarty->fetch(TMPL_DIR . $this->response['_layout'] . '.tpl');
		}else{
			return $smarty->fetch($this->template);
		}
	}
}