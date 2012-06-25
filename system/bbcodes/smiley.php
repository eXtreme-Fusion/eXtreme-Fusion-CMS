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
$_locale->load('smiley');

$bbcode_info = array(
	'name' => __('Smiley'),
	'description' => __('Displays available smileys'),
	'value' => 'smiley'
);

if($bbcode_used)
{
	$text = $text;
}