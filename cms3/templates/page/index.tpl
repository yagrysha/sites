<h1>{$page.name}</h1><p>
						{$page.text}
						</p>
							<div class="tx-sep"></div>
{foreach from=$items item=i name=ff}
							<h1><a href="/article/{$i.alias}.html" style="color:#67AA0D;">{$i.name}</a></h1>

							<p>{$i.description}</p>
							<div class="posted"><b>Posted in</b> <a href="/articles/{$categorys[$i.pid].alias}.html">{$categorys[$i.pid].name}</a> | <a href="/article/{$i.alias}.html">Read the rest of this entry »</a></div>
							<div class="tx-sep"></div>
{/foreach}

