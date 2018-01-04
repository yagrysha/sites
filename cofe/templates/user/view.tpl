<div class="content">
<div class="right"></div>
<div class="left"></div>
<div class="center">
<div id="content" >
<div class="title"><div class="h1"><img src="/img/r1.gif" align="right"><?=$page['name']?></div></div>
<div class="page">

<?if($_SESSION['user']['name']=='admin' && $_SESSION['user']['id']!=$user['id']):?>
<script>

</script>
<?if($user['name']=='nobody'):?><a href="#">разблокировать</a><?elseif($user['name']=='user'):?><a href="#">заблокировать</a><?endif;?>
<?endif;?>
<?if($_SESSION['user']['id']==$user['id']):?><a href="/user/edit/<?=$user['id']?>"><img src="/img/b_edit.png" border="0" alt="edit"></a><?endif;?>
ФИО: <?=$user['fullname']?><br />
пол <?if($user['sex']==1):?>m<?elseif ($user['sex']==2):?>k<?endif;?><br />
датар 
<?=$user['birthday']?>
в
<?=$user['about']?>
<?if($user['photo']):?>
<img src="<?=IMAGES.'/m'.$user['photo']?>" border="0">
<?endif;?><br />
<?$user['country_id']?>
adre<?=$user['address']?>
adre<?=$user['website']?>
	</div>
</div>
</div>
	<div class="clear"><!-- --></div>
