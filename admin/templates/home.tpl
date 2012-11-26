<div class="ui-corner-all grid_6">
	<h3 class="ui-corner-all top17">{i18n('Update')}</h3>
	<div class="HomeBox"><div class="valid">{i18n('You&nbsp;have&nbsp;currently&nbsp;version&nbsp;eXtreme&nbsp;-&nbsp;Fusion&nbsp;:version', array(':version' => $version))}</div></div>
	
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
	
	<h3 class="ui-corner-all">{i18n('Download')}</h3>
	<div class="HomeBox"><div class="error">{i18n('Not&nbsp;plugged.')}</div></div>
</div>
<div class="ui-corner-all grid_6">
	<h3 class="ui-corner-all">{i18n('Notes')} {if $current}({$current}/{$notes_per_page}){/if}</h3>
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

					<div class="tbl AdminButtons">
						<div class="center grid_2 button-l">
							<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
						</div>
						<div class="center grid_2 button-r">
							<input type="hidden" name="note_add_save" value="yes" />
							<span id="SendForm_This" class="Save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></a>
						</div>
					</div>
				</form>
			{else}
				{if $notes}
				<div id="notes">

					{section=notes}
					<h3><a href="#" class="{if $notes.block == 1}{if $notes.author_id == $notes.user_id}edit{/if}{else}edit{/if}" id="{$notes.id}">{$notes.title}</a></h4>
					<div>
						<p>{if $notes.block == 1}<img src="{$ADDR_ADMIN_ICONS}logout.png" alt="">{/if}<small>{i18n('Added')} {$notes.datestamp} {i18n('by')} <a href="{$SITE_ADDRESS}profile,{$notes.author_id}.html">{$notes.author}</a></small></p>
						<br>
						<p class="{if $notes.block == 1}{if $notes.author_id == $notes.user_id}edit_area{/if}{else}edit_area{/if}" id="{$notes.id}">{$notes.note}</p>
						<br>
						<p><a href="home.php?action=delete&note_id={$notes.id}"><img src="{$ADDR_ADMIN_ICONS}delete.png" alt=""></a></p>

					</div>
					{/section}
				</div>
				<br>
				{/if}

			<form id="ThisNotes" action="{$URL_REQUEST}" method="post">
				<div class="AdminButtons">
					<div class="center grid_2 button-c">
						<input type="hidden" name="note_add" value="yes" />
						<span {if $current < $notes_per_page}id="SendForm_ThisNotes"{/if} class="{if $current < $notes_per_page}Save{/if}"><strong>{if $current < $notes_per_page}{i18n('Add')}{else}{i18n('You have reached maximum number of notes. Remove some.')}{/if}<img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></span>
					</div>
				</div>
			</form>

			{/if}

		<div class="clear"></div>
		</div>
	
	<h3 class="ui-corner-all">{i18n('Quick&nbsp;news')}</h3>
	{if $message && $quick_news_log}<div class="{$class}">{$message}</div>{/if}
	<form id="ThisQuickNews" action="{$URL_REQUEST}" method="post">
		<div class="HomeBox">
				<div class="formField grid_5">
					<input type="text" id="quick_news_title" name="quick_news_title" maxlength="200" class="num_200" value="" />
				</div>
				<div class="clear"></div>
				<div class="formField grid_5">
					<textarea cols="80" name="quick_news_content" id="quick_news_content" class="num_255 resize" rows="4"></textarea>
				</div>
				<div class="clear"></div>
				<div class="tbl AdminButtons">
					<div class="center grid_2 button-c"><input type="hidden" name="quick_news_add" value="yes" />
						<span id="SendForm_ThisQuickNews" class="Save"><strong>{i18n('Add')}<img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></span>
					</div>
				</div>
				<div class="clear"></div>
		</div>
	</form>
</div>
<div class="clear"></div>