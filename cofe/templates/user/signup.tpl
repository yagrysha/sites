<div class="content">
<div class="right"></div>
<div class="left"></div>
<div class="center">
<div id="content" >
<div class="title"><div class="h1"><img src="/img/r1.gif" align="right"><?=$page['name']?></div></div>
<div class="page">

<?if($ok):?>
ok
<?else:?>
<?=$error?>
<form action="" method="post">
<input type="hidden" name="signup" value="1">
Имя пользователя: <input type="text" name="login" value="<?=$_POST['login']?>"><br />
Пароль:<input type="password" name="password">
ещёраз<input type="password" name="password2"><br />
email<input type="text" name="mail" value="<?=$_POST['mail']?>"><br />
code<input type="text" name="code" value=""><img src="/img.php" alt="" border="0"><br />
Имя: <input type="text" name="fullname" value="<?=$_POST['fullname']?>"><br />
пол <input type="radio" name="sex" value="1"<?if($_POST['sex']==1):?> checked<?endif;?>>м<input type="radio" name="sex" value="2"<?if($_POST['sex']==2):?> checked<?endif;?>>ж<br />
датар 
<select name="day">
<option value=""></option>
<?for($i=1;$i<32;$i++):?>
<option value="<?=$i?>"<?if($_POST['day']==$i):?> selected<?endif;?>><?=$i?></option>
<?endfor;?>
</select>
<select name="month">
<option value=""></option>
<?for($i=1;$i<13;$i++):?>
<option value="<?=$i?>"<?if($_POST['month']==$i):?> selected<?endif;?>><?=$i?></option>
<?endfor;?>
</select>
<select name="year">
<option value=""></option>
<?for($i=1960;$i<2000;$i++):?>
<option value="<?=$i?>"<?if($_POST['year']==$i):?> selected<?endif;?>><?=$i?></option>
<?endfor;?>
</select><br />
cj
<textarea name="about" rows="4" cols="60"><?=$_POST['about']?></textarea>
<input type="submit" value="login">
</form>
<?endif;?>
	</div>
</div>
</div>
	<div class="clear"><!-- --></div>
