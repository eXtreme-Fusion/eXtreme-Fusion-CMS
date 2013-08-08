<?php defined('EF5_SYSTEM') || exit;
/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
|
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
$_locale->load('maintenance');

$theme = array(
	'Title' => __('Ongoing maintenance work on the site. Please come back later on :sitename', array(':sitename' => $_sett->get('site_name'))),
	'Keys' => __('service mode, technical break, malfunction, update'),
	'Desc' => __('Ongoing maintenance work on the site. We apologize for the malfunction')
);

// Blokuje wykonywanie pliku TPL z katalogu szablonu.
define('THIS', TRUE);

$_tpl->assign('maintenance',
	array(
		'sitebanner' => ADDR_SITE.$_sett->get('site_banner'),
		'sitename' => $_sett->get('site_name'),
		'message' => nl2br($_sett->get('maintenance_message')),
		'year' => date('Y'),
		'login_form' => $_sett->get('maintenance_form'),
		'logged_in' => $_user->isLoggedIn()
	)
);