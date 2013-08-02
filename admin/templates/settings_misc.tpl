{*
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
|
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+------------------------------------------------------
| Author: Nick Jones (Digitanium)
+------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+------------------------------------------------------*/
*}

<h3>{$SystemVersion} - {i18n('Settings')} &raquo; {i18n('Miscellaneous')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Enable visitor counter:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="visits_counter_enabled" value="1"{if $visits_counter_enabled == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="visits_counter_enabled" value="0"{if $visits_counter_enabled == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	{*<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Use CKEditor HTML editor:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="tinymce_enabled" value="1"{if $tinymce_enabled == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="tinymce_enabled" value="0"{if $tinymce_enabled == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>*}
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="SmtpHost">{i18n('SMTP Host:')}</label><small>{i18n('Leave blank if sending e-mails is enabled.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="smtp_host" value="{$smtp_host}" id="SmtpHost" class="num_200" maxlength="200" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="SmtpPort">{i18n('SMTP Port:')}</label></div>
		<div class="grid_4 formField"><input type="text" name="smtp_port" value="{$smtp_port}" id="SmtpPort" class="num_10" maxlength="10" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="SmtpUsername">{i18n('SMTP Username:')}</label></div>
		<div class="grid_4 formField"><input type="text" name="smtp_username" value="{$smtp_username}" id="SmtpUsername" class="num_100" maxlength="100" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="SmtpPassword">{i18n('SMTP Password:')}</label></div>
		<div class="grid_4 formField"><input type="password" name="smtp_password" value="{$smtp_password}" id="SmtpPassword" class="num_100" maxlength="100" /></div>
	</div>
	{*<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Enable commenting:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="comments_enabled" value="1"{if $comments_enabled == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="comments_enabled" value="0"{if $comments_enabled == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel">{i18n('Enable evaluating:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="ratings_enabled" value="1"{if $ratings_enabled == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="ratings_enabled" value="0"{if $ratings_enabled == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>*}
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
