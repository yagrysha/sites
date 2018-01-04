<?php
define("DBHOST", 'localhost');
define("DBUSER", 'root');
define("DBPASSW", '');
define("DBNAME", 'rin');

define("DOMAINN", 'rin');
define("DOMAIN", 'http://'.DOMAINN);
//        site domain (eq http://example.com)
//        must be without end slashes
define("PATHFLDR", 0);
define("PARAM_DELIMETER", '_');
define("PAGE404", DOMAIN.'/404.html');

define("TMPL_DIR",			ROOT_DIR.'/templates/');
define("MODULES_DIR", 		ROOT_DIR.'/modules/');
define("INC_DIR", 			ROOT_DIR.'/includes/');
define("INCV_DIR", 			INC_DIR.'view/');
define("INCDB_DIR", 		INC_DIR.'db/');
define("LIB_DIR", 			ROOT_DIR.'/lib/');
define("MODEL_DIR", 		LIB_DIR.'model/');
define("IMAGES_DIR", 		ROOT_DIR.'/images/');
define("DATA_DIR", 			ROOT_DIR.'/data/');
define("CONFIG_DIR", 		ROOT_DIR.'/config/');

define("ITMP", 20); //items on page
define("PRP", 10);  //properties on page
define("ONPAGE", 30);
define("NODESIZE",1000);
define("DEF_MOD", 'page');  //default module
define("DEF_MOD_VIEW", 'smarty');  //default view

define("DEBUG", false);
//define("DEBUG", true);

//define("CACHE", true);
define("CACHE", false);
define("CACHE_DIR", ROOT_DIR.'/cache/');
define("COMPONENTS_CACHE_DIR", CACHE_DIR.'components/');
define("CACHE_LTIME", 600);

define("ER_REP", E_ALL);//^E_NOTICE

define('T_USER',1);
define('T_ARTICLE',2);
define('HITS',2000);
?>