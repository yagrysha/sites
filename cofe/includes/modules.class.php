<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 */
class Modules {
	var $table = 'modules';
	var $db = null;

	function Modules(){
		global $db;
		$this->db = $db;
	}

	function getModuleByAlias($alias){
		return $this->db->SelectOne($this->table, array('where'=>array('alias'=>$alias)));
	}

	function load($moduleName){
		$moduleName = $moduleName.'Actions';
		if(file_exists(MODULES_DIR.$moduleName.'.class.php')){
			include_once MODULES_DIR.$moduleName.'.class.php';
			return new $moduleName($this->db);
		}else{
			Error::fatal($moduleName.' module not exist');
		}
	}
}