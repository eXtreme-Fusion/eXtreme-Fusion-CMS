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
$_locale->load('i');

$bbcode_info = array(
	'name' => __('Italics'),
	'description' => __('Display the selected text in italics'),
	'value' => 'i'
);

if($bbcode_used)
{
	$text = preg_replace('#\[i\](.*?)\[/i\]#si', '<em>\1</em>', $text);
}