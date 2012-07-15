{if $is_logged_in}
	{php} openside(__('User Panel')) {/php}
		<div id="user_panel">
			<div>Witaj <b>{$user.username}!</b></div>
			<div style="float:left">{if $user.avatar}
                    <img src="{$ADDR_IMAGES}avatars/{$user.avatar}" class="avatar" alt="Avatar">
                {else}
                    <img src="{$ADDR_IMAGES}avatars/none.gif" class="avatar" alt="No Avatar">
                {/if}
            </div>
			<ul>
				<li><a href="{$url_account}">{i18n("Edit profile")}</a></li>
				<li><a href="{$url_messages}">{i18n("Messages")}</a></li>
				<li><a href="{$url_users}">{i18n("Users")}</a></li>
				<li><a href="{$url_logout}">{i18n("Logout")}</a></li>
			</ul>
			{if $is_admin}
            	<div><a href="{$ADDR_ADMIN}" class="button">{i18n("Admin Panel")}</a></div>
			{/if}
			{if $messages}
				<p class="bold"><a href="{$url_messages}">{$messages}</a></p>
			{/if}
		</div>
	{php} closeside() {/php}
{else}
	{php} openside(__('Login')) {/php}
		<div id="user_panel">
			<form method="post" action="{$URL_REQUEST}">
				<div>
					<label for="username">{i18n("Username:")}</label>
					<div><input type="text" name="username" id="username" class="textbox" /></div>
				</div>
				<div>
					<label for="password">{i18n("Password:")}</label>
					<div><input type="password" name="password" id="password" class="textbox" /></div>
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
