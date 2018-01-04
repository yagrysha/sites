<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>{if $page.title}{$page.title}{else}{$conf.site_title}{/if}</title>
<meta name="description" content="{if $page.description}{$page.description}{else}{$conf.site_description}{/if}" />
<meta name="keywords" content="{if $page.keywords}{$page.keywords}{else}{$conf.site_keywords}{/if}" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="revisit-after" content="7 days">
<meta name="robots" content="index,follow,all">
<link href="/css/style.css" rel="stylesheet" type=text/css>

{*<script type="text/javascript" src="/js/prototype.js"></script>*}
</head>


<body>
<center>
<div id="contain">
	<div id="logo-inn">
		<div id="logo">
			<a href="/" class="logo-lnk">Acne-Site<span id="logo-tx">-Logo</span><span id="logo-tx2">.com</span></a>
		</div>
	</div>

	<table cellspacing="0" cellpadding="0" id="content-table">
	  <tr valign="top">
	    <td id="left-row">
		<div id="mnu-l-area">
			<div id="mnu-l-ttl">Site Menu</div>
			<div id="mnu-l-pad">
				<a class="menu-lnk" href="/">Home</a>
				<img src="/img/mnu-sep.gif" alt="" /><br />
				<a class="menu-lnk" href="/page/basics.html">Acne Basics</a>
				<img src="/img/mnu-sep.gif" alt="" /><br />
				<a class="menu-lnk" href="/page/top.html">Top 5 Products</a>
				<img src="/img/mnu-sep.gif" alt="" /><br />
				<a class="menu-lnk" href="/page/tips.html">Acne Removal Tips</a>
				<img src="/img/mnu-sep.gif" alt="" /><br />
				<a class="menu-lnk" href="/page/guidelines.html">Herbal Guidelines</a>
				<img src="img/mnu-sep.gif" alt="" /><br />
				<a class="menu-lnk" href="/page/links.html">Retailer Links</a>
				<img src="img/mnu-sep.gif" alt="" /><br />
				<a class="menu-lnk" href="/page/faq.html">FAQ</a>
				<img src="img/mnu-sep.gif" alt="" /><br />
				<a class="menu-lnk" href="/page/newsletter.html">Newsletter</a>
				<img src="img/mnu-sep.gif" alt="" /><br />
			</div>
			<img src="img/mnu-l-bot.gif" alt="" /><br />
		</div>
		<div id="mnu-l-area">
			<div id="mnu-l-ttl">Featured Merchant</div>
			<div id="mnu-l-pad">
				<center><a href="{$conf.link1}"><img src="/img/acne-ban.gif" alt="" /></a><br /></center>
			</div>
			<img src="/img/mnu-l-bot.gif" alt="" /><br />
		</div>
		<div id="mnu-l-area">
			<div id="mnu-l-ttl">Newsletter</div>
			<div id="mnu-l-pad" style="color: #445A7B">
			{$right.text}
			{*<a href="/"><img src="/img/letter-ico.gif" alt="Sign Up" align="left" /></a>
				<a href="/">Sign-up</a> for updates on new acne treatments, ranking changes, and skin care tips!
				*}
			</div>
			<img src="/img/mnu-l-bot.gif" alt="" /><br />
		</div>
	    </td>
	    <td id="center-row">
		{include file=$template}
 	    </td>
	    <td id="right-row">
		<div id="mnu-r-area">
			<div id="mnu-r-ttl">ADS</div>
			<div id="mnu-r-pad">
				<center><a href="{$conf.link2}"><img src="/img/acne-ban.gif" alt="" /></a><br /></center>
			</div>
			<img src="/img/mnu-l-bot.gif" alt="" /><br />
		</div>
		<div id="mnu-r-area">
			<div id="mnu-r-ttl">Site Menu</div>
			<div id="mnu-r-pad">
				<a class="menu-lnk" href="/">Home</a>
				<img src="/img/mnu-sep.gif" alt="" /><br />
				<a class="menu-lnk" href="/page/basics.html">Acne Basics</a>
				<img src="/img/mnu-sep.gif" alt="" /><br />
				<a class="menu-lnk" href="/page/top.html">Top 5 Products</a>
				<img src="/img/mnu-sep.gif" alt="" /><br />
				<a class="menu-lnk" href="/page/tips.html">Acne Removal Tips</a>
				<img src="/img/mnu-sep.gif" alt="" /><br />
			</div>
			<img src="/img/mnu-r-bot.gif" alt="" /><br />
		</div>
	    </td>
	  </tr>
	  <tr valign="top">
	    <td align="center" colspan="3">
		<img src="/img/txar-bot.gif" alt="" /><br />
	    </td>
	  </tr>
	</table>
	<div id="cpr-block">
		<div id="cpr-l">
			<div id="cpr-r">
		 		<a class="bot-lnk" href="/">Home Page</a>|
		 		<a class="bot-lnk" href="/page/basics.html">Acne Basics</a>|
		 		<a class="bot-lnk" href="/page/top.html">Top 5 Products</a>|
		 		<a class="bot-lnk" href="/page/tips.html">Acne Removal Tips</a>|
		 		<a class="bot-lnk" href="/page/guidelines.html">Herbal Guidelines</a>|
		 		<a class="bot-lnk" href="/page/links.html">Retailer Links</a>|
		 		<a class="bot-lnk" href="/page/faq.html">FAQ</a>|
		 		<a class="bot-lnk" href="/page/newsletter.html">Newsletter</a>
			</div>
		</div>
	</div>
	&copy; 2008 Acne-Site-Logo.com. All Rights Reserved.
</div>
</center>

</body>
</html>