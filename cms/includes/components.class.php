<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */
class Components {
	var $request = null;
	var $response = null;
	var $componentName = null;
	var $view = DEF_MOD_VIEW;
	var $version = DEF_VERSION;
	var $db = null;
	var $layout = false;
	var $user = null;

	function Components(){
		global $app;
		$this->version = $app->version;
		$this->db = $app->db;
	}

	function runComponent($componentName, $vars){
		$this->request = $vars;
		$this->componentName = $componentName;
		$this->user = $this->getUser();
		$componentName = $componentName . 'Action';
		if(!method_exists($this, $componentName))
			if(DEBUG) Error::notice('Component ' . $component_name . ' not exist in ' . $componentModule);
		else return true;
		return $this->$componentName();
	}

	function getView(){
		//return view class
		include_once INCV_DIR . $this->view . 'view.class.php';
		$view = $this->view . 'View';
		$this->response['_layout'] = $this->layout;
		$this->response['_version'] = $this->version;
		$this->response['_component'] = $this->componentName;
		$this->response['_module'] = $this->moduleName;
		$this->response['_user'] = $this->user;
		$this->response['_template'] = '_' . $this->componentName;
		$this->response['_request'] = $this->request;
		return new $view($this->response);
	}

	function getUser(){
		if(isset($_SESSION['user'])) return $_SESSION['user'];
		return array('id'=>0, 'type'=>0);
	}
}