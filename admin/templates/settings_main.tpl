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

<h3>{$SystemVersion} - {i18n('Settings')} &raquo; {i18n('Main')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="SiteName">{i18n('Site name:')}</label></div>
		<div class="grid_4 formField"><input type="text" name="site_name" value="{$site_name}" id="SiteName" class="num_165" maxlength="168" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="Description">{i18n('Site description:')}</label></div>
		<div class="grid_4 formField"><textarea name="description" id="Description" class="resize" rows="1">{$description}</textarea></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="Keywords">{i18n('Keywords:')}</label><small>{i18n('Separate keywords by comma.')}</small></div>
		<div class="grid_4 formField"><textarea name="keywords" id="Keywords" class="resize" rows="1">{$keywords}</textarea></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="SiteBanner">{i18n('Banner:')}</label><small>{i18n('This banner will be displayed if only design author will use correct template methods.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="site_banner" value="{$site_banner}" id="SiteBanner" class="num_255" maxlength="255" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="SiteEmail">{i18n('Admin e-mail address:')}</label></div>
		<div class="grid_4 formField"><input type="text" name="siteemail" value="{$siteemail}" id="SiteEmail" class="num_128" maxlength="128" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="SiteUsername">{i18n('Your nick:')}</label></div>
		<div class="grid_4 formField"><input type="text" name="siteusername" value="{$siteusername}" id="SiteUsername" class="num_32" maxlength="32" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="SiteIntro">{i18n('Welcome message:')}</label><small>{i18n('Optional, field can be empty. HTML code is allowed.')}</small></div>
		<div class="grid_4 formField"><textarea name="siteintro" id="SiteIntro" class="resize" rows="1">{$siteintro}</textarea></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="Footer">{i18n('Footer:')}</label><small>{i18n('HTML code is not allowed.')}</small></div>
		<div class="grid_4 formField"><textarea name="footer" id="Footer" class="resize" rows="1">{$footer}</textarea></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="OpeningPage">{i18n('Set as a homepage:')}</label></div>
		<div class="grid_4 formField"><input type="text" name="opening_page" value="{$opening_page}" id="OpeningPage" class="num_100" maxlength="100" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Enable language detection by browser?')}<small><span title="To ustawienie dotyczy osób, które podczas rejestracji nie wybrały preferowanego języka oraz nie posiadających jeszcze konta w serwisie lub sytuacji, gdy wybrany język już nie jest dostępny w systemie. Jeśli wykrywanie języka zostanie włączone, to strona wyświetli się w języku ustawionym w przeglądarce internetowej. Jeżeli język ten nie jest dostępny w systemie, użyty zostanie <strong>Domyślny język strony</strong>." class="tip">Pomoc</span></small></div>
		<div class="grid_1 formField"><label><input type="radio" name="language_detection" value="1"{if $language_detection == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="language_detection" value="0"{if $language_detection == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="LocaleSet">{i18n('Default language:')}</label></div>
		<div class="grid_4 formField">
			<select name="locale_set" id="LocaleSet">
				{section=locale_set}
					<option value="{$locale_set.value}"{if $locale_set.selected} selected="selected"{/if}>{$locale_set.display}</option>
				{/section}
			</select>
		</div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="ThemeSet">{i18n('Default theme:')}</label></div>
		<div class="grid_4 formField">
			<select name="theme_set" id="ThemeSet">
				{section=theme_set}
					<option value="{$theme_set.value}"{if $theme_set.selected} selected="selected"{/if}>{$theme_set.display}</option>
				{/section}
			</select>
		</div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="DefaultSearch">{i18n('Default search place:')}</label></div>
		<div class="grid_4 formField">
			<select name="default_search" id="DefaultSearch">
				{section=search_opts}
					<option value="{$search_opts.entry}"{if $search_opts.entry == $default_search} selected="selected"{/if}>{$search_opts.value}</option>
				{/section}
			</select>
		</div>
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