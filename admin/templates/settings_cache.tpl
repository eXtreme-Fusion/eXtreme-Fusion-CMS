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
	<h3>{i18n('Settings cache')}</h3>
	<!--<div class="tbl1">
		<div class="grid_6 formLabel"><label for="CacheContact">{i18n('Cache contact:')}</label><small>{i18n('in seconds.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="expire_contact" value="{$cache_contact}" id="CacheContact" class="num_100" maxlength="100" /></div>
	</div>-->
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="CacheNews">{i18n('Cache news:')}</label><small>{i18n('in seconds.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="expire_news" value="{$cache_news}" id="CacheNews" class="num_100" maxlength="100" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="CacheNewsCats">{i18n('Cache news cats:')}</label><small>{i18n('in seconds.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="expire_news_cats" value="{$cache_news_cats}" id="CacheNewsCats" class="num_100" maxlength="100" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="CachePages">{i18n('Cache pages:')}</label><small>{i18n('in seconds.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="expire_pages" value="{$cache_pages}" id="CachePages" class="num_100" maxlength="100" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="CacheProfile">{i18n('Cache profile:')}</label><small>{i18n('in seconds.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="expire_profile" value="{$cache_profile}" id="CacheProfile" class="num_100" maxlength="100" /></div>
	</div>
	<!--<div class="tbl2">
		<div class="grid_6 formLabel"><label for="CacheRules">{i18n('Cache rules:')}</label><small>{i18n('in seconds.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="expire_rules" value="{$cache_rules}" id="CacheRules" class="num_100" maxlength="100" /></div>
	</div>-->
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="CacheTags">{i18n('Cache tags:')}</label><small>{i18n('in seconds.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="expire_tags" value="{$cache_tags}" id="CacheTags" class="num_100" maxlength="100" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="CacheTeam">{i18n('Cache team:')}</label><small>{i18n('in seconds.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="expire_team" value="{$cache_team}" id="CacheTeam" class="num_100" maxlength="100" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="CacheUsers">{i18n('Cache users:')}</label><small>{i18n('in seconds.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="expire_users" value="{$cache_users}" id="CacheUsers" class="num_100" maxlength="100" /></div>
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