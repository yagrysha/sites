<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="{if $page.description}{$page.description}{else}{$_conf.site_description}{/if}" />
<meta name="keywords" content="{if $page.keywords}{$page.keywords}{else}{$_conf.site_keywords}{/if}" />
<title>{if $page.title}{$page.title}{else}{$_conf.site_title}{/if}</title>
<link rel="stylesheet" type="text/css"  href="/css/style.css" />
</head>
<body>
<div id="wrapper">
	<div id="wrapper2">
		<div id="header">
			<div id="logo">
				<h1>{$_conf.site_name}</h1>
			</div>
			{include_component name="page/mainmenu"}
		</div>
		<!-- end #header -->
		<div id="page">
		{include file=$template}
			<!-- end #content -->
			<div id="sidebar">
				<ul>
				{include_component name="page/rightblock"}
				{include_component name="page/rightmenu" id=$page.id pid=$page.pid}	
				</ul>
			</div>
			<!-- end #sidebar -->
			<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #page -->
	</div>
	<!-- end #wrapper2 -->
	<div id="footer">
		<p>(c) 2009 {$smarty.const.DOMAINN}</p>
	</div>
</div>
<!-- end #wrapper -->
</body>
</html>