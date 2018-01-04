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
<table width="100%"  class="block">
<?foreach ($users as $v):?>
<tr id="user<?=$v['id']?>">
<td><?if($v['photo']):?>
<a href="/user/view/<?=$v['id']?>"><img src="<?=IMAGES.'/s'.$v['photo']?>" width="30" height="30" border="0"></a>
<?endif;?><a href="/user/view/<?=$v['id']?>"><?=$v['login']?></a>
</td><td> <?=$v['login']?> </td><td>
<?if($_SESSION['user']['id']==$v['id']):?><a href="/user/edit/<?=$v['id']?>"><img src="/img/b_edit.png" border="0" alt="edit"></a><?endif;?>
<?if($_SESSION['user']['id']!=$v['id'] && $_SESSION['user']['name']=='admin' && $v['name']!='admin'):?>
<a href="#" onclick="return bloc(<?=$v['id']?>, <?if($v['name']=='user'):?>1<?elseif($v['name']=='nobody'):?>1<?endif;?>);"><?if($v['name']=='nobody'):?>un<?endif;?>block</a>
<a href="#" onclick="return del(<?=$v['id']?>);"><img src="/img/b_drop.png" border="0" alt="delete"></a>
<?endif;?>
 </td><td></td></tr>
<?endforeach;?>
</table>
<br />
<?if(isset($pager)):?>
Pages:<?foreach ($pager as $k=>$v):?> <a href="/user/list/page_<?=$v?>"> <?if($page==$v):?><strong><?=$v?></strong><?else:?><?=$v?><?endif;?></a><?endforeach;?>
<?endif;?>

	</div>
</div>
</div>
	<div class="clear"><!-- --></div>
