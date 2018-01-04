<?php
class mailActions extends Actions
{
    var $moduleName = 'mail';
    var $m = null;
    
    function mailActions() {
        parent::Actions();
        if(!$_SESSION['user'])    Error::location('/user/login');
        include_once(LIB.'/Mail.class.php');
        $this->m = new Mail();
    }

    function indexAction() {
        Error::location('/mail/inbox');
    }

    function inboxAction() {
        $this->response['mail'] = $this->m->getImbox($this->request['page'], ITMP);
        $count = $this->m->getCountInbox();
        if ($count > sizeof($this->response['mail'])) {
            $this->response['pager'] = $this->getPages($count, $this->request['page'], ITMP);
            $this->response['page'] = $this->request['page'];
        }
    }

    function outboxAction() {
        $this->response['mail'] = $this->m->getOutbox($this->request['page'], ITMP);
        $count = $this->m->getCountOubox();
        if ($count > sizeof($this->response['mail'])) {
            $this->response['pager'] = $this->getPages($count, $this->request['page'], ITMP);
            $this->response['page'] = $this->request['page'];
        }
    }

    function trashAction() {
        $this->response['mail'] = $this->m->getTrash($this->request['page'], ITMP);
        $count = $this->m->getCountTrash();
        if ($count > sizeof($this->response['mail'])) {
            $this->response['pager'] = $this->getPages($count, $this->request['page'], ITMP);
            $this->response['page'] = $this->request['page'];
        }
    }

    function sendAction() {
        if (! @$_REQUEST['send'])
            return true;
        if (empty($_REQUEST['subject'])) {
            $this->response['error'] = 'subject';
        }elseif (empty($_REQUEST['message'])) {
            $this->response['error'] = 'message';
        }elseif (empty($_REQUEST['to'])) {
            $this->response['error'] = 'to';
        }
        $user = $this->u->getByLogin(trim($_REQUEST['to']));
        if (! $user) {
            $this->response['error'] = 'to';
        }
        if ($this->response['error'])
            return false;
        $this->m->send($user['id'], $_REQUEST['subject'], $_REQUEST['message']);
        $this->response['ok'] = true;
    }
    
    function totrashAction(){
        $this->view='';
        if(!isset($this->request['path'][2]))return false;
        return $this->m->totrash((int)$this->request['path'][2]);
    }
    
    function deleteAction(){
        $this->view='';
        if(!isset($this->request['path'][2]))return false;
        return $this->m->delete((int)$this->request['path'][2]);
    }
    
    function viewAction(){
        if(!isset($this->request['path'][2]))Error::e404();
        $mail = $this->m->getItem((int)$this->request['path'][2]);
        if($mail['user_id']!=$_SESSION['user']['id'])Error::e404();
        if (!$mail['readed']) $this->m->update($mail['id'], array('readed'=>1));
        $this->response['mail'] = $mail;
    }
    
    function replyAction(){
        if(!isset($this->request['path'][2]))Error::e404();
        $mail = $this->m->getItem((int)$this->request['path'][2]);
        if($mail['to_id']!=$_SESSION['user']['id'])Error::e404();
        $user = $this->u->getItem($mail['from_id']);
        if (!$user)Error::e404();
        $this->response['mail'] = $mail;
        $this->response['user'] = $user;
        return $this->send();
    }
}