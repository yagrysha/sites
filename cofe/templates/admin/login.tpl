<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title><?=@$page['title']?></title>

	<meta name="description" content="<?=@$page['description']?>" />
	<meta name="keywords" content="<?=@$page['keywords']?>" />
	<link rel="stylesheet" type="text/css"  href="/css/default.css" />
	<script type="text/javascript" src="/js/prototype.js"></script> 
	<script type="text/javascript" src="/js/main.js"></script> 
</head>
<body>
<div id="outer">

<script>
function flogin(){
	 $('notice').innerHTML = '<img src="/img/circle-apple-small.gif">';
	 new Ajax.Request('/admin/', {
		method: 'post',
		parameters: $('login').serialize(),
	onSuccess: function(transport) {
		var notice = $('notice');
	if (transport.responseText=='ok'){
		window.location.href= '/admin/';
	}else
		notice.update('Wrong Login/Password. Please, try again.').setStyle({ background: '#dfd' });
}});
	return false;
}
</script>


<div class="block" style="margin:0px auto;width:200px;float:none;">
<form id="login" action="" onsubmit="flogin();return false;">
<input type="hidden" name="action" value="login">
Login: <br />
<input type="text" name="login"><br>
Password: <br />
<input type="password" name="password"><br>
<input type="submit" value="Login">
</from><div id="notice"></div>
</div>
<?include TMPL.'/admin/footer_a.tpl'?>