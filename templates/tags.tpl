{if $tag}
	{php} opentable(__('Materiały zawierające słowo kluczowe: :tagTitle', array(':tagTitle' => $this->data['tag_name']))) {/php}
		<p class="tag_top dark text_dark">
			<a href="{$url_tag}">Tagi</a> <img src="{$THEME_IMAGES}bullet.png" alt=""> <strong>{$tag_name}</strong> ({$tag_frequency})
		</p>
		<ul class="tagged_elements clearfix">
		{section=tag}
			<li><a href="{$tag.tag_url_iteam}" class="light">{$tag.tag_title_iteam}</a></li>
		{/section}
		</ul>
		<a href="{$url_tag}" class="button">Wróć do listy tagów</a>
	{php} closetable() {/php}
{elseif $tags}
	{php} opentable(__('Lista słów kluczowych')) {/php}
		<div class="tag_cloud">
			<ul>
				{section=tags}
					<li><a href="{$tags.tag_url}" rel="{if $tags.tag_frequency<30}{$tags.tag_frequency}{else}30{/if}">{$tags.tag_name}</a></li>
				{/section}
			</ul>
		</div>
	{php} closetable() {/php}
{else}
	{php} opentable(__('Brak tagów w bazie')) {/php}	
		<p class="status">{i18n('Brak takiego tagu')}</p>
	{php} closetable() {/php}
{/if}