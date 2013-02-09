{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	<h3>{i18n('Routing')}</h3>
	
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="ParamSeparator">{i18n('Separator of parameters:')}</label><small>{i18n('The default is `-`.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="param_sep" value="{$param_sep}" id="ParamSeparator" class="num_10" maxlength="10" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="MainSeparator">{i18n('Separator of links:')}</label><small>{i18n('The default is `/`.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="main_sep" value="{$main_sep}" id="MainSeparator" class="num_10" maxlength="10" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="UrlExtension">{i18n('Extensions of links:')}</label><small>{i18n('The default is `.html`.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="url_ext" value="{$url_ext}" id="UrlExtension" class="num_10" maxlength="10" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="LogicExtension">{i18n('Extensions of logic:')}</label><small>{i18n('The default is `.php`.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="logic_ext" value="{$logic_ext}" id="LogicExtension" class="num_10" maxlength="10" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="TplExtension">{i18n('Extensions of templates:')}</label><small>{i18n('The default is `.tpl`.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="tpl_ext" value="{$tpl_ext}" id="TplExtension" class="num_10" maxlength="10" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel">{i18n('Dozwolone rozszerzenie plików:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="ext_allowed" value="1"{if $ext_allowed == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="ext_allowed" value="0"{if $ext_allowed == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	
	<div class="tbl Buttons">
		<div class="grid_2 center button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
		</div>
		<div class="grid_2 center button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
		</div>
	</div>
</form>