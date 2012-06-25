<?php
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
$_head->set('<script src="'.ADDR_SITE.'modules\chat\templates\shoutbox.js"></script>');

if ($_user->isLoggedIn()) 
{
	$_panel->assign('IsLoggedIn', TRUE);
}

$_panel->assign('url_chat', $_url->path(array('controller' => 'chat')));