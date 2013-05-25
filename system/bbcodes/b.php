<?php defined('EF5_SYSTEM') || exit;
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/ 
**********************************************************
 	Some open-source code comes from
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Wooya
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
$_locale->load('b');

$bbcode_info = array(
	'name' => __('Bold'),
	'description' => __('Display the selected text in bold'),
	'value' => 'b'
);

if($bbcode_used)
{
	$text = preg_replace('#\[b\](.*?)\[/b\]#si', '<span class=\'bold\'>\1</span>', $text);
}
