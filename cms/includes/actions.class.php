<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */
class Actions {
	var $request = null;
	var $response = null;
	var $actionName = null;
	var $user = null;
	var $view = DEF_MOD_VIEW;
	var $version = DEF_VERSION;
	var $db = null;
	var $conf = null;
	var $layout = 'layout';
	var $nocache = false;
	var $cachekey = null;
	var $cachetime = null;

	function Actions(){
		global $app;
		include_once (MODEL_DIR . 'Users.class.php');
		$this->db = $app->db;
		session_start();
		$this->request = $this->getRequest();
		$this->user = $this->getUser();
		$this->actionName = $this->getActionName();
		$this->version = $app->version;
	}

	function getActionName(){
		if(@$this->actionName) return $this->actionName;
		$node = @$this->request['path'][($this->moduleName == DEF_MOD)? 0:1];
		return ($node)? $node:'index';
	}

	function runAction(){
		$actionName = $this->actionName . 'Action';
		if(!method_exists($this, $actionName)) if(DEBUG)
			Error::fatal('Action '.$actionName.' not exist in '.$this->moduleName.'Module');
		else Error::e404();
		return $this->$actionName();
	}

	function getView(){
		global $app;
		include_once INCV_DIR . $this->view . 'view.class.php';
		$view = $this->view . 'View';
		$this->response['_version'] = $this->version;
		$this->response['_conf'] = $this->conf;
		$this->response['_action'] = $this->actionName;
		$this->response['_module'] = $this->moduleName;
		$this->response['_user'] = $this->user;
		$this->response['_layout'] = $this->layout;
		$this->response['_request'] = $this->request;
		$this->response['_versions'] = $app->versions;
		return new $view($this->response);
	}

	function getRequest(){
		global $app;
		$req = array('page'=>1);
		if(@$app->patharray){
			foreach($app->patharray as $k=>$v){
				if(strpos($v, PARAM_DELIMETER)){
					list($var, $val) = explode(PARAM_DELIMETER, $v);
					$req[$var] = $val;
				}
			}
			$req['path'] = $app->patharray;
		}
		return $req;
	}

	function getUser(){
		$userConf = (@$_COOKIE['cust'])? unserialize(base64_decode($_COOKIE['cust'])):array();
		//autologin
		if(@!$_SESSION['user'] && @$userConf['sid'] && @$userConf['id']){
			$u = new Users();
			$user = $u->getItem((int)$userConf['id']);
			if($userConf['sid'] == md5($user['code'])){
				$_SESSION['user'] = $user;
				$u->update($user['id'], array('last_login'=>time()));
			}
		}
		if(isset($_SESSION['user'])){
			$_SESSION['user']['conf'] = $userConf;
			return $_SESSION['user'];
		}
		return array('id'=>0, 'type'=>0, 'conf'=>$userConf);
	}

	function getConfig(){
		include_once (MODEL_DIR . 'Config.class.php');
		$config = new Config();
		return $config->getConfig();
	}

	function setCust(){
		return setcookie("cust", base64_encode(serialize($this->user['conf'])),	time() + 5184000, '/');
	}
}