			<div id="content">
				<div class="post">
					<h2 class="title">Восстановление пароля</h2>
					<div class="entry">{if $wrong}
<span style="color:red;">Неверный email</span>
{/if}
{if $sent}
Для вас сформирован новый пароль и отправлен  на почту.
{else}
<form action="" method="post">
<strong>Введите Ваш Email: </strong><input type="text" name="email">
<input type="submit" value="ok" >
</form>
{/if}
					</div>
				</div>
			</div>