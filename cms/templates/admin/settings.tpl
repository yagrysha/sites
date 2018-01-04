<form action="" method="post">
<input type="hidden" name="save" value="1">
{if $ok}<div class="red">Сохранено</div>{/if}
<table class="block">
<tr><td>Email</td><td><input type="text" name="conf[contact_mail]" size="100" value="{$conf.contact_mail}"></td></tr>
<tr><td>Заголовок (по умочанию)</td><td><input type="text" name="conf[site_title]" size="100" value="{$conf.site_title}"></td></tr>
<tr><td>Название</td><td><input type="text" name="conf[site_name]" size="100" value="{$conf.site_name}"></td></tr>
<tr><td>meta Description</td><td><input type="text" name="conf[site_description]" size="100" value="{$conf.site_description}"></td></tr>
<tr><td>meta Keywords</td><td><input type="text" name="conf[site_keywords]" size="100" value="{$conf.site_keywords}"></td></tr>
<tr><td colspan="2"><input type="submit" value="Сохранить"></td></tr>
</table>
</form>
