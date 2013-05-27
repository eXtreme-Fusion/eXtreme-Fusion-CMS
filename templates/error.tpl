{if $error == '401'}
	{panel=i18n('Error 401')}
		<h4 class="center info">Error 401</h4>
		<p class="center error bold">Brak autoryzacji.</p>
	{/panel}
{elseif $error == '404'}
	{panel=i18n('Error 404')}
		<h4 class="center info">Error 404</h4>
		<p class="center error bold">Nie znaleziono strony o podanym adresie.</p>
	{/panel}	
{elseif $error == '403'}
	{panel=i18n('Error 403')}
		<h4 class="center info">Error 403</h4></td>
		<p class="center error bold">Brak dostępu do tej części strony.</p>
	{/panel}
{elseif $error == '500'}
	{panel=i18n('Error 500')}
		<h4 class="center info">Error 500</h4>
		<p class="center error bold">Bł±d Wewnętrzny Serwera.<br>Za utrudnienia Przepraszamy.</p>
	{/panel}
{/if}