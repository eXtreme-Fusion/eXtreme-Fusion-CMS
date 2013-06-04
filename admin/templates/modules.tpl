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
| Co-Author: Christian Damsgaard J?rgensen (PMM)
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

<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Modules')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

{if $mod}
	<form id="This" method="post" action="{$URL_REQUEST}">
		<div class="tbl2">
			<div class="sep_1 grid_2 bold">{i18n('Name:')}</div>
			<div class="grid_5 bold">{i18n('Description:')}</div>
			<div class="grid_1 bold">{i18n('Version:')}</div>
			<div class="grid_3 bold">{i18n('Author:')}</div>
		</div>
		{section=mod}
			<div class="tbl {if $mod.development}{if $mod.installed}modInstalledDevelopment{else}modDevelopment{/if}{else}{if $mod.installed}modInstallOfficial{else}modOfficial{/if}{/if}">
				<div class="sep_1 grid_2">
					<input type='checkbox' name='mod[]' id='{$mod.id}' value='{$mod.value}' {if $mod.installed}checked='checked'{/if}/>
					{$mod.label}
					{if $mod.is_to_update}
						<p class="red">
							<input type="checkbox" name="update[]" value="{$mod.value}" />{i18n('Update')}
						</p>
					{/if}
				</div>
				<div class="grid_5">{$mod.desc}</div>
				<div class="grid_1">{$mod.version}</div>
				<div class="grid_3"><a href="{$mod.webURL}">{$mod.author}</a></div>

			</div>
		{/section}
		{*<div class="tbl">
			<div class="tbl">{i18n('Legend:')}</div>
			<div class="modDevelopmentLegend">{i18n('Modules under Development.')}</div>
			<div class="modInstalledDevelopmentLegend">{i18n('Installed Modules under Development.')}</div>
			<div class="modOfficialLegend">{i18n('Modules officially released.')}</div>
			<div class="modInstallOfficialLegend">{i18n('Installed Modules officially released.')}</div>
		</div>*}
		<div class="tbl Buttons">
			<div class="center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<input type="hidden" name="save" value="yes" />
				<span class="save" id="SendForm_This" ><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	</form>
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('')}</div>
	</div>
{/if}