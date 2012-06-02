<?php defined('EF5_SYSTEM') || exit;
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/

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
		eval($_panels->closePHPSet($data['content'], TRUE));
	}
}

define($side, ob_get_contents());
ob_end_clean();

defined('LEFT') or define('LEFT', '');
defined('RIGHT') or define('RIGHT', '');
defined('TOP_CENTER') or define('TOP_CENTER', '');
defined('BOTTOM_CENTER') or define('BOTTOM_CENTER', '');