<h1>{$page.name}</h1>
			<div id="text-block">
				<div id="ico-right">
					<p>
						{$page.text}
					</p>

					{if $text}<strong style="color:red;">{$text}</strong>{/if}
			<form id="contact" action="" method="post"><input type="hidden" name="action" value="send">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr><td>Name:</td><td><input  type="text" name="name" value="" size="30"></td></tr>
				<tr><td>Contacts <br />
<small>(e-mail, phone)</small>:</td><td><input type="text" name="phone" size="30" value="" ></td></tr>
				<tr><td>Message:</td><td><textarea rows="4" cols="40" name="text" ></textarea></td></tr>
				<tr><td colspan="2"  align="center"><input type="submit" value="Send"> </td></tr>
				</table>
</form>
				</div>
			</div>


