<div class="content">
<div class="right"></div>
<div class="left"></div>
<div class="center">
<div id="content" >
<div class="title"><div class="h1"><img src="/img/r1.gif" align="right"><?=$page['name']?></div></div>
<div class="page">

<?if($ok):?>
ok
<?endif;?>
<?=$error?>
<form action="" method="post">
<input type="hidden" name="update" value="1">
Пароль:<input type="password" name="password">
ещёраз<input type="password" name="password2"><br />
email<input type="text" name="mail" value="<?=$user['mail']?>"><br />
ФИО: <input type="text" name="fullname" value="<?=$user['fullname']?>"><br />
пол <input type="radio" name="sex" value="1"<?if($user['sex']==1):?> checked<?endif;?>>м<input type="radio" name="sex" value="2"<?if($user['sex']==2):?> checked<?endif;?>>ж<br />
датар 
<select name="day">
<option value=""></option>
<?for($i=1;$i<32;$i++):?>
<option value="<?=$i?>"<?if($user['day']==$i):?> selected<?endif;?>><?=$i?></option>
<?endfor;?>
</select>
<select name="month">
<option value=""></option>
<?for($i=1;$i<13;$i++):?>
<option value="<?=$i?>"<?if($user['month']==$i):?> selected<?endif;?>><?=$i?></option>
<?endfor;?>
</select>
<select name="year">
<option value=""></option>
<?for($i=1960;$i<2000;$i++):?>
<option value="<?=$i?>"<?if($user['year']==$i):?> selected<?endif;?>><?=$i?></option>
<?endfor;?>
</select><br />
в
<textarea name="about" rows="4" cols="60"><?=$user['about']?></textarea>
fo<input type="file" name="photo">
<?if($user['photo']):?>
<img src="<?=IMAGES.'/m'.$user['photo']?>" border="0">
<?endif;?><br />
<select name="country_id">
<?foreach($countrys as $v):?>
<option value="<?=$v['id']?>"<?if($v['id']==$user['country_id']):?> selected<?endif;?>><?=$v['name']?></option>
<?endforeach;?>
</select><br />
adre<input type="text" name="address" value="<?=$user['address']?>"><br />
adre<input type="text" name="website" value="<?=$user['website']?>">
<input type="submit" value="login">

</form>
	</div>
</div>
</div>
	<div class="clear"><!-- --></div>
