<?include TMPL.'/admin/header_a.tpl'?>

<form action="/admin/savesettings/" method="post">
<div class="red"><?=@$text?></div>
<table>
<tr><td>Email</td><td><input type="text" name="conf[contact_mail]" value="<?=$conf['contact_mail']?>"></td></tr>
<tr><td>Title</td><td><input type="text" name="conf[site_title]" value="<?=$conf['site_title']?>"></td></tr>
<tr><td>Description</td><td><input type="text" name="conf[site_description]" value="<?=$conf['site_description']?>"></td></tr>
<tr><td>Keywords</td><td><input type="text" name="conf[site_keywords]" value="<?=$conf['site_keywords']?>"></td></tr>
<tr><td colspan="2"></td></tr>

<tr><td colspan="2"><input type="submit" value="Save"></td></tr>
</table>
</form>

<?include TMPL.'/admin/footer_a.tpl'?>
