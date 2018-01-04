<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.10.2007
 * 
 */
class Item {
	
	var $table = '';
	var $db = null;

	function Item(){
		global $db;
		$this->db = $db;	
	}
	
	function getItems($limit=array(), $where=array(), $select='', $order=array('desc'=>'id')){
		if (is_numeric($limit)) $limit = array('from'=>0,'to'=>$limit);
		return $this->db->Select($this->table, 
				array('select'=>$select, 'where'=>$where, 'limit'=>$limit,'order'=>$order));
	}
	
	function getItem($id){
		return $this->db->SelectOne($this->table, 
				array('where'=>array('id'=>$id)));
	}
	
	function delete($id){
		return $this->db->Delete($this->table, array('id'=>$id));
	}
	
	function add($item){
		if (!is_array($item)) return false;
		return $this->db->Insert($this->table, $item);
	}
	
	function update($id, $set){
		if (!is_array($set)) return false;
		return $this->db->Update($this->table, $set, array('id'=>$id));
	}
	
	function getCount($where=array()){
		return $this->db->SelectCell($this->table, 
				array('where'=>$where, 'select'=>'count(*)'));
	}
	
	function up($id){
		$i = $this->getItem($id);
		$priv  = $this->db->SelectOne($this->table, 
				array('where'=>'so<'.$i['so'], 'order'=>array('desc'=>'so')));
		if($priv){
			$this->db->Update($this->table, array('so'=>$i['so']), array('id'=>$priv['id']));
			$this->db->Update($this->table, array('so'=>$priv['so']), array('id'=>$i['id']));
		}
	}
	
	function down($id){
		$i = $this->getItem($id);
		$nxt  = $this->db->SelectOne($this->table, 
				array('where'=>'so>'.$i['so'], 'order'=>array('asc'=>'so')));
		if($nxt){
			$this->db->Update($this->table, array('so'=>$i['so']), array('id'=>$nxt['id']));
			$this->db->Update($this->table, array('so'=>$nxt['so']), array('id'=>$i['id']));
		}
	}
	
	function move($id, $pid){
		return false;
	}
	
	function copy($id, $pid){
		return false;
	}
}

?>