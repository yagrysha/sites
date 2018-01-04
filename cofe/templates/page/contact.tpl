<div class="main-wide">
<h1><?=$page['name']?></h1>
<?=$page['text']?>
			<form id="contact" action="" onsubmit="contact();return false;"><input type="hidden" name="action" value="send"><div id="notice"></div>
				<table cellpadding="0" cellspacing="0">
				<tr><td>ФИО:</td><td><input type="text" name="name" value="" size="40"></td></tr>
				<tr><td>Контакты <br />
<small>(e-mail, телефон)</small>:</td><td><input type="text" name="phone" size="40" value=""></td></tr>
				<tr><td>Сообщение:</td><td><textarea rows="5" cols="40" name="text"></textarea></td></tr>
				<tr><td colspan="2"  align="center"><input type="submit" value="ОТправить"> </td></tr>
				</table>
</form>

</div>