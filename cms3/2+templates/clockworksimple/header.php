<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">

<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_get_archives('type=monthly&format=link'); ?>
<?php wp_head(); ?>
</head>
<body>

<a name="topofpage"></a>
<div id="wrapper">

	<div id="header">

		<div class="topmenu">

		<ul>
			<li><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></li>
			<?php wp_list_pages('title_li=&depth=1'); ?>
			<li><a href="<?php bloginfo('rss2_url') ?>" rel="noindex" class="feed">Subscribe</a></li>
		</ul>

		</div>

		<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
		<div class="descri"><?php bloginfo('description'); ?></div>

	</div>

	<div id="teaser">

	<?php

	// this is the breadcrumb

	if ( is_404() || is_category() || is_single () || is_tag() || is_month() || is_year() || is_search() || is_page() || is_author() ) {

		$clockworkbreadcrumb = "<a href='" . get_bloginfo('url') . "'>" . get_bloginfo('name') . "</a> &raquo; ";

		if(is_404()) 		{ $clockworkbreadcrumb .= "Error: Page not found"; }
		if(is_category())	{ $clockworkbreadcrumb .= "Posts in <strong>'" . single_cat_title('', false) . "'</strong> category"; }
		if(is_single()) 	{ $clockworkbreadcrumb .= "Page <strong>'" . single_post_title('', false) . "'</strong>"; }
		if(is_tag()) 		{ $clockworkbreadcrumb .= "Posts for tag <strong>'" . single_tag_title('', false) . "'</strong>"; }
		if(is_month()) 		{ $clockworkbreadcrumb .= "Archive of <strong>'" . get_the_time('M, Y') . "'</strong>"; }
		if(is_year()) 		{ $clockworkbreadcrumb .= "Archive of the year <strong>'" . get_the_time('Y') . "'</strong>"; }
		if(is_search()) 	{ $clockworkbreadcrumb .= "Search results for <strong>'" . $_GET['s'] . "'</strong>"; }
		if(is_page()) 		{ $clockworkbreadcrumb .= "Page <strong>'" . single_post_title('', false) . "'</strong>"; }
		if(is_author()) 	{ $clockworkbreadcrumb .= "List of Author's posts"; }

	} else { 

		$clockworkbreadcrumb  = "This is the place to enter a short introduction and maybe encourage people to <a href='";
		$clockworkbreadcrumb .= get_bloginfo('url') . "/about/";
		$clockworkbreadcrumb .= "' rel='nofollow'>read more about the author of the blog</a> ?";
	
	} 

	echo $clockworkbreadcrumb;

	?>

	</div>
