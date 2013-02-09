<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Contact')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl1">
		<div class="formLabel sep_1 grid_4"><label for="title">{i18n('Nazwa')}:</label><small>Przykładowo: Dział techniczny</small></div>
		<div class="formField grid_6"><input type="text" name="title" id="title" value="{$contact.title}" /></div>
	</div>
	
	<div class="tbl2">
		<div class="formLabel sep_1 grid_4"><label for="email">{i18n('Adres e-mail odbiorcy')}:</label><small>Adres e-mail, na który będą wysyłane wiadomości</small></div>
		<div class="formField grid_6"><input type="text" name="email" id="email" value="{$contact.email}" /></div>
	</div>

	
	<div class="tbl1">
		<div class="formLabel sep_1 grid_4"><label for="Description">{i18n('Opis nad formularzem')}:</label><small>Przykładowo: Aby skontaktować się z Działem technicznym, wypełnij poniższy formularz.</small></div>
		<div class="formField grid_6"><textarea name="description" id="Description">{$contact.description}</textarea></div>
	</div>
	
	<div class="tbl2">
		<div class="formLabel sep_1 grid_4"><label for="Value">{i18n('Domyślna wartość')}:</label><small>W polu do wpisania wiadomości można zamieścić wytyczne, które nadawca powinien uwzględnić. Przykładowo: zainteresowania, stanowisko pracy, używany system CMS.</small></div>
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
	<h4>Aktualne formularze</h4>
	<div class="tbl2">
		<div class="sep_1 grid_3 bold">Tytuł</div>
		<div class="grid_3 bold">Adres e-mail</div>
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