<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 */
class Category extends Item{
	
	var $table = 'category';
	
	function getByType($type){
	    $cat = $this->db->Select($this->table, 
                array('where'=>array('type'=>$type),
                'order'=>array('asc'=>'name')));
         $ret = array();
         foreach ($cat as $v){
          $ret[$v['id']] = $v['name'];   
         }
         return $ret;
	}
}
