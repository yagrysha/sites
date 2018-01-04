<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.10.2007
 * 
 */

include_once INC.'/view.class.php';

// ��� �������. 
class csvView extends View{
	
	function csvView(&$response){
		parent::View($response);
		$this->template = TMPL.'/csv.tpl';
		if (!file_exists($this->template)) Error::fatal($this->template.' template not exist');
	}

	//private
	function getView(){
		ob_start();
		$o= &$this->response;
		if($o['_user']['name']=='admin'){
			define('ADMIN', true);
		}else{
			define('ADMIN', false);
		}
		include($this->template);
		return ob_get_clean();
	}
	
	function arrtocsv($arr, $delimiter=';', $enclosure='"'){
		$ret = '';
		foreach ($arr as $k=>$v){
			if (strchr($v, $delimiter)){
				$v= $enclosure.str_replace($enclosure, $enclosure.$enclosure,$v).$enclosure;
			}
			$ret.=$v.$delimiter;
		}
		return substr($ret, 0, -1);
	}
	
	
}
