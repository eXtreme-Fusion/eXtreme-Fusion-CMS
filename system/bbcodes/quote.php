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
$_locale->load('quote');

$bbcode_info = array(
	'name' => __('Quote'),
	'description' => __('Changes the selected text in quote'),
	'value' => 'quote'
);

if($bbcode_used)
{
	$qcount = substr_count($text, "[quote]");
	for ($i=0;$i < $qcount;$i++) $text = preg_replace('#\[quote\](.*?)\[/quote\]#si', '<blockquote><div class=\'quote\'>\1</div></blockquote>', $text);
}
