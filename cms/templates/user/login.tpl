			<div id="content">
				<div class="post">
					<h2 class="title">Авторизация</h2>
					<div class="entry">{$page.text}
					{if $wrong}<div style="color:red;">Неверно</div>{/if}
					<form action="" method="post">
<table cellspacing="5" cellpadding="5" border="0">
<tr><td><strong>Имя пользователя:</strong></td><td><input type="text" name="login" ></td></tr>
<tr><td align="right"><strong>Пароль:</strong></td><td><input type="password" name="password" ></td></tr>
<tr><td><a href="/user/forgot.html">забыли пароль?</a></td><td>запомнить <input type="checkbox" name="saveme" value="1"></td></tr>
<tr><td></td><td><input type="submit" value="Вход" ></td></tr>
</table>
</form>
					</div>
				</div>
			</div>   