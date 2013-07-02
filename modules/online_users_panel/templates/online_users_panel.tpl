{php} $this->sidePanel(__('Users online')); {/php}
	<dl id="users_online">
		<dt>{i18n('Guests online')}:</dt>
		<dd>{$guests}</dd>
		<dt>{i18n('Logged in users')}:</dt>
		<dd>{$members}</dd>
		<dt>{i18n('Total members')}:</dt>
		<dd>{$total}</dd>
		<dt>{i18n('Last member')}:</dt>
		<dd>{$member}</dd>
		{if $inactive}
		<dt>{i18n('Inactive Members')}:</dt>
		<dd>{$member}</dd>
		{/if}
	</dl>
	{if $online}
	<p class="links">
		{section=online}
		{$online}{if ! $opt.section.online.last}, {/if}
		{/section}
	</p>
	{/if}
{php} $this->sidePanel(); {/php}