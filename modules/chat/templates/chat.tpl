{php} opentable(__('Chat')) {/php}
    {if $iUSER} 
    <div id="request_chat"></div>
    <div id="chat_post"></div>
    <div id="chat_messages"><section></section></div>
    
    <div id="chat_form">
      <form action="chat.html" method="post">
        <input type="text" name="content" style="width:90%;" autocomplete="off" class="textbox"/>
        <input type="submit" name="send" class="button" value="{i18n('Send')}" />
      </form>
    </div>
    {else}
    <div class="admin-message">{i18n('Avaible only for registered users.')}</div>
    {/if}
{php} closetable() {/php}