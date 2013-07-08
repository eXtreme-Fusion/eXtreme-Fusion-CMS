<?php

require_once '../../../system/sitecore.php';

$_locale->moduleLoad('cookies', 'cookies');

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

	// Pobranie z cache zapytania sprawdzającego czy moduł jest zainstalowany
	$policy = $_system->cache('cookies_policy', NULL, 'cookies', 2592000);

	if ($policy === NULL)
	{
		// Pobieranie ustawień z bazy danych oraz umieszczenie go w cache
		$policy = $_pdo->getField('SELECT `policy` FROM [cookies]');
		$_system->cache('cookies_policy', $policy, 'cookies');
	}

	if ($policy)
	{
		$_tpl->assign('policy', $policy);
	}

	$_tpl->template('message.tpl');
}