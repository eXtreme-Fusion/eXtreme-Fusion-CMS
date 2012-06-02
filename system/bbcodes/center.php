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
$_locale->load('center');

$bbcode_info = array(
	'name' => __('Center'),
	'description' => __('Centers the selected text'),
	'value' => 'center'
);

if($bbcode_used)
{
	$text = preg_replace('#\[center\](.*?)\[/center\]#si', '<div style=\'text-align:center\'>\1</div>', $text);
}