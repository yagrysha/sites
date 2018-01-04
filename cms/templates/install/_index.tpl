<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Install</title>
<meta name="description" content="minimvc 404 page" />
<meta name="keywords" content="minimvc" />
<link href="/css/style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<div id="wrapper">
	<div id="wrapper2">
		<div id="header">
			<div id="logo">
				<h1>minimvc</h1>
			</div>
			<div id="menu">
				<ul>
				</ul>
			</div>
		</div>
		<!-- end #header -->
		<div id="page">
			<div id="content" style="width:95%;">
				<div class="post">
<h2>Установка</h2>
{if $phpversion}Ваша версия PHP {$phpversion}. <span style="color:red;">Необходима версия 5 и выше.</span>{/if}
<form action="install.php" method="post">
<input type="hidden" name="step" value="1">
<table width="100%">
<tr><td><strong></strong></td><td>{if $error}<strong style="color:red;">{$error}</strong>{/if}</td></tr>
<tr><td>Домен (site.com)</td><td><input type="text" size="60" name="domain" value="{$smarty.post.domain}"></td></tr>
<tr><td>Название</td><td><input type="text" name="site_name" size="90" value="{$smarty.post.site_name}"></td></tr>
<tr><td>Заголовок (title)</td><td><input type="text" name="site_title" size="90" value="{$smarty.post.site_title}"></td></tr>
<tr><td>Описание (meta description)</td><td><input type="text" size="90" name="site_description" value="{$smarty.post.site_description}"></td></tr>
<tr><td>Ключевые слова (meta keywords)</td><td><input type="text" size="90" name="site_keywords" value="{$smarty.post.site_keywords}"></td></tr>

<tr><td><strong>База данных</strong></td><td><select name="database">
<option value=""> Выберите </option>
<option value="mysql"{if $smarty.post.database=='mysql'} selected{/if}>MySql</option>
<option value="sqlite"{if $smarty.post.database=='sqlite'} selected{/if}>SQLite</option></select></td></tr>
<tr><td>База данных</td><td><input type="text" name="dbname" size="60" value="{$smarty.post.dbname|default:'minimvc'}"></td></tr>
<tr><td>Хост</td><td><input type="text" name="dbhost" size="60" value="{$smarty.post.dbhost|default:'localhost'}"></td></tr>
<tr><td>Пользователь</td><td><input type="text" name="dbuser" size="60" value="{$smarty.post.dbuser|default:'root'}"></td></tr>
<tr><td>Пароль</td><td><input type="text" name="dbpassword" size="60" value="{$smarty.post.dbpassword}"></td></tr>

<tr><td><strong>Администратор</strong></td><td></td></tr>
<tr><td>Логин</td><td><input type="text" name="login" size="60" value="{$smarty.post.login|default:'admin'}"></td></tr>
<tr><td>Пароль</td><td><input type="text" name="password" size="60" value="{$smarty.post.password|default:'admin'}"></td></tr>
<tr><td>Email</td><td><input type="text" name="email" size="60" value="{$smarty.post.email|default:'admin@nomail.ru'}"></td></tr>
<tr><td></td><td><input type="submit" value="Install"></td></tr>
</table>
</form>
				</div>
			</div>
			<!-- end #content -->
			<div style="clear: both;">&nbsp;</div>
			
		</div>
		<!-- end #page -->
	</div>
	<!-- end #wrapper2 -->
	<div id="footer">
		<p></p>
	</div>
</div>
<!-- end #wrapper -->
</body>
</html>