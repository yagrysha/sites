<h1>{$page.name}</h1>
			<div id="text-block">
				<div id="ico-right">
					<p>
						{$page.text}
					</p>
					{foreach from=$menu item=i}
                    <a  href="/page/{$i.alias}.html">{$i.name}</a><br />
                    {if $i.description}<p>{$i.description}</p><br />{/if}
                    {/foreach}
				</div>
			</div>

