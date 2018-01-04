<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?=DOMAINN?> - admin</title>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="/css/admin.css" rel=stylesheet type=text/css>
	<script type="text/javascript" src="/js/prototype.js"></script> 
	<?if(@$_user['name']=='admin'):?>
	<script type="text/javascript" src="/js/admin.js"></script> 
	<?endif;?>
</head>
<body>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td  valign="top" class="nav"><br>

<?include TMPL.'/admin/leftmenu.tpl'?>
</td>
</tr>
<tr>
<td valign="top">