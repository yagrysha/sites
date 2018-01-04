<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 */
class Article extends Item{
	
	var $table = 'article';
	
    function delete($id){
        $where = array('id'=>$id);
        if(@$_SESSION['user']['name']!='admin'){
            $where['user_id'] = $_SESSION['user']['id'];   
        }
        $this->db->Delete('comment', array('item_id'=>$id, 'item_type'=>T_ARTICLE));
        return $this->db->Delete($this->table, $where);
    }
    
    function show($id){
        $where = array('id'=>$id);
        if(@$_SESSION['user']['name']!='admin'){
        return $this->db->Update($this->table, array('hidden'=>0), $where);      
        }
        
    }
    
    function hide($id){
        $where = array('id'=>$id);
        if(@$_SESSION['user']['name']!='admin'){
        return $this->db->Update($this->table, array('hidden'=>1), $where);    
        }
    }
    
    function add(&$item){
        if(@is_uploaded_file($_FILES['image']['tmp_name'])){
            $path_parts = pathinfo($_FILES['image']['name']);
            $to_file = md5(uniqid(rand(5), true)).'.'.$path_parts['extension'];
            Utils::resize_img($_FILES['photo']['tmp_name'], IMAGES.'/a'.$to_file, 300, 300);
            $item['image'] = $to_file;
        }
        return parent::add($item);
    }
    function update($id, &$set){
        if(@$_REQUEST['delimg']){
            $article = $this->getItem($id);
            if($article['image']) unlink(IMAGES.'/'.$article['image']);
            $set['image'] ='';
        }
        if(@is_uploaded_file($_FILES['image']['tmp_name'])){
            $article = $this->getItem($id);
            if($article['image']) unlink(IMAGES.'/'.$article['image']);
            $path_parts = pathinfo($_FILES['image']['name']);
            $to_file = md5(uniqid(rand(5), true)).'.'.$path_parts['extension'];
            Utils::resize_img($_FILES['photo']['tmp_name'], IMAGES.'/a'.$to_file, 300, 300);
            $set['image'] = $_FILES['image']['name'];
        }
        return parent::update($id, $set);
    }
}
