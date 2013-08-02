{*
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
|
*********************************************************/
*}
{panel=i18n('Notice')}
{if $url_valid}
	<div class="info">
		<p class="bold">{i18n('Are you sure, want to leave this site and go to the selected web address:')}</p>
		<p class="small">{$url}</p>
	</div>
	<div class="valid">
		<p class="small italic"><a href="{$url}" target="_blank">{i18n('If during the 15 seconds you are not redirected, please click on this text to move to chosen web address.')}</a></p>
	</div>
{else}
	<div class="error">
		<p class="bold">{i18n('Given the Internet address is not valid!')}</p>
	</div>
{/if}
{/panel}