{php} $this->sidePanel(__('Cloud of tags')); {/php}
	<div class="center">
		{if $tags}
			{section=tags}
				<a href="{$tags.url}" style="font-size: 1.{$tags.range}em; color: {$tags.colour};">{$tags.tag}</a>
			{/section}
		{else}
			<div class="admin-message">{i18n('There are no tags to display.')}</div>
		{/if}
	</div>
{php} $this->sidePanel(); {/php}