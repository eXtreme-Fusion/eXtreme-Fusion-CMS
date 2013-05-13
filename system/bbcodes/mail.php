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
$_locale->load('mail');

$bbcode_info = array(
	'name' => __('Mail'),
	'description' => __('Displays the text as an e-mail address'),
	'value' => 'mail'
);

if($bbcode_used)
{
	$text = preg_replace('#\[mail=([\r\n]*)([^\s\'\";:\+]*?)\](.*?)([\r\n]*)\[/mail\]#sie', "'<a href=\'http://mailto:'.HELP::hide_email('\\2').'\' target=\'_blank\' title=\''.HELP::hide_email('\\2', '\\2').'\'>'.HELP::hide_email('\\2').'</a>'", $text);
	$text = preg_replace('#\[mail\]([\r\n]*)([^\s\'\";:\+]*?)([\r\n]*)\[/mail\]#sie', "'<a href=\'http://mailto:'.HELP::hide_email('\\2').'\' target=\'_blank\' title=\''.HELP::hide_email('\\2').'\'>'.HELP::hide_email('\\2').'</a>'", $text);
}
