<?php defined('EF5_SYSTEM') || exit;
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/ 
**********************************************************
 	Some open-source code comes from
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+-------------------------------------------------------
| Author: Wooya
+-------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+-------------------------------------------------------*/
$_locale->load('img');

$bbcode_info = array(
	'name' => __('Image'),
	'description' => __('Viewing image from the given link'),
	'value' => 'img'
);

if($bbcode_used)
{
	if ( ! function_exists("img_bbcode_callback"))
	{
		function img_bbcode_callback($matches)
		{
			if (substr($matches[3], -1, 1) != "/")
			{
				return '<span style="display: block; width: 300px; max-height: 300px;" class="forum-img-wrapper"><img src="'.$matches[1].str_replace(array("?","&amp;","&","="), "", $matches[3]).$matches[4].'" alt="'.$matches[3].$matches[4].'" style="border:0px" class="forum-img" /></span>';
			}
			else 
			{
				return $matches[0];
			}
		}
	}

	$text = preg_replace_callback("#\[img\]((http|ftp|https|ftps)://)(.*?)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))\[/img\]#si", "img_bbcode_callback", $text);
}
