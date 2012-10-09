{php} opentable(__('Points & Rank')); {/php}
	{if $message}<div class="{$class}">{$message}</div>{/if}
	{if $config.development}<div class="error">{$config.developmentMessage}</div>{/if}
	<div class="tbl">
		<div class="center grid_10 sep_1 red">{i18n('Points:&nbsp;')}{$points}<br/>{i18n('Rank:&nbsp;')}{$ranks}</div>
	</div>
	{section=history}
		<div class="tbl margin100">
			<div class="grid_10 center"><hr /></div>
		</div>
		<div class="tbl margin100">
			<div class="grid_10"><small>{i18n('Date added:&nbsp;')}{$history.date}</small></div>
		</div>
		<div class="tbl margin100">
			<div class="grid_10">{$history.text}</div>
		</div>
		<div class="tbl margin100">
			<div class="grid_10 center"><hr /></div>
		</div>
	{/section}
	{$page_nav}
	<form method="post" action="{$URL_REQUEST}" class="center">
		<input type="submit" name="clear_history" value="{i18n('Clear History')}" class="button"/>
	</form>
{php} closetable(); {/php}
