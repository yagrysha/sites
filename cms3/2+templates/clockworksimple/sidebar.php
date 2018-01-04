<div id="sidebar">

	<form method="get" id="searchform" action="">

		<input type="text" name="s" id="s" value="Type here..." onfocus="if (this.value == 'Type here...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Type here...';}" />
		<input type="submit" id="searchsubmit" value="Search" />

	</form>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('main_sidebar') ) : ?>

	<h4>About</h4>
	<p>This is a piece of text which you can either delete or replace with something more to your liking.</p>

	<h4>New Entries</h4>
	<ul class="new"><?php get_archives('postbypost', 5); ?></ul>

	<h4>Tags</h4>
	<?php wp_tag_cloud('number=15'); ?>

<?php endif; ?>

<div style="clear: both;"></div>

	<div class="rightbar">

	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sub_sidebar_right') ) : ?>

		<h4>Archives</h4>
		<ul><?php wp_get_archives('type=monthly&limit=5'); ?></ul>

	<?php endif; ?>

	</div>

	<div class="leftbar">

	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sub_sidebar_left') ) : ?>

	<h4>Categories</h4>
 	<ul><?php wp_list_categories('title_li='); ?></ul>

	<?php

	if(is_front_page()) { ?>

		<h4>Meta</h4>
		<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<?php wp_meta(); ?>
		</ul>

	<?php } ?>

	<?php endif; ?>

	</div>
	
	<div style="clear: both;"></div>
	
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('main_sidebar_bottom') ) : ?>

		<h4>Links</h4>
		<ul><?php get_links(-1, '<li>', '</li>', ' - '); ?></ul>
		
	<?php endif; ?>

</div>
