<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */
class Item {
	var $table = null;
	var $db = null;

	function Item(){
		global $app;
		$this->db = $app->db;
		if($app->version != DEF_VERSION) $this->table = $app->version . '_' . $this->table;
	}

	function getItems($limit = array(), $where = array(), $select = '', $order = array('desc'=>'id')){
		return $this->db->Select($this->table, 
				array(
						'select'=>$select, 'where'=>$where, 
						'limit'=>$limit, 'order'=>$order));
	}

	function getItem($id){
		if(is_array($id) && sizeof($id) > 0){
			return $this->db->SelectOne($this->table, array('where'=>$id));
		}else{
			return $this->db->SelectOne($this->table, 
					array('where'=>array('id'=>$id)));
		}
	}

	function delete($id){
		return $this->db->Delete($this->table, array('id'=>$id));
	}

	function add($item){
		if(!is_array($item)) return false;
		return $this->db->Insert($this->table, $item);
	}

	function update($id, $set){
		return $this->db->Update($this->table, $set, array('id'=>$id));
	}

	function wupdate($set, $where = array()){
		return $this->db->Update($this->table, $set, $where);
	}

	function getCount($where = array()){
		return $this->db->SelectCell($this->table, 
				array('where'=>$where, 'select'=>'count(*)'));
	}

	/**********************************/
	function up($id){
		$i = $this->getItem($id);
		$priv = $this->db->SelectOne($this->table, 
				array(
						'where'=>'so<' . $i['so'], 
						'order'=>array('desc'=>'so')));
		if($priv){
			$this->db->Update($this->table, array('so'=>$i['so']), 
					array('id'=>$priv['id']));
			$this->db->Update($this->table, array('so'=>$priv['so']), 
					array('id'=>$i['id']));
		}
	}

	function down($id){
		$i = $this->getItem($id);
		$nxt = $this->db->SelectOne($this->table, 
				array('where'=>'so>' . $i['so'], 'order'=>array('asc'=>'so')));
		if($nxt){
			$this->db->Update($this->table, array('so'=>$i['so']), 
					array('id'=>$nxt['id']));
			$this->db->Update($this->table, array('so'=>$nxt['so']), 
					array('id'=>$i['id']));
		}
	}
}