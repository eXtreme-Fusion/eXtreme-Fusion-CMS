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

// Wszystkie linki wychodzące ze strony w postaci komentarzy, postów, czatów będą przechodziły przez ten plik w postaci redirect.php?url=http://asd.pl
// Można będzie wykorzystać później do reklamy :) lub inforamcji czy jesteś pewien, że chcesz przejść pod wskazany adres.
$_locale->load('redirect');

$_tpl->assign('url', base64_decode(urldecode($_route->getAction())));

if (filter_var(base64_decode(urldecode($_route->getAction())), FILTER_VALIDATE_URL))
{
	$_tpl->assign('url_valid', TRUE);
	header('Refresh: 15; url='.base64_decode(urldecode($_route->getAction())));
}