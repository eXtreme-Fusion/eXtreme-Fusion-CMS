{section=pages}
	<div class="EF-Nav ui-corner-all grid_2">
		<a href="{$pages.url}" title="{$pages.title}">{$pages.title}</a>
		<span>$pages.title</span>
	</div>
{sectionelse}
	<div id="status">{i18n('You do not have favourite items.')}</div>
{/section}