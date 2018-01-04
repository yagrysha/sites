<?php
class commentActions extends Actions
{
    var $moduleName = 'comment';
    var $c = null;
    
    function commentActions() {
        parent::Actions();
        if(!$_SESSION['user'])    Error::location('/user/login');
        include_once(LIB.'/Comment.class.php');
        $this->c = new Comment();
    }

    function deleteAction(){
        $this->view='';
        if(!isset($this->request['path'][2]))return false;
        return $this->c->delete((int)$this->request['path'][2]);
    }
    
    function addAction(){
        $this->view='';
        $id = $this->c->add(array('user_id'=>$_SESSION['user']['id'],
                'item_id'=>(int)$_REQUEST['item_id'], 
                'item_type'=>(int)$_REQUEST['type'],
                'message'=>htmlspecialchars($_REQUEST['message'])
        ));
        if(!$id) {
            $this->request['text']='err';
            return false;
        }
        $this->request['text']=$id;
    }
}