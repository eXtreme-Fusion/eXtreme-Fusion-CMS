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

$id = ($_route->getByID(1) ? $_route->getByID(1) : 1);

include DIR_MODULES.'forum'.DS.'config.php';

$_tpl->assign('config', $mod_info);


$_tpl->assign('drzewko', $_tree->get($id));

// Pobieranie listy potomastwa
//var_dump($_tree->getChildren($id));

// Dodawanie nowej galêzi
//$_tree->add('15');

// Wyœwietlanie okruszków chleba :D
$_tpl->assign('Breadcrumb', $_tree->getNav($id));

$_tpl->setPageCompileDir(DIR_MODULES.'forum'.DS.'templates'.DS);