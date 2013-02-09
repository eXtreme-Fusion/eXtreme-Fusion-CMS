{if $type == 'permission'}
	<h3>{$SystemVersion} - {if $permission.description}{i18n('Edit permission')}{else}{i18n('Add new permission')}{/if}</h3>
	{if $message}<div class="{$class}">{$message}</div>{/if}

	<form id="This" action="{$URL_REQUEST}" method="post">
		<div class="tbl1">
			<div class="formLabel sep_1 grid_2"><label for="name">{i18n('Identyfikator:')}</label></div>
			<div class="formField grid_7"><input type="text" name="name" value="{$permission.name}" id="name"></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_2"><label for="description">{i18n('Description:')}</label></div>
			<div class="formField grid_7"><input type="text" name="description" value="{$permission.description}" id="description"></div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_2"><label for="section">{i18n('Section:')}</label></div>
			<div class="formField grid_7"><select name="section" id="section">{section=sections}<option value="{$sections.id}"{if $permission.section == $sections.id} selected="selected"{/if}>{$sections.description}</option>{/section}</select></div>
		</div>
		<div class="tbl Buttons">
			<div class="center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}"></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<span class="save" id="SendForm_This" ><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	</form>
{elseif $type == 'section'}
	<h3>{$SystemVersion} - {if $section}{i18n('Edit section')}{else}{i18n('Add new section')}{/if}</h3>
	{if $message}<div class="{$class}">{$message}</div>{/if}
	<form id="This" action="{$URL_REQUEST}" method="post">
		<div class="tbl1">
			<div class="formLabel sep_1 grid_5"><label for="name">{i18n('Identyfikator:')}</label></div>
			<div class="formField grid_6"><input type="text" name="name" value="{$section.name}" id="name"></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_4"><label for="description">{i18n('Description:')}</label></div>
			<div class="formField grid_7"><input type="text" name="description" value="{$section.description}" id="description"></div>
		</div>
		<div class="tbl Buttons">
			<div class="center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}"></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<span class="save" id="SendForm_This" ><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	</form>
{/if}