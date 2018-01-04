			<div id="content">
				<div class="post">
					<h2 class="title">{$page.name}</h2>
					<div class="entry">{$page.text}{if $text}<br /><p style="color:#FF0000">{$text}</p>{/if}<br />
      <div >
        <form action="" method="post"><input type="hidden" name="action" value="send">
          <p>First and last name*:</p>
          <p><input type="text" name="name" value="" size="50" maxlength="100"></p>
          <p>Contact information (E-mail, phone)*:</p>
          <p><input type="text" name="phone" size="50" value="" maxlength="200"></p>
          <p>Message topic*:</p>
          <p><input type="text" name="subject" size="50" value="" maxlength="200"></p>
          <p>Message*:</p>
          <p><textarea rows="5" name="text" cols="60" id="contmess"></textarea></p>
          <p class="note">Fields marked with an asterisk (*),   are required</p>
          <p><input type="submit" value="Send message" onclick="if(document.getElementById('contmess').value==''){ldelim}alert('Enter message');return false;{rdelim}" />  <input type="reset" value="Reset"></p>
        </form>
        </div>
					</div>
				</div>
			</div>