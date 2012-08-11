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
if (function_exists('formatcode'))
{
	function formatcode($text) {
		$text = str_replace("  ", "  ", $text);
		$text = str_replace("\t", "   ", $text);
		$text = preg_replace("/^ {1}/m", ' ', $text);
		return $text;
	}
}

if($bbcode_used)
{
	if (preg_match("/\/forum\//i", URL_REQUEST)) global $data;

	$code_count = substr_count($text, "[code]");
	for ($i=0; $i < $code_count; $i++) {
		if (preg_match("/\/forum\//i", URL_REQUEST) && isset($data['post_id'])) {
		   $code_save = "<a href=\'".DIR_SYSTEM."bbcodes/code_bbcode_save.php?thread_id=".$_GET['thread_id']."&amp;post_id=".$data['post_id']."&amp;code_id=".$i."\'><img src=\'".INCLUDES."bbcodes/images/code_save.png\' alt=\'".__('Kod źródłowy')."\' title=\'".__('Kod źródłowy')."\' style=\'border:none\' /></a>&nbsp;&nbsp;";
		} else {
		   $code_save = "";
		}
		$text = preg_replace("#\[code\](.*?)\[/code\]#sie", "'<div class=\'code_bbcode\'><div class=\'tbl-border tbl2\' style=\'width:400px\'>".__('Kod źródłowy')."<strong>".__('Kod źródłowy')."</strong></div><div class=\'tbl-border tbl1\' style=\'width:400px;white-space:nowrap;overflow:auto\'><code style=\'white-space:nowrap\'>'.'\\1'.'<br /><br /><br /></code></div></div>'", $text, 1);
	}
}