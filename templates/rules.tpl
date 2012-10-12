{php} opentable(__('Rules')) {/php}
	{if $license_agreement}
		{$license_agreement}
	{else}
		<div class="error">{i18n('The Rules has not been written yet.')}</div>
	{/if}
{php} closetable() {/php}