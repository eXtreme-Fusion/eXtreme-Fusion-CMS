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
$_locale->load('url');

$bbcode_info = array(
	'name' => __('URL'),
	'description' => __('Display the selected text as link'),
	'value' => 'url'
);

if($bbcode_used)
{
	$text = preg_replace('#\[url\]([\r\n]*)(http://|ftp://|https://|ftps://)([^\s\'\"]*?)([\r\n]*)\[/url\]#sie', "'<a href=\'\\2\\3\' target=\'_blank\' title=\'\\2\\3\'>'.HELP::trimlink('\\2\\3', 20).(strlen('\\2\\3')>30?substr('\\2\\3', strlen('\\2\\3')-10, strlen('\\2\\3')):'').'</a>'", $text);
	$text = preg_replace('#\[url\]([\r\n]*)([^\s\'\"]*?)([\r\n]*)\[/url\]#sie', "'<a href=\'http://\\2\' target=\'_blank\' title=\'\\2\'>'.HELP::trimlink('\\2', 20).(strlen('\\2')>30?substr('\\2', strlen('\\2')-10, strlen('\\2')):'').'</a>'", $text);
	$text = preg_replace('#\[url=([\r\n]*)(http://|ftp://|https://|ftps://)([^\s\'\"]*?)\](.*?)([\r\n]*)\[/url\]#si', '<a href=\'\2\3\' target=\'_blank\' title=\'\2\3\'>\4</a>', $text);
	$text = preg_replace('#\[url=([\r\n]*)([^\s\'\"]*?)\](.*?)([\r\n]*)\[/url\]#si', '<a href=\'http://\2\' target=\'_blank\' title=\'\2\'>\3</a>', $text);
}