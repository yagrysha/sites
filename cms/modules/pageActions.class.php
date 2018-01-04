<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * http://mvc.yagrysha.com/
 */
include_once(MODEL_DIR.'Pages.class.php');
class pageActions extends Actions {

	var $moduleName = 'page';

	function pageActions(){
		parent::Actions();
		$this->p = new Pages();
		if (!method_exists($this, $this->actionName.'Action') && @$this->request['path'][0]){
			$this->actionName = 'page';
			$this->request['path'][1] = $this->request['path'][0];
		}
		$this->conf = $this->getConfig();
	}

	function indexAction(){
		$this->response['page']  = $this->p->getPage('index');
	}

	function pageAction(){
		Error::e404Unless(@$this->request['path'][1]);
		$page = $this->p->getPage($this->request['path'][1]);
		Error::e404Unless($page);
		Error::e404If($page['hidden'] && $this->user['type']!=2);
		$this->response['menu'] = $this->p->getMenu(($page['pid'])?$page['pid']:$page['id']);
		if($page['pid']){
			$this->response['parent'] = $this->p->getItem($page['pid']);
		}
		$this->response['page'] = $page;
	}

	function contactAction(){
		$this->response['page'] = $this->p->getPage('contact');
		if (@$_REQUEST['action']=='send'){
			if(empty($_REQUEST['text'])){
				$this->response['error'] = 1;//'Enter message';
				return false;
			}
			$message = "Message from ".DOMAINN."\n\nname: ".$_REQUEST['name'].' '.$_REQUEST['phone']."\nsubject: ".$_REQUEST['subject']."\nmessage:\n  ".$_REQUEST['text'];
			mail($this->conf['contact_mail'], DOMAINN.' Contact form',$message, 'From: '.DOMAINN."<info@".DOMAINN.">\nContent-Type: text/plain; charset=utf-8");
			$this->response['ok'] = 1;//'Message sent';
		}
	}
}