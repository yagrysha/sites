<?php
class categoryActions extends Actions
{
    var $moduleName = 'category';
    var $c = null;
    
    function categorytActions() {
        parent::Actions();
        if(!$_SESSION['user'] && $_SESSION['user']['name']!='admin')   Error::location('/user/login');
        include_once(LIB.'/Category.class.php');
        $this->c = new Category();
    }

    function deleteAction(){
        $this->view='';
        if(!isset($this->request['path'][2]))return false;
        return $this->c->delete((int)$this->request['path'][2]);
    }
    
    function addAction(){
        $this->view='';
        $id = $this->c->add(array(
                'name'=>$_REQUEST['name'], 
                'type'=>(int)$_REQUEST['type']));
        if(!$id) {
            $this->request['text']='err';
            return false;
        }
        $this->request['text']=$id;
    }
}