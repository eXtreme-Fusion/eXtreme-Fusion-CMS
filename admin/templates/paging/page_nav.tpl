{if $nums}
	<div class="center">
		{if $first}<a href="?{$page}&amp;{$id}{$first}{$ext}" title="{i18n('Go to first page')}" class="buttone">Do pierwszej</a>{/if}
			{section=nums}
				{if $nums == $current}
					<strong class="button">{$nums}</strong>
				{else}
					<a href="?{$page}&amp;{$id}{$nums}{$ext}" title="{i18n('Go to page')}" class="buttone">{$nums}</a>
				{/if}
			{/section}
		{if $last}<a href="?{$page}&amp;{$id}{$last}{$ext}" title="{i18n('Go to last page')}" class="buttone">Do ostatniej{/if}
	</div>
{/if}