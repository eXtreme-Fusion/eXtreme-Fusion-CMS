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
$_locale->load('u');

$bbcode_info = array(
	'name' => __('Underline'),
	'description' => __('Display the selected text in underline'),
	'value' => 'u'
);

if($bbcode_used)
{
	$text = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $text);
}