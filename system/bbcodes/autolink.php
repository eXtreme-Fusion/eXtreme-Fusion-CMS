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
$_locale->load('autolink');

$bbcode_info = array(
	'name' => __('Autolink'),
	'description' => __('It creates an automatic link'),
	'value' => 'autolink'
);

if($bbcode_used)
{
	$text = preg_replace('#(^|[\n ])((http|https|ftp|ftps)://[\w\#$%&~/.\-;:=,?@\(\)+]*)#sie', "'\\1<a href=\"'.trim('\\2').'\" target=\"_blank\" title=\"autolink\">'.\HELP::trimlink('\\2', 20).(strlen('\\2')>30?substr('\\2', strlen('\\2')-10, strlen('\\2')):'').'</a>'", $text);
	$text = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\(\)+]*)#sie", "'\\1<a href=\"http://'.trim('\\2').'\" target=\"_blank\" title=\"autolink\">'.\HELP::trimlink('\\2', 20).(strlen('\\1')>30?substr('\\2', strlen('\\2')-10, strlen('\\2')):'').'</a>'", $text);
}