<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */
define("DBCLASS", 'sqlite'); 
define("DBHOST", 'localhost');
define("DBUSER", 'root');
define("DBPASSW", '');
define("DBNAME", 'cms');
//define("SET_NAMES", 'utf-8');

define("DOMAINN", 'cms.local');
define("DOMAIN", 'http://'.DOMAINN);
//        site domain (eq http://example.com)
//        must be without end slashes
define("PATHFLDR", 0);
define("PARAM_DELIMETER", '_');
define("EXTENSION", 'html');
define("PAGE404", DOMAIN.'/404.html');

define("TMPL_DIR",			ROOT_DIR.'/templates/');
define("MODULES_DIR", 		ROOT_DIR.'/modules/');
define("INC_DIR", 			ROOT_DIR.'/includes/');
define("INCV_DIR", 			INC_DIR. 'view/');
define("INCDB_DIR", 		INC_DIR. 'db/');
define("LIB_DIR", 			ROOT_DIR.'/lib/');
define("MODEL_DIR", 		LIB_DIR. 'model/');
define("IMAGES_DIR", 		ROOT_DIR.'/images/');
define("DATA_DIR", 			ROOT_DIR.'/data/');
define("CONFIG_DIR", 		ROOT_DIR.'/config/');

define("ITMP", 20); //items on page
define("PRP", 10);  //properties on page
define("ONPAGE", 30);

define("DEF_MOD", 'page');  //default module
define("DEF_MOD_VIEW", 'smarty');  //default view
define("DEF_VERSION", 'ru');
define("DEBUG", false);
//define("DEBUG", true);

define("CACHE", false); // отключен
define("CACHE_DIR", ROOT_DIR.'/cache/');
define("COMPONENTS_CACHE_DIR", CACHE_DIR.'components/');
define("CACHE_LTIME", 600);

define("ER_REP", E_ALL);//^E_NOTICE
?>