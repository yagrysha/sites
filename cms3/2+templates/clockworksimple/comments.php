<?php // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Please do not load this page directly. Thanks!');
if (!empty($post->post_password)) { // if there's a password
	if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
?>

<h2><?php _e('Password Protected'); ?></h2>
<p><?php _e('Enter the password to view comments.'); ?></p>

<?php return;
	}
}

?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>

	<div class="indicator">&bull;</div>
	<h2 id="comments"><?php comments_number('No comments yet', 'One comment', '% comments' );?> to &#8220;<?php the_title(); ?>&#8221;</h2>

<ol class="commentlist">
<?php foreach ($comments as $comment) : ?>

	<?php
	$commenthighlighter = 'commentmetadata';
	if($comment->comment_author_email == get_the_author_email()) { $commenthighlighter = 'commentmetadata_author'; }
	?>

	<li id="comment-<?php comment_ID() ?>">

<div class="<?php echo $commenthighlighter; ?>">
<?php if(function_exists('get_avatar')){
  echo get_avatar($comment, '30');
} ?>
<b><?php comment_author_link() ?> wrote:</b><br />
<a href="#comment-<?php comment_ID() ?>" title="" rel="nofollow"><?php comment_time('j. F Y') ?> at <?php comment_time() ?></a>
: <?php edit_comment_link('Edit Comment','',''); ?>
 		<?php if ($comment->comment_approved == '0') : ?>
		<em>Your comment is waiting to be approved</em>
 		<?php endif; ?>
</div>
<?php if(function_exists("MyAvatars"))
MyAvatars(); ?>
<?php comment_text() ?>
	</li>

<?php endforeach; /* end for each comment */ ?>
	</ol>

<?php else : // this is displayed if there are no comments so far ?>

<?php if ('open' == $post->comment_status) : ?>
	<!-- If comments are open, but there are no comments. -->
	<?php else : // comments are closed ?>

	<!-- If comments are closed. -->
<p class="nocomments">Comments are closed.</p>

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

	<div class="indicator">&bull;</div>	
	<h2 id="respond">Leave a comment</h2>

<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p>You need to <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>" rel="nofollow">log in</a> to comment.</p>

<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( $user_ID ) : ?>

<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>

<?php else : ?>

<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="40" tabindex="1" />
<label for="author"><small>Name <?php if ($req) echo "(required)"; ?></small></label></p>

<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="40" tabindex="2" />
<label for="email"><small>Email <?php if ($req) echo "(required)"; ?></small></label></p>

<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="40" tabindex="3" />
<label for="url"><small>Website</small></label></p>

<?php endif; ?>

<p><small><strong>XHTML</strong> - You can use:<?php echo allowed_tags(); ?></small></p>

<p><textarea name="comment" id="comment" cols="80" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>

<?php do_action('comment_form', $post->ID); ?>

</form>

<?php endif; // If registration required and not logged in ?>

<?php endif; // if you delete this the sky will fall on your head ?>
