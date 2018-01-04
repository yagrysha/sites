<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
<channel>
<title>{if $page.title}{$page.title}{else}{$conf.site_title}{/if}</title>
<link>{$smarty.const.DOMAIN}</link>
<description>{if $page.description}{$page.description}{else}{$conf.site_description}{/if}</description>
<lastBuildDate>{$smarty.now|date_format:"%a, %d %b %Y %H:%M:%S "} +0200</lastBuildDate>
{foreach from=$items item=i}
<item>
<title>{$i.title}</title>
<link>{$smarty.const.DOMAIN}/page/{$i.alias}.html</link>
<description>{$i.description}</description><pubDate>{$smarty.now|date_format:"%a, %d %b %Y %H:%M:%S "}</pubDate>
<guid>{$smarty.const.DOMAIN}/page/{$i.alias}.html</guid>
</item>
{/foreach}
</channel>
</rss>
