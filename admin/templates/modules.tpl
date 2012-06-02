<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Modules')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

{if $mod}
	<form id="This" method="post" action="{$URL_REQUEST}">
		<div class="tbl2">
			<div class="sep_1 grid_2 bold">{i18n('Name:')}</div>
			<div class="grid_5 bold">{i18n('Description:')}</div>
			<div class="grid_1 bold">{i18n('Version:')}</div>
			<div class="grid_3 bold">{i18n('Author:')}</div>
		</div>
		{section=mod}
			<div class="tbl {$mod.row_color}">
				<div class="sep_1 grid_2">
					<input type='checkbox' name='mod[]' id='{$mod.id}' value='{$mod.value}' {if $mod.installed}checked='checked'{/if}/>
					{$mod.label}
					{if $mod.is_to_update}
						<p style="margin-left:27px;" class="red">
							<input type="checkbox" name="update[]" value="{$mod.value}" />{i18n('Update')}
						</p>
					{/if}
				</div>
				<div class="grid_5">{$mod.desc}</div>
				<div class="grid_1">{$mod.version}</div>
				<div class="grid_3"><a href="{$mod.webURL}">{$mod.author}</a></div>

			</div>
		{/section}
		<div class="tbl AdminButtons">
			<div class="center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<input type="hidden" name="save" value="yes" />
				<span class="Save" id="SendForm_This" ><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	</form>
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('')}</div>
	</div>
{/if}