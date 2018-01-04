
<script>

function del(id){
	if(confirm('Delete?'))
	new Ajax.Request('/admin/delpage/', {
		method: 'post',
		parameters: 'id='+id,
	onSuccess: function(transport) {
		if (transport.responseText=='ok'){
			$('item'+id).remove();
	}else{
		alert('Error');
	}
}});
	return false;
}


</script>
<div class="block" style="width:95%;">
<?if (isset($trail)):?>
<?foreach ($trail as $k=>$v):?>
<?if($v['id']!=$_REQUEST['pid']):?>
<a href="/admin/pages/?pid=<?=$v['id']?>"><?=$v['name']?></a> /
<?else:?><?=$v['name']?><?endif;?>
<?endforeach;?><br />
<?endif;?>


<div class="clr"></div> 
<a href="/admin/pages_add?pid=<?=@$_REQUEST['pid']?>">Добавить</a><br />

<table width="100%"  class="block">
<tr bgcolor="#E0EAF3">
<td colspan="2" style="font-weight:bold;">#</td><td>Название</td><td>Заглоловок</td><td>Alias</td></tr>

<?$i=0;foreach ($pages as $v): $i++;?>
<tr id="item<?=$v['id']?>" <?if(@$v['hidden']):?> bgcolor="#eeeeee"<?endif;?>>
<td><?=$v['id']?></td><td>
<a href="/admin/pages_add?id=<?=$v['id']?>&pid=<?=@$_REQUEST['pid']?>" ><img src="/img/b_edit.png" border="0" alt="edit"></a><a href="#" onclick="return del(<?=$v['id']?>);"><img src="/img/b_drop.png" border="0" alt="delete"></a>

<?if($i!=1):?><a href="/admin/pages/?up=<?=$v['id']?>&pid=<?=@$_REQUEST['pid']?>"><img src="/img/s_asc.png" border="0" alt="up"></a><?endif;?> 
<?if($i!=sizeof($pages)):?><a href="/admin/pages/?dn=<?=$v['id']?>&pid=<?=@$_REQUEST['pid']?>"><img src="/img/s_desc.png" border="0" alt="dn"></a> <?endif;?>
</td><td> <a href="/admin/pages/?pid=<?=$v['id']?>"><?=$v['name']?></a> </td><td> <?=$v['title']?> </td><td><?=$v['alias']?>

</td>
</tr>
<?endforeach;?>
</table>

<br />
<?if(isset($pager)):?>
Pages:<?foreach ($pager as $k=>$v):?> <a href="/admin/pages/page_<?=$v?>"> <?if($page==$v):?><strong><?=$v?></strong><?else:?><?=$v?><?endif;?></a><?endforeach;?>
<?endif;?>
</div>

