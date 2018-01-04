<?php
class adminActions extends Actions {

	var $moduleName = 'admin';

	function  adminActions(){
		include_once(MODEL_DIR.'Pages.class.php');
		parent::Actions();
		if($this->user['type']!='admin') Utils::redirect('/user/login');
		$this->response['_nocashe'] = true;
		$this->response['_layout'] = 'admin';
		$this->view='php';
	}

	function indexAction(){
		$this->response['text'] = 'index  text in action';
	}
	/*************************************************************/
	////admins adm
	function adminsAction(){
		$u = new Users();
		$this->response['admins'] = $u->getItems(array('page'=>$this->request['page'], 'to'=>ITMP), array('type'=>'admin'));

		$count = $u->getCount(array('type'=>'admin'));
		if ($count>sizeof($this->response['admins'])){
			$this->response['pager'] = Utils::getPages($count, $this->request['page'],ITMP);
			$this->response['page'] = $this->request['page'];
		}
	}

	function addadminAction(){
		$this->view = '';
		$p = new Users();

		if(empty($_REQUEST['add']['login'])){
			$this->response['text'] = 'Пустой логин ';
			return false;
		}
		if($p->getByLogin($_REQUEST['add']['login'])){
			$this->response['text'] = 'Логин занят';
			return false;
		}
		$_REQUEST['add']['login'] = str_replace(' ','_',urlencode($_REQUEST['add']['login']));

		if(isset($_REQUEST['add']['old_password'])){
			$_REQUEST['add']['password'] = md5($_REQUEST['add']['password']);
			unset($_REQUEST['add']['old_password']);
		}
		$_REQUEST['add']['type'] = 'admin';
		if($p->add($_REQUEST['add'])){
			$this->response['text'] = 'ok';
			$_SESSION['notice'] = $this->response['text'];
			return true;
		}

		$this->response['text'] = 'neok';
		$_SESSION['notice'] = $this->response['text'];
		return false;
	}

	function deladminAction(){
		$this->view = '';
		$id = (int)$_POST['id'];
		$items = new Users();
		$page = $items->getItem($id);
		if ($items->delete($id)){
			$this->response['text'] = 'ok';
			return true;
		}
		$this->response['text'] = 'neok';
		return false;
	}

	function editadminAction(){
		$this->view = '';
		$id = (int)$_POST['id'];
		$users = new Users();
		if(isset($_REQUEST['add']['old_password']) && !empty($_REQUEST['add']['old_password'])){
			if ($users->UserLogin($_REQUEST['add']['login'], $_REQUEST['add']['old_password'], 'admin')){
				$_REQUEST['add']['password'] = md5($_REQUEST['add']['password']);
			}else{
				$this->response['text'] = 'neok1';
				return false;
			}
		}else{
			unset($_REQUEST['add']['password']);
		}
		unset($_REQUEST['add']['old_password']);
		if ($users->update($id, $_REQUEST['add'])){
			$this->response['text'] = 'ok';
			return true;
		}
		$this->response['text'] = 'neok2';
		return false;
	}

	function getadminAction(){
		$this->view = 'xml';
		$this->response['_layout'] = false;
		if (@$_POST['id']){
			$p = new Users();
			$this->response['item'] = $p->getItem($_POST['id']);
		}
	}
	/*************************************************************/
	///users adm
	function usersAction(){
		$u = new Users();
		$this->response['users'] = $u->getItems(array('page'=>$this->request['page'], 'to'=>ITMP), array('type'=>'user'));

		$count = $u->getCount(array('type'=>'user'));
		if ($count>sizeof($this->response['users'])){
			$this->response['pager'] = Utils::getPages($count, $this->request['page'],ITMP);
			$this->response['page'] = $this->request['page'];
		}
	}

	function adduserAction(){
		$this->view = '';
		$p = new Users();

		if(empty($_REQUEST['add']['login'])){
			$this->response['text'] = 'Пустой логин ';
			return false;
		}
		if(empty($_REQUEST['add']['password'])){
			$this->response['text'] = 'Пустой пароль ';
			return false;
		}
		if($p->getByLogin($_REQUEST['add']['login'])){
			$this->response['text'] = 'Логин занят';
			return false;
		}
		$_REQUEST['add']['login'] = str_replace(' ','_',urlencode($_REQUEST['add']['login']));
		$_REQUEST['add']['password'] = md5($_REQUEST['add']['password']);
		$_REQUEST['add']['type'] = 'user';
		if($p->add($_REQUEST['add'])){
			$this->response['text'] = 'ok';
			$_SESSION['notice'] = $this->response['text'];
			return true;
		}

		$this->response['text'] = 'neok';
		$_SESSION['notice'] = $this->response['text'];
		return false;
	}

	function deluserAction(){
		$this->view = '';
		$id = (int)$_POST['id'];
		$items = new Users();
		$page = $items->getItem($id);
		if ($items->delete($id)){
			$this->response['text'] = 'ok';
			return true;
		}
		$this->response['text'] = 'neok';
		return false;
	}

	function edituserAction(){
		$this->view = '';
		$id = (int)$_POST['id'];

		$users = new Users();
		if(isset($_REQUEST['add']['password']) && !empty($_REQUEST['add']['password'])){
			$_REQUEST['add']['password'] = md5($_REQUEST['add']['password']);
		}
		if ($users->update($id, $_REQUEST['add'])){
			$this->response['text'] = 'ok';
			return true;
		}
		$this->response['text'] = 'neok';
		return false;
	}

	function getuserAction(){
		$this->view = 'xml';
		$this->response['_layout'] = false;
		if (@$_POST['id']){
			$p = new Users();
			$this->response['item'] = $p->getItem($_POST['id']);
		}
	}
	/*************************************************************/
	////pages adm
	function pagesAction(){
		$p = new Pages();
		if (@$_REQUEST['pid']){
			$this->response['trail'] = $p->getTrail($_REQUEST['pid']);
		}
		if (@$_REQUEST['up']){
			$p->up((int)$_REQUEST['up']);
		}
		if (@$_REQUEST['dn']){
			$p->down((int)$_REQUEST['dn']);
		}
		$pid = (@$_REQUEST['pid'])?$_REQUEST['pid']:0;

		$this->response['pages'] = $p->getPagesMenu($pid, ITMP, $this->request['page']);
		$count = $p->getPagesCount($pid);
		if ($count>sizeof($this->response['pages'])){
			$this->response['pager'] = Utils::getPages($count, $this->request['page'],ITMP);
			$this->response['page'] = $this->request['page'];
		}
	}

	function pages_addAction(){
		$p = new Pages();
		if (@$_REQUEST['pid']){
			$this->response['trail'] = $p->getTrail($_REQUEST['pid']);
		}
		if (@$_REQUEST['id']){
			$this->response['page'] = $p->getItem((int)$_REQUEST['id']);
		}
		if (isset($_SESSION['notice'])){
			$this->response['text'] = $_SESSION['notice'];
			unset($_SESSION['notice']);
		}

	}

	function addpageAction(){
		$this->view = '';
		$p = new Pages();
		$_REQUEST['add']['pid'] ='?pid='.(@$_REQUEST['pid'])?$_REQUEST['pid']:0;

		if(empty($_REQUEST['add']['alias'])){
			$_REQUEST['add']['alias'] = uniqid('p');
		}
		if($p->getPage($_REQUEST['add']['alias'])){
			$_REQUEST['add']['alias'] = uniqid('p');
			//$_SESSION['notice'] = 'Alias есть в базе';
			//Utils::redirect('/admin/pages_add?pid='.$_REQUEST['add']['pid']);
			//return false;
		}
		if($p->add($_REQUEST['add'])){
			$this->response['text'] = 'ok';
			Utils::redirect('/admin/pages/?pid='.$_REQUEST['add']['pid']);
			return true;
		}

		$_SESSION['notice'] = 'Error';
		Utils::redirect('/admin/pages_add?pid='.$_REQUEST['add']['pid']);
		return false;
	}

	function delpageAction(){
		$this->view = '';
		$items = new Pages();
		if ($items->delete((int)$_POST['id'])){
			$this->response['text'] = 'ok';
			return true;
		}
		$this->response['text'] = 'neok';
		return false;
	}

	function editpageAction(){
		$this->view = '';
		$id = (int)$_POST['id'];
		$items = new Pages();
		$pid = (@$_REQUEST['add']['pid'])?('?pid='.$_REQUEST['add']['pid']):'';
		if ($items->update($id, $_REQUEST['add'])){
			$this->response['text'] = 'ok';
			Utils::redirect('/admin/pages/?pid='.$pid);
			return true;
		}
		$_SESSION['notice'] = 'Error';
		Utils::redirect('/admin/pages_add?id='.$id.$pid);
		return false;
	}
	/*************************************************************/

	function settingsAction(){
		include_once(MODEL_DIR.'Config.class.php');
		$config = new Config();
		$this->response['conf'] = $config->getConfig();
		if (isset($_SESSION['notice'])){
			$this->response['text'] = $_SESSION['notice'];
			unset($_SESSION['notice']);
		}
	}

	function savesettingsAction(){
		include_once(MODEL_DIR.'Config.class.php');
		$config = new Config();
		$config->Save($_POST['conf']);
		$_SESSION['notice'] = 'Сохранено';
		Utils::redirect('/admin/settings');
	}

	function mailAction(){
		if (@$_REQUEST['action']=='send'){
			if(empty($_REQUEST['message'])){
				$this->response['text'] = 'Введите сообщение';
				return false;
			}
			$message = $_REQUEST['message'];
			$p = new Users();
			$users = $p->getItems(array(), array('type'=>'user', 'nomail'=>0));
			if($users){
				foreach ($users as $v){
					mail($v['mail'], $_REQUEST['subject'],$message, 'From: '.DOMAINN);
				}
			}
			$this->response['text'] = 'Сообщение отправлено';
		}
	}
////////////////////////////
}
