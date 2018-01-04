<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * http://mvc.yagrysha.com/
 */
class installComponents extends Components {

	var $moduleName = 'install';

	function indexAction(){
		
		
		if (version_compare(phpversion(), '5.0.0', '<')===true) {
			$this->response['phpversion'] = phpversion();
		}
		if(@$_REQUEST['step']==1){
			$this->response['error'] = 'Error test';
			$sql = explode(';',file_get_contents(ROOT_DIR.'/cms.sql'));
			foreach ($sql as $s){
				$this->db->sql(trim($s, "\n\r\t "));
			}
		}
	}
}