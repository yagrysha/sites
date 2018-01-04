<?php
/**
 * Yaroslav Gryshanovich
 * http://mvc.yagrysha.com/
 */
class userActions extends Actions {

	var $moduleName = 'user';
	var $u = null;

	function userActions(){
		include_once(MODEL_DIR.'Pages.class.php');
		parent::Actions();
		$this->u = new Users();
		
		if($this->user['id']){	// если залогинен
			if ( in_array($this->actionName, array('login', 'forgot', 'signup', 'confirm', 'index')) )
			Utils::redirect('/');
		}else{//не залогинен
			if ( !in_array($this->actionName, array('login', 'forgot', 'signup', 'confirm', 'index')) )
			Utils::redirect('/user/login.html');
		}
		$this->conf = $this->getConfig();
	}

	function loginAction(){
		if(!empty($_POST['login']) && !empty($_POST['password'])){
			$user = $this->u->login($_POST['login'], $_POST['password']);
			if($user){
				$this->user = array_merge($this->user, $user);
				if(@$_POST['saveme']){
					$this->user['conf']['sid']=md5($user['code']);
					$this->user['conf']['id']=$user['id'];
					$this->setCust();
				}
				if($this->user['type']==2){
					Utils::redirect('/admin/');
				}else{
					Utils::redirect('/');
				}
			}
			$this->response['wrong'] = true;
		}
	}

	function logoutAction(){
		unset($_SESSION);
		session_unset();
		session_destroy();
		unset($this->user['conf']['sid']);
		unset($this->user['conf']['id']);
		$this->setCust();
		setcookie(session_name(), "",0 , "/", DOMAIN, 0);
		Utils::redirect('/');
	}

	function forgotAction(){
		if(!empty($_POST['email'])){
			$user = $this->u->getByEmail(trim($_POST['email']));
			if (!$user){
				$this->response['wrong'] = true;
				return true;
			}
			$passw = substr(md5(uniqid(rand(5, 10),true)),3,10);
			$this->u->update($user['id'],  array('password'=> md5($passw)));
			if($this->version=='en'){
				$mess = "Hello! You (or someone else) requested password for user:".$user['login'].".\nNew password was generated for you:".$passw."\n";
			}else{
				$mess = "Здравствуйте! \n Вы (или кто-то от вашего имени) запросили пароль для для пользователя с логином ".$user['login'].".\nДля Вас был автоматически сгенерирован новый пароль:".$passw."\n";
			}

			mail($user['mail'], DOMAINN.' Password Changing',$mess, 'From: '.DOMAINN."<info@".DOMAINN.">\nContent-Type: text/plain; charset=utf-8");
			$this->response['sent'] = true;
		}
	}

	function deleteAction(){
		$this->view = '';
		if(@$this->request['id']){
			$this->u->delete((int)$this->request['id']);
			$this->response['text'] = 'ok';
			return true;
		}
		$this->response['text'] = 'err';
	}

	function blockAction(){
		$this->view = '';
		if(@$this->request['id']){
			if($this->u->update((int)$this->request['id'], array('type'=>0))){
				$this->response['text'] = 'ok';
				return true;
			}
		}
		$this->response['text'] = 'err';
	}

	function unblockAction(){
		$this->view = '';
		if(@$this->request['id']){
			if($this->u->update((int)$this->request['id'], array('type'=>1))){
				$this->response['text'] = 'ok';
				return true;
			}
		}
		$this->response['text'] = 'err';
	}
	
	function confirmAction(){
		if(!isset($this->request['path'][2])) Error::e404();
		if ($this->u->confirmEmail($this->request['path'][2])){
			$this->response['ok'] = true;
		}
	}
}