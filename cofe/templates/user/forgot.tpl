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
email<input type="text" name="email">
<input type="submit" value="ok">
</form>

	</div>
</div>
</div>
	<div class="clear"><!-- --></div>
