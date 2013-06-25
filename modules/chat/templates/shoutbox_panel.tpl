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
+-------------------------------------------------------
| Author: Nick Jones (Digitanium)
| Author: Marcus Gottschalk (MarcusG)
+-------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+-------------------------------------------------------*/
*}

<div id="ShoutBoxPanel">
	{php} openside(__('ShoutBox')) {/php}
	<div id="ShoutBoxUpArrow"></div>
		<p>{i18n('Posted in')}: <span id="chat_post"></span></p>

		<div class="InfoBoxPanel">
			{if $IsLoggedIn}
			<form action="{$URL_REQUEST}" method="post" id="ShoutBoxForm">
				<div class="line center">
					<textarea class="InfoBoxInput" name="content"></textarea>
					<input class="InfoBoxButton" type="submit" name="send" value="{i18n('Send')}" />
				</div>
			</form>
			{else}
			<div class="InfoBoxTop"></div>
			<div class="InfoBoxCon">
				<div id="ShoutboxIcon"></div>
				<div class="InfoBoxCenterRight">
					<p class="center margin-vertical-10"><strong>{i18n('You must login to post a message!')}</p>
				</div>
			</div>
			<div class="InfoBoxEnd"></div>
			{/if}
		</div>
		<div id="ShoutBoxPosts">
		</div>
		<div class="center margin-top-10"><a href="{$url_chat}">{i18n('ARCHIVE')}</a></div>

	<div id="ShoutBoxDownArrow"></div>
	{php} closeside() {/php}
</div>
