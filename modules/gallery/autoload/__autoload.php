<?php defined('EF5_SYSTEM') || exit;
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/

// Pobranie z cache zapytania sprawdzającego czy moduł jest zainstalowany
$row = $_system->cache('install_status', NULL, 'gallery', 60);

if ($row === NULL)
{
	// Sprzwdzanie czy moduł znajduje się na liście zainstalowanych modułów oraz umieszczenie go w cache
	$row = $_pdo->getRow('SELECT `id` FROM [modules] WHERE `folder` = :folder',
		array(':folder', 'gallery', PDO::PARAM_STR)
	);
	$_system->cache('install_status', $row, 'gallery');
}

if ($row)
{
	// Usunięcie z pamięci zmiennej $row przechowującej informacje o poprzednim cache
	unset($row);
	
	// Sprawdzenie czy aktualnie znajdujemy się w galerii
	if ($_route->getFileName() === 'gallery')
	{
		include DIR_MODULES.'gallery'.DS.'class'.DS.'sett.php';
		
		$_gallery_sett = new GallerySett($_system, $_pdo);
		
		// Dodanie do sekcji head kodów js/css
		$_head->set('
		<link href="'.ADDR_MODULES.'gallery/templates/css/prettyPhoto.css" media="screen" rel="stylesheet" />
		<script src="'.ADDR_MODULES.'gallery/templates/js/jquery.prettyPhoto.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function(){
				$("a[rel^=\'prettyPhoto\']").prettyPhoto({
					animation_speed: \''.$_gallery_sett->get('animation_speed').'\', /* fast/slow/normal */
					slideshow: '.$_gallery_sett->get('slideshow').', /* false OR interval time in ms */
					autoplay_slideshow: '.$_gallery_sett->get('autoplay_slideshow').', /* true/false */
					opacity: '.$_gallery_sett->get('opacity').', /* Value between 0 and 1 */
					show_title: '.$_gallery_sett->get('show_title').', /* true/false */
					allow_resize: '.$_gallery_sett->get('allow_resize').', /* Resize the photos bigger than viewport. true/false */
					default_width: '.$_gallery_sett->get('default_width').',
					default_height: '.$_gallery_sett->get('default_hight').',
					counter_separator_label: \''.$_gallery_sett->get('counter_separator_label').'\', /* The separator for the gallery counter 1 "of" 2 */
					theme: \''.$_gallery_sett->get('theme').'\', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
					horizontal_padding: '.$_gallery_sett->get('horizontal_padding').', /* The padding on each side of the picture */
					hideflash: '.$_gallery_sett->get('hideflash').', /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
					wmode: \''.$_gallery_sett->get('wmode').'\', /* Set the flash wmode attribute */
					autoplay: '.$_gallery_sett->get('autoplay').', /* Automatically start videos: True/False */
					modal: '.$_gallery_sett->get('modal').', /* If set to true, only the close button will close the window */
					deeplinking: '.$_gallery_sett->get('deeplinking').', /* Allow prettyPhoto to update the url to enable deeplinking. */
					overlay_gallery: '.$_gallery_sett->get('overlay_gallery').', /* If set to true, a gallery will overlay the fullscreen image on mouse over */
					keyboard_shortcuts: '.$_gallery_sett->get('keyboard_shortcuts').', /* Set to false if you open forms inside prettyPhoto */
					social_tools: \'<div class="facebook"><iframe src="http://www.facebook.com/plugins/like.php?locale=en_US&href=\'+location.href+\'&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:23px;" allowTransparency="true"></iframe></div></div>\',
					ie6_fallback: '.$_gallery_sett->get('ie6_fallback').'
				});
			});
		</script>
		');
	}
}