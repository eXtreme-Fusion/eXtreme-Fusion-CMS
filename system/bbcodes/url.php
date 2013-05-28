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
$_locale->load('url');

$bbcode_info = array(
	'name' => __('URL'),
	'description' => __('Display the selected text as link'),
	'value' => 'url'
);

if($bbcode_used)
{
	$text = preg_replace('#\[url\]([\r\n]*)(http://|ftp://|https://|ftps://)([^\s\'\"]*?)([\r\n]*)\[/url\]#sie', "'<a href=\''.ADDR_SITE.'redirect/'.urlencode(base64_encode(trim('\\2\\3'))).'.html\' target=\'_blank\' title=\'\\2\\3\'>'.HELP::trimlink('\\2\\3', 20).(strlen('\\2\\3')>30?substr('\\2\\3', strlen('\\2\\3')-10, strlen('\\2\\3')):'').'</a>'", $text);
	$text = preg_replace('#\[url\]([\r\n]*)([^\s\'\"]*?)([\r\n]*)\[/url\]#sie', "'<a href=\''.ADDR_SITE.'redirect/'.urlencode(base64_encode(trim('\\2'))).'.html\' target=\'_blank\' title=\'\\2\'>'.HELP::trimlink('\\2', 20).(strlen('\\2')>30?substr('\\2', strlen('\\2')-10, strlen('\\2')):'').'</a>'", $text);
	$text = preg_replace('#\[url=([\r\n]*)(http://|ftp://|https://|ftps://)([^\s\'\"]*?)\](.*?)([\r\n]*)\[/url\]#si', "'<a href=\''.ADDR_SITE.'redirect/'.urlencode(base64_encode(trim('\\2\\3'))).'.html\' target=\'_blank\' title=\'\\2\\3\'>\4</a>'", $text);
	$text = preg_replace('#\[url=([\r\n]*)([^\s\'\"]*?)\](.*?)([\r\n]*)\[/url\]#si', "'<a href=\''.ADDR_SITE.'redirect/'.urlencode(base64_encode(trim('\\2'))).'.html\' target=\'_blank\' title=\'\\2\'>\3</a>'", $text);
}
