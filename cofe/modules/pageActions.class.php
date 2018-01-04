<?php
class pageActions extends Actions {

	var $moduleName = 'page';

	function pageActions(){
		parent::Actions();
		$this->p = new Pages();
	}
	
	function indexAction(){
		$this->response['page']  = $this->p->getPage('index');
	}

	function aboutAction(){
		$this->response['page']  = $this->p->getPage('about');
		$this->response['_template'] = 'page';
	}
	
	function p404Action(){
		$this->response['page']  = $this->p->getPage('404');
	}

	function pageAction(){
		if(isset($this->request['path'][1])){
			$page = $this->p->getPage($this->request['path'][1]);
			if ($page ){
				$this->response['page'] = $page;
				return true;
			}
		}
		Error::e404();
	}

	function contactAction(){
		$this->response['page'] = $this->p->getPage('contact');
		if (@$_REQUEST['action']=='send'){
			$this->view = '';
			if(empty($_REQUEST['text'])){
				$this->response['text'] = '������� ���������';
				return false;
			}
			$message = "��������� � ����� ".DOMAINN."
	���: ".iconv("UTF-8", "CP1251", htmlspecialchars($_REQUEST['name']))."
	���������:  ".iconv("UTF-8", "CP1251", htmlspecialchars($_REQUEST['text']));
			mail($this->response['conf']['contact_mail'], DOMAINN.' Contact form',$message, 'From: '.DOMAINN);
			$this->response['text'] = 'ok';
		}
	}
}