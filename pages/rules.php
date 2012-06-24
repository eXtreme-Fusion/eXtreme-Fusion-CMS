<?php defined('EF5_SYSTEM') || exit;
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/
$_locale->load('rules');

$theme = array(
	'Title' => __('Rules').' &raquo; '.$_sett->get('site_name'),
	'Keys' => 'Regulamin, polityka prywatności, informacje prawne',
	'Desc' => 'Chcesz poznać regulamin, politykę prywatności lub informacje prawne: '.$_sett->get('site_name').'? Możesz to zrobić już teraz.'
);

$_tpl->assign('license_agreement', $_sett->get('license_agreement'));