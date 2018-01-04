			<div id="menu">
				<ul>
					<li><a href="/">Homepage</a></li>
					{foreach from=$menu item=i}
						<li><a href="/{$i.alias}.html">{$i.name}</a></li>
					{/foreach}
				</ul>
			</div>