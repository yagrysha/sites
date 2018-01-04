{if $menu}<li>
		<ul>
{foreach from=$menu item=i}
						<li{if $_request.id==$i.id} style="font-weight:bold;"{/if}><a href="/page/{$i.alias}.html">{$i.name}</a></li>
{/foreach}
</ul></li>{/if}