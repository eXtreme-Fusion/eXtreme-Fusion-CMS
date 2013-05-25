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

<h3>{$SystemVersion} - {i18n('Settings')} &raquo; {i18n('Banners')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	<div class="tbl1">
		<div class="grid_2 formLabel"><label for="site_banner1">{i18n('1. banner:')}</label></div>
		<div class="grid_10 formField"><textarea name="site_banner1" id="site_banner1">{$site_banner1}</textarea></div>
	</div>
    <script>
        {literal}
            var editor = CKEDITOR.replace('site_banner1', {
				extraPlugins : 'docprops',
				uiColor: '#4B4B4B'
            });
        {/literal}
	</script>
	<div class="tbl1">
		<div class="grid_2 formLabel"><label for="site_banner2">{i18n('2. banner:')}</label></div>
		<div class="grid_10 formField"><textarea name="site_banner2" id="site_banner2">{$site_banner2}</textarea></div>
	</div>
    <script>
        {literal}
			var editor = CKEDITOR.replace('site_banner2', {
				extraPlugins : 'docprops',
				uiColor: '#4B4B4B'
            });
		{/literal}
	</script>
	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img alt="" src="{$ADDR_ADMIN_ICONS}pixel/undo.png"></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
</form>