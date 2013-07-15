<?php
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
*********************************************************/
$_locale->moduleLoad('shoutbox', 'chat');
$_head->set('	<script src="'.ADDR_SITE.'modules/chat/templates/shoutbox.js"></script>');

if ($_user->isLoggedIn()) 
{
	$_panel->assign('IsLoggedIn', TRUE);
}

$_panel->assign('url_chat', $_url->path(array('controller' => 'chat')));