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
$_locale->load('mail');

$bbcode_info = array(
	'name' => __('Mail'),
	'description' => __('Displays the text as an e-mail address'),
	'value' => 'mail'
);

if($bbcode_used)
{
	$text = preg_replace('#\[mail\]([\r\n]*)([^\s\'\";:\+]*?)([\r\n]*)\[/mail\]#sie', "'\\2'", $text);
	$text = preg_replace('#\[mail=([\r\n]*)([^\s\'\";:\+]*?)\](.*?)([\r\n]*)\[/mail\]#sie', "'\\2'", $text);
}