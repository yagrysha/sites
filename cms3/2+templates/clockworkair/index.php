<?php

	get_header();
	get_sidebar();

?>

<div id="content">

 	<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

	<div class="entry">

		<?php if(is_single() || is_page()) { ?>

			<h1><?php the_title(); ?></h1>

		<?php } else { ?>

			<h1><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>

		<?php } ?>

		<?php if(!is_page()) { ?>

		<div class="metadata">

			<div class="m_date alignleft"><?php the_time('F j, Y'); ?></div>
			<div class="m_author alignleft">by <?php the_author(); ?></div>
			<div class="m_comments alignleft"><?php comments_popup_link('No comments yet', '1 Comment', '% Comments' ); ?></div>
			
			<div style="clear: left;"></div>
			
		</div>
			
		<?php } ?>

		<?php
		if(is_search()) {
			the_excerpt();
		} else {
			the_content('Read more &raquo;');
		}
		?>
		
		Posted in <?php the_category(', ') ?>
		<?php the_tags('<br />Tags: ', ', ', '<br />'); ?>
		<?php edit_post_link('Edit', ' &#124; ', ''); ?>

		<?php if(is_single()) {	?>

			<div class="comments-template"><?php comments_template(); ?></div>

		<?php } ?>

	</div>

	<?php endwhile; ?>

		<div class="navigation">

			<div class="alignleft"><?php next_posts_link('&laquo; Older posts') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer posts &raquo;') ?></div>

		</div>

		<div class="navigation">
	
			<?php previous_post_link('&laquo; %link') ?> <?php next_post_link(' %link &raquo;') ?>

		</div>

	

	<?php else : ?>

		<h2><?php _e('Not Found'); ?></h2>

	<?php endif; ?>

</div>

<?php get_footer(); ?>
