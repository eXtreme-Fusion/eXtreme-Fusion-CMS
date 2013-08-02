{if $is_logged_in}
	{php} $this->sidePanel(__('User Panel')); {/php}
        <div id="user_info_panel">
					<div class="top">
						<strong>{i18n('Hello :username!', array(':username' => $username))}</strong>
						<a href="{$url_messages}"{if $messages} class="message"{/if}></a>
					</div>
					<div class="mid">
						<div class="avatar">
							<img src="{$avatar}" alt="Avatar" />
						</div>
						<ul id="nav">
							<li><a href="{$url_account}"><p class="edit">{i18n("Edit profile")}</p></a></li>
							<li><a href="{$url_messages}"><p class="messages">{i18n("Messages")}</p></a></li>
							<li><a href="{$url_users}"><p class="members">{i18n("Users")}</p></a></li>
							{if $is_admin}<li><a href="{$ADDR_ADMIN}" class="long"><p class="admin">{i18n("Admin Panel")}</p></a></li>{/if}
						</ul>
					</div>
					<div class="bot">
						<p><a href="{$url_logout}" class="button">{i18n("Logout")}</a></p>
					{if $messages}
						<p><a href="{$url_messages}">{$messages}</a></p>
                    {/if}
					</div>
				</div>
	{php} $this->sidePanel(); {/php}
{else}
	{php} $this->sidePanel(__('Login')); {/php}
	<div id="user_info_panel">
		<form method="post" action="{$URL_REQUEST}">
			<input type="text" name="username" id="username" class="valueSystem" value="{i18n("Username:")}">
			<input type="password" name="password" id="password" class="valueSystem" value="{i18n("Password:")}">
			<div>
				<input type="checkbox" name="remember_me" value="y" id="remember">
				<label for="remember">{i18n("Remember me")}</label>
			</div>
			<input type="submit" name="login" value="{i18n("Login")}">
		</form>
		{if $enable_reg}<p><a href="{$url_register}">{i18n("Register")}</a></p>{/if}
		<p><a href="{$url_password}">{i18n("Forgot password")}</a></p>
	</div>
	{php} $this->sidePanel(); {/php}
{/if}