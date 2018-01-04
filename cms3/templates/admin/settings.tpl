<form action="/admin/savesettings/" method="post">
<div class="red"><?=@$text?></div>
<table class="block">
<tr><td>Email</td><td><input type="text" name="conf[contact_mail]" size="100" value="<?=$conf['contact_mail']?>"></td></tr>
<tr><td>Заголовок (по умочанию)</td><td><input type="text" name="conf[site_title]" size="100" value="<?=$conf['site_title']?>"></td></tr>
<tr><td>meta Description</td><td><input type="text" name="conf[site_description]" size="100" value="<?=$conf['site_description']?>"></td></tr>
<tr><td>meta Keywords</td><td><input type="text" name="conf[site_keywords]" size="100" value="<?=$conf['site_keywords']?>"></td></tr>

<tr><td colspan="2"></td></tr>
<tr><td>link 1</td><td><input type="text" name="conf[link1]" size="100" value="<?=$conf['link1']?>"></td></tr>
<tr><td>link 2</td><td><input type="text" name="conf[link2]" size="100" value="<?=$conf['link2']?>"></td></tr>
<tr><td>link 3</td><td><input type="text" name="conf[link3]" size="100" value="<?=$conf['link3']?>"></td></tr>
<tr><td>link 4</td><td><input type="text" name="conf[link4]" size="100" value="<?=$conf['link4']?>"></td></tr>
<tr><td>link 5</td><td><input type="text" name="conf[link5]" size="100" value="<?=$conf['link5']?>"></td></tr>
<tr><td colspan="2"><input type="submit" value="Сохранить"></td></tr>
</table>
</form>
