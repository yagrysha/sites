<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 */
class Comment extends Item{
	
	var $table = 'comment';
	
	function getComments($item_id, $item_type){
	    return $this->db->Select($this->table.' c left join users u on c.user_id=u.id', 
                array('select'=>'c.*, u.login, u.photo', 'where'=>array('c.item_id'=>$item_id, 'c.item_type'=>$item_type),
                'order'=>array('desc'=>'c.created_at')));
	}
    function getCommentsCount($item_id, $item_type){
        return $this->db->SelectCell($this->table, 
                array('select'=>'count(*)', 'where'=>array('item_id'=>$item_id, 'item_type'=>$item_type)));
    }
    function delete($id){
        $where = array('id'=>$id);
        if(@$_SESSION['user']['name']!='admin'){
            $where['user_id'] = $_SESSION['user']['id'];   
        }
        return $this->db->Delete($this->table, $where);
    }
}
