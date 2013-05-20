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

{if $is_logged_in}
	{panel=i18n('User Panel')}
		<div class="center">
			<ul>
				<li><a href="{$url_account}" class="side">{i18n("Edit profile")}</a></li>
				<li><a href="{$url_messages}" class="side">{i18n("Messages")}</a></li>
				<li><a href="{$url_users}" class="side">{i18n("Users")}</a></li>
				{if $is_admin}
					<li><a href="{$ADDR_ADMIN}" class="side">{i18n("Admin Panel")}</a></li>
				{/if}
				<li><a href="{$url_logout}" class="side">{i18n("Logout")}</a></li>
			</ul>
			{if $messages}
				<p class="bold"><a href="{$url_messages}" class="side">{$messages}</a></p>
			{/if}
		</div>
	{/panel}
{else}
	{panel=i18n('Login')}
		<div style="text-align:center"><br />
			<form method="post" action="{$URL_REQUEST}">
				<div>
					<label for="username">{i18n("Username:")}</label>
					<div><input type="text" name="username" id="username" class="textbox" style="width:100px" /></div>
				</div>
				<div>
					<label for="password">{i18n("Password:")}</label>
					<div><input type="password" name="password" id="password" class="textbox" style="width:100px" /></div>
				</div>
				<div>
					<input type="checkbox" name="remember_me" value="y" id="remember" />
					<label for="remember">{i18n("Remember me")}</label>
				</div>
				<div><input type="submit" name="login" value="{i18n("Login")}" class="button" /></div>
			</form>
			{if $enable_reg}<div><a href="{$url_register}" class="side"><span>{i18n("Register")}</span></a></div>{/if}
			<div><a href="{$url_password}" class="side"><span>{i18n("Forgot password")}</span></a></div>
		</div>
	{/panel}
{/if}