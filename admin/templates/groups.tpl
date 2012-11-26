<h3>{$SystemVersion} - {i18n('Groups permissions')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl2">
		<div class="right">
			<a href="{$FILE_SELF}?action=add"><strong>{i18n('Add group')}</strong></a>
		</div>	
	</div>
	<div class="uiTable">
		<div class="tbl1">
			<div class="grid_6 bold">{i18n('Name:')}</div>
			<div class="grid_3 bold">{i18n('Show on team page:')}</div>
			<div class="grid_3 bold">{i18n('Options:')}</div>
		</div>
		{section=groups}
			<div class="tbl{$data.row_color}">
				<div class="grid_6">
					{$groups.title}<p>{$groups.description}</p>
				</div>
				<div class="grid_3">
					{if $groups.team == 1}
						{i18n('Yes')}
					{else}
						{i18n('No')}
					{/if}
				</div>
				<div class="grid_3">
					<a href="{$FILE_SELF}?action=edit&id={$groups.id}" class="tip" title="{i18n('Edit')}"><img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}"></a>
					{if ! $groups.is_system}
					<a href="{$FILE_SELF}?action=delete&id={$groups.id}" class="tip confirm_button" title="{i18n('Delete')}"><img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}"></a>
					{/if}
				</div>
			</div>
		{/section}
	</div>
</form>