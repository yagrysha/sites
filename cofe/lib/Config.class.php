<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.10.2007
 * 
 */
class Config extends Item{
	
	var $table = 'config';
	
	function getConfig(){
		$vars = $this->db->Select($this->table, array());
		$config = array();
		foreach ($vars as $k=>$v){
			$config[$v['var']] = $v['val'];
		}
		return   $config;
	}
	
	function setVal($var, $val){
		 return $this->db->Update($this->table, array('val'=>$val), array('var'=>$var));
	}
	
	function Save($config){
		foreach ($config as $var=>$val){
			$this->setVal($var, $val);
		}
		return true;
	}
	
	function addVal($var, $val){
		$item = array('var'=>$var, 'val'=>$val);
		return $this->add($item);
	}
}
?>