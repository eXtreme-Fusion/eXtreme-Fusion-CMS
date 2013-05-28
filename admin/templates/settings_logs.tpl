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
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
*}

<h3>{$SystemVersion} - {i18n('Settings')} &raquo; {i18n('Logs')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="Active">{i18n('Enable Logs:')}</label></div>
		<div class="grid_1 formField"><input type="checkbox" name="active" value="1" id="Active"{if $active} checked="checked"{/if} /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="SaveRemovalAction">{i18n('Save cleaning Logs or deleting single log:')}</label></div>
		<div class="grid_1 formField"><input type="checkbox" name="save_removal_action" value="1" id="SaveRemovalAction"{if $save_removal_action} checked="checked"{/if} /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="OptimizeActive">{i18n('Optimize for database:')}</label></div>
		<div class="grid_1 formField"><input type="checkbox" name="optimize_active" value="1" id="OptimizeActive"{if $optimize_active} checked="checked"{/if} /></div>
	</div>
	<div id="ExpireSett" class="tbl2">
		<div class="grid_6 formLabel"><label for="ExpireDays">{i18n('Storage time logs in database (days):')}</div>
		<div class="grid_4 formField"><input type="text" name="expire_days" value="{$expire_days}" id="ExpireDays" class="num_3" maxlength="3" /></div>
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

<script src="{$ADDR_ADMIN_PAGES_JS}settings_logs.js"></script>