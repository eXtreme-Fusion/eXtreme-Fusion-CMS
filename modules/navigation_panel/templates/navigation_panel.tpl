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

{php} $this->sidePanel(__('Navigation Panel')); {/php}
{if $nav}
<ul id="side_nav">
	{section=nav}
	{if $nav.type == 1}
	<li class="header">{$nav.name}</li>
	{elseif $nav.type == 2}
	<li class="separator"></li>
	{else}
	<li><a href="{$nav.url}"{if $nav.link_target} {$nav.link_target}{/if}>{$nav.name}</a></li>
	{/if}
	{/section}
</ul>
{else}
<div class="error center">{i18n('No site links')}</div>
{/if}
{php} $this->sidePanel(); {/php}