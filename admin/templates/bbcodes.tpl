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
| Author: Wooya
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

<h3 class="ui-corner-all">{$SystemVersion} - {i18n('BBCodes')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<h4>{i18n('Active BBCodes')}</h4>
{if $bbcode_active}
	<div id='ResponseBBcode' class='valid'></div>
	<div class="tbl2">
		<div class="sep_1 grid_2 bold">{i18n('Name:')}</div>
		<div class="grid_2 bold">{i18n('Icon:')}</div>
		<div class="grid_4 bold">{i18n('Description:')}</div>
		<div class="grid_2 bold">{i18n('Options:')}</div>
	</div>
	<div id='ListBBcode'>
		<ul>
			{section=bbcode_active}
				<li class='sort' id='ArrayOrderBBcode_{$bbcode_active.id}'>
					<div class="tbl2">
						<div class="formLabel sep_1 grid_2">{$bbcode_active.name}</div>
						<div class="formField grid_2">
							{if $bbcode_active.image}
								<img src="{$ADDR_IMAGES}bbcodes/{$bbcode_active.row}{if $bbcode_active.image == 1}.png{elseif $bbcode_active.image == 2}.gif{elseif $bbcode_active.image == 3}.jpg{/if}" alt="{$bbcode_active.name}" />
							{else}
								-
							{/if}
						</div>
						<div class="formField grid_4">{$bbcode_active.description}</div>
						<div class="formField grid_2">
							<a href="{$FILE_SELF}?action=unactive&id={$bbcode_active.id}" class="tip confirm_button" title="{i18n('Unactive')}">
								<img src="{$ADDR_ADMIN_ICONS}pixel/x.png" alt="{i18n('Unactive')}" />
							</a>
						</div>
					</div>
				</li>
			{/section}
		</ul>
	</div>
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('No BBCode tags')}</div>
	</div>
{/if}

<h4>{i18n('Inactive BBCodes')}</h4>
{if $bbcode_inactive}
	<div class="tbl2">
		<div class="sep_1 grid_2 bold">{i18n('Name:')}</div>
		<div class="grid_2 bold">{i18n('Icon:')}</div>
		<div class="grid_4 bold">{i18n('Description:')}</div>
		<div class="grid_2 bold">{i18n('Options:')}</div>
	</div>
	{section=bbcode_inactive}
		<div class="{$bbcode_inactive.row_color}">
			<div class="sep_1 grid_2">{$bbcode_inactive.name}</div>
			<div class="grid_2">
				{if $bbcode_inactive.image}
					<img src="{$ADDR_IMAGES}bbcodes/{$bbcode_inactive.row}{if $bbcode_inactive.image == 1}.png{elseif $bbcode_inactive.image == 2}.gif{elseif $bbcode_inactive.image == 3}.jpg{/if}" alt="{$bbcode_inactive.name}" />
				{else}
					-
				{/if}
			</div>
			<div class="grid_4">{$bbcode_inactive.description}</div>
			<div class="grid_2">
				<a href="{$FILE_SELF}?action=active&name={$bbcode_inactive.row}" class="tip" title="{i18n('Active')}">
					<img src="{$ADDR_ADMIN_ICONS}pixel/checkmark.png" alt="{i18n('Active')}" />
				</a>
			</div>
		</div>
	{/section}
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('No BBCode tags')}</div>
	</div>
{/if}