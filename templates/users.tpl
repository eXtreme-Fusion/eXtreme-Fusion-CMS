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
+-------------------------------------------------------
| Author: Nick Jones (Digitanium)
+-------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+-------------------------------------------------------*/
*}

{panel=i18n('Members List')}
	{if $users}
	<table class="tbl2">
		<thead>
			<tr>
				<th class="grid_4 bold">{i18n('Username')}</th>
				<th class="grid_2 bold">{i18n('User Level')}</th>
				<th class="grid_3 bold">{i18n('Groups')}</th>
				<th class="grid_3 bold">{i18n('Last visit')}</th>
			</tr>
		</thead>
		<tbody>
			{section=users}
			<tr class="{$users.row}">
				<td>{$users.link}</td>
				<td>{$users.role}</td>
				<td>{$users.roles}</td>
				<td>{$users.visit}</td>
			</tr>
			{/section}
		</tbody>
	</table>
	{else}
	<div class="tbl2">
		<div class="sep_2 center">{i18n('No members have been found')}</div>
		<div class="clear"></div>
	</div>
	{/if}
	{if $show_all}
	<div class="tbl1 center">
		<a href="{$link}">{i18n('Show all')}</a>
	</div>
	{/if}
	<div class="tbl center">
		{section=sort}
		<a href="{$sort.link}"{if $sort.sel} class="white bold"{/if}>{$sort.disp}</a>
		{/section}
	</div>
	{$page_nav}
{/panel}