<h1>{$page.name}</h1>
							<p>{$page.text}

							</p>
							{if $page.comments}
							Feedback: “{$page.name}”
							<p>{$page.comments}

							</p>{/if}
<h3 id="respond">Leave a Reply</h3>


<form action="/sendrev.html" method="post" id="commentform">


<p><input name="author" id="author" value="" size="22" tabindex="1" type="text">
<label for="author"><small>Name (required)</small></label></p>

<p><input name="email" id="email" value="" size="22" tabindex="2" type="text">
<label for="email"><small>Mail (will not be published) (required)</small></label></p>

<p><input name="url" id="url" value="" size="22" tabindex="3" type="text">
<label for="url"><small>Website</small></label></p>


<p><textarea name="rev" id="comment" cols="40" rows="5" tabindex="4"></textarea></p>

<p><input name="submit" id="submit" tabindex="5" value="Submit Comment" type="submit">
<input name="comment_post_ID" value="33" type="hidden">
</p>

</form>


