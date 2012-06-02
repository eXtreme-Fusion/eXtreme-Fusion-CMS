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
$_locale->load('s');

$bbcode_info = array(
	'name' => __('Przekreślenie'),
	'description' => __('Przekreśla zaznaczony tekst'),
	'value' => 's'
);

if($bbcode_used)
{
	$text = preg_replace('#\[s\](.*?)\[/s\]#si', '<s>\1</s>', $text);
}