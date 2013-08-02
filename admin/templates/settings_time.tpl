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

<h3>{$SystemVersion} - {i18n('Settings')} &raquo; {i18n('Date and Time')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<div id="page-menu">
	<a href="javascript:void(0)" title="settings" class="page-tab">{i18n('Settings')}</a>
	<a href="javascript:void(0)" title="formats" class="page-tab">{i18n('Formats')}</a>
</div>

<div id="settings" class="page-tab-cont">
	<form action="{$URL_REQUEST}" method="post" id="Settings">
		{*<h4>{i18n('Date & Time')}</h4>*}
		<div class="tbl1">
			<div class="grid_5 formLabel"><label for="ShortDate">{i18n('Short date format:')}</label></div>
			<div class="grid_3 formField">
				<select name="shortdate" id="ShortDate">
					{section=shortdate}
						<option value="{$shortdate.value}"{if $shortdate.selected} selected="selected"{/if}>{$shortdate.display}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="grid_5 formLabel"><label for="LongDate">{i18n('Long date format:')}</label></div>
			<div class="grid_3 formField">
				<select name="longdate" id="LongDate">
					{section=longdate}
						<option value="{$longdate.value}"{if $longdate.selected} selected="selected"{/if}>{$longdate.display}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="grid_5 formLabel">
				<label for="Timezone">{i18n('Default time zone:')}</label>
				<small>{i18n('Time zone help 1')}</small>
				<small>{i18n('Time zone help 2')}</small>
				<small class="red">{i18n('We do not recommend to change this setting!')}</small>
			</div>
			<div class="grid_3 formField"><input type="text" name="timezone" value="{$timezone}" id="Timezone" class="num_200" maxlength="200" /></div>
		</div>
		<div class="tbl2">
			<div class="grid_5 formLabel"><label for="OffsetTimezone">{i18n('The default time shift relative to the time zone:')}</label></div>
			<div class="grid_3 formField">
				<select name="offset_timezone" id="OffsetTimezone">
					{section=offset_timezone}
						<option value="{$offset_timezone.value}"{if $offset_timezone.selected} selected="selected"{/if}>{$offset_timezone.display}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="grid_5 formLabel">{i18n('Registered:')}</div>
			<div class="grid_6 formField">
				<div><label><input type="radio" name="user_custom_offset_timezone" value="0"{if $user_custom_offset_timezone == 0} checked="checked"{/if} /> {i18n('Let define the time shift relative to the default zone.')}</label></div>
				<div><label><input type="radio" name="user_custom_offset_timezone" value="1"{if $user_custom_offset_timezone == 1} checked="checked"{/if} /> {i18n('Set the default value of time shift.')}</label></div>
			</div>
		</div>
		<div class="tbl Buttons">
			<div class="grid_2 center button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
			</div>
			<div class="grid_2 center button-r">
				<input type="hidden" name="save_settings" value="yes" />
				<span id="SendForm_Settings" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
			</div>
		</div>
	</form>
</div>
<div id="formats" class="page-tab-cont">
	<form action="{$URL_REQUEST}" method="post" id="Formats">
		<div class="tbl1">
			<div class="grid_5 formLabel">
				<label for="NewTimeFormat">{i18n('New date and time format:')}</label>
				<small>{i18n('Time format help')}</small>
			</div>
			<div class="grid_3 formField">
				<input type="text" name="new_time_format" value="{$new_time_format}" id="NewTimeFormat" class="num_200" maxlength="200" />
				<span id="time_preview"></span>
			</div>
		</div>
		{if $format}
			<h4>{i18n('Existing date and time formats')}</h4>
			<div class="tbl2">
				<div class="grid_4 sep_2 bold">{i18n('Value:')}</div>
				<div class="grid_2 bold">{i18n('Overview:')}</div>
				<div class="grid_2 bold">{i18n('Options:')}</div>
			</div>
			{section=format}
				<div class="tbl {$format.row_color}">
					<div class="grid_4 sep_2">{$format.value}</div>
					<div class="grid_4">{$format.preview}</div>
					<div class="grid_2">
						<a href="{$FILE_SELF}?action=edit&amp;id={$format.id}" class="tip" title="{i18n('Edit')}">
							<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
						</a>
						<a href="{$FILE_SELF}?action=delete&id={$format.id}" class="tip confirm_button" title="{i18n('Delete')}">
							<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
						</a>
					</div>
				</div>
			{/section}
		{/if}
		<div class="tbl Buttons">
			<div class="grid_2 center button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
			</div>
			<div class="grid_2 center button-r">
				<input type="hidden" name="save_formats" value="yes" />
				<span id="SendForm_Formats" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
			</div>
		</div>
	</form>
</div>

<script src="{$ADDR_ADMIN_PAGES_JS}settings_time.js"></script>
<script>var page_active_tab = '{$view}';</script>