<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 */
class Actions{

	var $request = null;
	var $response = null;
	var $actionName = null;
	var $user = null;
	var $view = DEF_MOD_VIEW;
	var $db=null;

	function Actions(){
		global $db;
		$this->db = $db;
		session_start();
		include_once(MODEL_DIR.'Users.class.php');
		include_once(MODEL_DIR.'Config.class.php');
		
		$this->request=$this->getRequest();
		$this->user=$this->getUser();
		$this->actionName = $this->getActionName();
		$config = new Config();
		$this->response['conf'] = $config->getConfig();
		$this->response['_layout'] = 'layout';
	}

	function getActionName(){
		if (@$this->actionName) return $this->actionName;
		$node = @$this->request['path'][($this->moduleName == DEF_MOD)?0:1];
		$actionName = ($node)?$node:'index';
		return $actionName;
	}

	function runAction(){
		$actionName = $this->actionName.'Action';
		if (!method_exists($this, $actionName))
		if (DEBUG) Error::fatal('Action '.$actionName.' not exist in '.$this->moduleName.'Module');
		else Error::e404();
		$this->response['_action'] = $this->actionName;
		$this->response['_module'] = $this->moduleName;
		$this->response['_user']   = $this->user;
		$this->$actionName();
	}

	function getView(){
		//return view class
		include_once INCV_DIR.$this->view.'view.class.php';
		$view = $this->view.'View';
		return new $view($this->response);
	}

	//protected
	function getRequest(){
		$patharray = Utils::parseUrl();
		$req=array('page'=>1);
		if (@$patharray) {
			foreach($patharray as $k=>$v){
				if(strpos($v, PARAM_DELIMETER)){
					list($var, $val) = explode(PARAM_DELIMETER, $v);
					$req[$var]=$val;
				}
			}
			$req['path']=$patharray;
		}
		return $req;
	}

	//protected
	function getUser(){
		$userConf = (@$_COOKIE['cust'])?unserialize(base64_decode($_COOKIE['cust'])):array();
		//autologin
		if(@!$_SESSION['user'] && @$userConf['sid'] && @$userConf['id']){
			$u = new Users();
			$user = $u->getItem($userConf['id']);
			if($userConf['sid'] == md5($user['code'])){
				$_SESSION['user'] = $user;
				$set = array('last_login'=>time());
				$u->update($user['id'], $set);
			}
		}
		if(isset($_SESSION['user'])){
			$_SESSION['user']['conf'] = $userConf;
			return $_SESSION['user'];
		}
		return array('id'=>0, 'type'=>'guest', 'conf'=>$userConf);
	}
	
	//protected
	function setCust(){
		return setcookie("cust", base64_encode(serialize($this->user['conf'])), time()+5184000, '/');
	}
}