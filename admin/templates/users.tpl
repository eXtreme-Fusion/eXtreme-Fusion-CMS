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
| Author: Paul Buek (Muscapaul)
| Author: Hans Kristian Flaatten (Starefossen)
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

<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Users')}</h3>
<link href="{$ADDR_ADMIN_CSS}user.css" media="screen" rel="stylesheet" />

		<div class="center tbl Buttons">
			<div class="grid_2 center button-l">
				{if $page === 'add'}
					<span class="Cancels"><strong>{i18n('Create account')} <img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></span>
				{else}
					<span><a href="{$FILE_SELF}?page=add"><strong>{i18n('Create account')}<img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></a></span>
				{/if}
			</div>
			{if $page !== 'users'}
				<div class="center grid_2 button-c">
					<span><a href="{$FILE_SELF}?page=users"><strong>{i18n('Users')}<img src="{$ADDR_ADMIN_ICONS}pixel/user.png" alt="" /></strong></a></span>
				</div>
			{/if}
			<div class="grid_2 center button-r">
				{if $page === 'mail'}
					<span class="Cancels"><strong>{i18n('Send e-mail')}<img src="{$ADDR_ADMIN_ICONS}pixel/mail.png" alt="" /></strong></span>
				{else}
					<span><a href="{$FILE_SELF}?page=mail"><strong>{i18n('Send e-mail')}<img src="{$ADDR_ADMIN_ICONS}pixel/mail.png" alt="" /></strong></a></span>
				{/if}
			</div>
		</div>

		<div class="buttons-bg">&nbsp;</div>

		{if $page !== 'mail'}
			<div class="tbl">
				<div class="grid_5 right">{i18n('Find user:')}</div>
				<div class="grid_7"><input type="text" id="search_user" style="width:300px;" /></div>
			</div>
			<div class="tbl">
				<div id="search_user_result" class="sep_3 grid_7"></div>
			</div>

			<div class="buttons-bg">&nbsp;</div>
		{/if}

	{if $page === 'users'}
		{if $action !== 'edit'}
				<div class="center tbl Buttons">
					<div class="grid_2 center button-l">
					{if $page === 'users' && $status === 'active'}
						<span class="Cancels"><strong>{i18n('Active')}<img src="{$ADDR_ADMIN_ICONS}pixel/user.png" alt="" /></strong></span>
					{else}
						<span><a href="{$FILE_SELF}?page=users"><strong>{i18n('Aktywne')}<img src="{$ADDR_ADMIN_ICONS}pixel/user.png" alt="" /></strong></a></span>
					{/if}
				</div>
				<div class="center grid_2 button-c">
					{if $page === 'users' && $status === 'suspend'}
						<span class="Cancels"><strong>{i18n('Banned')}</strong></span>
					{else}
						<span><a href="{$FILE_SELF}?page=users&amp;status=suspend"><strong>{i18n('Banned')}</strong></a></span>
					{/if}
				</div>
				<div class="center grid_2 button-c">
					{if $page === 'users' && $status === 'unactive'}
						<span class="Cancels"><strong>{i18n('Deactivated')}</strong></span>
					{else}
						<span><a href="{$FILE_SELF}?page=users&amp;status=unactive"><strong>{i18n('Deactivated')}</strong></a></span>
					{/if}
				</div>
				<div class="center grid_2 button-c">
					{if $page === 'users' && $status === 'required'}
						<span class="Cancels"><strong>{i18n('Activation required')}</strong></span>
					{else}
						<span><a href="{$FILE_SELF}?page=users&amp;status=required"><strong>{i18n('Activation required')}</strong></a></span>
					{/if}
				</div>
				<div class="grid_2 center button-r">
					{if $page === 'users' && $status === 'hidden'}
						<span class="Cancels"><strong>{i18n('Hidden')}</strong></span>
					{else}
						<span><a href="{$FILE_SELF}?page=users&amp;status=hidden"><strong>{i18n('Hidden')}</strong></a></span>
					{/if}
				</div>
			</div>
		{/if}

		<div class="buttons-bg">&nbsp;</div>
			{if $message}<div class="{$class}">{$message}</div>{/if}
			<div class="info">{$info}</div>
		<div class="buttons-bg">&nbsp;</div>

		{if $action === 'edit'}
			<h4>{i18n('Edit account :username', array(':username' => $user.username))}{$Error}</h4>
			<form id="This" class="UserAdd" action="{$URL_REQUEST}" method="post" enctype="multipart/form-data">
				<div class="tbl1">
					<div class="formLabel sep_1 grid_3"><label for="username">{i18n('Username:')}</label></div>
					<div class="formField grid_7"><input type="text" name="username" value="{$user.username}" id="username" /></div>
				</div>
				<div class="tbl2">
					<div class="formLabel sep_1 grid_3"><label for="password1">{i18n('Password:')}<br /><small>{i18n('Minimum 6 characters.')}</small></label></div>
					<div class="formField grid_7"><input type="password" name="user_pass" id="password1" /></div>
				</div>
				<div class="tbl1">
					<div class="formLabel sep_1 grid_3"><label for="password2">{i18n('Confirm password:')}</label></div>
					<div class="formField grid_7"><input type="password" name="user_pass2" id="password2" /></div>
				</div>
				<div class="tbl2">
					<div class="formLabel sep_1 grid_3"><label for="email">{i18n('E-mail address:')}</label></div>
					<div class="formField grid_7"><input type="text" name="user_email" value="{$user.email}" id="email" /></div>
				</div>
				<div class="tbl1">
					<div class="formLabel sep_1 grid_3">{i18n('Hide e-mail:')}</div>
					<div class="formField grid_7">
						<label for="hide_email_1"><input type="radio" id="hide_email_1" name="hide_email" value="1"{if $user.hide_email == 1} checked="checked"{/if}>{i18n('Yes')}</label>
						<label for="hide_email_0"><input type="radio" id="hide_email_0" name="hide_email" value="0"{if $user.hide_email == 0} checked="checked"{/if}>{i18n('No')}</label>
					</div>
				</div>
				<div class="tbl2">
					<div class="formLabel sep_1 grid_3">{if $user.avatar}<img src="{$ADDR_IMAGES}avatars/{$user.avatar}"><br />{/if}<label for="Avatar">{i18n('Avatar:')}</label></div>
					<div class="formField grid_7"><input type="file" name="avatar" id="Avatar" rows="1" /><small>{i18n('Avatar requirements', array(':filesize' => $avatar_filesize, ':width' => $avatar_width, ':height' => $avatar_height))}</small></div>
				</div>
				<div class="tbl1">
					<div class="formLabel sep_1 grid_3">{i18n('Theme')}:</div>
					<div class="formField grid_7">
						<select name="theme_set" id="ThemeSet">
							<option value="Default">{i18n('Default')}</option>
							{section=theme_set}
								<option value="{$theme_set.value}"{if $theme_set.selected} selected="selected"{/if}>{$theme_set.display}</option>
							{/section}
						</select>
					</div>
				</div>

					<input type="hidden" name="roles[]" value="2" />
					<input type="hidden" name="roles[]" value="3" />

				<div class="tbl2">
					<div class="formLabel sep_1 grid_3">{i18n('Roles:')}{i18n('Default roles')}</div>
					<div class="formField grid_7">
						<select name="roles[]" multiple id="InsightGroups" class="select-multi" size="5" >
							{section=all_groups}
								{if $all_groups.value != 2 && $all_groups.value != 3}
									<option value="{$all_groups.value}"{if $all_groups.selected} selected="selected"{/if}>{$all_groups.display}</option>
								{/if}
							{/section}
						</select>
					</div>
				</div>
				<div class="tbl1">
					<div class="formLabel sep_1 grid_3"><label for="role">{i18n('Main role:')}</label></div>
					<div class="formField grid_7">
						<select name="role" id="user_groups">
							{section=insight_groups}
								{if $insight_groups.value}
									<option value="{$insight_groups.value}"{if $insight_groups.selected} selected="selected"{/if}>{$insight_groups.display}</option>
								{/if}
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
				{section=cats}
					<div class="tbl1">
						<div class="formField sep_1 grid_10">{$cats.name}</div>
					</div>
					{section=fields}
						<div class="tbl2">
							<div class="formLabel sep_1 grid_3"><label for="{$fields.name}">{i18n($fields.name)}</label></div>
							{if $fields.type == 1}
								<div class="formField grid_7">
									<input type="text" name="data[{$fields.index}]" value="{if $fields.value}{$fields.value}{/if}" id="{$fields.name}" />
								</div>
							{elseif $fields.type == 3}
								<div class="formField grid_7">
									<select name="data[{$fields.index}]" class="textbox">
										{foreach=$fields.option; var}
											<option value="{@var.value}"{if $fields.value == @var.value} selected="selected"{/if}>{@var.value}</option>
										{/foreach}
									</select>
								</div>
							{else}
								<div class="formField grid_7">
									<div><textarea name="data[{$fields.index}]" id="{$fields.name}" rows="3" class="resize">{$fields.value}</textarea></div>
									<div>
										{section=bbcode}
											<button type="button" onClick="addText('{$fields.index}', '[{$bbcode.value}]', '[/{$bbcode.value}]', 'account');"><img src="{$bbcode.image}" title="{$bbcode.description}" class="tip"></button>
										{/section}
									</div>
									<div>
										{section=smiley}
											<img src="{$ADDR_IMAGES}smiley/{$smiley.image}" title="{$smiley.text}" class="tip" onclick="insertText('{$fields.index}', '{$smiley.code}', 'account');">
											{if $smiley.i % 10 == 0}</div><div">{/if}
										{/section}
									</div>
								</div>
							{/if}
						</div>
					{/section}
				{/section}

				<div class="tbl Buttons">
					<div class="grid_2 center button-l">
						<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
					</div>
					<div class="grid_2 center button-r">
						<input type="hidden" name="save" value="yes" />
						<span id="SendForm_This" class="save"><strong>{i18n('Edit account')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
					</div>
				</div>
			</form>
		{elseif $account}
			<div class="user">
				<h4>Profil - {$account.username}<span class="box-right">{i18n('Main group: ')}{$account.role}</span></h4>
				<div class="tbl">
					<div class="avatar">
						<img src="{$account.avatar}">
					</div>
					<div class="data">
						<div>{i18n('User ID: ')}{$account.id}</div>
						<div>{i18n('Status:')}
							{if $account.status === '0'}<span style="color:#177d0a">{i18n('Active')}</span>{/if}
							{if $account.status === '1'}<span style="color:#fd7903">{i18n('E-mail verification required')}</span>{/if}
							{if $account.status === '2'}<span style="color:#06c8cf">{i18n('Administrator activation required')}</span>{/if}
							{if $account.status === '3'}<span style="color:#ec0000">{i18n('Banned')}</span>{/if}
							{if $account.status === '4'}<span style="color:#989796">{i18n('Hidden')}</span>{/if}
						</div>
						<div>Adres e-mail: {$account.email}</div>
						<div>Data rejestracji: {$account.joined}</div>
						<div>Ostatnia wizyta: {$account.visit}</div>
						<div>Skórka: {$account.theme}</div>
						<div>Grupy użytkownika: {$account.roles}</div>
					</div>

					{if $account.id !== '1' || $id === '1'}
						<div class="box">
							<h4>{i18n('Administration options')}</h4>
							<div class="center Buttons">
								{if $action === 'edit'}
									<p><span class="Cancels"><strong>{i18n('Edit the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/edit.png" alt="" /></strong></span></p>
								{else}
									<p><span><a href="{$FILE_SELF}?page=users&amp;user={$account.id}&amp;edit"><strong>{i18n('Edit the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/edit.png" alt="" /></strong></a></span></p>
								{/if}
								{if $account.status == 1}
									<p><span><a href="{$FILE_SELF}?page=users&amp;user={$account.id}&amp;active"><strong>{i18n('Activate the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/against.png" alt="" /></strong></a></span></p>
								{else}
									{if $action === 'suspend' || $account.status == 2 || $account.status == 4}
										<p><span class="Cancels"><strong>{i18n('Ban the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/lock.png" alt="" /></strong></span></p>
									{else}
										{if $account.status === '3'}
											<p><span><a href="{$FILE_SELF}?page=users&amp;user={$account.id}&amp;suspend"><strong>{i18n('Unban the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/lock.png" alt="" /></strong></a></span></p>
										{else}
											<p><span><a href="{$FILE_SELF}?page=users&amp;user={$account.id}&amp;suspend"><strong>{i18n('Ban the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/lock.png" alt="" /></strong></a></span></p>
										{/if}
									{/if}
									{if $action === 'unactive' || $account.status == 3 || $account.status == 4}
										<p><span class="Cancels"><strong>{i18n('Deactivate the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/against.png" alt="" /></strong></span></p>
									{else}
										{if $account.status === '2' || $account.status === '1'}
											<p><span><a href="{$FILE_SELF}?page=users&amp;user={$account.id}&amp;unactive"><strong>{i18n('Activate the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/against.png" alt="" /></strong></a></span></p>
										{else}
											<p><span><a href="{$FILE_SELF}?page=users&amp;user={$account.id}&amp;unactive"><strong>{i18n('Deactivate the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/against.png" alt="" /></strong></a></span></p>
										{/if}
									{/if}
									{if $action === 'hide' || $account.status == 2 || $account.status == 3}
										<p><span class="Cancels"><strong>{i18n('Hide the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/bulb.png" alt="" /></strong></span></p>
									{else}
										{if $account.status === '4'}
											<p><span><a href="{$FILE_SELF}?page=users&amp;user={$account.id}&amp;hide"><strong>{i18n('Make the account visible')}<img src="{$ADDR_ADMIN_ICONS}pixel/bulb.png" alt="" /></strong></a></span></p>
										{else}
											<span><a href="{$FILE_SELF}?page=users&amp;user={$account.id}&amp;hide"><strong>{i18n('Hide the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/bulb.png" alt="" /></strong></a></span></p>
										{/if}
									{/if}
								{/if}
								{if $action === 'delete'}
									<p><span class="Cancels"><strong>{i18n('Delete the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/x.png" alt="" /></strong></span></p>
								{else}
									<p><span><a href="{$FILE_SELF}?page=users&amp;user={$account.id}&amp;delete"  class="confirm_button"><strong>{i18n('Delete the account')}<img src="{$ADDR_ADMIN_ICONS}pixel/x.png" alt="" /></strong></a></span></p>
								{/if}
							</div>
						</div>
					{/if}
				</div>
				<div class="tbl">
					<div class="misc">
						<h4>{i18n('Additional informations')}</h4>
						{section=cats}
							<div class="tbl1">
								<div class="sep_1 grid_7"><strong>{$cats.name}</strong></div>
							</div>
							{section=fields}
								<div class="tbl2">
									<div class="formLabel grid_2">{$fields.name}: </div>
									<div class="formField grid_2">{$fields.value}</div>
								</div>
							{/section}
						{/section}
					</div>
				</div>
			</div>
		{elseif $user}
			<div class="tbl2">
				<div class="sep_2 grid_4 bold">{i18n('User')}</div>
				<div class="grid_5 bold">{i18n('Roles')}</div>
			</div>
			{section=user}
				<div class="tbl {$user.row_color}">
					<div class="sep_2 grid_4"><a href='{$FILE_SELF}?page=users&amp;user={$user.id}'>{$user.username}</a></div>
					<div class="grid_5">{$user.roles}</div>
				</div>
			{/section}
			{$page_nav}
			<div class="tbl center">
				<div class="buttons-bg">&nbsp;</div>
				{if $show_all}
					<div><a href='{$FILE_SELF}?status={$status}'>{i18n('Show all')}</a></div>
				{/if}
				{section=sort}
					<span class="small"><a href='{$FILE_SELF}?sortby={$sort.val}&amp;status={$Status}'{if $sort.sel} class="red"{/if}>{$sort.val}</a></span>
				{/section}
				<div class="buttons-bg">&nbsp;</div>
			</div>
		{else}
			<div class="tbl center">
				<div class="buttons-bg">&nbsp;</div>
				<span>{i18n('There are no users.')}</span>
			</div>
		{/if}

		{if $action !== 'edit'}
			<div class="tb1 Buttons">
				<div class="grid_2 center button-l">
					<span class="Cancels"><a href="{$FILE_SELF}"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></a></span>
				</div>
				<div class="grid_2 center button-r">
					<span class="save"><a href="{$FILE_SELF}?page=add"><strong>{i18n('Create account')}<img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></a></span>
				</div>
			</div>
		{/if}

		<div class="buttons-bg">&nbsp;</div>
	{/if}

	{if $page === 'add'}
		{if $message}<div class="{$class}">{$message}</div>{/if}
		<h4>{i18n('Adding an user')}</h4>
		<form id="This" class="UserAdd" action="{$URL_REQUEST}" method="post">
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="username">{i18n('Username:')}</label></div>
				<div class="formField grid_7"><input type="text" name="username" value="{$user.username}" id="username" /></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_3"><label for="password1">{i18n('Password:')}<br /><small>{i18n('Minimum 6 characters')}</small></label></div>
				<div class="formField grid_7"><input type="password" name="user_pass" value="" id="password1" /></div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="password2">{i18n('Confirm password:')}</label></div>
				<div class="formField grid_7"><input type="password" name="user_pass2" value="" id="password2" /></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_3"><label for="email">{i18n('Email:')}</label></div>
				<div class="formField grid_7"><input type="text" name="user_email" value="{$user.email}" id="email" /></div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3">{i18n('Hide e-mail:')}</div>
				<div class="formField grid_7">
					<label for="HideEmail_1"><input type="radio" id="hide_email_1" name="hide_email" value="1"{if $user.hide_email == 1} checked="checked"{/if}>{i18n('Yes')}</label>
					<label for="HideEmail_0"><input type="radio" id="hide_email_0" name="hide_email" value="0"{if $user.hide_email == 0} checked="checked"{/if}>{i18n('No')}</label>
				</div>
			</div>

			<input type="hidden" name="roles[]" value="2" />
			<input type="hidden" name="roles[]" value="3" />

			<div class="tbl2">
				<div class="formLabel sep_1 grid_3">{i18n('Roles:')}{i18n('Default roles')}</div>
				<div class="formField grid_7">
					<select name="roles[]" multiple id="InsightGroups" class="select-multi" size="5" >
						{section=all_groups}
							{if $all_groups.value != 2 && $all_groups.value != 3}
								<option value="{$all_groups.value}"{if $all_groups.selected} selected="selected"{/if}>{$all_groups.display}</option>
							{/if}
						{/section}
					</select>
				</div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="role">{i18n('Main role:')}</label></div>
				<div class="formField grid_7">
					<select name="role" id="user_groups">
						{section=insight_groups}
							{if $insight_groups.value}
								<option value="{$insight_groups.value}"{if $insight_groups.selected} selected="selected"{/if}>{$insight_groups.display}</option>
							{/if}
						{/section}
					</select>
				</div>
			</div>
			{if $active}
				<div class="tbl1">
					<div class="formLabel sep_1 grid_3">Aktywuj konto od razu</div>
					<div class="formField grid_7"><input type="checkbox" name="active" value="yes" {if $user.active}checked="checked"{/if}/> {i18n('Yes')}</div>
				</div>
			{/if}
			{if $cats}
				<h4>{i18n('Additional informations')}</h4>
				{section=cats}
					<div class="tbl1">
						<div class="formField sep_1 grid_10">{$cats.name}</div>
					</div>
					{section=fields}
						<div class="tbl2">
							<div class="formLabel sep_1 grid_3"><label for="{$fields.name}">{i18n($fields.name)}</label></div>
							{if $fields.type == 1}
								<div class="formField grid_7">
									<input type="text" name="data[{$fields.index}]" value="{if $fields.value}{$fields.value}{/if}" id="{$fields.name}" />
								</div>
							{elseif $fields.type == 3}
								<div class="formField grid_7">
									<select name="data[{$fields.index}]" class="textbox">
										{foreach=$fields.option; var}
											<option value="{@var.value}"{if $fields.value == @var.value} selected="selected"{/if}>{@var.value}</option>
										{/foreach}
									</select>
								</div>
							{else}
								<div class="formField grid_7">
									<div><textarea name="data[{$fields.index}]" id="{$fields.name}" rows="3" class="resize">{$fields.value}</textarea></div>
									<div>
										{section=bbcode}
											<button type="button" onClick="addText('{$fields.index}', '[{$bbcode.value}]', '[/{$bbcode.value}]', 'account');"><img src="{$bbcode.image}" title="{$bbcode.description}" class="tip"></button>
										{/section}
									</div>
									<div>
										{section=smiley}
											<img src="{$ADDR_IMAGES}smiley/{$smiley.image}" title="{$smiley.text}" class="tip" onclick="insertText('{$fields.index}', '{$smiley.code}', 'account');">
											{if $smiley.i % 10 == 0}</div><div">{/if}
										{/section}
									</div>
								</div>
							{/if}
						</div>
					{/section}
				{/section}
			{/if}
			<div class="tbl Buttons">
				<div class="grid_2 center button-l">
					<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
				</div>
				<div class="grid_2 center button-r">
					<input type="hidden" name="create_account" value="yes" />
					<span id="SendForm_This" class="save"><strong>{i18n('Create account')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
				</div>
			</div>
		</form>
	{/if}

	{if $page === 'mail'}
		<script src="{$ADDR_ADMIN_TEMPLATES}javascripts/jquery.users.js"></script>
		<script>
			{literal}
				$(function() {
					$( ".users-full" ).find('input.mail').tagedit({
						autocompleteURL: 'ajax/users.php',
						texts: {
							removeLinkTitle: '{/literal}{i18n('Delete from list.')}{literal}',
							saveEditLinkTitle: '{/literal}{i18n('Save changes.')}{literal}',
							deleteLinkTitle: '{/literal}{i18n('Delete this entry from database.')}{literal}',
							deleteConfirmation: '{/literal}{i18n('Are you sure?')}{literal}',
							deletedElementTitle: '{/literal}{i18n('This element has been deleted.')}{literal}',
							breakEditLinkTitle: '{/literal}{i18n('Cancel')}{literal}'
						}
					});

				});
			{/literal}
		</script>

		{if $message}<div class="{$class}">{$message}</div>{/if}

		<form id="This" class="users-full" action="{$URL_REQUEST}" method="post">
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2">{i18n('Recipients')}</div>
				<div class="formField grid_9">
					<p>
						<input type="text" name="mail[]" id="Mail" class="mail">
					</p>
				</div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="Subject">{i18n('Subject:')}</label></div>
				<div class="formField grid_7"><input type="text" name="subject" id="Subject" /></div>
			</div>
			<div class="tbl1">
				<div class="formField sep_2 grid_9">{i18n('Message:')}</div>
			</div>
			<div class="tbl1">
				<div class="formField sep_2 grid_9"><textarea name="email_message" id="Message" cols="80" rows="3"></textarea></div>
			</div>
			<script>
				{literal}
					var editor = CKEDITOR.replace('Message', {
						toolbar : [
							['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
							['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
							['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
							['Link','Unlink','Anchor'],
							['Source','-','Preview']
						]
					});
				{/literal}
			</script>
			<div class="tbl2">
				<div class="sep_1 grid_2 formLabel">{i18n('Hide recipients?')}</div>
				<div class="grid_1 formField"><label><input type="radio" name="hide" value="1" checked="checked" /> {i18n('Yes')}</label></div>
				<div class="grid_6 formField"><label><input type="radio" name="hide" value="0" /> {i18n('No')}</label></div>
			</div>
			<div class="tbl Buttons">
				<div class="grid_2 center button-l">
					<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
				</div>
				<div class="grid_2 center button-r">
					<input type="hidden" name="send" value="yes" />
					<span id="SendForm_This" class="save"><strong>{i18n('Send message')}<img src="{$ADDR_ADMIN_ICONS}pixel/mail.png" alt="" /></strong></span>
				</div>
			</div>
		</form>
	{/if}

	<script>var ADDR_SITE = '{$ADDR_SITE}';</script>
	<script src="{$ADDR_ADMIN_PAGES_JS}users.js"></script>