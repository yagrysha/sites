{if $trail}<a href="/admin/pages">index</a> / 
{foreach from=$trail item=v}
{if $v.id!=$_request.pid}
<a href="/admin/pages/pid_{$v.id}">{$v.name}</a> / {else}{$v.name}{/if}
{/foreach}<br />
{/if}

<strong>{if !$_request.id}Новая страница{else}Измеение{/if}</strong>
<div id="addform"><a name="form"></a>
<form id="item" action="" method="post">
<input type="hidden" name="save" value="1">
<div class="red">{$text}</div>
<table width="100%"  class="block">
<tr><td width="100">Название:</td><td> <input type="text" name="add[name]" id="item_name" size="50" value="{$page.name}"></td></tr>
<tr><td>Alias:</td><td> <input type="text" name="add[alias]" id="item_alias" size="100" maxlength="100" value="{$page.alias}"></td></tr>
<tr><td>Заголовок:</td><td> <input type="text" name="add[title]" id="item_title" size="100" maxlength="255" value="{$page.title}"></td></tr>
<tr><td>Meta keywords:</td><td><input type="text" name="add[keywords]" id="item_keywords" maxlength="255" size="100" value="{$page.keywords}"></td></tr>
<tr><td>Meta description:</td><td><input type="text" name="add[description]" id="item_description" maxlength="255" size="100" value="{$page.description}"></td></tr>
<tr><td>Скрытая:</td><td><input type="checkbox" name="add[hidden]" id="item_hidden" value="1" {if $page.hidden} checked{/if}></td></tr>

<tr><td colspan="2"> Text
{php}
include_once(ROOT_DIR.'/fckeditor/fckeditor.php') ;
$oFCKeditor = new FCKeditor('add[text]') ;
$oFCKeditor->Height = 400;
$oFCKeditor->Value		=  @$this->_tpl_vars['page']['text'];
$oFCKeditor->Create() ;
{/php}
</td></tr>
<tr><td></td><td><input type="submit" value="Сохранить" id="submit_btn"></td></tr>
</table>
</from>
<div class="clr"></div>
</div>