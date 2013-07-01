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

<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Update')}</h3>                           

{if $message}
	<div class="{$class}">{$message}</div>
{else}
	{if $upgrade}
		<form id="This" action="{$URL_REQUEST}" method="post"> 
			<div class="tbl1">
				<div class="sep_1 center grid_10">{i18n('A database update is available for this installation of eXtreme-Fusion.')}</div>
			</div>    
			<div class="tbl Buttons">
				<div class="center grid_2 button-l">
					<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
				</div>
				<div class="center grid_2 button-r">
					<input type="hidden" name="save" value="yes" />
					<span id="SendForm_This" class="save"><strong>{i18n('Upgrade')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
				</div>
			</div>
		</form>
	{else}
		<div class="tbl1">
			<div class="center grid_12">{i18n('There is no database upgrade available.')}</div>
		</div>
	{/if}
{/if}

{if $message || !$upgrade}
	<div class="tbl Buttons">
		<div class="center grid_2 button-c">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
	</div>
{/if}