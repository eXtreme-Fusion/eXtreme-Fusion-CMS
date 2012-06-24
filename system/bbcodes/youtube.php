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
$_locale->load('youtube');

$bbcode_info = array(
	'name' => __('YouTube'),
	'description' => __('Displays video from YouTube'),
	'value' => 'youtube'
);

if($bbcode_used)
{
	$text = preg_replace('#\[youtube\](.*?)\[/youtube\]#si', '<strong><img src="'.ADDR_BBCODE.'images/youtube.png" alt="'.__('YouTube').'">'.__('Film z YouTube').'</strong><br /><iframe width="480" height="360" src="http://www.youtube.com/embed/\1?rel=0" frameborder="0" allowfullscreen></iframe>', $text);

}