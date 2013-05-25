{if $nums}
	<div class="center">
		{if $first}<a href="?{Router('page')}&amp;current={$nums}{$first}{Router('ext')}" title="{i18n('Go to first page')}" class="buttone">Do pierwszej</a>{/if}
			{section=nums}
				{if $nums == $current}
					<strong class="button">{$nums}</strong>
				{else}
					<a href="?page={Router('page')}&amp;current={$nums}{Router('ext')}" title="{i18n('Go to page')}" class="buttone">{$nums}</a>
				{/if}
			{/section}
		{if $last}<a href="?{Router('page')}&amp;current={$nums}{$last}{Router('ext')}" title="{i18n('Go to last page')}" class="buttone">Do ostatniej{/if}
	</div>
{/if}