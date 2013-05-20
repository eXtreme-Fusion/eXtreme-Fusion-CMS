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

{panel=i18n('Edit account')}
{if $message}<div class="{$class}">{$message}</div>{/if}
	<h4>{i18n('Edit account')}</h4>

	<form action="{$URL_REQUEST}" method="post" enctype="multipart/form-data" name="account">
		{if $change_name == 1}
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="UserName">{i18n('Username:')}</label></div>
				<div class="formField grid_7"><input type="text" name="username" value="{$user.username}" id="UserName" /></div>
			</div>
		{else}
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label>{i18n('Username:')}</label></div>
				<div class="formField grid_7"><strong>{$user.username}</strong></div>
			</div>
		{/if}
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="OldPassword">{i18n('Current password:')}<sup>*</sup></label></div>
			<div class="formField grid_7"><input type="password" name="old_password" id="OldPassword" class="long_textbox" /></div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="Password1">{i18n('New password:')}</label></div>
			<div class="formField grid_7"><input type="password" name="password1" id="Password1" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="Password2">{i18n('Confirm new password:')}</label></div>
			<div class="formField grid_7"><input type="password" name="password2" id="Password2" /></div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="Email">{i18n('E-mail address:')}</label></div>
			<div class="formField grid_7"><input type="text" name="email" value="{$user.email}" id="Email" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="HideEmail">{i18n('Hide e-mail:')}</label></div>
			<div class="formField grid_7">
				<label for="HideEmail_1"><input type="radio" id="HideEmail_1" name="hideemail" value="1"{if $user.hide_email == 1} checked="checked"{/if}>{i18n('Yes')}</label>
				<label for="HideEmail_0"><input type="radio" id="HideEmail_0" name="hideemail" value="0"{if $user.hide_email == 0} checked="checked"{/if}>{i18n('No')}</label>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label>{i18n('Avatar:')}</label><small>{i18n('Avatar requirements', array(':filesize' => $avatar_filesize, ':width' => $avatar_width, ':height' => $avatar_height))}</small></div>
			<div class="formField grid_7">
				{if $user.avatar}
					<img src="{$user.avatar}" alt="Avatar">
				{/if}
				<p>
					<input type="file" name="avatar" value="" id="Avatar" rows="1" />
					
				</p>
				
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="Theme">{i18n('Theme:')}</label></div>
			<div class="formField grid_7">
				<select name="theme" class="textbox" id="Theme">
					<option value="Default">Default</option>
					{section=theme_set}
						<option value="{$theme_set.value}" {if $theme_set.selected}selected="selected"{/if}>{$theme_set.display}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="language">{i18n('Language:')}</label></div>
			<div class="formField grid_7">
				<select name="language" class="textbox" id="language">
					{section=locale_set}
						<option value="{$locale_set.value}" {if $locale_set.selected}selected="selected"{/if}>{$locale_set.display}</option>
					{/section}
				</select>
			</div>
		</div>

		<h4>{i18n('Additional informations')}</h4>

		{section=cats}
			<div class="tbl1">
				<div class="formField sep_1 grid_10">{$cats.name}</div>
			</div>
			{section=fields}
				<div class="tbl2">
					<div class="formLabel sep_1 grid_3"><label for="{$fields.label}">{i18n($fields.name)}:</label></div>
					{if $fields.type == 1}
						<div class="formField grid_7">
							<input type="text" name="{$fields.index}" value="{if $fields.value}{$fields.value}{/if}" id="{$fields.label}" />
						</div>
					{elseif $fields.type == 3}
						<div class="formField grid_7">
							<select name="{$fields.index}" class="textbox">
								{foreach=$fields.option; value}
									<option value="{@value.value}"{if @value.selected} selected="selected"{/if}>{@value.display}</option>
								{/foreach}
							</select>
						</div>
					{else}
							<div class="formField grid_7">
								<div><textarea name="{$fields.index}" id="{$fields.label}" rows="3" class="resize">{$fields.value}</textarea></div>
							</div>
						</div>
						<div class="tbl2">
							<div class="line center">
								{foreach=$fields.bbcode; bbcode}
									<button type="button" onClick="addText('{$fields.index}', '[{@bbcode.value}]', '[/{@bbcode.value}]', 'account');"><img src="{@bbcode.image}" title="{@bbcode.description}" class="tip"></button>
								{/foreach}
							</div>
							<div class="line center">
								{foreach=$fields.smiley; smiley}
									<img src="{$ADDR_IMAGES}smiley/{@smiley.image}" title="{@smiley.text}" class="tip" onclick="insertText('{$fields.index}', '{@smiley.code}', 'account');">
									{if @smiley.i % 20 == 0}</div><div">{/if}
								{/foreach}
							</div>
					{/if}
				</div>
			{/section}
		{/section}
		<div class="tbl center">
			<input type="hidden" name="save" value="yes" />
			<input type="submit" name="save" class="button" value="{i18n('Save')}">
		</div>
	</form>
{/panel}