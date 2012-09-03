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
$_locale->moduleLoad('lang', 'forum');

! class_exists('Tree') || $_tree = New Tree($_pdo, 'forum_drzewko');

$id = 1;

//var_dump($_tree->get($id));

$_tpl->assign('drzewko', $_tree->get($id));

$_tpl->setPageCompileDir(DIR_MODULES.'forum'.DS.'templates'.DS);