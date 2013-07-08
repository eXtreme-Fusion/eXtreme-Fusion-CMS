<?php

require_once '../../../system/sitecore.php';

// Pobranie z cache zapytania sprawdzającego czy moduł jest zainstalowany
$message = $_system->cache('cookies_msg', NULL, 'cookies', 2592000);

if ($message === NULL)
{
	// Pobieranie ustawień z bazy danych oraz umieszczenie go w cache
	$message = $_pdo->getField('SELECT `message` FROM [cookies]');
	$_system->cache('cookies_msg', $message, 'cookies');
}

if ($message)
{
	$_tpl = new General(dirname(__DIR__).DS.'templates'.DS);
	$_tpl->assign('message', $message);
	$_tpl->template('message.tpl');
}