<?php
chdir(dirname(__DIR__));

define('F_CLASS', '.'.DS.'class'.DS);
define('F_VIEW', '.'.DS.'view'.DS);
define('F_TPL', F_VIEW.'tpl'.DS);

define('F_EXT', '.php');

// $app - nazwa aplikacji (podstrony)
// $param[1] - akcja
// $_params - parametry

function autoloader($class_name)
{
	$class_name = strtolower($class_name);
	if (file_exists(F_CLASS.$class_name.F_EXT))
	{
		include F_CLASS.$class_name.F_EXT;
		return;
	}
	
	if (file_exists(F_VIEW.$class_name.F_EXT))
	{
		include F_VIEW.$class_name.F_EXT;
		return;
	}
	
	throw new systemException('Class '.$name.' undefined');
}

spl_autoload_register('autoloader');

if ($_route->getAction())
{
	$app = $_route->getAction();
}
else
{
	$app = 'index';
}

if (file_exists(F_CLASS.$app.F_EXT))
{
	// Pobieranie wszystkich parametrów. Indeksy numeryczne, od 1.
	$params = $_route->getParams();
	
	// Kopiowanie pramaterów z wyjątkiem 1., który odpowiada za akcję.
	// Nowa tablica jest przekazywana do konstruktora aplikacji.
	$_params = array();

	for($i = 2, $c = count($params); $i < $c; $i++)
	{
		$_params[] = $params[$i];
	}
	
	// Załączanie klasy aplikacji
	include F_CLASS.$app.F_EXT;
	$class_name = $app.'_Controller';
	try
	{
		$_obj = new $class_name(isset($params[1]) ? $params[1] : 'index', $_params);
		$_obj->set('ec', $ec);
		
		$_obj->render();
	}
	catch(optException $exception)
	{
		optErrorHandler($exception);
	}
	catch(systemException $exception)
	{
		systemErrorHandler($exception);
	}
	catch(userException $exception)
	{
		userErrorHandler($exception);
	}
	catch(PDOException $exception)
	{
	   echo $exception;
	}
	
	spl_autoload_unregister('autoloader');
	spl_autoload_register('__autoload');
}
else
{
	// Błąd 404 bez przekierowania.
	$_route->trace(array('controller' => 'error', 'action' => 404));
}