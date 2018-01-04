<div class="post">
<h2 class="title"><a href="#"></a></h2>
	<div class="entry"></div>
</div>

       <div class="floatL boxL">
                <div class="box">
                    
                </div>
            </div>
            <div class="floatL boxC674 padL25">
            
                <h5>Авторизация</h5>
                {if $wrong}
<span style="color:red;">Неверно</span>
{/if}
<form action="" method="post">
<table cellspacing="5" cellpadding="5">
<tr><td><strong>Имя пользователя:</strong></td><td><input type="text" name="login" style="border:1px solid #000;"></td></tr>
<tr><td align="right"><strong>Пароль:</strong></td><td><input type="password" name="password" style="border:1px solid #000;"></td></tr>
<tr><td>{*a href="/user/forgot">забыли пароль?</a>*}</td><td>запомнить <input type="checkbox" name="saveme" value="1"></td></tr>
<tr><td></td><td><input type="submit" value="Вход"></td></tr>
</table>
</form>
            </div>

