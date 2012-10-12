<h3>{$SystemVersion} - {i18n('Permissions')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<div class="tbl2">
	<div class="sep_1 grid_8">{i18n('Remember')}</div>
</div>
<div class="tbl2 grid_10 sep_1">
	<span class="box-left">
		<a href="{$FILE_SELF}?action=add&amp;type=section"><strong>{i18n('Add new section')}</strong></a>
	</span>
	<span class="box-right sep_1">
		<a href="{$FILE_SELF}?action=add&amp;type=permission"><strong>{i18n('Add new permission')}</strong></a>
	</span>
</div>
<div class="clear"></div>
<div class="uiTable" id="permissions">
	<div class="tbl2">
		<div class="grid_9 bold">{i18n('Description:')}</div>
		<div class="grid_3 bold">{i18n('Options:')}</div>
	</div>
	{section=sections}
		<div class="tbl1">
			<div class="grid_9 bold">{$sections.description}</div>
			{if ! $sections.is_system}
				<div class="grid_3">
					<a href="{$FILE_SELF}?action=edit&amp;type=section&amp;id={$sections.id}" class="tip" title="{i18n('Edit')}"><img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" /></a>
					<a href="{$FILE_SELF}?action=delete&amp;type=section&amp;id={$sections.id}" class="tip confirm_button" title="{i18n('Delete')}"><img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" /></a>
				</div>
			{/if}
		</div>
		{section=permissions}
			<div class="tbl{$data.row_color}">
				<div class="grid_9">{$permissions.description}</div>
				<div class="grid_3">
					{if ! $permissions.is_system}
					<a href="{$FILE_SELF}?action=edit&amp;type=permission&amp;id={$permissions.id}" class="tip" title="{i18n('Edit')}"><img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" /></a>
					<a href="{$FILE_SELF}?action=delete&amp;type=permission&amp;id={$permissions.id}" class="tip confirm_button" title="{i18n('Delete')}"><img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" /></a>
					{/if}
				</div>
			</div>
		{/section}
	{/section}
</div>