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

{if $message}<div class="{$class}">{$message}</div>{/if}
<form action="{$URL_REQUEST}" method="post" id="This">
	<h3>{i18n('Logging and Cookies')}</h3>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="CookieDomain">{i18n('Domain cookies:')}</label><small>{i18n('www domain')}</small></div>
		<div class="grid_4 formField"><input type="text" name="cookie_domain" value="{$cookie_domain}" id="CookieDomain" class="num_100" maxlength="100" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="CookiePatch">{i18n('Path for cookies:')}</label><small>{i18n('Default path for cookies')}</small></div>
		<div class="grid_4 formField"><input type="text" name="cookie_patch" value="{$cookie_patch}" id="CookiePatch" class="num_1" maxlength="1" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel">{i18n('Secured cookies:')}<small>{i18n('The security cookie')}</small></div>
		<div class="grid_1 formField"><label><input type="radio" name="cookie_secure" value="1"{if $cookie_secure == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="cookie_secure" value="0"{if $cookie_secure == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<h3>{i18n('Data cache')}</h3>
	<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Enabled:')}<small>{i18n('Cache for OPT and SmartOptimizer for JS and CSS files.')}</small></div>
		<div class="grid_1 formField"><label><input type="radio" name="cache_active" value="1"{if $cache_active == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="cache_active" value="0"{if $cache_active == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="CacheExpire">{i18n('Validity:')}</label><small>{i18n('How many days do the cache files should be kept in memory?')}</small></div>
		<div class="grid_4 formField"><input type="text" name="cache_expire" value="{$cache_expire}" id="CacheExpire" class="num_3" maxlength="3" /></div>
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