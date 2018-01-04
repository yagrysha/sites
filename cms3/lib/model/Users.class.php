<?php
/**
 * yagrysha@gmail.com
 * Yaroslav Gryshanovich
 * 31.10.2007
 * 
 */
class Users extends Item
{

    var $table = 'users';

    function UserLogin($login, $passw) {
        return $this->db->SelectOne($this->table, array('where' => 'login="' . $login . '" AND password ="' . md5($passw) . '" AND type!="nobody"'));
    }

    function UserRegister($arr) {
        return $this->db->Insert($this->table, $arr);
    }

    function getAdmin() {
        return $this->db->SelectOne($this->table, array('where' => array('type' => 'admin', 'id' => 1)));
    }

    function getByLogin($login) {
        return $this->db->SelectOne($this->table, array('where' => array('login' => $login)));
    }

    function getByEmail($mail) {
        return $this->db->SelectOne($this->table, array('where' => array('mail' => $mail)));
    }

    function confirmEmail($code) {
        $user = $this->db->SelectOne($this->table, array('where' => array('code' => $code)));
        if (! $user)
            return false;
        return $this->db->Update($this->table, array('type' => 'user'), array('code' => $code));
    }
    
    function update($id, &$set){
        if(@$_REQUEST['delimg']){
            $user = $this->getItem($id);
            if($user['photo']) {
                unlink(IMAGES.'/s'.$user['photo']);
                unlink(IMAGES.'/m'.$user['photo']);
                unlink(IMAGES.'/b'.$user['photo']);
            }
            $set['photo'] ='';
        }
        if(@is_uploaded_file($_FILES['photo']['tmp_name'])){
            $user = $this->getItem($id);
            if($user['photo']) {
                unlink(IMAGES.'/s'.$user['photo']);
                unlink(IMAGES.'/m'.$user['photo']);
                unlink(IMAGES.'/b'.$user['photo']);
            }
            $path_parts = pathinfo($_FILES['photo']['name']);
            $to_file = md5(uniqid(rand(5), true)).'.'.$path_parts['extension'];
            Utils::resize_img($_FILES['photo']['tmp_name'], IMAGES.'/s'.$to_file, 30, 30);
            Utils::resize_img($_FILES['photo']['tmp_name'], IMAGES.'/m'.$to_file, 100, 100);
            Utils::resize_img($_FILES['photo']['tmp_name'], IMAGES.'/b'.$to_file, 300, 300);
            $set['photo'] = $_FILES['photo']['name'];
        }
        return parent::update($id, $set);
    }
}
