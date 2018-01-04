<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.07.2007
 * 
 */
define("PREFIX", dirname(__FILE__));
include_once 'config/conf.php';
include_once INC.'/controller.class.php';
$app = new Controller();
$app->run();
?>