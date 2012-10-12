<div id="ShoutBoxPanel">
	{php} openside(__('ShoutBox')) {/php}
	<div id="ShoutBoxUpArrow"></div>
		{i18n('Posted in')}: <div id="chat_post"></div>
	
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
					<strong>{i18n('Cautions!')}</strong><br />{i18n('You must be logged in to post a message!')}
				</div>
			</div>
			<div class="InfoBoxEnd"></div>
			{/if}
		</div>			
		<div id="ShoutBoxPosts">
		</div>
		<div class="center"><img src="{$THEME_IMAGES}bullet.png"> <a href="{$url_chat}">{i18n('ARCHIVES')}</a></div>
		
	<div id="ShoutBoxDownArrow"></div>
	{php} closeside() {/php}
</div>