<?php
try
{
	chdir(dirname(__DIR__));

	define('F_CLASS', '.'.DS.'app'.DS);
	define('F_VIEW', '.'.DS.'views'.DS);
	define('F_SRC', '.'.DS.'src'.DS);

	define('F_ADMIN', 'acp');

	define('F_EXT', '.php');

	define('LEFT', FALSE);
	define('RIGHT', FALSE);

	function autoloader($class_name)
	{
		$class_name = strtolower($class_name);

		if (file_exists($path = F_CLASS.$class_name.F_EXT))
		{
			require_once $path;
			return;
		}

		if (file_exists($path = F_SRC.$class_name.F_EXT))
		{
			require_once $path;
			return;
		}

		throw new systemException(__('Class :name does not exist',
			array(':name' => $class_name)));
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

	$path = F_CLASS.$app.F_EXT;

	// Pobieranie wszystkich parametrów
	$params = $_route->getParams();

	if ($app === F_ADMIN && ! empty($params))
	{
		$app = array_shift($params);

		$path = F_CLASS.F_ADMIN.DIRECTORY_SEPARATOR.$app.F_EXT;
	}

	if (file_exists($path))
	{
		// Załączanie klasy aplikacji
		require_once $path;

		$class_name = ucfirst($app).'_Controller';

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
			->set('locale', $_locale)
			->set('user', $_user)
			->set('url', $_url)
			->set('theme', $_theme);

		// Wyświetlanie strony
		$_obj->render();

		spl_autoload_unregister('autoloader');
		spl_autoload_register('__autoload');
	}
	else
	{
		// Błąd 404 bez przekierowania.
		$_route->trace(array('controller' => 'error', 'action' => 404));
	}
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
	PDOErrorHandler($exception);
}