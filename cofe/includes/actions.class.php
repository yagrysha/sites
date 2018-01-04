<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.07.2007
 * 
 */
class Actions{

//	var $db = null;
	var $request = null;
	var $response = null;
	var $actionName = null;
	var $user = null;
	var $view = 'php';

	function Actions(){
		include_once(LIB.'/Users.class.php');
		include_once(LIB.'/Pages.class.php');
		include_once(LIB.'/Config.class.php');

		session_start();
		$this->request=$this->getRequest();
		$this->user=$this->getUser();
		$this->actionName = $this->getActionName();
		
		$config = new Config();
		$this->response['conf'] = $config->getConfig();
	}

	function getActionName(){
		if (@$this->actionName) return $this->actionName;
		$actionName = 'index';
		$node = ($this->moduleName == DEF_MOD)?0:1;
		if(@$this->request['path'][$node]){
			$actionName = $this->request['path'][$node];
		}
		return $actionName;
	}
	
	function runAction(){
		$actionName = $this->actionName.'Action';
		if (!method_exists($this,$actionName)) 
		if (DEBUG) Error::fatal('Action '.$actionName.' not exist in '.$this->moduleName.'Module');
		else Error::e404();
		$this->response['_action'] = $this->actionName;
		$this->response['_module'] = $this->moduleName;
		$this->response['_user']   = $this->user;
		$this->response['_layout'] = true;
		$this->$actionName();
	}
	
	function getView(){
		//return view class
		include_once INC.'/'.$this->view.'view.class.php';
		$view = $this->view.'View';
		return new $view($this->response);
	}

	//private
	function getRequest(){
		// todo get post file cookie
		$patharray = Utils::parseUrl();
		$req=array('page'=>1);
		if (@$patharray) {
			$pageid = $patharray[sizeof($patharray)-1];
			$id = str_replace('page_','', $pageid);
			if ($id!=$pageid){
				$req['page']=(int)$id;
				unset($patharray[sizeof($patharray)-1]);
			}
			$req['path']=$patharray;
		}
		return $req;
	}

	//private
	function getUser(){
		$userName = (!isset($_SESSION['user']['name']))?'Guest':$_SESSION['user']['name'];
		$userConf = (@$_COOKIE['cust'])?unserialize(base64_decode($_COOKIE['cust'])):array();
		if(@$userConf['sid']&&@$userConf['id']){
			$u = new Users();
			$user = $u->getItem($userConf['id']);
			if($userConf['sid'] == md5($user['code'])){
				$_SESSION['user'] = $user; 
				$set = array('last_login'=>time());
				$u->update($user['id'], $set);
			}
		}
		return array('id'=>@(int)$_SESSION['user']['id'], 'name'=>$userName, 'conf'=>$userConf);
	}

	function setCust(){
		return setcookie("cust", base64_encode(serialize($this->user['conf'])), time()+3600*24*99, '/');
	}
	
	//protected
	function getPages($count, $npage, $onpage=20, $nn=5){
		$lastpage = ceil($count/$onpage);
		$end = ceil($npage/$nn)*$nn;
		$start = $end - ($nn-1);
		$end = ($end>$lastpage)? $lastpage:$end;
		$pages = array();
		if($start>1) $pages[$start-1] = '...';
		for($i=$start; $i<=$end;$i++){
			$pages[$i]=$i;
		}
		if($end<$lastpage) $pages[$end+1] = '...';
		return $pages;
	}
}