<?php

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

	// Definiowanie katalogu templatek modułu
	$_tpl->setPageCompileDir(DIR_MODULES.'cookies'.DS.'templates'.DS);
}
else
{
	$_route->trace(array('controller' => 'error', 'action' => 404));
}

