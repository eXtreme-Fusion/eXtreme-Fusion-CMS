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

{php} openside(__('Navigation Panel')) {/php}
<nav id="side_nav">
	<ul>
		{if $nav}
			{section=nav}
				{if $nav.type == 1}
					<div class="side-label"><strong>{$nav.name}</strong></div>
				{elseif $nav.type == 2}
					<div><hr class="side-hr" /></div>
				{else}
					<li><a href="{$nav.url}"{if $nav.link_target} {$nav.link_target}{/if}>{$nav.name}</a></li>
				{/if}
			{/section}
		{else}
			<div class="error center">{i18n('No site links')}</div>
		{/if}
	</ul>
</nav>
{php} closeside() {/php}