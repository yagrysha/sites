<?php
class pageActions extends Actions {

	var $moduleName = 'page';

	function pageActions(){
		include_once(MODEL_DIR.'Pages.class.php');
		parent::Actions();
		$this->p = new Pages();
		$this->response['categorys'] = $this->p->getMenu(0);
		$this->response['page2']  = $this->p->getPage('index2');
        $this->response['page3']  = $this->p->getPage('index3');
        foreach ($this->response['categorys'] as $k=>$v){
        	$this->response['menu'][$v['id']] = $this->p->getMenu($v['id']);
        }
	}

	function indexAction(){
		$this->response['page']  = $this->p->getPage('index');
		$this->response['items']  = $this->p->getItems(array(), array('ontop'=>1));
		//$this->response['catname']= $this->response['categorys'][0]['name'];
	}

	function pageAction(){
		if(isset($this->request['path'][1])){
			$page = $this->p->getPage($this->request['path'][1]);
			if ($page ){
				if($page['hidden'] && $this->user['type']!='admin'){
					  Error::e404();
				}
				$this->response['page'] = $page;
				return true;
			}
		}
		Error::e404();
	}
	function articleAction(){
		if(isset($this->request['path'][1])){
			$page = $this->p->getPage($this->request['path'][1]);
			if ($page ){
				if($page['hidden'] && $this->user['type']!='admin'){
					  Error::e404();
				}
				$this->response['page'] = $page;
				return true;
			}
		}
		Error::e404();
	}

	function p404Action(){
		$this->response['_template']='page';
		$this->response['page']['name'] = 'Not Found';
		$this->response['page']['title'] = 'Not Found';
		$this->response['page']['text'] = 'Not Found 404';
	}
	function redirectAction(){
	     Utils::location($_REQUEST['url']);
	 }

	function articlesAction(){
		$page = $this->p->getPage($this->request['path'][1]);
		if ($page ){
			if($page['hidden'] && $this->user['type']!='admin'){
				Error::e404();
			}
			$id = ($page['pid'])?$page['pid']:$page['id'];
			$this->response['menu'][$id] = $this->p->getMenu($id);
			if($page['pid']){
				$this->response['parent'] = $this->p->getItem($page['pid']);
			}
			$this->response['page'] = $page;
			return true;
		}

	}

	function contactAction(){
		$this->response['page'] = $this->p->getPage('contact');
		if (@$_REQUEST['action']=='send'){
			if(empty($_REQUEST['text'])){
				$this->response['text'] = 'Enter Message';
				return false;
			}
			$message = "Собщение с ".DOMAINN."
	имя: ".$_REQUEST['name'].' '.$_REQUEST['phone']."
	сообщение:  ".$_REQUEST['text'];
			mail($this->response['conf']['contact_mail'], DOMAINN.' Contact form',$message, 'From: '.DOMAINN);
			$this->response['text'] = 'Message sent';
		}
	}

	function sendrevAction(){
	    $message = $_REQUEST['rev'];
	    mail($this->response['conf']['contact_mail'], DOMAINN.' revievs form',$message, 'From: '.DOMAINN);
	    Utils::location('/');
	}

	function rssAction(){
		header('Content-type: text/xml');
		$this->response['_layout']='';
		//$this->view='xml';
		$this->response['items']  = array();
		foreach ($this->response['categorys'] as $k=>$v){
		$this->response['items']  = array_merge($this->response['items'], $this->p->getItems(array(), array('pid'=>$v['id'])));
		}
	}
	 function learnmoreAction(){
	     Utils::location($this->response['conf']['link1']);
	 }
	 
	 function product1Action(){
	     Utils::location($this->response['conf']['link1']);
	 }
	 function product2Action(){
	     Utils::location($this->response['conf']['link2']);
	 }
	 function product3Action(){
	     Utils::location($this->response['conf']['link3']);
	 }
	 function product4Action(){
	     Utils::location($this->response['conf']['link4']);
	 }
	 function product5Action(){
	     Utils::location($this->response['conf']['link5']);
	 }
}