<h3>{$SystemVersion} - {i18n('Settings')} &raquo; {i18n('Registration')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Enabled registration:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="enable_registration" value="1"{if $enable_registration == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="enable_registration" value="0"{if $enable_registration == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel">{i18n('E-mail verification:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="email_verification" value="1"{if $email_verification == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="email_verification" value="0"{if $email_verification == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Admin activation:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="admin_activation" value="1"{if $admin_activation == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="admin_activation" value="0"{if $admin_activation == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel">{i18n('Validation way:')}</div>
		<div class="grid_6 formField">
			<select name="validation">
				{section=validation}
					<option value="{$validation.id}"{if $validation.selected}selected="selected"{/if}>{$validation.name}</option>
				{/section}
			</select>
		</div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Login method:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="login_method" value="sessions"{if $login_method == 'sessions'} checked="checked"{/if} /> {i18n('Session')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="login_method" value="cookies"{if $login_method == 'cookies'} checked="checked"{/if} /> {i18n('Cookies')}</label></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel">{i18n('Display information about the rules:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="enable_terms" value="1"{if $enable_terms == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="enable_terms" value="0"{if $enable_terms == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<h4>{i18n('Rules')}</h4>
	<div class="tbl1">
		<div class="formField sep_1 grid_10"><textarea name="license_agreement" id="LicenseAgreement" cols="80" rows="3">{$license_agreement}</textarea></div>
	</div>
	<div class="tbl Buttons">
		<div class="grid_2 center button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
		<div class="grid_2 center button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
</form>
<script>
	{literal}
		var editor = CKEDITOR.replace('LicenseAgreement', {
			toolbar : [
				['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
				['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
				['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
				['Link','Unlink','Anchor'],
				['Source','-','Preview']
			]
		});
	{/literal}
</script>