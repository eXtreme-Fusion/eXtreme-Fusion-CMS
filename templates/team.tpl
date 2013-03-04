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

{php} opentable(__('Site Team')) {/php}
	<h4>{i18n('Main Administrator')}</h4>
	{if $site_admin}
		<div class="team tbl2">
			<div class="team-title tbl1"><img src="{$ADDR_IMAGES}profile/info.png"><span id="team-username">{i18n('User')} {$site_admin.link}</span><p id="team-status">{$site_admin.role}</p></div>
			<div class="team-avatar">
				{if $site_admin.avatar}
					<img src="{$ADDR_IMAGES}avatars/{$site_admin.avatar}" class="avatar">
				{else}
					<img src="{$ADDR_IMAGES}avatars/none.jpg" class="avatar">
				{/if}
			</div>
			<div class="team-info tbl2">
				<div><strong>{i18n('Joined')}:</strong>{$site_admin.joined}</div>
				<div><strong>{i18n('Last Visit')}:</strong>{if $site_admin.is_online == 1}{i18n('Online')}<img src="{$ADDR_IMAGES}profile/online.png" alt="{i18n('Online')}">{else}{$site_admin.last_visit_time}<img src="{$ADDR_IMAGES}profile/offline.png" alt="{i18n('Offline')}">{/if}</div>
				<div><strong>{i18n('Groups')}:</strong>{$site_admin.roles}</div>
			</div>
		</div>
	{else}
		<div class="info">{i18n('It was impossible to find users.')}</div>
	{/if}
	
	{if $admin}
		<h4>{i18n('Administrators')}</h4>
		{section=admin}
			<div class="team tbl2">
				<div class="team-title tbl1"><img src="{$ADDR_IMAGES}profile/info.png"><span id="team-username">{i18n('User')} {$admin.link}</span><p id="team-status">{$admin.role}</p></div>
				<div class="team-avatar">
					{if $sdmin.svatar}
						<img src="{$ADDR_IMAGES}avatars/{$sdmin.svatar}" class="avatar">
					{else}
						<img src="{$ADDR_IMAGES}avatars/none.jpg" class="avatar">
					{/if}
				</div>
				<div class="team-info tbl2">
					<div><strong>{i18n('Joined')}:</strong>{$admin.joined}</div>
					<div><strong>{i18n('Last Visit')}:</strong>{if $admin.is_online == 1}{i18n('Online')}<img src="{$ADDR_IMAGES}profile/online.png" alt="{i18n('Online')}">{else}{$admin.last_visit_time}<img src="{$ADDR_IMAGES}profile/offline.png" alt="{i18n('Offline')}">{/if}</div>
					<div><strong>{i18n('Groups')}:</strong>{$admin.roles}</div>
				</div>
			</div>
		{/section}
	{/if}
	
	{if $groups}
		{section=groups}
			<h4>{$groups.title}</h4>
			{if $message}<div class="{$class}" style="width:90%;margin:auto;">{$message}</div>{/if}
			{if $users}
				{section=users}
					<div class="team tbl2">
						<div class="team-title tbl1"><img src="{$ADDR_IMAGES}profile/info.png"><span id="team-username">{i18n('User')} {$users.link}</span><p id="team-status">{$users.role}</p></div>
						<div class="team-avatar">
							{if $users.avatar}
								<img src="{$ADDR_IMAGES}avatars/{$users.avatar}" class="avatar">
							{else}
								<img src="{$ADDR_IMAGES}avatars/none.jpg" class="avatar">
							{/if}
						</div>
						<div class="team-info tbl2">
							<div><strong>{i18n('Joined')}:</strong>{$users.joined}</div>
							<div><strong>{i18n('Last Visit')}:</strong>{if $users.is_online == 1}{i18n('Online')}<img src="{$ADDR_IMAGES}online.png" alt="{i18n('Online')}">{else}{$users.last_visit_time}<img src="{$ADDR_IMAGES}offline.png" alt="{i18n('Offline')}">{/if}</div>
							<div><strong>{i18n('Groups')}:</strong>{$users.roles}</div>
						</div>
					</div>
				{/section}
			{else}
				<div class="info">{i18n('It was impossible to find users.')}</div>
			{/if}
		{/section}
	{/if}
{php} closetable() {/php}
