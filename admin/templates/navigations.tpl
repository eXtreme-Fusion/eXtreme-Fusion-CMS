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

<h4>{i18n('Add Navigation Link')}</h4>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="Name">{i18n('Name:')}</label></div>
		<div class="formField grid_7"><input type="text" name="name" value="{$name}" id="Name" rows="1" /></div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="Url">{i18n('URL:')}</label></div>
		<div class="formField grid_7"><input type="text" name="url" value="{$url}" id="Url" rows="1" /></div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="Visibility">{i18n('Visible for:')}</label></div>
		<div class="formField grid_7">
			<select name="visibility[]" id="Visibility" multiple class="select-multi" size="5">
				{section=access}
					<option value="{$access.value}"{if $access.selected} selected="selected"{/if}>{$access.display}</option>
				{/section}
			</select>

		</div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="Order">{i18n('Order:')}</label></div>
		<div class="formField grid_7"><input type="text" name="order" value="{$order}" id="Order" /></div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="Position">{i18n('Position:')}</label></div>
		<div class="formField grid_7">
			<input type="radio" name="position" value="1" {if $position == 1}checked='checked'{elseif ! $position}checked='checked'{/if} id="Position" /> {i18n('Vertical menu')}
			<input type="radio" name="position" value="2" {if $position == 2}checked='checked'{/if} id="Position" /> {i18n('Horizontal menu')}
			<input type="radio" name="position" value="3" {if $position == 3}checked='checked'{/if} id="Position" /> {i18n('Vertical and horizontal menu')}
		</div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="Window">{i18n('Open in new window:')}</label></div>
		<div class="formField grid_7">
			<input type="radio" name="window" value="1" {if $window == 1} checked='checked'{/if} id="Window" /> {i18n('Yes')}
			<input type="radio" name="window" value="0" {if $window == 0} checked='checked'{/if} id="Window" /> {i18n('No')}
		</div>
	</div>
	{if $modRewrite_unavailable}
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="Rewrite">{i18n('Modyfikacja linku przez system:')}</label></div>
			<div class="formField grid_7">
				<input type="radio" name="rewrite" value="1" {if $rewrite == 1} checked='checked'{/if} id="Window" /> {i18n('Yes')}
				<input type="radio" name="rewrite" value="0" {if $rewrite == 0} checked='checked'{/if} id="Window" /> {i18n('No')}
			</div>
		</div>
	{/if}
	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
</form>
<h4>{i18n('Navigation')}</h4>
{if $data}
	<div id='ResponseNavigations' class='valid'></div>
	<div class="tbl2">
		<div class="sep_1 grid_2 bold">{i18n('Name:')}</div>
		<div class="grid_3 bold">{i18n('URL:')}</div>
		<div class="grid_2 bold">{i18n('Visible for:')}</div>
		<div class="grid_2 bold">{i18n('Options:')}</div>
	</div>
	<div id='ListNavigations'>
		<ul>
			{section=data}
				<li class='sort' id='ArrayOrderNavigation_{$data.id}'>
					<div class="tbl2">
						<div class="sep_1 grid_2">{if $data.position == 2} <em> {/if} {$data.name} {if $data.position == 2} </em> {/if}</div>
						<div class="grid_3">
							{if $data.name != "---" && $data.url == "---"}
								<strong>[{$data.name}]</strong>
							{else}
								{if $data.perse_url}
									<a href="{$ADDR_SITE}{$data.url}">{if $data.url}{$data.url}{else}{$ADDR_SITE}{/if}</a>
								{else}
									<a href="{$ADDR_SITE}{$data.url}"><small>{if $data.url}{$data.url}{else}{$ADDR_SITE}{/if}</small></a>
								{/if}
							{/if}
						</div>
						<div class="grid_2">
							{$data.visibility}
						</div>
						<div class="grid_2">
							<a href="{$FILE_SELF}?action=edit&amp;id={$data.id}" class="tip" title="{i18n('Edit')}">
								<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
							</a>
							<a href="{$FILE_SELF}?action=delete&amp;id={$data.id}" class="tip confirm_button" title="{i18n('Delete')}">
								<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
							</a>
						</div>
					</div>
				</li>
			{/section}
		</ul>
	</div>
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('No site links.')}</div>
	</div>
{/if}