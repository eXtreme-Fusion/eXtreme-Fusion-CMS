{php} opentable(__('Strona główna forum')) {/php}
	<div class="tbl2">
		{section=drzewko}
			{if $drzewko.new}
				<ul>
			{elseif $drzewko.end}
				</ul>
			{/if}
			<li>{$drzewko.name}</li>
		{/section}
		<div class="info">{i18n('Tu będzie znajdować się forum.')}</div>
	</div>	
{php} closetable() {/php}