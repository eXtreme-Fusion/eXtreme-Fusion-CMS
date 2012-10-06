{php} openside(__('Navigation Panel')) {/php}
		<nav id="side_nav">
						<ul>
{if $nav}
	{section=nav}
		{if $navigation.name == 1}
			<div class="side-label"><strong>{$nav.name}</strong></div>
		{elseif $navigation.name == 2}
			<div><hr class="side-hr" /></div>
		{else}
				<li><a href="{$nav.url}"{if $nav.link_target} {$nav.link_target}{/if}>{$nav.name}</a></li>
{/if}
	{/section}
{else}
	<div class="error center">No site links</div>
{/if}
					</ul>
					</nav>
{php} closeside() {/php}