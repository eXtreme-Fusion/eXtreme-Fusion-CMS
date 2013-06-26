<?php
chdir(dirname(__DIR__));

define('F_CLASS', '.'.DS.'app'.DS);
define('F_VIEW', '.'.DS.'views'.DS);
define('F_SRC', '.'.DS.'src'.DS);

define('F_EXT', '.php');

/*
define('LEFT', FALSE);
define('RIGHT', FALSE);
*/

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

	$class_name = substr($class_name, 0, strpos($class_name, '_data'));

	if (file_exists(F_SRC.$class_name.F_EXT))
	{
		include F_SRC.$class_name.F_EXT;
		return;
	}

	throw new systemException('Class '.$class_name.' undefined');
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
	// Pobieranie wszystkich parametrów
	$params = $_route->getParams();

	// Załączanie klasy aplikacji
	include F_CLASS.$app.F_EXT;

	$class_name = $app.'_Controller';

	try
	{
		// Pobieranie nazwy akcji oraz usuwanie jej z parametrów
		$action = array_shift($params);

		// Jeżeli zamiast nazwy akcji znajdzie się liczba, jest ona dodawana na początek parametrów
		$params = is_numeric($action) ? array_merge(array($action), $params) : $params;

		// W przypadku, gdy nazwą akcji jest liczba, automatycznie wybierana jest akcja `index`
		$action = isset($action) && ! is_numeric($action) ? $action : 'index';

		// Przekazywanie do konstruktora akcji i parametrów
		$_obj = new $class_name($action, $params);

		// Przekazywanie obiektu DI
		$_obj
			->set('ec', $ec)
			->set('router', $_route)
			->set('locale', $_locale);

		// Wyświetlanie strony
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