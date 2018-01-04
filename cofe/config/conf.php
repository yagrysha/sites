<?
define("DBHOST", 'localhost');
define("DBUSER", 'root');
define("DBPASSW", '');
define("DBNAME", 'cofe');

define("USERAGENT", $_SERVER['HTTP_USER_AGENT']);

define("DOMAINN", 'cofe');
define("DOMAIN", 'http://'.DOMAINN);
//        site domain (eq http://example.com)
//        must be without end slashes
define("TMPL", PREFIX.'/templates');
define("TMPLB", TMPL.'/block');
define("PROC", PREFIX.'/modules');
define("INC", PREFIX.'/includes');
define("LIB", PREFIX.'/lib');
define("IMAGES", PREFIX.'/pict');

define("ITMP", 20); //items on page
define("PRP", 10);  //properties on page
define("NODESIZE",1000);
define("DEF_MOD", 'page');  //properties on page
define("DEBUG", false);
//define("DEBUG", true);
define("SET_NAMES", true);

//define("CACHE", true);
define("CACHE", false);
define("ONPAGE", 300);
define("VIEW_CACHE", false);
define("CACHE_DIR", PREFIX.'/cache/');
define("COMPONENTS_CACHE_DIR", PREFIX.'/cache/components');
define("CACHE_LTIME", 600);

define("ER_REP", E_ALL^ E_NOTICE);

define('T_USER',1);
define('T_ARTICLE',2);
?>