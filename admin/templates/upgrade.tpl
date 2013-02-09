<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Upgrade')}</h3>                           
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post"> 
	{if $ver}
		<div class="tbl1">
			<div class="sep_1 center grid_10">{i18n('A new database upgrade is available for this installation of eXtreme-Fusion.')}</div>
			<div class="grid_1">&nbsp;</div>
			<div class="sep_1 center grid_10">{i18n('Simply click Upgrade to update your eXtreme-Fusion.')}</div>
		</div>    
		<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
	{else}
		<div class="tbl1">
			<div class="formField sep_1 center grid_10">{i18n('There is no database upgrade available.')}</div>
			<div class="formField grid_1">&nbsp;</div>
		</div>
  		<div class="tbl Buttons">
			<div class="center grid_2 button-c">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
		</div>
	{/if}
</form>