{if $nums}
	<div class="center">
	{if ! Router('action') || Router('action') == 'page'}
		{if $first}<a href="{url("controller =>", Router('controller'), 'page', $first)}" title="{i18n('Go to first page')}" class="buttone">Do pierwszej</a>{/if}
			{section=nums}
				{if $nums == $current}
					<strong class="button">{$nums}</strong>
				{else}
					<a href="{url("controller =>", Router('controller'), 'page', $nums)}" title="{i18n('Go to page')}" class="buttone">{$nums}</a>
				{/if}
			{/section}
		{if $last}<a href="{url("controller =>", Router('controller'), 'page', $last)}" title="{i18n('Go to last page')}" class="buttone">Do ostatniej</a>{/if}
	{else}
		{if $first}<a href="{url("controller =>", Router('controller'), Router('action'), Router(2), Router(3), 'page', $first)}" title="{i18n('Go to first page')}" class="buttone">Do pierwszej</a>{/if}
			{section=nums}
				{if $nums == $current}
					<strong class="button">{$nums}</strong>
				{else}
					<a href="{url("controller =>", Router('controller'), Router('action'), Router(2), Router(3), 'page', $nums)}" title="{i18n('Go to page')}" class="buttone">{$nums}</a>
				{/if}
			{/section}
		{if $last}<a href="{url("controller =>", Router('controller'), Router('action'), Router(2), Router(3), 'page', $last)}" title="{i18n('Go to last page')}" class="buttone">Do ostatniej</a>{/if}
	{/if}
	</div>
{/if}