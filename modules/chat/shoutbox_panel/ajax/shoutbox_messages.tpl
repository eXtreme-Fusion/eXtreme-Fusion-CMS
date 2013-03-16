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
| Author: Marcus Gottschalk (MarcusG)
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

<script>{literal}$(document).ready(function(){$('#chat_post').html({/literal}{$rows}{literal});});{/literal}</script>
{section=posts}
	<div class="InfoBoxPanel">
		<div class="InfoBoxTop"></div>
		<div class="InfoBoxCon">
			<span>{$posts.date}</span><br />
			{$posts.content}
		</div>
		<div class="InfoBoxEndd">
		{$posts.user}
		{if $iAdmin}<div style="float:right;"><a href="{$ADDR_SITE}modules/chat/shoutbox_panel/ajax/delete.php?post={$posts.id}" class="shoutbox_delete_post">{i18n('Delete')}</a> <a href="{$ADDR_SITE}modules/chat/shoutbox_panel/ajax/edit.php?post={$posts.id}" class="shoutbox_edit_post">{i18n('Edit')}</a></div>{/if}
		</div>
	</div>
{sectionelse}
	<p class="center margin-vertical-10">{i18n('No messages have been posted.')}</p>
{/section}
