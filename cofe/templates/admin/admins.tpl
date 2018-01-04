<?include TMPL.'/admin/header_a.tpl'?>
<script language="javascript">
var itemaction ='addadmin';
function aitem(){
	 $('notice').innerHTML = '<img src="/img/circle-apple-small.gif">';
	 new Ajax.Request('/admin/'+itemaction+'/', {
		method: 'post',
		parameters: $('item').serialize(),
	onSuccess: function(transport) {
		var notice = $('notice');
	if (transport.responseText=='ok'){
		window.location.href= '/admin/admins';
	}else
		notice.update('Error. Please, try again.').setStyle({ background: '#dfd' });
}});

	return false;
}

function adel(id){
	if(confirm('Delete?'))
	new Ajax.Request('/admin/deladmin/', {
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

function aedit(id){
	$('addform').show();
	$('notice').innerHTML = '<img src="/img/circle-apple-small.gif">';
	new Ajax.Request('/admin/getadmin/', {
		method: 'post',
		parameters: 'id='+id,
	onSuccess: function(transport) {
		
	if (transport.responseXML){
		var item = transport.responseXML.getElementsByTagName('item')[0];
		itemaction = 'editadmin';
		$('item_id').value = item.attributes.getNamedItem('id').value;
		$('item_login').value = item.getElementsByTagName('login')[0].firstChild.nodeValue;
		$('item_mail').value = item.getElementsByTagName('mail')[0].firstChild.nodeValue;
		$('submit_btn').value = 'edit';
		$('notice').innerHTML = '';
		window.location.href= '#from';
	}else
		alert('Error');
}});
	
	
	
}
</script>


<div class="block" style="width:600px;">

<div class="block" style="width:500px;display:none;" id="addform"><a name="form"></a>
<form id="item" action="/admin/addadmin" method="post">
<input type="hidden" name="pid" value="<?=$_REQUEST['pid']?>">
<input type="hidden" name="id" value="" id="item_id">
<table>
<tr><td>login:</td><td> <input type="text" name="add[login]" id="item_login" size="50"></td></tr>
<tr><td>mail:</td><td> <input type="text" name="add[mail]" id="item_mail" size="50"></td></tr>
<tr><td>old password:</td><td> <input type="text" name="add[old_password]" id="oitem_password" size="50"></td></tr>
<tr><td>password:</td><td> <input type="text" name="add[password]" id="item_password" size="50"></td></tr>
<tr><td></td><td><input type="button" value="Save" id="submit_btn" onclick="return aitem();"></td></tr>
</table>
</from><div id="notice"></div>
</div>
<div class="clr"></div> 

<a href="#" onclick="$('addform').toggle();">Add</a>
<table width="100%"  class="block">
<?foreach ($admins as $v):?>
<tr id="item<?=$v['id']?>">
<td><?=$v['id']?> 
<a href="#" onclick="return aedit(<?=$v['id']?>);"><img src="/img/b_edit.png" border="0" alt="edit"></a><a href="#" onclick="return adel(<?=$v['id']?>);"><img src="/img/b_drop.png" border="0" alt="delete"></a>
</td><td> <?=$v['login']?> </td><td><?=$v['mail']?> </td><td> </td>
</tr>
<?endforeach;?>
</table>

<br />
<?if(isset($pager)):?>
Pages:<?foreach ($pager as $k=>$v):?> <a href="/admin/admins/page_<?=$v?>"> <?if($page==$v):?><strong><?=$v?></strong><?else:?><?=$v?><?endif;?></a><?endforeach;?>
<?endif;?>
</div>
<?include TMPL.'/admin/footer_a.tpl'?>