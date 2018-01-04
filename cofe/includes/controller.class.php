<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 29.11.2007
 * 
 */

//main controller
$db = null;
class Controller
{
  
    var $moduleName = null;

    function Controller() {
	global $db;
        error_reporting(ER_REP);
        require_once (INC . '/utils.class.php');
        if (DEBUG)  $this->start = Utils::getmicrotime();
        require_once (INC . '/error.class.php');
        require_once (INC . '/mysql.class.php');
        require_once (INC . '/modules.class.php');
        require_once (INC . '/actions.class.php');
        require_once (INC . '/item.class.php');
        if (CACHE) require_once (LIB . '/Cache/Lite.php');
        $db = new Mysql();
        $db->connect();
    }

    function run() {
        $alias = Utils::getfirstPathNode();
        $this->moduleName = DEF_MOD;
        $modules = new Modules();
        if (! empty($alias)) {
            $moduleItem = $modules->getModuleByAlias($alias);
            if (@$moduleItem) {
                $this->moduleName = $moduleItem['processor'];
            }
        }
        $module = $modules->load($this->moduleName);
        if (CACHE && ! isset($module->response['_nocashe'])) {
            $options = array('cacheDir' => CACHE_DIR, 'lifeTime' => CACHE_LTIME);
            // Create a Cache_Lite object
            $Cache_Lite = new Cache_Lite($options);
            $id = $module->moduleName . '_' . $module->actionName . $module->user['name'];
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
    	require_once (INC . '/components.class.php');
        if (DEBUG) $start = Utils::getmicrotime();
        $componentModule = $component_module . 'Components';
        if (file_exists(PROC . '/' . $componentModule . '.class.php')) {
            include_once PROC . '/' . $componentModule . '.class.php';
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