<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 */
class Mail extends Item{
	
	var $table = 'mail';
	
	function getInbox($page, $onpage){
	    $limit = array('page'=>$page,'to'=>$onpage);
        return $this->db->Select($this->table.'m left join users u on m.from_id=u.id ', 
                array('select'=>'m.*, u.from', 'where'=>array('m.folder'=>1, 'm.to_id'=>$_SESSION['user']['id']), 'limit'=>$limit,'order'=>array('desc'=>'m.created_at')));
	}
	function getCountInbox(){
	   return $this->db->SelectCell($this->table, 
                array('where'=>array('folder'=>1, 'to_id'=>$_SESSION['user']['id']), 'select'=>'count(*)'));
	}
	function getOutbox($page, $onpage){
        $limit = array('page'=>$page,'to'=>$onpage);
        return $this->db->Select($this->table.' m left join users u on m.to_id=u.id ', 
                array('select'=>'m.*, u.to','where'=>array('m.folder'=>2, 'm.from_id'=>$_SESSION['user']['id']), 'limit'=>$limit,'order'=>array('desc'=>'m.created_at')));
    }
    function getCountOutbox(){
       return $this->db->SelectCell($this->table, 
                array('where'=>array('folder'=>2, 'from_id'=>$_SESSION['user']['id']), 'select'=>'count(*)'));
    }
    function getTrash($page, $onpage){
        $limit = array('page'=>$page,'to'=>$onpage);
        return $this->db->Select($this->table.'m left join users u on m.from_id=u.id left join users u2 m.to_id=u2.id ', 
                array('select'=>'m.*, u.from, u2.to','where'=>array('m.folder'=>3, 'm.user_id'=>$_SESSION['user']['id']), 'limit'=>$limit,'order'=>array('desc'=>'m.created_at')));
    }
    function getCountTrash(){
       return $this->db->SelectCell($this->table, 
                array('where'=>array('folder'=>3, 'user_id'=>$_SESSION['user']['id']), 'select'=>'count(*)'));
    }
    
    function send($to_id, $subject, $message){
        $item = array(
        'user_id'=>$to_id,
        'from_id'=>$_SESSION['user']['id'],
        'to_id'=>$to_id,
        'subject'=>htmlspecialchars($subject),
        'message'=>nl2br(htmlspecialchars($message)),
        'folder'=>1,
        'created_at'=>time()
        );
        $this->db->Insert($this->table, $item);
        $item['folder']=2;
        $item['user_id']=$_SESSION['user']['id'];
        $this->db->Insert($this->table, $item);
        return true;
    }
    
    function delete($id){
        return $this->db->Delete($this->table, array('id'=>$id, 'user_id'=>$_SESSION['user']['id']));
    }
    function totrash($id){
        return $this->db->Update($this->table, array('folder'=>3), array('id'=>$id, 'user_id'=>$_SESSION['user']['id']));
    }
}
