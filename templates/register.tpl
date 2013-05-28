{panel=i18n('Register')}
	{if $error}
	<div class="error">
		{section=error}
		<p>
			{if $error == 1}
			{i18n('Fields marked with an asterisk are required.')}
			{elseif $error == 2}
			{i18n('Username contains illegal characters.')}
			{elseif $error == 3}
			{i18n('The passwords do not match.')}
			{elseif $error == 4}
			{i18n('Incorrect e-mail address.')}
			{elseif $error == 5}
			{i18n('Username is already in use.')}
			{elseif $error == 6}
			{i18n('E-mail address is already in use.')}
			{elseif $error == 7}
			{i18n('E-mail address is on blacklist.')}
			{elseif $error == 8}
			{i18n('Security code is not valid.')}
			{/if}
		</p>
		{/section}
	</div>
	{/if}

	{if $create}
	<div class="valid">{i18n('The user account has been created successfully.')}</div>
	{elseif $email_send}
	<div class="valid">{i18n('Activation e-mail has been sent.')}</div>
	{elseif $email_not_send}
	<div class="error">{i18n('The user account has been created successfully however activation e-mail has not been sent. Please contact an administrator and report the problem to him')}</div>
	{elseif $active}
	<div class="info">{i18n('Account is waiting for an admin activation.')}</div>
	{else}
	<div class="tbl">
		<div class="center grid_12">{i18n('Welcome to', array(':portal' => $portal))}</div>
	</div>
	<h4>{i18n('Create Account')}</h4>
	<form action="{$URL_REQUEST}" method="post">
		<div class="tbl1">
			<div class="formLabel grid_3"><label for="login">{i18n('Username:')}<sup>*</sup></label></div>
			<div class="formField grid_7"><input type="text" name="username" id="login" value="{$username}"></div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_3"><label for="pass">{i18n('Password:')}<sup>*</sup></label></div>
			<div class="formField grid_7">
				<input type="password" name="user_pass" id="pass">
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_3"><label for="confirm_password">{i18n('Confirm password:')}<sup>*</sup></label></div>
			<div class="formField grid_7"><input type="password" name="user_pass2" id="confirm_password"></div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_3"><label for="email">{i18n('E-mail address:')}<sup>*</sup></label></div>
			<div class="formField grid_7"><input type="text" name="user_email" id="email" value="{$email}"></div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_3"><label>{i18n('Hide e-mail?')}</label></div>
			<div class="formField grid_7">
				<label for="hide_email"><input type="radio" id="hide_email" name="hide_email" value="1"{if $hide_email == 1} checked{/if}>{i18n('Yes')}</label>
				<label for="show_email"><input type="radio" id="show_email" name="hide_email" value="0"{if $hide_email != 1} checked{/if}>{i18n('No')}</label>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_3"><label for="language">{i18n('Language:')}</label></div>
			<div class="formField grid_7">
				<select name="language" class="textbox" id="language">
					{section=locale_set}
					<option value="{$locale_set.value}"{if $locale_set.selected} selected{/if}>{$locale_set.display}</option>
					{/section}
				</select>
			</div>
		</div>

		{if $enable_terms == '1'}
		<h4>{i18n('Rules')}</h4>
		<div class="tbl">
			<div class="center rules">{$license_agreement}</div>
		</div>
		{/if}

		{if $data}
		<h4>{i18n('Additional information')}</h4>
		{section=data}
		<div class="{$data.row_color}">
			<div class="formLabel grid_3"><label for="{$data.id}">{i18n($data.name)}</label></div>
			<div class="formField grid_7">
				{if $data.type == 1}<input type="text" name="{$data.index}" id="{$data.id}">{/if}
				{if $data.type == 2}<textarea name="{$data.index}" id="{$data.name}" rows="3" class="resize">{$data.value}</textarea>{/if}
				{if $data.type == 3}
				<select name="{$data.index}" class="textbox">
					{section=option}
					<option value="{$option.value}"{if $data.value == $option.value} selected{/if}>{$option.value}</option>
					{/section}
				</select>
				{/if}
			</div>
		</div>
		{/section}
		{/if}

		{if $security}{$security}{/if}

		<div class="tbl center">
			<input type="hidden" name="FieldMax" value="{$i}">
			<input type="submit" name="create_account" value="{i18n('Create Account')}" class="button">
		</div>
	</form>
	{/if}
{/panel}