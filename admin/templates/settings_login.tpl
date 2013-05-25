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

{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	<h3>{i18n('Website login sessions')}</h3>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="SiteNormalLogingTime">{i18n('The duration of the session on pages outside the administration panel:')}</label><small>{i18n('The duration of normal login session (in hours).')}</small></div>
		<div class="grid_4 formField"><input type="text" name="site_normal_loging_time" value="{$site_normal_loging_time}" id="SiteNormalLogingTime" class="num_10" maxlength="10" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="SiteRememberLogingTime">{i18n('The duration of the session with the "Remember me":')}</label><small>{i18n('The duration of "remember me" login session (in days).')}</small></div>
		<div class="grid_4 formField"><input type="text" name="site_remember_loging_time" value="{$site_remember_loging_time}" id="SiteNormalLogingTime" class="num_10" maxlength="10" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="UserActiveTime">{i18n('"Online" being time:')}</label><small>{i18n('Time since last activity on the site, within which the user is still considered to be "online" (in minutes).')}</small></div>
		<div class="grid_4 formField"><input type="text" name="user_active_time" value="{$user_active_time}" id="UserActiveTime" class="num_10" maxlength="10" /></div>
	</div>
	
	<h3>{i18n('Administration panel login sessions')}</h3>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="AdminLogingTime">{i18n('The duration of the session in the administration panel:')}</label><small>{i18n('The duration of login session (in minutes).')}</small></div>
		<div class="grid_4 formField"><input type="text" name="admin_loging_time" value="{$admin_loging_time}" id="AdminLogingTime" class="num_10" maxlength="10" /></div>
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