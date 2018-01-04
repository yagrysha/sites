	<div class="content">
		<div class="right">
	</div>
		<div class="left">
	
</div>
<div class="center">
<div id="content" >

<div class="title"><div class="h1"><img src="/img/r1.gif" align="right"><?=$page['name']?></div></div>
<div class="page">
<?=$page['text']?>
<?if($wrong):?>
<span style="color:red;">Неверно</span>
<?endif;?>
<form action="" method="post">
<input type="text" name="login">
<input type="password" name="password">
<input type="checkbox" name="saveme" value="1">
<input type="submit" value="login">
</form>
<a href="/user/forgot">забыли</a>
	</div>
</div>
</div>
	<div class="clear"><!-- --></div>
