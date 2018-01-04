<?php
/**
 * yagrysha@gmail.com
 * http://mvc.yagrysha.com/
 */
class adminActions extends Actions {
	var $moduleName = 'admin';

	function  adminActions(){
		include_once(MODEL_DIR.'Pages.class.php');
		parent::Actions();
		if($this->user['type']!=2) {
			Utils::redirect('/user/login.html');
		}
		$this->nocache = true;
		$this->layout = 'admin';
		$this->u = new Users();
	}

	function indexAction(){
	}
	/*************************************************************/
	////admins adm
	function adminsAction(){
		$where= array('type'=>2);
		$this->response['admins'] = $this->u->getItems(array('page'=>$this->request['page'], 'to'=>ITMP), $where);
		$count = $this->u->getCount($where);
		if ($count>sizeof($this->response['admins'])){
			$this->response['pager'] = Utils::getPages($count, $this->request['page'],ITMP);
		}
	}
	
	function addadminAction(){
		$this->view = '';
		if(empty($_REQUEST['add']['login'])){
			$this->response['text'] = 'Пустой логин ';
			return false;
		}
		if($this->u->getByLogin($_REQUEST['add']['login'])){
			$this->response['text'] = 'Логин занят';
			return false;
		}
		if(isset($_REQUEST['add']['old_password'])){
			$_REQUEST['add']['password'] = md5($_REQUEST['add']['password']);
			unset($_REQUEST['add']['old_password']);
		}else{
			$this->response['text'] = 'Пароль введите';
			return false;
		}
		$_REQUEST['add']['type'] = 2;
		if($this->u->add($_REQUEST['add'])){
			$this->response['text'] = 'ok';
			return true;
		}

		$this->response['text'] = 'error';
		return false;
	}

	function editadminAction(){
		$this->view = '';
		$id = (int)$_POST['id'];
		if(isset($_REQUEST['add']['old_password']) && !empty($_REQUEST['add']['old_password'])){
			if ($this->u->getItem(array('login'=>$_REQUEST['add']['login'], 'password'=>md5($_REQUEST['add']['old_password'])))){
				$_REQUEST['add']['password'] = md5($_REQUEST['add']['password']);
			}else{
				$this->response['text'] = 'неверный пароль';
				return false;
			}
		}else{
			unset($_REQUEST['add']['password']);
		}
		unset($_REQUEST['add']['old_password']);
		if ($this->u->update($id, $_REQUEST['add'])){
			$this->response['text'] = 'ok';
			return true;
		}
		$this->response['text'] = 'neok2';
		return false;
	}
	
	function getadminAction(){
		$this->view = 'xml';
		$this->layout = false;
		if (@$_POST['id']){
			$this->response['item'] = $this->u->getItem($_POST['id']);
		}
	}

	/*************************************************************/
	////pages adm

	function pagesAction(){
		$p = new Pages();
		$pid = (@$this->request['pid'])?(int)$this->request['pid']:0;
		if ($pid){
			$this->response['trail'] = $p->getTrail($pid);
		}
		if (@$this->request['up']){
			$p->up((int)$this->request['up'], $pid);
		}
		if (@$this->request['dn']){
			$p->down((int)$this->request['dn'], $pid);
		}
		$this->response['pages'] = $p->getPagesMenu($pid, ITMP, $this->request['page']);
		$count = $p->getPagesCount($pid);
		if ($count>sizeof($this->response['pages'])){
			$this->response['pager'] = Utils::getPages($count, $this->request['page'],ITMP);
		}
	}

	function pages_addAction(){
		$p = new Pages();
		$pid = (@$this->request['pid'])?(int)$this->request['pid']:0;
		if ($pid){
			$this->response['trail'] = $p->getTrail($pid);
		}
		if (@$this->request['id']){
			$this->response['page'] = $p->getItem((int)$this->request['id']);
		}
		if(@$_REQUEST['save']){
			if (@$this->request['id']){
				if (!$p->update($this->request['id'], $_REQUEST['add'])){
					$this->response['text'] = 'error';
					return true;
				}
			}else{
				$_REQUEST['add']['pid'] = $pid;
				if(!$p->add($_REQUEST['add'])){
					$this->response['text'] = 'error';
					return true;
				}
			}
			Utils::redirect('/admin/pages'.(($this->request['pid'])?('/pid_'.$pid):''));
		}
	}
	
	function delpageAction(){
		$this->view = '';
		$p = new Pages();
		if ($p->delete((int)$this->request['id'])){
			$this->response['text'] = 'ok';
			return true;
		}
		$this->response['text'] = 'neok';
	}
	/*************************************************************/
	function settingsAction(){
		include_once (MODEL_DIR . 'Config.class.php');
		$config = new Config();
		if(@$_REQUEST['save']){
			$config->save($_POST['conf']);
			$this->response['ok'] =1;
		}
		$this->response['conf'] = $config->getConfig();
	}
}
