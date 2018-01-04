<?php
class articleActions extends Actions {

	var $moduleName = 'page';

	function articleActions(){
	    include_once(LIB.'/Article.class.php');
	    include_once(LIB.'/Comment.class.php');
	    include_once(LIB.'/Category.class.php');
		parent::Actions();
		$this->a = new Article();
	}
	
	function indexAction(){
	    if(isset($this->request['path'][2])){
            $category_id = (int)$this->request['path'][2]; 	            
	    }
	    $where = array();
	    if(@$_SESSION['user']['name']!='admin'){
	        $where['hidden']=0;
	    }
	    if(@$category_id){
	        $where['category_id']=$category_id;
	    }
        $this->response['articles'] = $this->a->getItems(array('page'=>$this->request['page'], 'to'=>ITMP), $where);
        $count = $this->a->getCount($where);
        if ($count>sizeof($this->response['articles'])){
            $this->response['pager'] = $this->getPages($count, $this->request['page'],ITMP);
            $this->response['page'] = $this->request['page'];
        }
        $cat = new Category();
        $this->response['categories'] =$cat->getByType(T_ARTICLE); 
	}
	
   function myAction(){
        if(isset($this->request['path'][2])){
            $category_id = (int)$this->request['path'][2];              
        }
        $where = array('user_id'=>$_SESSION['user']['id']);
        if(@$category_id){
            $where['category_id']=$category_id;
        }
        $this->response['articles'] = $this->a->getItems(array('page'=>$this->request['page'], 'to'=>ITMP), $where);
        $count = $this->a->getCount($where);
        if ($count>sizeof($this->response['articles'])){
            $this->response['pager'] = $this->getPages($count, $this->request['page'],ITMP);
            $this->response['page'] = $this->request['page'];
        }
        $cat = new Category();
        $this->response['categories'] =$cat->getByType(T_ARTICLE);
    }
    
	function viewAction(){
	    if(!isset($this->request['path'][2]) && (int)$this->request['path'][2]<0) Error::e404();
	    $artilce = $this->a->getItem($this->request['path'][2]);
	    if($artilce['hidden'] && ($_SESSION['user']['name']!='admin' || $artilce['user_id']!=$_SESSION['user']['id']))
	    Error::e404();
	    $this->response['article'] =$artilce;
	    
	    $c = new Comment();
	    $this->response['comments'] = $c->getComments($article['id'], T_ARTICLE);
	    $cat = new Category();
        $this->response['categories'] =$cat->getByType(T_ARTICLE);
	}
	
    function editAction(){
        if(!isset($this->request['path'][2]) && (int)$this->request['path'][2]<0) Error::e404();
        $artilce = $this->a->getItem($this->request['path'][2]);
        if($artilce['hidden'] && ($_SESSION['user']['name']!='admin' || $artilce['user_id']!=$_SESSION['user']['id']))
        Error::e404();
        $cat = new Category();
        $this->response['categories'] =$cat->getByType(T_ARTICLE);
        
        $this->response['article'] =$artilce;
        if(@$_POST['update']){
            //TODO
        }
    }
    
    function addAction(){
        //TODO
        $cat = new Category();
        $this->response['categories'] =$cat->getByType(T_ARTICLE);
        if($_POST['add']){
        if (empty($_REQUEST['title'])) {
            $this->response['error'] = 'title';
        }elseif (empty($_REQUEST['text'])) {
            $this->response['error'] = 'text';
        }
        if ($this->response['error']) return false;
        $this->a->add(array(
        'title'=>$_REQUEST['title'],
        'content'=>$_REQUEST['text'],
        'user_id'=>$_SESSION['user']['id']
        ));
        $this->response['ok'] = true;
        }
    }
    
    function deleteAction(){
        $this->view='';
        if(!isset($this->request['path'][2]))return false;
        return $this->a->delete((int)$this->request['path'][2]);
    }
    
    function showAction(){
        $this->view='';
        if(!isset($this->request['path'][2]))return false;
        return $this->a->show((int)$this->request['path'][2]);
    }
    
    function hideAction(){
        $this->view='';
        if(!isset($this->request['path'][2]))return false;
        return $this->a->hide((int)$this->request['path'][2]);
   }
}