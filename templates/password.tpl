{php} opentable(__('Odzyskiwanie dostępu do konta')); {/php}
{if $message}<div class="{$class}">{$message}</div>{/if}

{if ! $action}
	<form action="{$URL_REQUEST}" method="post">
		<div>Podaj adres e-mail: <input type="text" name="email" /></div>
		<div><input type="submit" name="check" /></div>
	</form>
{/if}

{php} closetable(); {/php}