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
			<li class="tbl1"><a href="{$url_account}" class="side">{i18n("Edit profile")}</a></li>
			<li class="tbl2"><a href="{$url_messages}" class="side">{i18n("Messages")}</a></li>
			<li class="tbl1"><a href="{$url_users}" class="side">{i18n("Users")}</a></li>
			{if $is_admin}
			<li class="tbl2"><a href="{$ADDR_ADMIN}" class="side">{i18n("Admin Panel")}</a></li>
			{/if}
			<li class="{if $is_admin}tbl1{else}tbl2{/if}"><a href="{$url_logout}" class="side">{i18n("Logout")}</a></li>
		</ul>
		{if $messages}
		<p class="bold"><a href="{$url_messages}" class="side">{$messages}</a></p>
		{/if}
	</div>
{/panel}
{else}
{panel=i18n('Login')}
	<div class="center">
		<form method="post" action="{$URL_REQUEST}">
			<div class="tbl1">
				<div class="formLabel grid_3"><label for="login">{i18n("Username:")}</label></div>
				<div class="formField grid_7"><input type="text" name="username" id="login"></div>
			</div>
			<div class="tbl2">
				<div class="formLabel grid_3"><label for="pass">{i18n("Password:")}</label></div>
				<div class="formField grid_7"><input type="password" name="password" id="pass"></div>
			</div>
			<div class="tbl1">
				<div class="formLabel grid_3"><label for="remember">{i18n("Remember me")}:</label></div>
				<div class="formField grid_7"><input type="checkbox" name="remember_me" value="y" id="remember"></div>
			</div>
			<div class="tbl2 center">
				<input type="submit" name="login" value="{i18n("Login")}" class="button">
			</div>
		</form>
		<div class="tbl">
			{if $enable_reg}<p><a href="{$url_register}" class="side">{i18n("Register")}</a></p>{/if}
			<p><a href="{$url_password}" class="side">{i18n("Forgot password")}</a></p>
		</div>
	{/panel}
{/if}