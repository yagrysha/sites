<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */

require_once INC_DIR . 'item.class.php';
include_once CONFIG_DIR . 'routing.php';
require_once LIB_DIR . 'Cache/Lite.php';

class Controller {
	var $db = null;
	var $version = DEF_VERSION;
	var $patharray = null;
	var $versions = null;
	var $module = null;
	var $view = null;

	function Controller(){
		if(DEBUG) $this->start = Utils::getmicrotime();
		$this->parseUrl();
		$this->setDb(DBCLASS);
	}

	function run(){
		require_once INC_DIR . 'actions.class.php';
		$moduleName = $this->getModuleName();
		$moduleName = $moduleName . 'Actions';
		$moduleFile = MODULES_DIR . $moduleName.'.class.php';
		if(!file_exists($moduleFile)){
			$moduleName = DEF_MOD.'Actions';
			$moduleFile = MODULES_DIR . $moduleName.'.class.php';
			if(!file_exists($moduleFile)){
				Error::fatal($moduleName.' module not exist');
			}
		}
		include_once $moduleFile;
		$this->module = new $moduleName();
		if(CACHE && !$this->module->nocache){
			$options = array('cacheDir'=>CACHE_DIR,
				 'lifeTime'=>($this->module->cachetime)?$this->module->cachetime:CACHE_LTIME);
			// Create a Cache_Lite object
			$Cache_Lite = new Cache_Lite($options);
			$id = ($this->module->cachekey)?$this->module->cachekey:$this->module->moduleName . '_' . $this->module->actionName . $this->module->user['type'];
			if(!$data = $Cache_Lite->get($id)){
				$this->module->runAction();
				$this->view = $this->module->getView();
				$data = $this->view->getView();
				$Cache_Lite->save($data);
			}
			echo $data;
		}else{
			$this->module->runAction();
			$this->view = $this->module->getView();
			$this->view->display();
		}
		if(DEBUG) echo Utils::getmicrotime() - $this->start;
	}

	function include_component($component_module, $component_name, $vars=array(), $cachekey = null, $cachetime = null){
		require_once (INC_DIR . 'components.class.php');
		if(DEBUG) $start = Utils::getmicrotime();
		$componentModule = $component_module.'Components';
		if(file_exists(MODULES_DIR.$componentModule.'.class.php')){
			include_once MODULES_DIR.$componentModule.'.class.php';
			$component = new $componentModule();
		}else{
			if(DEBUG)
			Error::fatal($componentModule . ' not exist');
			else return false;
		}
		if($cachekey){
			$options = array(
			'cacheDir'=>COMPONENTS_CACHE_DIR,
			'lifeTime'=>($cachetime)? $cachetime:CACHE_LTIME);
			// Create a Cache_Lite object
			$Cache_Lite = new Cache_Lite($options);
			$id = $component_module . '_' . $component_name . $cachekey;
			if(!$data = $Cache_Lite->get($id)){
				$component->runComponent($component_name, $vars);
				$view = $component->getView();
				$data = $view->getView();
				$Cache_Lite->save($data);
			}
		}else{
			$component->runComponent($component_name, $vars);
			$view = $component->getView();
			$data = $view->getView();
		}
		echo $data;
		if(DEBUG) echo Utils::getmicrotime() - $start;
		return true;
	}

	function parseUrl(){
		global $versions;
		$path = @parse_url(DOMAIN . $_SERVER['REQUEST_URI']);
		if(isset($path['path'])){
			$path['path'] = trim($path['path'], '/');
			$path['path'] = str_replace('.'.EXTENSION, '', $path['path']);
			$ret = (!empty($path['path']))? explode('/', $path['path']):array();
			if(constant('PATHFLDR') > 0){
				$ret = array_slice($ret, PATHFLDR);
			}
		}else{
			$ret = array();
		}
		if(isset($versions)){
			$this->versions = $versions;
			if(isset($versions[@$ret[0]])){
				$this->version = array_shift($ret);
			}
		}
		$this->patharray = $ret;
	}

	function getModuleName(){
		global $routing;
		$alias = '';
		if(@$this->patharray[0]){
			$alias = (@$routing[$this->patharray[0]])? $routing[$this->patharray[0]]:$this->patharray[0];
		}
		return ($alias)? $alias:DEF_MOD;
	}

	function setDb($dbclass){
		if($dbclass){
			require_once INCDB_DIR . $dbclass.'.class.php';
			$dbclass = ucfirst($dbclass);
			$this->db = new $dbclass();
			$this->db->connect();
		}
	}
}