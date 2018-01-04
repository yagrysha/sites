<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<title><?=($page['title'])?$page['title']:$conf['site_title']?></title>
<meta name="description" content="<?=($page['description'])?$page['description']:$conf['site_description']?>" />
<meta name="keywords" content="<?=($page['keywords'])?$page['keywords']:$conf['site_keywords']?>" />

<script type="text/javascript" src="/js/prototype.js"></script> 
<link rel="stylesheet" href="/css/nav.css" media="screen" type="text/css" />
<link rel="stylesheet" href="/css/template_css.css" media="screen" type="text/css" />
<!--[if IE]>
	<link rel="stylesheet" href="/css/ie.css" media="screen" type="text/css" />
<![endif]-->

</head>


<body id="bd1">
<div id="main-wrapper">	

	<div class="topmenu"> <?if(@!$_SESSION['user']):?>
	<a href="/user/login.html">войти</a> | <a href="/user/signup.html">зарегистрироватся</a><?else:?> <a href="/user/view/<?=$_SESSION['user']['id']?>"><?=$_SESSION['user']['login']?></a> | <a href="/user/logout.html">выход</a>
<?endif;?> </div>
		
	<div id="header_graphic">
	       	<div class="inside">   
           <table border=0 height="130" width="100%">

           <tr><td valign="middle" align="center" width="100">&nbsp;&nbsp;&nbsp;<!--img src="http://cofe.by/templates/js_vintage_001/images/cofe.gif"></img--></td>
           <td valign="top"><!--div id="headermod"></div-->
			<h1><a href="http://cofe.by" title="Клуб любителей кофе"><i>Клуб любителей кофе</a></h1>
			<h2>Кофеманы всех стран соединяйтесь!</h2>

          </td></tr>
           </table>
			</div>

		</div>
	<div class="menubar">
		<div id="navmenu">
<ul><li class='active'><a href="/">Главная</a></li>
<li><a href="/">Кофе</a></li>
<li><a href="/">Кофеманы</a></li>
<li><a href="/">Кофейни</a></li>
<li><a href="/">ffh</a></li>
</ul>					</div>
	</div>
			<div class="main-top"></div>

	<div id="mainbody">
		<?include($this->template)?>						

	</div>
		<div id="footer"></div>
	
	
	<div class="copyright">Belorussian Coffee Community &copy; 2007-2008 | <a href="/about.html">о сайте</a> | <a href="/contact.html">контакт</a>  </div>
		</div>

</body>
</html>



