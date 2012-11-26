{php} opentable(__('Chat')) {/php}
    {if $iUSER} 
		<div id="request_chat"></div>
		<div id="chat_post"></div>
		<div id="chat_messages"><section></section></div>
    
		<div id="chat_form">
			<form action="chat.html" method="post" name="chat">
				<input type="text" name="content" style="width:90%;" autocomplete="off" class="textbox"/><br />
				<div>
					{section=bbcode}
						<button type="button" onClick="addText('{$bbcode.textarea}', '[{$bbcode.value}]', '[/{$bbcode.value}]', 'chat');"><img src="{$bbcode.image}" title="{$bbcode.description}" class="tip"></button>
					{/section}
				</div>
				<div>
					{section=smiley}
						<img src="{$ADDR_IMAGES}smiley/{$smiley.image}" title="{$smiley.text}" class="tip" onclick="insertText('{$smiley.textarea}', '{$smiley.code}', 'chat');">
						{if $smiley.i % 10 == 0}</div><div>{/if}
					{/section}
				</div>
				<input type="submit" name="send" class="button" value="{i18n('Send')}" style="float:right" />
			</form>
		</div>
    {else}
		<div class="admin-message">{i18n('Avaible only for registered users.')}</div>
    {/if}
{php} closetable() {/php}