<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Newsletter')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	<div class="tbl1">
		<div class="formLabel grid_2 sep_1"><label for="subject">Temat:</label></div>
		<div class="formField grid_7"><input type="text" name="subject" id="subject" class="textbox" style="width:368px;"/></div>
	</div>
	<div class="tbl2">
		<div class="formLabel grid_2 sep_1"><label for="message">Wiadomość:</label></div>
		<div class="formField grid_7"><textarea name="message" cols="50" rows="7" id="message" class="textbox"></textarea></div>
	</div>
	<div class="tbl Buttons">
		<div class="grid_2 center button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
		<div class="grid_2 center button-r">
			<input type="hidden" name="send" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
</form>
{if $data}
	<h4>{i18n('Mailing list')}</h4>
	<div class="clear"></div>
	<div>	
		<span class="box-right">
			<a href="{$FILE_SELF}?action=delete_all" class="tip confirm_button" title="{i18n('Delete all')}">{i18n('Delete all')}</a>
		</span>
	</div>
	<div class="tbl2">
		<div class="sep_1 grid_2 bold center">{i18n('Date:')}</div>
		<div class="grid_4 bold center">{i18n('Email:')}</div>
		<div class="grid_3 bold center">{i18n('IP:')}</div>
		<div class="grid_1 bold center">{i18n('Options')}</div>
	</div>
	{section=data}
		<div class="tbl {$data.row_color}">
			<div class="sep_1 grid_2 center">{$data.datestamp}</div>
			<div class="grid_4 center">{$data.email}</div>
			<div class="grid_3 center">{$data.ip}</div>
			<div class="grid_1 center">
				<a href="{$FILE_SELF}?action=delete&amp;id={$data.id}" class="tip confirm_button" title="{i18n('Delete')}">
					<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
				</a>
			</div>
		</div>
	{/section}
	<div class="tbl Buttons">
		<div class="center grid_2 button-c">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
	</div>
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('There are no data.')}</div>
	</div>
	<div class="tbl Buttons">
		<div class="center grid_2 button-c">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
	</div>
{/if}