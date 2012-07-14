{php} opentable(__('Odzyskiwanie dostępu do konta')); {/php}
{if $message}<p class="{$class}">{$message}</p>{/if}

{if !$action}
	<div id="passwordForm">
		<p>Podaj adres e-mail, na który konto zostało zarejestrowane.<br>Nowe hasło zostanie automatycznie utworzone i na niego wysłane.</p>
		<form action="{$URL_REQUEST}" method="post">
			<label><input type="email" name="email" class="textbox" required></label>
			<input type="submit" name="check" value="Wyślij hasło" class="button">
		</form>
	</div>
{/if}

{php} closetable(); {/php}