<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>{if $page.title}{$page.title}{else}{$conf.site_title}{/if}</title>
<meta name="description" content="{if $page.meta_description}{$page.meta_description}{else}{$conf.site_description}{/if}" />
<meta name="keywords" content="{if $page.keywords}{$page.keywords}{else}{$conf.site_keywords}{/if}" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="revisit-after" content="7 days">
<meta name="robots" content="index,follow,all">
<link href="/css/style.css" rel="stylesheet" type=text/css>
{*<script type="text/javascript" src="/js/prototype.js"></script>*}
</head>


<body>
<center>
	<div id="main-contain">
		<div id="main-contain-left"></div>
		<div id="main-contain-center">
			<div id="top-image">
				<div id="logo">
					<a href="/">Nail <span>Fungus Products </span><b>Reviews</b></a>
					Health related news, commentary and insights
				</div>
				<div id="top-menu">
					<div class="top-menu-select"><span><b>Nail Fungus Home</b></span></div>
					<a href="/page/treatment.html">Nail Fungus Treatment reviews</a>
					<a href="/page/guide.html">Nail Fungus Guide</a>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
				<div id="textarea">
					<div id="textarea-bgr">
						<div id="textarea-top">
						{include file=$template}
						</div>
						<img src="/img/textarea-bot.gif" alt="" /><br />
					</div>
				</div>
				<div id="right-col">
					<div class="title-inn">
						<div class="title">About</div>
					</div>
					<div class="swin">
						<img src="/img/swin-top.gif" alt="" /><br />
						<p>{$page2.text}

							<br /><br />
							<img src="/img/pic-women.gif" alt="" /><a href="/rss"><img src="/img/rss.gif" alt="" class="rss" /></a>
						</p>
						<img src="/img/swin-bot.gif" alt="" /><br />
					</div>

<div class="title-inn-bg">
						<div class="title">Guides</div>
					</div>
					<div class="swin">
						<img src="/img/swin-top.gif" alt="" /><br />
						<div class="submenu">
						{foreach from=$categorys item=i}
						{*<a href="/article/{$i.alias}.html">{$i.name}</a>*}
							{assign var=pp value=$menu[$i.id]}
							{if $pp}
							
							{foreach from=$pp item=ii}
							<a href="/article/{$ii.alias}.html">{$ii.name}</a>
						{/foreach}
							{/if}
						{/foreach}
						</div>
						<img src="/img/swin-bot.gif" alt="" /><br />
					</div>
					
					<div class="title-inn-bg">
						<div class="title">Categories</div>
					</div>
					<div class="swin">
						<img src="/img/swin-top.gif" alt="" /><br />
						<div class="submenu">
						{foreach from=$categorys item=i}
							<a href="/articles/{$i.alias}.html">{$i.name}</a>
						{/foreach}
						</div>
						<img src="/img/swin-bot.gif" alt="" /><br />
					</div>
					<div class="title-inn-bg">
						<div class="title">Blogroll</div>
					</div>
					<div class="swin">
						<img src="/img/swin-top.gif" alt="" /><br />
						<p>{$page3.text}

							<br />
						</p>
						<img src="/img/swin-bot.gif" alt="" /><br />
					</div>
					<div class="title-inn-bg">
						<div class="title">Meta</div>
					</div>
					<div class="swin">
						<img src="/img/swin-top.gif" alt="" /><br />
						<div class="submenu">
							<a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a>
<a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a>
<a href="/rss">E-RSS</a><a href="/rss">C-RSS</a>
						</div>
						<img src="/img/swin-bot.gif" alt="" /><br />
					</div>

				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div id="main-contain-right"></div>
		<div class="clear"></div>
	</div>

	<div id="footer">
		<div id="footer-width">
			<div id="footer-left"></div>
			<div id="footer-center">
				<div class="b-links">
					<a href="/page/about_us.html">About Us</a>|
					<a href="/page/policy.html">Privacy Policy</a>|
					<a href="/page/terms.html">Terms of Use</a>|
					<a href="/page/contact_us.html">Contact Us</a>|
					<a href="/page/sitemap.html">Site Map</a>
				</div>
				<div class="cpr">All content &copy; 2005-2008 of the original author, All Rights Reserved.</div>
			</div>
			<div id="footer-right"></div>
			<div class="clear"></div>
		</div>
	</div>
</center>
</body>
</html>