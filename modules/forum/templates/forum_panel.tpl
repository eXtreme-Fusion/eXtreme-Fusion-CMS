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
| Author: Marcus Gottschalk (MarcusG)
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
{if $isInstalled}
		{php} $this->sidePanel(__('Latest Active Threads')); {/php}
		{if $threads}
			<table class="tbl2">
				<thead>
					<tr>
						<th class="grid_4 bold">{i18n('Thread title')}</th>
						<th class="grid_2 bold">{i18n('Author')}</th>
						<th class="grid_3 bold">{i18n('Replies')}</th>
						<th class="grid_3 bold">{i18n('Last entry')}</th>
					</tr>
				</thead>
				<tbody>
					{section=threads}
					<tr class="{$threads.row}">
						<td><a href="{$threads.link}" class="text-link">{$threads.title}</a></td>
						<td>{$threads.autor}</td>
						<td>{$threads.entries}</td>
						<td>{$threads.entry_user}</td>
					</tr>
					{/section}
				</tbody>
			</table>
		{else}
			<div class="info">{i18n('No threads have been found')}</div>
		{/if}
		{php} $this->sidePanel(); {/php}
		</div>
{/if}
