<div id="ShoutBoxPanel">
	{php} openside(__('ShoutBox')) {/php}
	<div id="ShoutBoxUpArrow"></div>
		<div id="chat_post"></div>
	
		<div class="InfoBoxPanel">
			{if $IsLoggedIn}
			<form action="{$URL_REQUEST}" method="post" id="ShoutBoxForm">
				<textarea class="InfoBoxInput" type="text" name="content" autocomplete="off"></textarea>
				<input class="InfoBoxButton" type="submit" name="send" value="Wyślij" />
			</form>
			{else}
			<div class="InfoBoxTop"></div>
			<div class="InfoBoxCon">
				<div id="ShoutboxIcon"></div>
				<div class="InfoBoxCenterRight">
					<strong>Ostrzeżenie!</strong><br />Musisz się zalogować aby dodać wiadomość!
				</div>
			</div>
			<div class="InfoBoxEnd"></div>
			{/if}
		</div>			
		<div id="ShoutBoxPosts">
		</div>
		<div class="center"><img src="{$THEME_IMAGES}bullet.png"> <a href="{$url_chat}">ARCHIWUM</a></div>
		
	<div id="ShoutBoxDownArrow"></div>
	{php} closeside() {/php}
</div>