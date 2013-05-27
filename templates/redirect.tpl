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
{panel=i18n('Ostrzeżenie')}
	<div class="error">
		<p class="bold">Czy jesteś pewny, że chesz opuścić tę witrynę i przejść do linku:</p>
		<p class="small">{$url}</p>
	</div>
	<div class="valid italic">
		<p><a href="{$url}" target="_blank">Jeśli w ciągu 15 sekund nie nastąpiło przekierowanie, kliknij w ten tekst aby przejść do wybranego adresu internetowego</a></p>
	</div>
{/panel}