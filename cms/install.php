<?php
/**
 * miniMVC 0.2.3  04.01.2009
 * @author Yaroslav Gryshanovich <yagrysha@gmail.com>
 * @link http://mvc.yagrysha.com/
 */
define("ROOT_DIR", dirname(__FILE__));
include_once ROOT_DIR . '/config/conf.php';
error_reporting(ER_REP);
require_once INC_DIR . 'utils.class.php';
require_once INC_DIR . 'error.class.php';
require_once INC_DIR . 'controller.class.php';
include_once INCV_DIR . 'view.class.php';
$app = new Controller();
$app->include_component('install', 'index', array());