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
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+------------------------------------------------------
| Author: Paul Beuk (muscapaul)
+------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+------------------------------------------------------*/
*}

<h3>{$SystemVersion} - {i18n('Settings')} &raquo; {i18n('Users')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	{*<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Deactivation system enabled:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="enable_deactivation" value="1"{if $enable_deactivation == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="enable_deactivation" value="0"{if $enable_deactivation == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="DeactivationPeriod">{i18n('Allowed period on inactivity:')}</label><small>{i18n('Number of days inactive before the de-activation is enabled.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="deactivation_period" value="{$deactivation_period}" id="DeactivationPeriod" class="num_3" maxlength="3" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="DeactivationPesponse">{i18n('Reactivation link validation:')}</label><small>{i18n('Number of days to respond to deactivation notification email.')}</small></div>
		<div class="grid_4 formField"><input type="text" name="deactivation_pesponse" value="{$deactivation_pesponse}" id="DeactivationPesponse" class="num_3" maxlength="3" /></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel">{i18n('Deactivation action:')}</div>
		<div class="grid_2 formField"><label><input type="radio" name="deactivation_action" value="1"{if $deactivation_action == 1} checked="checked"{/if} /> {i18n('Hide accounts')}</label></div>
		<div class="grid_4 formField"><label><input type="radio" name="deactivation_action" value="0"{if $deactivation_action == 0} checked="checked"{/if} /> {i18n('Delete accounts')}</label></div>
	</div>
	<h4>{i18n('User profile settings')}</h4>
	<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Hide users profiles for guests:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="hide_user_profiles" value="1"{if $hide_user_profiles == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="hide_user_profiles" value="0"{if $hide_user_profiles == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>*}
	<div class="tbl2">
		<div class="grid_6 formLabel"><label for="AvatarWidth">{i18n('Maximum avatar size (pixels):')}</label><small>{i18n('Width x height')}</small></div>
		<div class="grid_2 formField"><input type="text" name="avatar_width" value="{$avatar_width}" id="AvatarWidth" class="num_3" maxlength="3" /></div>
		<div class="grid_2 formField"><input type="text" name="avatar_height" value="{$avatar_height}" id="AvatarHeight" class="num_3" maxlength="3" /></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel"><label for="AvatarFilesize">{i18n('Maximum avatar size (kilobytes):')}</label></div>
		<div class="grid_4 formField"><input type="text" name="avatar_filesize" value="{$avatar_filesize}" id="AvatarFilesize" class="num_10" maxlength="10" /></div>
	</div>
	{*<div class="tbl2">
		<div class="grid_6 formLabel">{i18n('Avatar ratio:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="avatar_ratio" value="1"{if $avatar_ratio == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="avatar_ratio" value="0"{if $avatar_ratio == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl1">
		<div class="grid_6 formLabel">{i18n('Allow users to change theme:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="user_themes" value="1"{if $user_themes == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="user_themes" value="0"{if $user_themes == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>
	<div class="tbl2">
		<div class="grid_6 formLabel">{i18n('Allow users to change name:')}</div>
		<div class="grid_1 formField"><label><input type="radio" name="change_name" value="1"{if $change_name == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
		<div class="grid_5 formField"><label><input type="radio" name="change_name" value="0"{if $change_name == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
	</div>*}
	<div class="tbl Buttons">
		<div class="grid_2 center button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
		</div>
		<div class="grid_2 center button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
		</div>
	</div>
</form>