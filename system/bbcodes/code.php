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
$_locale->load('code');

$bbcode_info = array(
	'name' => __('Code'),
	'description' => __('Display the selected text as the source code'),
	'value' => 'code'
);

if($bbcode_used)
{
	$text = preg_replace("#\[code\](.*?)\[/code\]#sie", "'<div class=\"code_bbcode\"><div class=\"tbl-border tbl2\" style=\"width:400px\"><strong>".__('Code')."</strong></div><div class=\"tbl-border tbl1\" style=\"width:400px;white-space:nowrap;overflow:auto\"><code style=\"white-space:nowrap\">'.'\\1'.'<br /><br /><br /></code></div></div>'", $text, 1);
}