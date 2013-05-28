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

<h3 class="ui-corner-all">{$SystemVersion} - {$NavigationTitle}</h3>
<div class="tbl">
	{section=AdminLink}
		<div class="EF-Nav ui-corner-all grid_2">
			<a href="{if !$Modules}{$ADDR_ADMIN}pages/{/if}{$AdminLink.Link}">
				<img src="{if !$Modules}{$ADDR_ADMIN_IMAGES}pages/{/if}{$AdminLink.Image}" alt="{$AdminLink.Title}" />
				<span>{$AdminLink.Title}</span>
			</a>
		</div>
	{sectionelse}
		<div class="status">{i18n('There are no navigation links.')}</div>
	{/section}
</div>
{if $Modules}
	<div class="tbl Buttons">
		<div class="center grid_2 button-c">
			<span class="save" id="SendForm_This" ><a href="{$ADDR_ADMIN_PAGES}modules.php"><strong>Zarządzanie</strong></a></span>
		</div>
	</div>
{/if}