<?php
/**
 * miniMVC 
 * http://mvc.yagrysha.com/
 */
class Users extends Item {
	var $table = 'users';

	function Users(){
		parent::Item();
		$this->table = 'users';
	}

	function login($login, $passw){
		$u = $this->db->SelectOne($this->table, 
				array(
						'where'=>'login="' . $login . '" AND password="' . md5(
								$passw) . '" AND type>0'));
		if(!$u) return false;
		$_SESSION['user'] = $u;
		$this->update($u['id'], array('last_login'=>time()));
		return $u;
	}

	function signup($arr){
		$arr['type'] = 1;
		$arr['code'] = md5(uniqid(rand(1, 9), true));
		$arr['created_at'] = time();
		$arr['ip'] = ($_SERVER["REMOTE_ADDR"])? $_SERVER["REMOTE_ADDR"]:(($_ENV["HTTP_X_FORWARDED_FOR"])? $_ENV["HTTP_X_FORWARDED_FOR"]:0);
		return $this->add($arr);
	}

	function getByLogin($login){
		return $this->getItem(array('login'=>$login));
	}

	function getByEmail($mail){
		return $this->getItem(array('mail'=>$mail));
	}

	function confirmEmail($code){
		$user = $this->getItem(array('code'=>$code));
		if(!$user) return false;
		return $this->wupdate(
				array('type'=>'user', 'code'=>md5(uniqid(rand(1, 9), true))), 
				array('code'=>$code));
	}
}