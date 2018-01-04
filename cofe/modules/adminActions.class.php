<?php
class adminActions extends Actions {

	var $moduleName = 'admin';

	function  adminActions(){
		parent::Actions();
		if($this->user['name']!='admin'){
			$this->actionName = 'login';
		}
		$this->response['_nocashe'] = true;
		$this->response['_layout'] = false;
	}

	function indexAction(){
		$this->response['text'] = 'index  text in action';
	}

	function loginAction(){
		if (@$_POST['action']=='login'){
			$this->view = '';
			if (!empty($_POST['login']) && !empty($_POST['password'])) {
				$users = new Users();
				$admin = $users->UserLogin($_POST['login'], $_POST['password']);
				if($admin){
					$_SESSION['user'] = $admin;
					$this->response['text'] = 'ok';
					return true;
				}
			}
			$this->response['text'] = 'wrong';
		}
	}

	function logoutAction(){
		unset($_SESSION['user']);
		session_unset();
		session_destroy();
		setcookie(session_name(), "",0 , "/", DOMAIN, 0);
		header('Location: '.DOMAIN);
	}

	/*************************************************************/
	////admins adm
	function adminsAction(){
		$u = new Users();
		$this->response['admins'] = $u->getItems(array('page'=>$this->request['page'], 'to'=>ITMP), array('name'=>'admin'));
		
		$count = $u->getCount(array('name'=>'admin'));
		if ($count>sizeof($this->response['admins'])){
			$this->response['pager'] = $this->getPages($count, $this->request['page'],ITMP);
			$this->response['page'] = $this->request['page'];
		}
	}
	
	function addadminAction(){
		$this->view = '';
		$p = new Users();

		///��� ����� ������������ � ��������� validate �������
		if(empty($_REQUEST['add']['login'])){
			$this->response['text'] = '������ ';
			return false;
		}
		if($p->getByLogin($_REQUEST['add']['login'])){
			$this->response['text'] = '�����  ��� ����';
			return false;
		}
		$_REQUEST['add']['login'] = str_replace(' ','_',urlencode($_REQUEST['add']['login']));
		
		if(isset($_REQUEST['add']['old_password'])){
			$_REQUEST['add']['password'] = md5($_REQUEST['add']['password']);
			unset($_REQUEST['add']['old_password']);
		}
		$_REQUEST['add']['name'] = 'admin';
		if($p->add($_REQUEST['add'])){
			// ����� ����� ���� flash
			$this->response['text'] = 'ok';
			$_SESSION['notice'] = $this->response['text'];
			//header('Location: /admin/admins');
			return true;
		}

		$this->response['text'] = 'neok';
		$_SESSION['notice'] = $this->response['text'];
		//header('Location: /admin/admins');
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
		if(isset($_REQUEST['add']['old_password'])){
			if ($users->UserLogin($_REQUEST['add']['login'], $_REQUEST['add']['old_password'], 'admin')){
				$_REQUEST['add']['password'] = md5($_REQUEST['add']['password']);
			}else{
				$this->response['text'] = 'neok';
				return false;
			}
			unset($_REQUEST['add']['old_password']);
		}
		if ($users->update($id, $_REQUEST['add'])){
			$this->response['text'] = 'ok';
			return true;
		}
		$this->response['text'] = 'neok';
		return false;
	}

	function getadminAction(){
		$this->view = 'xml';
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
			$this->response['pager'] = $this->getPages($count, $this->request['page'],ITMP);
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
		$_REQUEST['add']['pid'] =(@$_REQUEST['pid'])?$_REQUEST['pid']:0;

		if(empty($_REQUEST['add']['alias'])){
			$_SESSION['notice'] = '������ �����';
			header('Location: '.DOMAIN.'/admin/pages_add'.((@$_REQUEST['add']['pid'])?('?pid='.$_REQUEST['add']['pid']):''));
			return false;
		}
		if($p->getPage($_REQUEST['add']['alias'])){
			$_SESSION['notice'] = '����� ����� ��� ����';
			header('Location: '.DOMAIN.'/admin/pages_add'.((@$_REQUEST['add']['pid'])?('?pid='.$_REQUEST['add']['pid']):''));
			return false;
		}
		if($p->add($_REQUEST['add'])){
			$this->response['text'] = 'ok';
			header('Location: '.DOMAIN.'/admin/pages'.((@$_REQUEST['add']['pid'])?('?pid='.$_REQUEST['add']['pid']):''));
			return true;
		}

		$_SESSION['notice'] = 'Error';
		header('Location: '.DOMAIN.'/admin/pages_add'.((@$_REQUEST['add']['pid'])?('?pid='.$_REQUEST['add']['pid']):''));
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
		if ($items->update($id, $_REQUEST['add'])){
			$this->response['text'] = 'ok';
			header('Location: '.DOMAIN.'/admin/pages'.(($_REQUEST['add']['pid'])?('?pid='.$_REQUEST['add']['pid']):''));
			return true;
		}
		$_SESSION['notice'] = 'Error';
		header('Location: '.DOMAIN.'/admin/pages_add?id='.$id.(($_REQUEST['add']['pid'])?('?pid='.$_REQUEST['add']['pid']):''));
		return false;
	}

	function getpageAction(){
		$this->view = 'xml';
		if (@$_POST['id']){
			$p = new Pages();
			$this->response['item'] = $p->getItem($_POST['id']);
		}
	}
	////

	/*************************************************************/

	function settingsAction(){
		include_once(LIB.'/Config.class.php');
		$config = new Config();
		$this->response['conf'] = $config->getConfig();
		if (isset($_SESSION['notice'])){
			$this->response['text'] = $_SESSION['notice'];
			unset($_SESSION['notice']);
		}
	}
	
	function savesettingsAction(){
		include_once(LIB.'/Config.class.php');
		$config = new Config();
		$config->Save($_POST['conf']);
		$_SESSION['notice'] = '���������';
		Error::Location(DOMAIN.'/admin/settings');
	}
////////////////////////////
}
