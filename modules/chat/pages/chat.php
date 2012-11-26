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
$_locale->moduleLoad('lang', 'chat');

if(iUSER) {

	$chat_settings = $_pdo->getRow('SELECT * FROM [chat_settings]');

	$_head->set('<script>var refresh_chat = '.$chat_settings['refresh'].'000;</script>');
	$_head->set('<script src="'.ADDR_MODULES.'chat/ajax/chat.js"></script>');
	$_head->set('<link href="'.ADDR_MODULES.'chat/templates/chat.css" media="screen" rel="stylesheet" />');

}

$_sbb = $ec->sbb;

#*********** Settings
$theme = array(
	'Title' => __('Chat'),
	'Keys' => '',
	'Desc' => ''
);

$_tpl->assign('Theme', $theme);

// Blokuje wykonywanie pliku TPL z katalogu szablonu
define('THIS', TRUE);

$_tpl->assignGroup(array(
	'bbcode' => $_sbb->bbcodes('content'),
	'smiley' => $_sbb->smileys('content')
));

// Definiowanie katalogu templatek modu³u
$_tpl->setPageCompileDir(DIR_MODULES.'chat'.DS.'templates'.DS);
#***********
