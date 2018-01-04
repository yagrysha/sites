<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * http://mvc.yagrysha.com/
 */
include_once INCV_DIR . 'phpview.class.php';
class xmlView extends phpView {

	function getView(){
		header('Content-type: text/xml');
		return parent::getView();
	}
}
