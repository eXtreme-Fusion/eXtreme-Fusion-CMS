<?php defined('EF5_SYSTEM') || exit;
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
 	Some open-source code comes from
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+-------------------------------------------------------
| Author: PHP-Fusion Development Team
+-------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+-------------------------------------------------------*/

$side_types = array(
	'1' => 'LEFT',
	'2' => 'TOP_CENTER',
	'3' => 'BOTTOM_CENTER',
	'4' => 'RIGHT'
);

$_panels = new Panels($_pdo, '..'.DS.'..'.DS.'modules'.DS);

ob_start();

foreach($_panels->getPanelsList($_user) as $id => $data)
{
	if ( ! isset($side))
	{
		$side = $side_types[$data['side']];
	}
	elseif ($side_types[$data['side']] !== $side)
	{
		define($side, ob_get_contents());
		ob_end_clean();

		ob_start();
		$side = $side_types[$data['side']];
	}

	$to_load = $_panels->loadPanel($data['type'], $data['filename'], $data['content']);
	if ($to_load)
	{
		if ($to_load[0] !== NULL)
		{
			$_panel = new Panel($_route, $to_load[0]);
			include $to_load[1];
			$_panel->template($to_load[2]);
			unset($_panel);
		}
		else
		{
			include $to_load[1];
		}
	}
	else
	{
		if ($data['side'] === '2' || $data['side'] === '3')
		{
			opentable($data['name']);
				eval($_panels->closePHPSet($data['content'], TRUE));
			closetable();
		}
		else
		{
			openside($data['name']);
				eval($_panels->closePHPSet($data['content'], TRUE));
			closeside();
		}
	}
}

define($side, ob_get_contents());
ob_end_clean();

defined('LEFT') or define('LEFT', '');
defined('RIGHT') or define('RIGHT', '');
defined('TOP_CENTER') or define('TOP_CENTER', '');
defined('BOTTOM_CENTER') or define('BOTTOM_CENTER', '');
