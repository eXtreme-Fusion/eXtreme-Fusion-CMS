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

<h3 class="ui-corner-all">{$SystemVersion} - {$blaclist_form.title}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl2">
		<div class="formField sep_1 grid_10">{i18n('Blacklist description')}</div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="blacklist_ip">{i18n('Block IP address:')}</label></div>
		<div class="formField grid_7"><textarea name="blacklist_ip" id="blacklist_ip" rows="1">{$blaclist_form.ip}</textarea></div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="blacklist_email">{i18n('Block e-mail address:')}</label></div>
		<div class="formField grid_7"><textarea name="blacklist_email" id="blacklist_email" rows="1">{$blaclist_form.email}</textarea></div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="blacklist_reason">{i18n('Block reason:')}</label></div>
		<div class="formField grid_7"><textarea name="blacklist_reason" id="blacklist_reason" rows="3" class="resize">{$blaclist_form.reason}</textarea></div>
	</div>
	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			{if $blaclist_form.id}
				<input type="hidden" name="id" value="{$blaclist_form.id}" />
			{/if}
			<span class="save" id="SendForm_This" ><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
</form>
<h4>{i18n('Blocked addresses')}</h4>
{if $blacklist}
	<div class="tbl2">
		<div class="sep_1 grid_4 bold">{i18n('Address:')}</div>
		<div class="grid_2 bold">{i18n('User:')}</div>
		<div class="grid_2 bold">{i18n('Date:')}</div>
		<div class="grid_2 bold">{i18n('Options:')}</div>
	</div>
	{section=blacklist}
		<div class="tbl {$blacklist.row_color}">
			<div class="sep_1 grid_4">{$blacklist.ip}{if $blacklist.email && $blacklist.ip} : {/if}{$blacklist.email}<br /><small>{$blacklist.reason}</small></div>
			<div class="grid_2">{$blacklist.username}</div>
			<div class="grid_2">{$blacklist.datestamp}</div>
			<div class="grid_2">
				<a href="{$FILE_SELF}?action=edit&id={$blacklist.id}" class="tip" title="{i18n('Edit')}">
					<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
				</a>
				<a href="{$FILE_SELF}?action=delete&id={$blacklist.id}" class="tip confirm_button" title="{i18n('Delete')}">
					<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
				</a>
			</div>
		</div>
	{/section}
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('Blacklist is empty.')}</div>
	</div>
{/if}
