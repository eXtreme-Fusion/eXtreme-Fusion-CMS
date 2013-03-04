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

{php} opentable(__('Members List')) {/php}
	{if $users}
		<div class="tbl2">
			<div class="grid_4 bold">{i18n('Username')}</div>
			<div class="grid_2 bold">{i18n('User Level')}</div>
			<div class="grid_3 bold">{i18n('Groups')}</div>
			<div class="grid_3 bold">{i18n('Last visit')}</div>
		</div>
		{section=users}
			<div class="tbl {$users.row}">
				<div class="grid_4">{$users.link}</div>
				<div class="grid_2">{$users.role}</div>
				<div class="grid_3">{$users.roles}</div>
				<div class="grid_3">{$users.visit}</div>
			</div>
		{/section}
	{else}
		<div class="tbl2">
			<div class="sep_2 center">{i18n('No members have been found')}</div>
			<div class="clear"></div>
		</div>
	{/if}
	<div class="tbl center">
		<div class="buttons-bg">&nbsp;</div>
			{if $show_all}
				<div><a href='{$link}'>{i18n('Show all')}</a></div>
			{/if}
			{section=sort}
				<a href='{$sort.link}'{if $sort.sel} class="red bold"{/if}>{$sort.disp}</a>
			{/section}
		<div class="buttons-bg">&nbsp;</div>
	</div>
	{$page_nav}
{php} closetable() {/php}