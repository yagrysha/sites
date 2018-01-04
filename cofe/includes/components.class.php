<?php
class Components{

//	var $db = null;
	var $request = null;
	var $response = null;
	var $actionName = null;
	var $view = 'php';//� ��������� ���

	function Components(){
		$this->response['_layout'] = false;
	}

	function runComponent($componentName, $vars){
	    $this->request = $vars;
        $this->response['_component'] = $componentName;
        $this->response['_module'] = $this->moduleName;
        $this->response['_template'] =  '_'.$componentName;
		$componentName = $componentName.'Action';
        if (!method_exists($component,$actionName)) 
        if (DEBUG)Error::fatal('Component '.$component_name.' not exist in '.$componentModule);
        else return true;
		return $this->$componentName();
	}
	
	function getView(){
		//return view class
		include_once INC.'/'.$this->view.'view.class.php';
		$view = $this->view.'View';
		return new $view($this->response);
	}
}