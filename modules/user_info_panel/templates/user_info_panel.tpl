{if $is_logged_in}
	{php} openside(__('User Panel')) {/php}
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
	{php} closeside() {/php}
{else}
	{php} openside(__('Login')) {/php}
		<div style="text-align:center">
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
	{php} closeside() {/php}
{/if}
