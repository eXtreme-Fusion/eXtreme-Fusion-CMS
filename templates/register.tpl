{php} opentable(__('Register')) {/php}
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
						{i18n('Incorrect e-mail.')}
					{elseif $error == 5}
						{i18n('Username is in use.')}
					{elseif $error == 6}
						{i18n('E-Mail is to use.')}
					{elseif $error == 7}
						{i18n('E-Mail znajduje się na czarnej liście')}
					{elseif $error == 8}
						{i18n('Wpisany kod jest nieprawidłowy.')}
					{/if}
				</p>
			{/section}
		</div>
	{/if}
	
	{if $create}
		<div class="valid">{i18n('The user account has been created correctly.')}</div>
	{elseif $email_send}
		<div class="valid">{i18n('Activation email has been sent.')}</div>
	{elseif $active}
		<div class="info">{i18n('Account is waiting for admin activation.')}</div>
	{else}
		<div class="tbl">
			<div class="center grid_12">{i18n('Welcome to', array(':portal' => $portal))}</div>
		</div>
		<h4>{i18n('Create Account')}</h4>
		<form id="This" action="{$URL_REQUEST}" method="post">
			<div class="tbl1">
				<div class="formLabel grid_3"><label for="UserName">{i18n('Username:')}<sup>*</sup></label></div>
				<div class="formField grid_7"><input type="text" name="username" id="UserName" value="{$username}"/></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_3"><label for="UserPass">{i18n('Password:')}<sup>*</sup></label></div>
				<div class="formField grid_7">
					<input type="password" name="user_pass" id="UserPass"/>
				</div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="UserPass2">{i18n('Confirm password:')}<sup>*</sup></label></div>
				<div class="formField grid_7"><input type="password" name="user_pass2" id="UserPass2"/></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_3"><label for="Email">{i18n('E-mail address:')}<sup>*</sup></label></div>
				<div class="formField grid_7"><input type="text" name="user_email" id="Email" value="{$email}"/></div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3">{i18n('Hide e-mail?')}</div>
				<div class="formField grid_7">
					<label for="HideEmail_1"><input type="radio" id="HideEmail_1" name="hide_email" value="1"{if $hide_email == 1} checked="checked"{/if}>{i18n('Yes')}</label>
					<label for="HideEmail_0"><input type="radio" id="HideEmail_0" name="hide_email" value="0"{if $hide_email != 1} checked="checked"{/if}>{i18n('No')}</label>
				</div>
			</div>
			
			{if $enable_terms == '1'}
				<hr />
				<h4>Regulamin</h4>
				
				<div class="tbl">
					<div class="center rules">{$license_agreement}</div>
				</div>
				
				<hr />
			{/if}
			
			{if $data}
				<h4>{i18n('Dodatkowe informacje')}</h4>
				{section=data}
					<div class="{$data.row_color}">
						<div class="formLabel sep_1 grid_3"><label for="{$data.id}">{i18n($data.name)}</label></div>
						<div class="formField grid_7">
							{if $data.type == 1}<input type="text" name="{$data.index}" id="{$data.id}"/>{/if}
							{if $data.type == 2}<textarea name="{$data.index}" id="{$data.name}" rows="3" class="resize">{$data.value}</textarea>{/if}
							{if $data.type == 3}
								<select name="{$data.index}" class="textbox">
									{section=option}
										<option value="{$option.value}"{if $data.value == $option.value} selected="selected"{/if}>{$option.value}</option>
									{/section}
								</select>
							{/if}
						</div>
					</div>
				{/section}
			{/if}
			
			{if $security}{$security}{/if}
			
			<div class="tbl PageButtons">
				<div class="center grid_12 buttons">
					<input type="hidden" name="FieldMax" value="{$i}"/>
					<input type="submit" name="create_account" value="{i18n('Create Account')}" class="button"/>
				</div>
			</div>
		</form>
	{/if}
{php} closetable() {/php}