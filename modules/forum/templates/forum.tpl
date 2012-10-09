{php} opentable(__('Strona główna forum')) {/php}
<div class="tbl2 text-padding-h3">
	{if $config.development}<div class="error">{$config.developmentMessage}</div>{/if}
	<span class="bold">Jesteś tutaj: </span>
	{section=Breadcrumb}
		<span class="bold">{$Breadcrumb.name}</span> >>
	{/section}
</div>
	<div class="tbl2 formated_text">
		Elementy w tym zbiorze:
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