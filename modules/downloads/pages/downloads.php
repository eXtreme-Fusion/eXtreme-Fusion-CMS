<?php defined('EF5_SYSTEM') || exit;

$_locale->moduleLoad('lang', 'downloads');

// Blokuje wykonywanie pliku TPL z katalogu szablonu
define('THIS', TRUE);

$theme = array(
	'Title' => __('Download'),
	'Keys' => 'download, download',
	'Desc' => __('Dwonload')
);








$_tpl->assign('Theme', $theme);

// Definiowanie katalogu templatek modu³u
$_tpl->setPageCompileDir(DIR_MODULES.'downloads'.DS.'templates'.DS);
