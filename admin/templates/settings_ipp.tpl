<h3>{$SystemVersion} - {i18n('Settings')} &raquo; {i18n('Item per Page')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="NewsPerPage">{i18n('News per page:')}</label><small>({i18n('This should be an uneven number.')})</small></div>
		<div class="grid_4 formField"><input type="text" name="news_per_page" value="{$news_per_page}" id="NewsPerPage" class="num_2" maxlength="2" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="NewsCatsPerPage">{i18n('News cats per page:')}</label><small>({i18n('This should be an uneven number.')})</small></div>
		<div class="grid_4 formField"><input type="text" name="news_cats_per_page" value="{$news_cats_per_page}" id="NewsCatsPerPage" class="num_2" maxlength="2" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="NewsCatsIteamPerPage">{i18n('News cats item per page:')}</label><small>({i18n('This should be an uneven number.')})</small></div>
		<div class="grid_4 formField"><input type="text" name="news_cats_item_per_page" value="{$news_cats_item_per_page}" id="NewsCatsIteamPerPage" class="num_2" maxlength="2" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="UserPerPage">{i18n('Users per page:')}</label></div>
		<div class="grid_4 formField"><input type="text" name="users_per_page" value="{$users_per_page}" id="UserPerPage" class="num_2" maxlength="2" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="NotesPerPage">{i18n('Notes per page:')}</label></div>
		<div class="grid_4 formField"><input type="text" name="notes_per_page" value="{$notes_per_page}" id="NotesPerPage" class="num_2" maxlength="2" /></div>
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