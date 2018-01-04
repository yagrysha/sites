
<h1>{$page.name}</h1><p>
						{$page.text}
					</p>
							
{foreach from=$menu[$page.id] item=i}
							<h1><a href="/article/{$i.alias}.html" style="color:#67AA0D;">{$i.name}</a></h1>
							<p>{$i.description}	</p>
							<div class="posted"><b>Posted in</b> <a href="/articles/{$page.alias}.html">{$page.name}</a> | <a href="/article/{$i.alias}.html">No Comments »</a></div>
							<div class="tx-sep"></div>
{/foreach}
