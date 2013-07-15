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
*********************************************************/
*}
<h3>{i18n('Synchronization')}</h3>
{if $url}
	<div class="center">
		<p><a href="{$url}">Pobierz aktualizację</a></p>
		<p>{i18n('After putting files into FTP server, go here.', array(':url' => $upgrade))}</p>
	</div>
{else}
	{if $message}<div class="{$class}">{$message}</div>{/if}

	<form action="{$URL_REQUEST}" method="post" id="This">
		<div class="tbl1">
			<div class="grid_6 formLabel">{i18n('Synchro statement')}</div>
			<div class="grid_1 formField"><label><input type="radio" name="ext_allowed" value="1"{if $synchro == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
			<div class="grid_5 formField"><label><input type="radio" name="ext_allowed" value="0"{if $synchro == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
			<div id="synchro">{i18n('Synchro policy')}</div>
		</div>
		<div class="tbl Buttons">
			<div class="grid_2 center button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
			</div>
			<div class="grid_2 center button-r">
				<input type="hidden" name="save" value="yes" />
				<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
			</div>
		</div>
	</form>
{/if}