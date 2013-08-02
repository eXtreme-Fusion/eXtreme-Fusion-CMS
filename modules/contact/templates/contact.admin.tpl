<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Contact')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl1">
		<div class="formLabel sep_1 grid_4"><label for="title">{i18n('Name:')}</label><small>{i18n('For example: Technical department.')}</small></div>
		<div class="formField grid_6"><input type="text" name="title" id="title" value="{$contact.title}" /></div>
	</div>
	
	<div class="tbl2">
		<div class="formLabel sep_1 grid_4"><label for="email">{i18n('Reciever\'s e-mail address:')}</label><small>{i18n('E-mail address to which messages will be sent.')}</small></div>
		<div class="formField grid_6"><input type="text" name="email" id="email" value="{$contact.email}" /></div>
	</div>

	
	<div class="tbl1">
		<div class="formLabel sep_1 grid_4"><label for="Description">{i18n('Description above the form:')}</label><small>{i18n('For example: To contact the Technical department please fill out the form below.')}</small></div>
		<div class="formField grid_6"><textarea name="description" id="Description">{$contact.description}</textarea></div>
	</div>
	
	<div class="tbl2">
		<div class="formLabel sep_1 grid_4"><label for="Value">{i18n('Default value:')}</label><small>{i18n('In the box of message content you can place guidelines which the sender should include. For example: interests, job, experience.')}</small></div>
		<div class="formField grid_6"><textarea name="value" id="Value">{$contact.value}</textarea></div>
	</div>

	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span class="save" id="SendForm_This"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
		</div>
	</div>
</form>

{if $data}
	<h4>{i18n('Existing forms')}</h4>
	<div class="tbl2">
		<div class="sep_1 grid_3 bold">{i18n('Title')}</div>
		<div class="grid_3 bold">{i18n('E-mail address')}</div>
		<div class="grid_5 bold">{i18n('Options')}</div>
	</div>
	{section=data}
		<div class="tbl {$data.row_color}">
			<div class="sep_1 grid_3">{$data.title}</div>
			<div class="grid_3">{$data.email}</div>
			<div class="grid_5">
				<a href="{$FILE_SELF}?action=edit&amp;id={$data.id}" class="tip" title="{i18n('Edit')}">
					<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
				</a>
				<a href="{$FILE_SELF}?action=delete&amp;id={$data.id}" class="tip" title="{i18n('Delete')}">
					<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
				</a>
			</div>
		</div>
	{/section}
{/if}