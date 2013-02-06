<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
$_locale->moduleLoad('lang', 'navigation_panel');

$query = $_pdo->getData("
	SELECT `name`, `url`, `window`, `visibility` FROM [navigation]
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
				'url' => HELP::createNaviLink($data['url']),
				'bullet' => $bullet
			);

		}
	}

	$_panel->assign('nav', $nav);
}