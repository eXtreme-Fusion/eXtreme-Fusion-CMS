{php} opentable(__('Strona główna forum')) {/php}
	<div class="tbl2 formated_text">
		{section=drzewko}
			{if $drzewko.new}
				<ul>
			{elseif $drzewko.end}
				</ul>
			{/if}
			<li>{$drzewko.name}</li>
		{/section}
		<div class="info">{i18n('Tu będzie znajdować się forum.')}</div>
		<div class="error">Czy element ma potomstwo? - <strong>{$tree}</strong></div>
		{section=children}
			{if $children.new}
				<ul>
			{elseif $children.end}
				</ul>
			{/if}
			<li>{$children.name}</li>
		{/section}
	</div>	
{php} closetable() {/php}