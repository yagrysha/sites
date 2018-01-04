<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 23.12.07
 */
define("ROOT_DIR", dirname(__FILE__));
include_once ROOT_DIR.'/config/conf.php';
include_once INC_DIR.'controller.class.php';
$app = new Controller();
$app->run();
?>