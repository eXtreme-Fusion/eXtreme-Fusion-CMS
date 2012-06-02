{php} openside(__('Navigation Panel')) {/php}
{if $nav}
	{section=nav}
		{if $navigation.name == 1}
			<div class="side-label"><strong>{$nav.name}</strong></div>
		{elseif $navigation.name == 2}
			<div><hr class="side-hr" /></div>
		{else}
			<div><img src="{$nav.bullet}">&nbsp;<a href="{$nav.url}" {$nav.link_target}>{$nav.name}</a></div>
		{/if}
	{/section}
{else}
	<div class="error center">No site links</div>
{/if}
{php} closeside() {/php}