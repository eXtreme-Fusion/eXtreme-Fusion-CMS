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

<div id="Content">
	<table style="width:100%;height:100%">
		<tr>
			<td>
				<table cellpadding="0" cellspacing="1" width="80%" class="tbl-border center">
					<tr>
						<td class="tbl1">
							<div class="center">
								<div><img src="{$maintenance.sitebanner}" alt="{$maintenance.sitename}" /></div>
								<p>{$maintenance.message}</p>
							</div>
						</td>
					</tr>
				</table>
				{if !$maintenance.logged_in}
					{if $maintenance.login_form}
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
						</div>
					{/if}
				{else}
					<div><p class="center">&raquo; <a href="{$ADDR_SITE}">Strona główna</a></p></div>
				{/if}
	
				<div><p class="center">&raquo; <a href="{$ADDR_ADMIN}">Panel Administracyjny</a></p></div>
			</td>
		</tr>
	</table>
</div>