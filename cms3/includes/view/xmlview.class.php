<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.10.2007
 * 
 */
include_once INCV_DIR.'view.class.php';
include_once INCV_DIR.'phpview.class.php';
class xmlView extends phpView{
	
	function getView(){
		header('Content-type: text/xml');
		return parent::getView();
	}
}
