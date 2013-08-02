{php} $this->sidePanel(__('Cloud of keywords')); {/php}
	<div class="center">
		{if $tags}
			{section=tags}
				<a href="{$tags.url}" style="font-size: 1.{$tags.range}em; color: {$tags.colour};">{$tags.tag}</a>
			{/section}
		{else}
			<div class="admin-message">{i18n('There are no keywords to display.')}</div>
		{/if}
	</div>
{php} $this->sidePanel(); {/php}