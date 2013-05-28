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
| Author: Marcus Gottschalk (MarcusG)
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

<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Chat')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

	<div class="tbl Buttons">
		<div class="center grid_4 button-c">
			<span class="save"><a href="{$ADDR_ADMIN_PAGES}panels.php"><strong>{i18n('Panels managment')}</strong></a></span>
		</div>
	</div>

<form id="This" action="{$FormAction}" method="post">
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="refresh">{i18n('Refresh (seconds):')}</label></div>
		<div class="formField grid_7"><input type="text" name="refresh" id="refresh" value="{$refresh}" /></div>
	</div>

	<div class="tbl2">
		<div class="formLabel sep_1 grid_3">
      <label for="life_messages">{i18n('Life of messages (days):')}</label>
      <small>
      {i18n('Enter 0 if you do not want to delete messages.')}
      </small>
    </div>
		<div class="formField grid_7"><input type="text" name="life_messages" id="life_messages" value="{$life_messages}" /></div>
	</div>

	<div class="tbl1">
		<div class="formLabel sep_1 grid_3">
			<label for="panel_limit">{i18n('Number of posts to display in ShoutBox panel:')}</label>
			<small>{i18n('Enter 0 if you do not want to have a limit.')}</small>
		</div>
		<div class="formField grid_7"><input type="text" name="panel_limit" id="panel_limit" value="{$panel_limit}" /></div>
	</div>

	<div class="tbl2">
		<div class="formLabel sep_1 grid_3">
			<label for="archive_limit">{i18n('Number of posts to display on archive page of ShoutBox:')}</label>
			<small>{i18n('Enter 0 if you do not want to have a limit.')}</small>
		</div>
		<div class="formField grid_7"><input type="text" name="archive_limit" id="archive_limit" value="{$archive_limit}" /></div>
	</div>

	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
		</div>

		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span class="save" id="SendForm_This"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
		</div>

	</div>
</form>
