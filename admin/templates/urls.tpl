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

<h3 class="ui-corner-all">{$SystemVersion} - {i18n('URLs Generator')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl2">
		<div class="formField sep_1 grid_10">
			{i18n('About URLs')}
		</div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="full_path">{i18n('Current URL:')}</label></div>
		<div class="formField grid_7"><textarea name="full_path" id="full_path" rows="1">{$link.full}</textarea></div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="short_path">{i18n('New URL:')}</label></div>
		<div class="formField grid_7"><textarea name="short_path" id="short_path" rows="1">{$link.short}</textarea></div>
	</div>
	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			{if $edit}
			<input type="hidden" name="id" value="{$link.id}" />
			{/if}
			<span class="save" id="SendForm_This" ><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
</form>

{if $link && ! $edit}
	<div class="tbl2">
		<div class="sep_1 grid_4 bold">{i18n('Full URL')}</div>
		<div class="grid_2 bold">{i18n('Short URL')}</div>
		<div class="grid_2 bold">{i18n('Date')}</div>
		<div class="grid_2 bold">{i18n('Options')}</div>
	</div>
	{section=link}
		<div class="tbl {$link.row_color}">
			<div class="sep_1 grid_4">{$link.full}</div>
			<div class="grid_2">{$link.short}</div>
			<div class="grid_2">{$link.date}</div>
			<div class="grid_2">
				<a href="{$FILE_SELF}?action=edit&id={$link.id}" class="tip" title="{i18n('Edit')}">
					<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
				</a>
				<a href="{$FILE_SELF}?action=delete&id={$link.id}" class="tip confirm_button" title="{i18n('Delete')}">
					<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
				</a>
			</div>
		</div>
	{/section}
{/if}