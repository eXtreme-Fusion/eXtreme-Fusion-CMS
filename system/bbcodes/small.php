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
$_locale->load('small');

$bbcode_info = array(
	'name' => __('Small'),
	'description' => __('Minimizes the selected text'),
	'value' => 'small'
);

if($bbcode_used)
{
	$text = preg_replace('#\[small\](.*?)\[/small\]#si', '<span class"small">\1</span>', $text);
}