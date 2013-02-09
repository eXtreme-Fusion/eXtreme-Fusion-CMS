{if $preview}
	<h3 class="ui-corner-all">Podgląd panelu <span class="italic">{$name}</span></h3>
	<div class="tbl">{$preview}</div>
{/if}

<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Panel editor')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="panel_name">{i18n('Name:')}</label></div>
			<div class="formField grid_7"><input type="text" name="panel_name" id="panel_name" value="{$name}" /></div>
		</div>
		{if $is_file == 0}
			<div class="tbl2">
				<div class="formLabel sep_1 grid_3"><label for="panel_content">{i18n('Content:')}</label></div>
				<div class="formField grid_7"><textarea name="panel_content" id="panel_content" rows="5">{$content}</textarea></div>
			</div>
		{/if}
		{if $action != 'edit'}
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="panel_side">{i18n('Place:')}</label></div>
				<div class="formField grid_7">
					<select name="panel_side" id="panel_side">
						<option value="1"{if $side == 1} selected="selected"{/if}>{i18n('Left side')}</option>
						<option value="4"{if $side == 4} selected="selected"{/if}>{i18n('Right side')}</option>
						<option value="2"{if $side == 2} selected="selected"{/if}>{i18n('Top')}</option>
						<option value="3"{if $side == 3} selected="selected"{/if}>{i18n('Bottom')}</option>
					</select>
				</div>
			</div>
		{/if}
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="panel_access">{i18n('Visible for:')}</label></div>
			<div class="formField grid_7">
				<select name="panel_access[]" multiple id="panel_access" class="select-multi" size="5">
					{section=access}
						<option value="{$access.value}"{if $access.selected} selected="selected"{/if}>{$access.display}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl Buttons">
			<div class="center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-c">
				<input type="hidden" name="preview" value="yes" id="Preview" />
				<span class="preview" id="SendForm_This"><strong>{i18n('Preview')}<img src="{$ADDR_ADMIN_ICONS}pixel/zoom-in.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<input type="hidden" name="save" value="yes" id="save" />
				<span class="save" id="SendForm_This" ><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>

</form>