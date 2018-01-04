<form action="/admin/mail/" method="post">
<div class="red"><?=@$text?></div>
<input type="hidden" name="action" value="send">
<table>
<tr><td>Заголовок</td><td><input type="text" name="subject" size="100" value="<?=$_REQUEST['subject']?>" ></td></tr>
<tr><td>Сообщение</td><td><textarea name="message" cols="60" rows="6"><?=$_REQUEST['message']?></textarea></td></tr>
<tr><td colspan="2"></td></tr>
<tr><td colspan="2"><input type="submit" value="Отправить"></td></tr>
</table>
</form>
