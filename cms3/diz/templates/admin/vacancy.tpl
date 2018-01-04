<script>
function del(id){
	if(confirm('Delete?'))
	new Ajax.Request('/admin/delvacancy/', {
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


<div class="clr"></div> 
<a href="/admin/vacancy_add">Добавить</a><br />

<table width="100%"  class="block">
<tr bgcolor="#E0EAF3">
<td colspan="2" style="font-weight:bold;">#</td><td></td></tr>

<?$i=0;foreach ($items as $v): $i++;?>
<tr id="item<?=$v['id']?>">
<td><?=$v['id']?></td><td>
<a href="/admin/vacancy_add?id=<?=$v['id']?>" ><img src="/img/b_edit.png" border="0" alt="edit"></a><a href="#" onclick="return del(<?=$v['id']?>);"><img src="/img/b_drop.png" border="0" alt="delete"></a>
</td><td><b><?=date('d.m.y', $v['created_at'])?></b> <?=$v['title']?>
</td>
</tr>
<?endforeach;?>
</table>

<br />
<?if(isset($pager)):?>
Pages:<?foreach ($pager as $k=>$v):?> <a href="/admin/vacancy/page_<?=$v?>"> <?if($page==$v):?><strong><?=$v?></strong><?else:?><?=$v?><?endif;?></a><?endforeach;?>
<?endif;?>
</div>

