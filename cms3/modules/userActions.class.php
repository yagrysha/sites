<?php
class userActions extends Actions {

	var $moduleName = 'user';
    var $u = null;
    
	function userActions(){
	  //  include_once(MODEL_DIR.'Country.class.php');
		parent::Actions();
		$this->u = new Users();
	}
	
	function indexAction(){
        $where = ($this->user['type']=='admin')?'':'user !="nobody"';
        $this->response['users'] = $this->u->getItems(array('page'=>$this->request['page'], 'to'=>ITMP), $where);
        $count = $this->u->getCount($where);
        if ($count>sizeof($this->response['users'])){
            $this->response['pager'] = Utils::getPages($count, $this->request['page'],ITMP);
            $this->response['page'] = $this->request['page'];
        }
	}
	
	function loginAction(){
		if(!empty($_POST['login']) && !empty($_POST['password'])){
			$user = $this->u->UserLogin($_POST['login'], $_POST['password']);
			if($user){
				
					$_SESSION['user'] = $user; 
					$this->user = array_merge($this->user, $user);
					$set = array('last_login'=>time());
					$this->u->update($user['id'], $set);
					if(@$_POST['saveme']){
						$this->user['conf']['sid']=md5($user['code']);
						$this->user['conf']['id']=$user['id'];
						$this->setCust();
					}
					if($this->user['type']=='admin'){
						Utils::redirect('/admin/');
					}else{
						Utils::redirect('/cpage/client.html');
					}
					exit;
			}
			$this->response['wrong'] = true;
		}
	}
	
	function logoutAction(){
		unset($_SESSION);
		session_unset();
		session_destroy();		
		unset($this->user['conf']['sid']);
		unset($this->user['conf']['id']);
		$this->setCust();
		setcookie(session_name(), "",0 , "/", DOMAIN, 0);
		Utils::redirect('');

	}
	
	function forgotAction(){
		if(!empty($_POST['email'])){
			$user = $this->u->getByEmail(trim($_POST['email']));
			if (!$user){
				$this->response['wrong'] = true;
				return true;
			}
			$passw = substr(md5(uniqid(rand(5, 10),true)),3,10);
			$n = array('password'=> md5($passw));
			$this->u->update($user['id'], $n);
			$mess = "Здравствуйте! \n Вы (или кто-то от вашего имени) запросили пароль для для пользователя с логином ".$user['login'].".\n".
			"Для Вас был автоматически сгенерирован новый пароль:".$passw."\n"; 
			mail($user['mail'], 'Изменение пароля',$mess);
			$this->response['sent'] = true;
		}
	}
	
	function signupAction(){
		if($_POST['signup']){
			if(strlen($_POST['login'])<3 && strlen($_POST['login'])>20
			&& !preg_match('/^[a-zA-Z0-9_]+$/')){
				$this->response['error'] = 'Имя пользоваеля';
				
			}elseif(strlen($_POST['password'])<3){
				$this->response['error'] = 'пароль должен быть более 3х символов';
			}elseif($_POST['password']!=$_POST['password2']){
				$this->response['error'] = 'пароли не совпадают';
			}elseif(!preg_match("/^(?:[-a-z0-9_\.]+(?:[-_]?[a-z0-9]+)?@[a-z0-9]+(?:\.?[a-z0-9-_]+)?\.[A-Za-z]{2,5})$/i",
					trim($_POST['mail']))){
				$this->response['error'] = 'email неверный';
			}elseif($this->u->getByEmail($_POST['mail'])){
				$this->response['error'] = 'email есть в базе';
			}elseif($this->u->getByLogin($_POST['login'])){
				$this->response['error'] = 'login есть в базе';
			}elseif(trim($_POST['code'])!=$_SESSION['spamcode']){
				$this->response['error'] = 'код неверный';
			}
			if(!$this->response['error']){
				$code=md5(uniqid(rand(1,9),true));
				$birthdate = ($_POST['year'] && $_POST['month'] && $_POST['day'])?((int)$_POST['year'].'-'.(int)$_POST['month'].'-'.(int)$_POST['day']):'NULL';
				$this->u->add(array(
				'login'=>$_POST['login'],
				'password'=>md5($_POST['password']),
				'mail'=>$_POST['mail'],
				'type'=>'nobody',
				'fullname'=>htmlspecialchars($_POST['fullname']),
				'sex'=>(int)$_POST['sex'],
				'about'=>htmlspecialchars($_POST['about']),
				'birthday'=>$birthdate,
				'code'=>$code,
				'created_at'=>time()
				));
				$mess = 'подтвердить http://'.DOMAINN.'/confirm/'.$code."\n\n".
				'Подтверждение требуется для исключения несанкционированного использования вашего e-mail адреса. Для потверждения достаточно перейти по ссылке, дополнительных писем отправлять не требуется.';
				mail($_POST['mail'], 'регистрация', $mess, 'From: '.DOMAINN);
				$this->response['ok'] = true;
			}
		}
	}
	
	function confirmAction(){
	   if(isset($this->request['path'][2])){
            $code = $this->request['path'][2];
            if ($this->u->confirmEmail($code)){
                $this->response['ok'] = true;
                return true;
            }
	   }
	   Error::e404();     
	}
	
	function listAction(){
		$this->response['_template']='index';
        return $this->indexAction();
	}
	
	function editAction(){
        if(!isset($this->request['path'][2]) && (int)$this->request['path'][2]<1)Error::e404();
        $c = new Country();
        $this->response['countrys'] = $c->getItems();
        $user = $this->u->getItem((int)$this->request['path'][2]);
        if($user['id']!=$this->user['id']) return $this->viewAction();
        if(@$_POST['update']){
            $nuser= array();
            if(!empty($_POST['password'])){
            if(strlen($_POST['password'])<3){
                $this->response['error'] = 'пароль должен быть более 3х символов';
            }elseif($_POST['password']!=$_POST['password2']){
                $this->response['error'] = 'пароли не совпадают';
            }
             $nuser['password'] = md5($_POST['password']);
            }
            if(!preg_match("/^(?:[-a-z0-9_\.]+(?:[-_]?[a-z0-9]+)?@[a-z0-9]+(?:\.?[a-z0-9-_]+)?\.[A-Za-z]{2,5})$/i",
                    trim($_POST['mail']))){
                $this->response['error'] = 'email неверный';
            }
            if(!$this->response['error']){
                $nuser['fullname'] = htmlspecialchars($_POST['fullname']);
                $nuser['mail'] = $_POST['mail'];
                $nuser['sex'] = (int)$_POST['sex'];
                $nuser['about']=htmlspecialchars($_POST['about']);
                $nuser['address']=htmlspecialchars($_POST['address']);
                $nuser['website']=htmlspecialchars($_POST['website']);
                if($_POST['year'] && $_POST['month'] && $_POST['day'])
                $nuser['birthday']=(int)$_POST['year'].'-'.(int)$_POST['month'].'-'.(int)$_POST['day'];
                $nuser['country_id'] = (int)$_POST['country_id'];
                $this->u->update($user['id'], $nuser);
                $user = array_megre($user, $nuser);
                $this->response['ok'] = true;
            }
        }
        $this->response['user'] = $user;
	}
	
	function viewAction(){
	    if(!isset($this->request['path'][2]) && (int)$this->request['path'][2]<1)Error::e404();
        $user = $this->u->getItem((int)$this->request['path'][2]);
	    $this->response['user'] = $user;
	}
	
	function deleteAction(){
		$this->view = '';
       if(isset($this->request['path'][2])){
            $this->u->delete((int)$this->request['path'][2]);
            $this->response['text'] = 'ok';
            return true;
       }
       $this->response['ok'] = 'err';
	}

	function blockAction(){
		$this->view = '';
       if(isset($this->request['path'][2])){
            if($this->u->update((int)$this->request['path'][2], array('type'=>'nobody'))){
            $this->response['text'] = 'ok';
            return true;
            }
       }
       $this->response['ok'] = 'err';
    }
   	
    function unblockAction(){
   		$this->view = '';
       if(isset($this->request['path'][2])){
            if($this->u->update((int)$this->request['path'][2], array('type'=>'user'))){
            $this->response['text'] = 'ok';
            return true;
            }
       }
       $this->response['ok'] = 'err';
    }
    
    function subscrAction(){
    	          $this->view = ''; 
    	$a = array('nomail'=>(int)$_REQUEST['mail']);
    	if($this->u->update((int)$this->user['id'], $a)){
    		$_SESSION['user']['nomail'] = (int)$_REQUEST['mail'];
            $this->response['text'] = 'ok';
            return true;
            }
    }
}