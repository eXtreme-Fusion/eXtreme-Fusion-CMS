{php} opentable(__('Members List')) {/php}
	{if $users}
		<div class="tbl2">
			<div class="grid_4 bold">{i18n('Username')}</div>
			<div class="grid_2 bold">{i18n('User Level')}</div>
			<div class="grid_3 bold">{i18n('Groups')}</div>
			<div class="grid_3 bold">{i18n('Last visit')}</div>
		</div>
		{section=users}
			<div class="tbl {$users.row}">
				<div class="grid_4">{$users.link}</div>
				<div class="grid_2">{$users.role}</div>
				<div class="grid_3">{$users.roles}</div>
				<div class="grid_3">{$users.visit}</div>
			</div>
		{/section}
	{else}
		<div class="tbl2">
			<div class="sep_2 center">{i18n('No members have been found')}</div>
			<div class="clear"></div>
		</div>
	{/if}
	<div class="tbl center">
		<div class="buttons-bg">&nbsp;</div>
			{if $show_all}
				<div><a href='{$link}'>{i18n('Show all')}</a></div>
			{/if}
			{section=sort}
				<a href='{$sort.link}'{if $sort.sel} class="red bold"{/if}>{$sort.disp}</a>
			{/section}
		<div class="buttons-bg">&nbsp;</div>
	</div>
	{$page_nav}
{php} closetable() {/php}