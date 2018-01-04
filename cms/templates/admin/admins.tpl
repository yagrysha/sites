<script language="javascript">{literal}
var itemaction ='addadmin';
function aitem(){
	 $('notice').innerHTML = '<img src="/img/circle-apple-small.gif">';
	 new Ajax.Request('/admin/'+itemaction+'/', {
		method: 'post',
		parameters: $('item').serialize(),
	onSuccess: function(transport) {
	if (transport.responseText=='ok'){
		window.location.href= '/admin/admins.html';
	}else
		alert(transport.responseText);
		$('notice').innerHTML = '';
}});

	return false;
}

function adel(id){
	if(confirm('Delete?'))
	new Ajax.Request('/user/delete/id_'+id, {
		method: 'post',
		parameters: 'id='+id,
	onSuccess: function(transport) {
		if (transport.responseText=='ok'){
			$('item'+id).remove();
	}else{
		alert('Error: '.transport.responseText);
	}
}});
	return false;
}

function aedit(id){
	$('addform').show();
	$('notice').innerHTML = '<img src="/img/circle-apple-small.gif">';
	new Ajax.Request('/admin/getadmin/id_'+id, {
		method: 'post',
		parameters: 'id='+id,
	onSuccess: function(transport) {

	if (transport.responseXML){
		var item = transport.responseXML.getElementsByTagName('item')[0];
		itemaction = 'editadmin';
		$('item_id').value = item.attributes.getNamedItem('id').value;
		$('item_login').value = item.getElementsByTagName('login')[0].firstChild.nodeValue;
		$('item_mail').value = item.getElementsByTagName('mail')[0].firstChild.nodeValue;
		$('submit_btn').value = 'Сохранить';
		$('notice').innerHTML = '';
		window.location.href= '#from';
	}else
		alert('Error: '.transport.responseText);
		$('notice').innerHTML = '';
}});
}
function addd(){
	itemaction ='addadmin';
	$('submit_btn').value = 'Сохранить';
	$('item_id').value = '';
	$('item_login').value = '';
	$('item_mail').value = '';
	$('item_password').value = '';
	$('oitem_password').value = '';
	$('addform').show();
	$('notice').innerHTML = '';
}
</script>{/literal}

<div class="block" style="width:95%">
<div class="block" style="width:500px;display:none;" id="addform"><a name="form"></a>
<form id="item" action="/admin/addadmin" method="post">
<input type="hidden" name="id" value="" id="item_id">
<table class="block">
<tr><td>Имя пользователя:</td><td> <input type="text" name="add[login]" id="item_login" size="50"></td></tr>
<tr><td>Email:</td><td> <input type="text" name="add[mail]" id="item_mail" size="50"></td></tr>
<tr><td>Пароль(старый):</td><td> <input type="text" name="add[old_password]" id="oitem_password" size="50"></td></tr>
<tr><td>Пароль:</td><td> <input type="text" name="add[password]" id="item_password" size="50"></td></tr>
<tr><td></td><td><input type="button" value="Сохранить" id="submit_btn" onclick="return aitem();"></td></tr>
</table>
</from><div id="notice"></div>
</div>
<div class="clr"></div>

<a href="#" onclick="addd();">Добавить</a>
<table width="100%"  class="block"><tr bgcolor="#E0EAF3">
<td colspan="2" style="font-weight:bold;">#</td><td>Login</td><td>Email</td><td>created_at</td><td>last login</td></tr>
{foreach from=$admins item=v}
<tr id="item{$v.id}">
<td>{$v.id}
<a href="#" onclick="return aedit({$v.id});"><img src="/img/b_edit.png" border="0" alt="edit"></a>
{if $v.id!=$_user.id}<a href="#" onclick="return adel({$v.id});"><img src="/img/b_drop.png" border="0" alt="delete"></a>{/if}
</td><td>{$v.login} </td><td>{$v.mail} </td>
<td>{$v.created_at|date_format} </td>
<td>{$v.last_login|date_format} </td>
</tr>
{/foreach}
</table>

<br />{if $pager}
Pages:{foreach from=$pager key=k item=v} <a href="/admin/admins/page_{$v}"> {if $_request.page==$v}<strong>{$v}</strong>{else}{$v}{/if}</a>{/foreach}
{/if}</div>
