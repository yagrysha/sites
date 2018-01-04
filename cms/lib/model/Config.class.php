<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */
class Config extends Item {
	var $table = 'config';

	function getConfig(){
		$vars = $this->getItems(null, null, null, null);
		$config = array();
		if($vars) foreach($vars as $k=>$v){
			$config[$v['var']] = $v['val'];
		}
		return $config;
	}

	function setVal($var, $val){
		return $this->wupdate(array('val'=>$val), array('var'=>$var));
	}

	function save($config){
		foreach($config as $var=>$val){
			$this->wupdate(array('val'=>$val), array('var'=>$var));
		}
		return true;
	}

	function addVal($var, $val){
		return $this->add(array('var'=>$var, 'val'=>$val));
	}
}