{if $welcome_message}
	{php} opentable(__('Welcome Message')) {/php}
		{$welcome_message}
	{php} closetable() {/php}
{/if}