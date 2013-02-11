<?php
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
class Tag {

	// Przechowuje obiekt bazy danych, do późniejszych zapytań
	protected $_pdo = array();

	// Przechowuje obiekt głownego silnika systemu
	protected $_system = array();

	// Przechowuje obiekt danych
	protected $_data = array();

	/**
	 * Ładuje obiekt bazy danych, po czym wczytuje ustawienia do tablicy.
	 *
	 * @param   System    silnik systemu
	 * @param   Database  silnik bazy danych
	 * @return  void
	 */
	public function __construct(System $system, Data $pdo)
	{
		$this->_system = $system;
		$this->_pdo = $pdo;
	}

	/**
	 * Dodaje nowy tag do bazy danych.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->addTag('SUPPLEMENT', 1, 'Title tag', array(1,2,3))
	 *
	 * @param   string    			Identyfikator supplement
	 * @param   int		  			ID supplement dodającego tag
	 * @param   string/array		Tag
	 * @param   array				Liczbowa tablica z dostępem do tagu
	 * @return  boolen
	 */
	public function addTag($supplement, $supplement_id, $value, $access = array(1,2,3))
	{
		if (is_string($value))
		{
			$value = array($value);
		}

		if (is_string($access))
		{
			if (isNum($access, FALSE, FALSE))
			{
				$access = array($access => $access);
			}
			else
			{

				throw new systemException('Parametr $access ma nieprawidłową wartość');
			}
		}

		if (is_array($value))
		{
			$count = FALSE;
			foreach($value as $key)
			{
				$this->_data = new Edit(
					array(
						'supplement' => $supplement,
						'supplement_id' => $supplement_id,
						'value' => $key,
						'value_for_link' => HELP::Title2Link($key),
						'access' => $access
					)
				);

				if ($this->_data->arr('supplement_id')->isNum(FALSE, FALSE) && $this->_data->arr('access')->isArrayNum(FALSE))
				{
					$count = $this->_pdo->exec('INSERT INTO [tags] (`supplement`, `supplement_id`, `value`, `value_for_link`, `access`) VALUES (:supplement, :supplement_id, :value, :value_for_link, :access)',
						array(
							array(':supplement', $this->_data->arr('supplement')->filters('trim', 'strip'), PDO::PARAM_STR),
							array(':supplement_id', $this->_data->arr('supplement_id')->isNum(TRUE, FALSE), PDO::PARAM_INT),
							array(':value', $this->_data->arr('value')->filters('trim', 'strip'), PDO::PARAM_STR),
							array(':value_for_link', $this->_data->arr('value_for_link')->filters('trim', 'strip'), PDO::PARAM_STR),
							array(':access', $this->_data->arr('access')->filters('getNumArray', 'implode'), PDO::PARAM_STR)
						)
					);
				}
			}

			if ($count)
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Pobiera wszystkie wiersze z bazy.
	 *
	 *	$_tag->getAllTag()
	 *
	 * @return  array
	 */
	public function getAllTag()
	{
		$query = $this->_pdo->getData('SELECT `id`, `supplement`, `supplement_id`, `value`, `value_for_link`, `access` FROM [tags] ORDER BY `id` DESC');

		$res = array();
		if ($this->_pdo->getRowsCount($query))
		{
			foreach($query as $row)
			{
				if($table = strtolower($row['supplement']))
				{
					$item = $this->_pdo->getRow('SELECT `title`, `description` FROM ['.$table.'] WHERE id= :id ORDER BY `id` DESC',
						array(':id', $row['supplement_id'], PDO::PARAM_INT)
					);
				}

				$res[] = array(
					'id' => (int)$row['id'],
					'supplement' => $row['supplement'],
					'supplement_id' => (int)$row['supplement_id'],
					'value' => $row['value'],
					'value_for_link' => $row['value_for_link'],
					'access' => $row['access'],
					'title' => $item['title'],
					'description' => $item['description']
				);
			}

			return $res;
		}

		return FALSE;
	}

	/**
	 * Pobiera wiersze z bazy danych na podstawie pola supplement.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->getTagFromSupplement('SUPPLEMENT')
	 *
	 * @param   string    	Identyfikator supplement
	 * @return  array
	 */
	public function getTagFromSupplement($supplement)
	{
		$this->_data = new Edit($supplement);

		$query = $this->_pdo->getData('SELECT `id`, `supplement`, `supplement_id`, `value`, `value_for_link`, `access` FROM [tags] WHERE supplement = :supplement ORDER BY `id` DESC',
			array(':supplement', $this->_data->filters('trim', 'strip'), PDO::PARAM_STR)
		);

		$res = array();
		if ($this->_pdo->getRowsCount($query))
		{
			foreach($query as $row)
			{
				if($table = strtolower($row['supplement']))
				{
					$item = $this->_pdo->getRow('SELECT `title`, `description` FROM ['.$table.'] WHERE id= :id ORDER BY `id` DESC',
						array(':id', $row['supplement_id'], PDO::PARAM_INT)
					);
				}

				$res[] = array(
					'id' => (int)$row['id'],
					'supplement' => $row['supplement'],
					'supplement_id' => (int)$row['supplement_id'],
					'value' => $row['value'],
					'value_for_link' => $row['value_for_link'],
					'access' => $row['access'],
					'title' => $item['title'],
					'description' => $item['description']
				);
			}

			return $res;
		}

		return FALSE;
	}

	/**
	 * Pobiera wiersze z bazy danych na podstawie pola supplement_id.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->getTagFromSupplementID(1);
	 *
	 * @param   int    	Identyfikator supplement_id
	 * @return  array
	 */
	public function getTagFromSupplementID($supplement_id)
	{
		$this->_data = new Edit($supplement_id);

		if ($this->_data->isNum(FALSE, FALSE))
		{
			$query = $this->_pdo->getData('SELECT `id`, `supplement`, `supplement_id`, `value`, `value_for_link`, `access` FROM [tags] WHERE supplement_id = :supplement_id ORDER BY `id` DESC',
				array(':supplement_id', $this->_data->isNum(TRUE, FALSE), PDO::PARAM_INT)
			);

			$res = array();
			if ($this->_pdo->getRowsCount($query))
			{
				foreach($query as $row)
				{
					if($table = strtolower($row['supplement']))
					{
						$item = $this->_pdo->getRow('SELECT `title`, `description` FROM ['.$table.'] WHERE id= :id ORDER BY `id` DESC',
							array(':id', $row['supplement_id'], PDO::PARAM_INT)
						);
					}

					$res[] = array(
						'id' => (int)$row['id'],
						'supplement' => $row['supplement'],
						'supplement_id' => (int)$row['supplement_id'],
						'value' => $row['value'],
						'value_for_link' => $row['value_for_link'],
						'access' => $row['access'],
						'title' => $item['title'],
						'description' => $item['description']
					);
				}

				return $res;
			}
		}

		return FALSE;
	}

	/**
	 * Pobiera wiersze z bazy danych na podstawie pola supplement_id oraz supplement.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->getTagFromSupplementID('SUPPLEMENT', 1);
	 *
	 * @param   string    	Identyfikator
	 * @param   int    		Identyfikator supplement_id
	 * @return  array
	 */
	public function getTag($supplement, $supplement_id)
	{
		$this->_data = new Edit(
			array(
				'supplement' => $supplement,
				'supplement_id' => $supplement_id
			)
		);

		if ($this->_data->arr('supplement_id')->isNum(FALSE, FALSE))
		{
			$query = $this->_pdo->getData('SELECT `id`, `supplement`, `supplement_id`, `value`, `value_for_link`, `access` FROM [tags] WHERE supplement_id = :supplement_id AND supplement = :supplement ORDER BY `id` DESC',
				array(
					array(':supplement', $this->_data->arr('supplement')->filters('trim', 'strip'), PDO::PARAM_STR),
					array(':supplement_id', $this->_data->arr('supplement_id')->isNum(TRUE, FALSE), PDO::PARAM_INT)
				)
			);

			$res = array();
			if ($this->_pdo->getRowsCount($query))
			{
				foreach($query as $row)
				{
					if($table = strtolower($row['supplement']))
					{
						$item = $this->_pdo->getRow('SELECT `title`, `description` FROM ['.$table.'] WHERE id= :id ORDER BY `id` DESC',
							array(':id', $row['supplement_id'], PDO::PARAM_INT)
						);
					}

					$res[] = array(
						'id' => (int)$row['id'],
						'supplement' => $row['supplement'],
						'supplement_id' => (int)$row['supplement_id'],
						'value' => $row['value'],
						'value_for_link' => $row['value_for_link'],
						'access' => $row['access'],
						'title' => $item['title'],
						'description' => $item['description']
					);
				}

				return $res;
			}
		}

		return FALSE;
	}

	/**
	 * Pobiera wiersz z bazy danych na podstawie pola id.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->getTagFromID(1);
	 *
	 * @param   int    	Identyfikator id
	 * @return  array
	 */
	public function getTagFromID($id)
	{
		$this->_data = new Edit($id);

		if ($this->_data->isNum(FALSE, FALSE))
		{
			$row = $this->_pdo->getRow('SELECT `id`, `supplement`, `supplement_id`, `value`, `value_for_link`, `access` FROM [tags] WHERE id= :id ORDER BY `id` DESC',
				array(':id', $this->_data->isNum(TRUE, FALSE), PDO::PARAM_INT)
			);

			if ($row)
			{
				if($table = strtolower($row['supplement']))
				{
					$item = $this->_pdo->getRow('SELECT `title`, `description` FROM ['.$table.'] WHERE id= :id ORDER BY `id` DESC',
						array(':id', $row['supplement_id'], PDO::PARAM_INT)
					);
				}

				return array(
					'id' => (int)$row['id'],
					'supplement' => $row['supplement'],
					'supplement_id' => (int)$row['supplement_id'],
					'value' => $row['value'],
					'value_for_link' => $row['value_for_link'],
					'access' => $row['access'],
					'title' => $item['title'],
					'description' => $item['description']
				);
			}
		}

		return FALSE;
	}

	/**
	 * Pobiera wiersze z bazy danych na podstawie value.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->getTagFromValue('Title tag');
	 *
	 * @param   string    	Nazwa tagu
	 * @return  array
	 */
	public function getTagFromValue($value)
	{
		$this->_data = new Edit($value);

		$query = $this->_pdo->getData('SELECT `id`, `supplement`, `supplement_id`, `value`, `value_for_link`, `access` FROM [tags] WHERE value= :value ORDER BY `id` DESC',
			array(':value', $this->_data->filters('trim', 'strip'), PDO::PARAM_STR)
		);

		$res = array();
		if ($this->_pdo->getRowsCount($query))
		{
			foreach($query as $row)
			{
				if($table = strtolower($row['supplement']))
				{
					$item = $this->_pdo->getRow('SELECT `title`, `description` FROM ['.$table.'] WHERE id= :id ORDER BY `id` DESC',
						array(':id', $row['supplement_id'], PDO::PARAM_INT)
					);
				}

				$res[] = array(
					'id' => (int)$row['id'],
					'supplement' => $row['supplement'],
					'supplement_id' => (int)$row['supplement_id'],
					'value' => $row['value'],
					'value_for_link' => $row['value_for_link'],
					'access' => $row['access'],
					'title' => $item['title'],
					'description' => $item['description']
				);
			}

			return $res;
		}

		return FALSE;
	}

	/**
	 * Pobiera wiersze z bazy danych na podstawie value_for_link.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->getTagFromValueLink('Title tag');
	 *
	 * @param   string    	Nazwa tagu
	 * @return  array
	 */
	public function getTagFromValueLink($value)
	{
		$this->_data = new Edit($value);

		$query = $this->_pdo->getData('SELECT `id`, `supplement`, `supplement_id`, `value`, `value_for_link`, `access` FROM [tags] WHERE value_for_link= :value_for_link ORDER BY `id` DESC',
			array(':value_for_link', $this->_data->filters('trim', 'strip'), PDO::PARAM_STR)
		);

		$res = array();
		if ($this->_pdo->getRowsCount($query))
		{
			foreach($query as $row)
			{
				if($table = strtolower($row['supplement']))
				{
					$item = $this->_pdo->getRow('SELECT `title`, `description` FROM ['.$table.'] WHERE id= :id ORDER BY `id` DESC',
						array(':id', $row['supplement_id'], PDO::PARAM_INT)
					);
				}

				$res[] = array(
					'id' => (int)$row['id'],
					'supplement' => $row['supplement'],
					'supplement_id' => (int)$row['supplement_id'],
					'value' => $row['value'],
					'value_for_link' => $row['value_for_link'],
					'access' => $row['access'],
					'title' => $item['title'],
					'description' => $item['description']
				);
			}

			return $res;
		}

		return FALSE;
	}

	/**
	 * Zwraca ilość powtóżeń tego samego tagu.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->delTagFromValue('Tag title');
	 *
	 * @param   string    	Nazwa tagu
	 * @return  int
	 */
	public function getTagCountFromValue($value)
	{
		$this->_data = new Edit($value);

		return $this->_pdo->getMatchRowsCount('SELECT `id` FROM [tags] WHERE value= :value',
			array(':value', $this->_data->filters('trim', 'strip'), PDO::PARAM_STR)
		);
	}

	/**
	 * Kasowanie wierszy z bazy danych na podstawie value.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->delTagFromValue('Tag title');
	 *
	 * @param   string    	Nazwa tagu
	 * @return  boolen
	 */
	public function delTagFromValue($value)
	{
		$this->_data = new Edit($value);

		return $this->_pdo->exec('DELETE FROM [tags] WHERE value= :value',
			array(':value', $this->_data->filters('trim', 'strip'), PDO::PARAM_STR)
		);
	}

	/**
	 * Kasowanie wierszy z bazy danych na podstawie identyfikatora.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->delTagFromSupplement('SUPPLEMENT');
	 *
	 * @param   string    	Identyfikator
	 * @return  boolen
	 */
	public function delTagFromSupplement($supplement)
	{
		$this->_data = new Edit($supplement);

		return $this->_pdo->exec('DELETE FROM [tags] WHERE supplement= :supplement',
			array(':supplement', $this->_data->filters('trim', 'strip'), PDO::PARAM_STR)
		);
	}

	/**
	 * Kasowanie wierszy z bazy danych na podstawie ID supplement.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->delTagFromSupplementID(1);
	 *
	 * @param   int    	id_supplement
	 * @return  boolen
	 */
	public function delTagFromSupplementID($supplement_id)
	{
		$this->_data = new Edit($supplement_id);

		if ($this->_data->isNum(FALSE, FALSE))
		{
			return $this->_pdo->exec('DELETE FROM [tags] WHERE supplement_id= :supplement_id',
				array(':supplement_id', $this->_data->isNum(TRUE, FALSE), PDO::PARAM_INT)
			);
		}

		return FALSE;
	}

	/**
	 * Kasowanie wierszy z bazy danych na podstawie ID supplement oraz supplement.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->delTag('SUPPLEMENT', 1);
	 *
	 * @param   string  supplement
	 * @param   int    	id_supplement
	 * @return  boolen
	 */
	public function delTag($supplement, $supplement_id)
	{
		$this->_data = new Edit(
			array(
				'supplement' => $supplement,
				'supplement_id' => $supplement_id
			)
		);

		if ($this->_data->arr('supplement_id')->isNum(FALSE, FALSE))
		{
			return $this->_pdo->exec('DELETE FROM [tags] WHERE supplement_id = :supplement_id AND supplement = :supplement',
				array(
					array(':supplement', $this->_data->arr('supplement')->filters('trim', 'strip'), PDO::PARAM_STR),
					array(':supplement_id', $this->_data->arr('supplement_id')->isNum(TRUE, FALSE), PDO::PARAM_INT)
				)
			);
		}

		return FALSE;
	}

	/**
	 * Kasowanie wierszy z bazy danych na podstawie ID.
	 * Metoda nie potrzebuje filtracji danych ma ją zawartą w sobie.
	 *
	 *	$_tag->delTagFromValue(1);
	 *
	 * @param   int    	id
	 * @return  boolen
	 */
	public function delTagFromID($id)
	{
		$this->_data = new Edit($id);

		if ($this->_data->isNum(FALSE, FALSE))
		{
			return $this->_pdo->exec('DELETE FROM [tags] WHERE id= :id',
				array(':supplement_id', $this->_data->isNum(TRUE, FALSE), PDO::PARAM_INT)
			);
		}

		return FALSE;
	}

	/**
	 * Aktualizacja na podstawie Supplement i Supplement ID.
	 *
	 *	$_tag->updTag('SUPPLEMENT', 1);
	 *
	 * @param   int    	id
	 * @return  boolen
	 */
	public function updTag($supplement, $supplement_id, $keyword, $access = array(1,2,3))
	{
		if ( ! $this->getTag($supplement, $supplement_id))
		{
			return $this->addTag($supplement, $supplement_id, $keyword, $access);
		}
		else
		{
			if ($this->delTag($supplement, $supplement_id))
			{
				return $this->addTag($supplement, $supplement_id, $keyword, $access);
			}
		}

		return FALSE;
	}
}