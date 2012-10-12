{if $tag}
	{php} opentable(__('Materiały zawierające słowo kluczowe: :tagTitle', array(':tagTitle' => $__Tag_val['tag_name']))) {/php}
		<div class="tbl2">
			<div class="grid_8 bold">{i18n('News')}</div>
		</div>
		{section=tag}
			<div class="tbl">
				<div class="grid_8"><a href="{$tag.tag_url_iteam}">{$tag.tag_title_iteam}</a></div>
			</div>
		{/section}
	<a href="{$url_tag}">Wróć do listy tagów.</a>
{elseif $tags}
	{php} opentable(__('Lista słów kluczowych')) {/php}
		<div class="tbl2">
			<div class="grid_3 bold">{i18n('Tag')}</div>
			<div class="grid_8 bold">{i18n('News')}</div>
		</div>
		{section=tags}
			<div class="tbl">
				<div class="grid_3"><span><a href="{$tags.tag_url}">{$tags.tag_name}</a></span></div>
				<div class="grid_8"><a href="{$tags.tag_url_iteam}">{$tags.tag_title_iteam}</a></div>
			</div>
		{/section}
{else}
	{php} opentable(__('Brak tagów w bazie')) {/php}	
		<div class="tbl2">
			<div class="status">{i18n('Brak takiego tagu')}</div>
		</div>
	{/if}
{php} closetable() {/php}