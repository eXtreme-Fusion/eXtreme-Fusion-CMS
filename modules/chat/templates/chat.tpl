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

{php} opentable(__('Chat')) {/php}
    {if $iUSER}
		<div id="request_chat"></div>
		<div id="archive_chat_post"></div>
		<div id="chat_messages"><section></section></div>

		<div id="chat_form">
			<form action="{$URL_REQUEST}" method="post" name="chat">
				<input type="text" name="content" style="width:90%;" autocomplete="off" class="textbox" /><br />
				<div>
					{section=bbcode}
						<button type="button" onclick="addText('{$bbcode.textarea}', '[{$bbcode.value}]', '[/{$bbcode.value}]', 'chat');"><img src="{$bbcode.image}" alt="{$bbcode.value}" title="{$bbcode.description}" class="tip" /></button>
					{/section}
				</div>
				<div>
					{section=smiley}
						<img src="{$ADDR_IMAGES}smiley/{$smiley.image}" alt="{$smiley.text}" class="tip" onclick="insertText('{$smiley.textarea}', '{$smiley.code}', 'chat');" />
						{if $smiley.i % 10 == 0}</div><div>{/if}
					{/section}
				</div>
				<input type="submit" name="send" class="button" value="{i18n('Send')}" style="float:right" />
			</form>
		</div>
    {else}
		<div class="admin-message">{i18n('You must login to post a message.')}</div>
    {/if}
{php} closetable() {/php}
