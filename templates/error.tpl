{if $error == '401'}
	{panel=i18n('Error 401')}
		<p class="center error bold">Brak autoryzacji.</p>
	{/panel}
{elseif $error == '404'}
	{panel=i18n('Error 404')}
		<p class="center error bold">Nie znaleziono strony o podanym adresie.</p>
	{/panel}	
{elseif $error == '403'}
	{panel=i18n('Error 403')}
		<p class="center error bold">Brak dostępu do tej części strony.</p>
	{/panel}
{elseif $error == '500'}
	{panel=i18n('Error 500')}
		<p class="center error bold">Błąd Wewnętrzny Serwera.<br>Za utrudnienia Przepraszamy.</p>
	{/panel}
{/if}