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
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
*}

{panel=i18n('Recovering access to your account')}
	{if $message}<div class="{$class}">{$message}</div>{/if}

	{if !$action}
	<div id="password_form">
		<p>{i18n('Enter your e-mail address to which the account is registered.')}</p>
		<p>{i18n('The new password will be created and sent to this address.')}</p>
		<form action="{$URL_REQUEST}" method="post">
			<input type="email" name="email" required>
			<input type="submit" name="check" value="Wyślij hasło" class="button">
		</form>
	</div>
	{/if}
{/panel}