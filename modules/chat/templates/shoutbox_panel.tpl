<div id="ShoutBoxPanel">
	{php} openside(__('ShoutBox')) {/php}
	<div id="ShoutBoxUpArrow"></div>
		<p>{i18n('Posted in')}: <span id="chat_post"></span></p>
	
		<div class="InfoBoxPanel">
			{if $IsLoggedIn}
			<form action="{$URL_REQUEST}" method="post" id="ShoutBoxForm">
				<textarea class="InfoBoxInput" type="text" name="content" autocomplete="off"></textarea>
				<input class="InfoBoxButton" type="submit" name="send" value="{i18n('Send')}" />
			</form>
			{else}
			<div class="InfoBoxTop"></div>
			<div class="InfoBoxCon">
				<div id="ShoutboxIcon"></div>
				<div class="InfoBoxCenterRight">
					<p class="center margin-vertical-10"><strong>{i18n('You must be logged in to post a message!')}</p>
				</div>
			</div>
			<div class="InfoBoxEnd"></div>
			{/if}
		</div>			
		<div id="ShoutBoxPosts">
		</div>
		<div class="center margin-top-10"><a href="{$url_chat}">{i18n('ARCHIVES')}</a></div>
		
	<div id="ShoutBoxDownArrow"></div>
	{php} closeside() {/php}
</div>