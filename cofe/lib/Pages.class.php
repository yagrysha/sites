<?php
class Pages extends Item {
	var $table = 'pages';

	function getPage($alias){
		return $this->db->SelectOne($this->table, array('where'=>array('alias'=>$alias)));
	}

	function getPagesMenu($pid=0, $limit=array(), $page=1){
		if (is_numeric($limit)) {
			$limit = array('to'=>$limit);
			$limit['page']=$page;
		}
		return $this->getItems($limit, array('pid'=>$pid), 'id, alias, name, title, hidden',array('asc'=>'so'));
	}
	
	function getMenu($pid=0,  $hidden=0){
		return $this->getItems(null, array('pid'=>$pid, 'hidden'=>$hidden),
								 'id, alias, name, title, hidden',array('asc'=>'so'));
	}

	function getPagesCount($pid=0){
		return $this->getCount(array('pid'=>$pid));
	}

	function getTrail($pid){
		$_trail = array();
		do {
			$parent = $this->getItem($pid);
			array_push($_trail, array('id'=>$parent['id'], 'name'=>$parent['name']));
			$pid = $parent['pid'];
		}while($pid);
		array_push($_trail,array('id'=>0, 'name'=>'Index'));
		return array_reverse($_trail);
	}
	
	function add(&$item){
		$item['alias'] = str_replace(' ','_',urlencode($item['alias']));
		$item['hidden'] = (isset($item['hidden']))?1:0;
		if(@is_uploaded_file($_FILES['image']['tmp_name'])){
			move_uploaded_file($_FILES['image']['tmp_name'], IMAGES.'/'.$_FILES['image']['name']);
			$item['image'] = $_FILES['image']['name'];
		}
		$item['date'] = time();
		$item['so'] = @$this->db->SelectCell($this->table,array('select'=>'MAX(so)'))+1;
		return parent::add($item);
	}
	
	function delete($id){
		$page = $this->getItem($id);
		if (@$page['image']) unlink(IMAGES.'/'.$page['image']);
		return parent::delete($id);
	}
	
	function update($id, &$set){
		$set['pid'] =(@$_REQUEST['pid'])?$_REQUEST['pid']:0;
		$set['hidden'] = (isset($set['hidden']))?1:0;

		if(@$_REQUEST['delimg']){
			$page = $this->getItem($id);
			if($page['image']) unlink(IMAGES.'/'.$page['image']);
			$set['image'] ='';
		}
		if(@is_uploaded_file($_FILES['image']['tmp_name'])){
			$page = $this->getItem($id);
			if($page['image']) unlink(IMAGES.'/'.$page['image']);
			move_uploaded_file($_FILES['image']['tmp_name'], IMAGES.'/'.$_FILES['image']['name']);
			$set['image'] = $_FILES['image']['name'];
		}
		return parent::update($id, $set);
	}
	
	function up($id){
		$i = $this->getItem($id);
		$priv  = $this->db->SelectOne($this->table, 
				array('where'=>'so<'.$i['so'].' AND pid = '.(int)$_REQUEST['pid'], 'order'=>array('desc'=>'so')));
		if($priv){
			$this->db->Update($this->table, array('so'=>$i['so']), array('id'=>$priv['id']));
			$this->db->Update($this->table, array('so'=>$priv['so']), array('id'=>$i['id']));
		}
	}
	
	function down($id){
		$i = $this->getItem($id);
		$nxt  = $this->db->SelectOne($this->table, 
				array('where'=>'so>'.$i['so'].' AND pid = '.(int)$_REQUEST['pid'], 'order'=>array('asc'=>'so')));
		if($nxt){
			$this->db->Update($this->table, array('so'=>$i['so']), array('id'=>$nxt['id']));
			$this->db->Update($this->table, array('so'=>$nxt['so']), array('id'=>$i['id']));
		}
	}
	
}
?>