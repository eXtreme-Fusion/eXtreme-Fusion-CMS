<?php

abstract class Abstract_Controller {

	protected $action, $params = array();

	protected $_helper = array();

	public function __construct($action, $params)
	{
		$this->action = $action;
		$this->params = $params;
	}

	public function set($name, $obj)
	{
		$this->_helper[$name] = $obj;
	}

	public function get($name)
	{
		if (isset($this->_helper[$name]))
		{
			return $this->_helper[$name];
		}

		throw new systemException('Undefined helper usage.');
	}

	public function __get($name)
	{
		return $this->get($name);
	}

	public function render()
	{
		return $this->{$this->action}();
	}

	public function view(array $data)
	{
		// Sprawdzanie, czy podano nazwę klasy widoku
		if (isset($data['class']))
		{
			// Sprawdzanie, czy plik z klasą istnieje
			if (file_exists(F_VIEW.$data['class'].F_EXT))
			{
				// Załączanie klasy
				include F_VIEW.$data['class'].F_EXT;

				$name = ucfirst($data['class']).'_View';

				// Sprawdzanie, czy obiekt ma argumenty konstruktora
				if (isset($data['construct']) && $data['construct'])
				{
					// Przekazywanie argumentów do konstruktora
					$_class = new ReflectionClass($name);
					$_class = $_class->newInstanceArgs($data['construct']);
				}
				else
				{
					$_class = new $name;
				}

				// Sprawdzanie, czy ma zostać uruchomiona jakaś metoda
				if (isset($data['method']) && $data['method'])
				{
					$temp = array();
					foreach($data['models'] as $name => $class)
					{
						// Tworzenie obiektów modeli
						$name = ucfirst($name).'_Data';
						$model = new ReflectionClass($name);
						// Przekazywanie prametrów do konstruktora obiektu
						$model = $model->newInstanceArgs($class);

						// Zapis obiektów do tablicy jako parametrów metody $data['method']
						$temp[] = $model;
					}

					call_user_func_array(array($_class, $data['method']), $temp);
				}

				// Return view
				return $_class;
			}

			throw new systemException('View '.$name.' not found');
		}

		throw new systemException('View is required.');
	}

}