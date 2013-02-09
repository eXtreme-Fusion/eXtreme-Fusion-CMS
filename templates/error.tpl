{if $error == '401'}
	<h1 align="center">Error 401</h1></td>
	<p align="center"><b>Brak autoryzacji</b></td>
{elseif $error == '404'}
	<h1 align="center">Error 404</h1></td>
	<p align="center"><b>Nie znaleziono strony o podanym adresie</b></td>
{elseif $error == '403'}
	<h1 align="center">Error 403</h1></td>
	<p align="center"><b>Brak dostępu do tej części strony</b></td>
{elseif $error == '500'}
	<h1 align="center">Error 500</h1></td>
	<p align="center"><b>Bł±d Wewnętrzny Serwera.<br>Za utrudnienia Przepraszamy</b></td>
{/if}