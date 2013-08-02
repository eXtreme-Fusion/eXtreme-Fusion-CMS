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

<div class="ui-corner-all grid_6">
	<h3 class="ui-corner-all">{i18n('Update')}</h3>

	{if $updating_error}
		<div class="HomeBox"><div class="error">{i18n('Updating error')}</div></div>
	{elseif $synchro_error || $error}
		<div class="HomeBox"><div class="error"><a href="{$ADDR_ADMIN_PAGES}settings_synchro.php">{i18n('Synchronization is not active.')}</a></div></div>
	{elseif $upgrade}
		<div class="HomeBox"><div class="valid"><a href="{$ADDR_ADMIN_PAGES}upgrade.php">{i18n('Update is ready to install.')}</a></div></div>
	{elseif $update_href}
		<div class="HomeBox"><div class="valid"><a href="{$ADDR_ADMIN_PAGES}settings_synchro.php?action=download&amp;url={$update_href}">{i18n('Update is available.')}</a></div></div>
	{else}
		<div class="HomeBox"><div class="valid">{i18n('You have currently version of eXtreme-Fusion CMS.')}</div></div>
	{/if}


	<h3 class="ui-corner-all">{i18n('History')}</h3>
	<div class="HomeBox">
	{if $logs}
		<div class="tbl2">
			<div class="grid_2 bold center">{i18n('User:')}</div>
			<div class="grid_3 bold center">{i18n('Message:')}</div>
		</div>
		{section=logs}
			<div class="tbl {$logs.row_color}">
				<div class="grid_2 center">{$logs.user}</div>
				<div class="grid_3 center">{$logs.message}</div>
			</div>
		{/section}
	{else}
		<div class="error">{i18n('There are no logs.')}</div>
	{/if}
	</div>
</div>
<div class="ui-corner-all grid_6">
	<!--<h3 class="ui-corner-all">{i18n('Notes')} {if $current}({$current}/{$notes_per_page}){/if}</h3>
		<div class="HomeBox">

			{if $message && $notes_log}<div class="{$class}">{$message}</div>{/if}

			{if $notes_add}
				<h4>{i18n('Add new note')}</h4>
				<form id="This" action="{$URL_REQUEST}" method="post">
					<div class="tbl1">
						<div class="formLabel grid_1"><label for="title">{i18n('Title:')}</label></div>
						<div class="formField grid_4"><input type="text" id="title" name="title" class="num_64"></div>
					</div>

					<div class="tbl2">
						<div class="formLabel grid_1"><label for="note">{i18n('Content:')}</label></div>
						<div class="formField grid_4"><textarea cols="80" name="note" id="note" class="num_255 resize" rows="4"></textarea></div>
					</div>
					<div class="tbl1">
						<div class="formField grid_6"><label for="block_edit"><input type="checkbox" name="block_edit" value="0" id="block_edit">{i18n('Block editing')}<small>{i18n('Only author can edit this note.')}</small></label></div>
					</div>

					<div class="tbl Buttons">
						<div class="center grid_2 button-l">
							<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
						</div>
						<div class="center grid_2 button-r">
							<input type="hidden" name="note_add_save" value="yes" />
							<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></a>
						</div>
					</div>
				</form>
			{else}
				{if $notes}
				<div id="notes">

					{section=notes}
					<h4><a href="#" class="{if $notes.block == 1}{if $notes.author_id == $notes.user_id}edit{/if}{else}edit{/if}" id="{$notes.id}">{$notes.title}</a></h4>
					<div>
						<p>{if $notes.block == 1}<img src="{$ADDR_ADMIN_ICONS}logout.png" alt="">{/if}<small>{i18n('Added')} {$notes.datestamp} {i18n('by')} {$notes.author}</small></p>
						<br>
						<p class="{if $notes.block == 1}{if $notes.author_id == $notes.user_id}edit_area{/if}{else}edit_area{/if}" id="{$notes.id}">{$notes.note}</p>
						<br>
						<p><a href="home.php?action=delete&note_id={$notes.id}"><img src="{$ADDR_ADMIN_ICONS}delete.png" alt=""></a></p>

					</div>
					{/section}
				</div>
				<br>
				{/if}

				{if $current < $notes_per_page}
					<form id="ThisNotes" action="{$URL_REQUEST}" method="post">
						<div class="Buttons">
							<div class="center grid_2 button-c">
								<input type="hidden" name="note_add" value="yes" />

								<span id="SendForm_ThisNotes" class="save"><strong>{i18n('Add')}<img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></span>

							</div>
						</div>
					</form>
				{else}
					<p class="center bold">{i18n('You have reached maximum number of notes. Remove some.')}</p>
				{/if}

			{/if}

			<div class="clear"></div>
		</div>
		-->
		<h3 class="ui-corner-all">{i18n('Statistics')}</h3>
		<div class="HomeBox"><div class="error">{i18n('Not&nbsp;plugged.')}</div></div>

		<h3 class="ui-corner-all">{i18n('Latest comments')}</h3>
		<div class="HomeBox"><div class="error">{i18n('Not&nbsp;plugged.')}</div></div>
</div>
<div class="clear"></div>
