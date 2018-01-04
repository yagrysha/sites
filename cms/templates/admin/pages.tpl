<script>
{literal}function del(id){
	if(confirm('Delete?'))
	new Ajax.Request('/admin/delpage/id_'+id, {
		method: 'post',
		parameters: '',
	onSuccess: function(transport) {
		if (transport.responseText=='ok'){
			$('item'+id).remove();
	}else{
		alert('Error');
	}
}});
	return false;
}</script>{/literal}
<div class="block" style="width:95%;">

{if $trail} <a href="/admin/pages">index</a> / 
{foreach from=$trail item=v}
{if $v.id!=$_request.pid}
<a href="/admin/pages/pid_{$v.id}">{$v.name}</a> / {else}{$v.name}{/if}
{/foreach}<br />
{/if}

<div class="clr"></div>
<a href="/admin/pages_add/pid_{$_request.pid}">Добавить</a><br />
<table width="100%"  class="block"><tr bgcolor="#E0EAF3">
<td colspan="2" style="font-weight:bold;">#</td><td>Название</td><td>Заглоловок</td><td>Alias</td></tr>
{foreach from=$pages item=v name=ff}
<tr id="item{$v.id}" {if $v.hidden} bgcolor="#eeeeee"{/if}>
<td>{$v.id}</td><td>
<a href="/admin/pages_add/id_{$v.id}/pid_{$_request.pid}" ><img src="/img/b_edit.png" border="0" alt="edit"></a><a href="#" onclick="return del({$v.id});"><img src="/img/b_drop.png" border="0" alt="delete"></a>

{if !$smarty.foreach.ff.first}<a href="/admin/pages/pid_{$_request.pid}/up_{$v.id}"><img src="/img/s_asc.png" border="0" alt="up"></a>{/if}
{if !$smarty.foreach.ff.last}<a href="/admin/pages/pid_{$_request.pid}/dn_{$v.id}"><img src="/img/s_desc.png" border="0" alt="dn"></a> {/if}
</td>
<td><a href="/admin/pages/pid_{$v.id}">{$v.name}</a></td>
<td>{$v.title}</td>
<td>{$v.alias}</td>
</tr>
{/foreach}
</table>

<br />{if $pager}
Pages:{foreach from=$pager key=k item=v} <a href="/{$_version}/admin/pages/page_{$v}"> {if $_request.page==$v}<strong>{$v}</strong>{else}{$v}{/if}</a>{/foreach}
{/if}</div>

