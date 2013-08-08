{if $error == '401'}
	{panel=i18n('Error 401 - Unauthorized')}
		<p class="center error bold">{i18n('The request requires user authentication')}</p>
	{/panel}
{elseif $error == '404'}
	{panel=i18n('Error 404 - Page not found')}
		<p class="center error bold">{i18n('Not found this pages')}</p>
	{/panel}	
{elseif $error == '403'}
	{panel=i18n('Error 403 - Forbidden')}
		<p class="center error bold">{i18n('Access this page is forbidden')}</p>
	{/panel}
{elseif $error == '500'}
	{panel=i18n('Error 500 - Internal Server Error')}
		<p class="center error bold">{i18n('Internal Server Error')}</p>
	{/panel}
{/if}