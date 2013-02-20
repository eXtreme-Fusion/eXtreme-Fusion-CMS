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
$_locale->load('code');

$bbcode_info = array(
	'name' => __('Code'),
	'description' => __('Display the selected text as the source code'),
	'value' => 'code'
);

if($bbcode_used)
{
	$text = preg_replace("#\[code\](.*?)\[/code\]#sie", "'<div class=\"code_bbcode\"><div class=\"tbl-border tbl2\" style=\"width:400px\"><strong>".__('Code')."</strong></div><div class=\"tbl-border tbl1\" style=\"width:400px;white-space:nowrap;overflow:auto\"><code style=\"white-space:nowrap\">'.'\\1'.'<br /><br /><br /></code></div></div>'", $text, 1);
}
