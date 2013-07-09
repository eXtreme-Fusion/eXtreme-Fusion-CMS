{if $upgrade}<div class="HomeBox"><div class="valid"><a href="{$ADDR_ADMIN_PAGES}upgrade.php">{i18n('Update is available.')}</a></div></div>{/if}
<h3 class="ui-corner-all">{i18n('Favourites')}</h3>
<div class="tbl">
	{section=pages}
		<div class="EF-Nav ui-corner-all grid_2">
			<a href="{$pages.url}" title="{$pages.title}">
				<img src="{$pages.src}" />
				<span>{$pages.title}</span>
			</a>
		</div>
	{sectionelse}
		<div id="status">{i18n('You do not have favourite items.')}</div>
	{/section}
</div>