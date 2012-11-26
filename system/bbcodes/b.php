<?php defined('EF5_SYSTEM') || exit;
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/
$_locale->load('b');

$bbcode_info = array(
	'name' => __('Bold'),
	'description' => __('Display the selected text in bold'),
	'value' => 'b'
);

if($bbcode_used)
{
	$text = preg_replace('#\[b\](.*?)\[/b\]#si', '<strong>\1</strong>', $text);
}
