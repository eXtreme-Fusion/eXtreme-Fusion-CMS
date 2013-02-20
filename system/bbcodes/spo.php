<?php defined('EF5_SYSTEM') || exit;
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/ 
|
| This product is licensed under the BSD License.
| http://extreme-fusion.org/ef5/license/
*********************************************************/
$_locale->load('spo');

$bbcode_info = array(
	'name' => __('Spoiler'),
	'description' => __('Displays text as a spoiler'),
	'value' => 'spo'
);

if($bbcode_used)
{
	$_head->set("<script>
		$(document).ready(function(){
			$('#spoilercontent').hide();
			$('#spoilerhead').click(function () {
				if ($('#spoilercontent').is(':hidden')) {
					$('#spoilercontent').fadeIn('slow');
				} else {
					$('#spoilercontent').fadeOut('slow');
				}
				return false;
			});
		});
	</script>");
	
	if ($_user->iUSER()) 
	{
		$text = preg_replace('#\[spo\](.*?)\[/spo\]#si', '<button id="spoilerhead" class="thead">'.__('Pokaż tekst').'</button><div id="spoilercontent" class="alt1">\1</div>', $text);
	}
	else 
	{
		$text = preg_replace('#\[spo\](.*?)\[/spo\]#si', '<button id="spoilerhead" class="thead">'.__('Pokaż tekst').'</button><div id="spoilercontent" class="alt1">'.__('Zaloguj się, żeby zobaczyć zawartość').'</div>', $text);
	}
}
