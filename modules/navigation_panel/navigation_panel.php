<?php
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
$_locale->moduleLoad('lang', 'navigation_panel');

$query = $_pdo->getData("
	SELECT `name`, `url`, `window`, `visibility`, `rewrite` FROM [navigation]
	WHERE `position` = 1 OR `position` = 3 ORDER BY `order`
");

if ($_pdo->getRowsCount($query))
{
	foreach ($query as $data)
	{
		if ($_user->hasAccess($data['visibility']))
		{
			if ($data['name'] != "---" && $data['url'] == "---")
			{
				$type = 1;
				$name = $ec->sbb->parseBBCode($data['name'], "b|i|u|color");
				$bullet = '';
			}
			else if ($data['name'] == "---" && $data['url'] == "---") {
				$type = 2;
				$name = '';
				$bullet = '';
			}
			else
			{
				$type = '';
				$link_target = ($data['window'] == '1' ? ' target="_blank"' : '');
				$name = $ec->sbb->parseBBCode($data['name'], "b|i|u|color");
				$bullet = THEME_IMAGES.'bullet.png';
			}

			$nav[] = array(
				'name' => $name,
				'type' => $type,
				'link_target' => $link_target,
				'url' => HELP::createNaviLink($data['url'], !$data['rewrite']),
				'bullet' => $bullet
			);

		}
	}

	$_panel->assign('nav', $nav);
}