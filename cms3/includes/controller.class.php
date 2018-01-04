<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 */
$db = null;
class Controller{

	var $moduleName = null;

	function Controller() {
		global $db;
		error_reporting(ER_REP);
		require_once (INC_DIR . 'utils.class.php');
		if (DEBUG)  $this->start = Utils::getmicrotime();
		require_once (INC_DIR . 'error.class.php');
		require_once (INCDB_DIR . 'mysql.class.php');
		require_once (INC_DIR . 'actions.class.php');
		require_once (INC_DIR . 'item.class.php');
		if (CACHE) require_once (LIB_DIR . 'Cache/Lite.php');
		$db = new Mysql();
		$db->connect();
	}

	function run() {
		$alias = Utils::getfirstPathNode();
		$this->moduleName = (!empty($alias))?$alias:DEF_MOD;
		$moduleName = $this->moduleName.'Actions';
		$moduleFile = MODULES_DIR.$moduleName.'.class.php';
		if(!file_exists($moduleFile)){
			$moduleName = DEF_MOD.'Actions';
			$moduleFile = MODULES_DIR.$moduleName.'.class.php';
			if(!file_exists($moduleFile)){
				Error::fatal($moduleName.' module not exist');
			}
		}
		include_once $moduleFile;
		$module = new $moduleName();
		if (CACHE && !isset($module->response['_nocashe'])) {
			$options = array('cacheDir' => CACHE_DIR, 'lifeTime' => CACHE_LTIME);
			// Create a Cache_Lite object
			$Cache_Lite = new Cache_Lite($options);
			$id = $module->moduleName . '_' . $module->actionName . $module->user['type'];
			if (! $data = $Cache_Lite->get($id)) {
				$module->runAction();
				$view = $module->getView();
				$data = $view->getView();
				$Cache_Lite->save($data);
			}
		}else {
			$module->runAction();
			$view = $module->getView();
			$data = $view->getView();
		}
		
		echo $data;
		if (DEBUG)  echo Utils::getmicrotime() - $this->start;
	}

	function include_component($component_module, $component_name, $vars, $cachekey = null) {
		require_once (INC_DIR . 'components.class.php');
		
		if (DEBUG) $start = Utils::getmicrotime();
		$componentModule = $component_module . 'Components';
		
		if (file_exists(MODULES_DIR . $componentModule . '.class.php')) {
			include_once MODULES_DIR . $componentModule . '.class.php';
			$component = new $componentModule();
		}else {
			if (DEBUG) Error::fatal($componentModule . ' not exist');
			else return false;
		}
		
		if (CACHE && $cachekey) {
			$options = array('cacheDir' => COMPONENTS_CACHE_DIR, 'lifeTime' => CACHE_LTIME);
			// Create a Cache_Lite object
			$Cache_Lite = new Cache_Lite($options);
			$id = $component_module . '_' . $component_name . $cachekey;
			if (! $data = $Cache_Lite->get($id)) {
				$component->runComponent($component_name, $vars);
				$view = $component->getView();
				$data = $view->getView();
				$Cache_Lite->save($data);
			}
		}else {
			$component->runComponent($component_name, $vars);
			$view = $component->getView();
			$data = $view->getView();
		}
		
		echo $data;
		if (DEBUG)   echo Utils::getmicrotime() - $start;
		return true;
	}
}