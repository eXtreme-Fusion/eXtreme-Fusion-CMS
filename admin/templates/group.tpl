<h3>{$SystemVersion} - {if $group.title}{i18n('Edit group')}{else}{i18n('Add group')}{/if}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="" method="post">
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="title">{i18n('Name:')}</label></div>
		<div class="formField grid_5"><input type="text" name="title" value="{$group.title}" id="title"></div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="description">{i18n('Description:')}</label></div>
		<div class="formField grid_5"><input type="text" name="description" value="{$group.description}" id="description"></div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="format">{i18n('Format')}:</label></div>
		<div class="formField grid_5"><input type="text" name="format" value="{$group.format}" id="format"></div>
	</div>
	<div class="tbl2">
		<div class="grid_3 formLabel sep_1">{i18n('Show on team page:')}</div>
		<div class="grid_5 formField">
			<label><input type="radio" name="team" value="1"{if $group.team == 1} checked="checked"{/if} /> {i18n('Yes')}</label>
			<label><input type="radio" name="team" value="0"{if $group.team == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}"></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}"></strong></span>
		</div>
	</div>
	{if $group.id > 1}
	<h4>{i18n('Permissions')}</h4>
	<div class="uiTable" id="permissions">
		<div class="tbl2">
			<div class="grid_1"><input type="checkbox" id="selectAll" class="tip" title="{i18n('Select all')}"></div>
			<div class="grid_11 bold">{i18n('Select all')}</div>
		</div>
		<div class="tbl2" id="wildcards">
			<div class="grid_1"><input type="checkbox" id="wildcard" name="permissions[all]"{if $permission && in_array('*', $permission)} checked="checked"{/if}></div>
			<div class="grid_11"><label for="wildcard"><strong>{i18n('All permissions')}</strong><p>{i18n('About all permissions')}</p></label></div>
		</div>
		{section=sections}
			<div class="tbl1">
				<div class="grid_1 bold">{$sections}</div>
			</div>
			{section=permissions}
				<div class="tbl2">
					<div class="grid_1"><input type="checkbox" id="permission_{$permissions.name}" name="permissions[{$permissions.name}]"{if $permission && in_array($permissions.name, $permission)} checked="checked"{/if}></div>
					<div class="grid_11"><label for="permission_{$permissions.name}">{$permissions.description}</label></div>
				</div>
			{/section}
		{/section}
	</div>
	{/if}
</form>