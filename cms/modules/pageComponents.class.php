<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * http://mvc.yagrysha.com/
 */
include_once(MODEL_DIR.'Pages.class.php');
class pageComponents extends Components {

	var $moduleName = 'page';
	
	function pageComponents(){
		parent::Components();
		$this->p = new Pages();
	}

	function mainmenuAction(){
		$this->response['menu'] = $this->p->getMenu(0);
	}

	function rightblockAction(){
		$this->response['page']  = $this->p->getPage('rightblock');
	}

	function rightmenuAction(){
		$this->response['menu'] = $this->p->getMenu(($this->request['pid'])?$this->request['pid']:$this->request['id']);
	}
}