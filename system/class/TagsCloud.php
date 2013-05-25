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
class TagsCloud {

	protected $_formatting = array();
	
	protected $_tags_array = array();
	
	protected $_color = array();

	// Konstruktor
	function __construct() 
	{
		// Przechowuje dane dotyczące formatowania tekstu
		$this->_formatting = array(
			'transformation' => 'lower',
			'trim' => TRUE
		);
		// Tablica pamięci podręcznej
		$this->_tags_array = array();
		
		// Tablica z listą kolorów do losowania
		$this->_color = array(
			'#6200CC',
			'#04688D',
			'#F6A504',
			'#474747',
			'#F8FF49',
			'#1B8700',
			'#070070',
			'#030020'
		);
	}
	
	/**
	 * Formatuje tekst
	 *
	 * @param   string  nazwa tagu
	 * @return  string
	 */
	private function formatTagCloud($string)
	{
		if($this->_formatting['transformation'])
		{
			switch($this->_formatting['transformation'])
			{
				case 'upper':
					$string = mb_convert_case($string, MB_CASE_UPPER, "UTF-8");
					break;
				default:
					$string = mb_convert_case($string, MB_CASE_LOWER, "UTF-8");
			}
		}
		
		if($this->_formatting['trim'])
		{
			$string = trim($string);
		}
		
		return strip_tags($string);
	}
	
	/**
	 * Dodawanie tagów do klasy
	 *
	 * @param   array  Tablica z informacjami o tagu
	 * @return  void
	 */
	public function addTag(array $row = array()) 
	{
		$row['tag'] = $this->formatTagCloud($row['tag']);
		
		if ( ! array_key_exists('size', $row)) 
		{
			$row = array_merge($row, array('size' => 1));
		}
		
		if ( ! isset($this->_tags_array[$row['tag']])) 
		{
			$this->_tags_array[$row['tag']] = array();
		}
		
		if (isset($this->_tags_array[$row['tag']]['size']) && isset($row['size']))
		{
			$row['size'] = $this->_tags_array[$row['tag']]['size'] + $row['size'];
		}
		elseif (isset($this->_tags_array[$row['tag']]['size'])) 
		{
			$row['size'] = $this->_tags_array[$row['tag']]['size'];
		}
		
		$this->_tags_array[$row['tag']] = $row;
		
		return $this->_tags_array[$row['tag']];
	}

	/**
	 * Dodawanie tagów do klasy z tablicy dwuwymiarowej
	 *
	 * @param   array  Tablica dwuwymiarowa z informacjami o tagu
	 * @return  void
	 */
	public function addTags($tags = array()) 
	{
		if ( ! is_array($tags)) 
		{
			$tags = func_get_args();
		}
		
		foreach ($tags as $row) 
		{
			$this->addTag($row);
		}		
	}
	
	/**
	 * Pobanie ID klasy css w jakiej ma być wyświetlony tag
	 *
	 * @param   int  	Liczba powtórzeń tagu
	 * @return  int
	 */
	private function getClass($percent) 
	{
		$percent = floor($percent);
		if ($percent >= 99)
		{
			$class = 9;
		}
		elseif ($percent >= 70)
		{
			$class = 8;
		}
		elseif ($percent >= 60)
		{
			$class = 7;
		}
		elseif ($percent >= 50)
		{
			$class = 6;
		}
		elseif ($percent >= 40)
		{
			$class = 5;
		}
		elseif ($percent >= 30)
		{
			$class = 4;
		}
		elseif ($percent >= 20)
		{
			$class = 3;
		}
		elseif ($percent >= 10)
		{
			$class = 2;     
		}
		elseif ($percent >= 5)
		{
			$class = 1;
		}
		else
		{
			$class = 0;
		}
		return $class;
	}
	
	/**
	 * Ustawienie limitu wyświetlanych tagów
	 *
	 * @param   int  Liczba powtórzeń tagu
	 * @return  void
	 */
	public function setLimitTags($limit) 
	{
		if (isset($limit)) 
		{
			$this->_limit_amount = $limit;
		}
		
		return $this->_limit_amount;
	}
	
	/**
	 * Limitowanie wyświetlania tagów w obiekcie
	 *
	 * @return  array
	 */
	private function limitTagsCloud() 
	{
		$i = 0;
		$tags_array = array();
		foreach ($this->_tags_array as $key => $value) 
		{
			if ($i < $this->_limit_amount) 
			{
				$tags_array[$value['tag']] = $value;
			}
			$i++;
		}
		
		$this->_tags_array = array();
		$this->_tags_array = $tags_array;
		return $this->_tags_array;
	}
	
	/**
	 * Ustawienie tagów do usunięcia z obiektu
	 *
	 * @return  void
	 */
	public function setRemoveTag($tag) {
		$this->_remove_tags[] = $this->formatTagCloud($tag);
	}
	
	/**
	 * Usuwanie tagów z obiektu
	 *
	 * @return  array
	 */
	private function removeTags() 
	{
		foreach ($this->_tags_array as $key => $value) 
		{
			if ( ! in_array($value['tag'], $this->_remove_tags)) 
			{
				$tags_array[$value['tag']] = $value;
			}
		}
		
		$this->_tags_array = array();
		$this->_tags_array = $tags_array;
		return $this->_tags_array;
	}
	
	/**
	 * Pobranie maksymalnego rozmiaru styly wyświetlania
	 *
	 * @return  int
	 */
	private function getMaxSize() 
	{
		$max = 0;
		if (isset($this->_tags_array)) 
		{
			$p_size = 0;
			foreach ($this->_tags_array as $c_key => $c_val) {
				$c_size = $c_val['size'];
				if ($c_size > $p_size) 
				{
					$max = $c_size;
					$p_size = $c_size;
				}
			}
		}
		
		return $max;
	}
	
	/**
	 * Generowanie tablicy z listą tagów i jej stylami, kolorami tekstu
	 *
	 * @return  array
	 */
	public function showTagsCloud() 
	{
		if (isset($this->_remove_tags)) 
		{
			$this->removeTags();
		}
		
		if (isset($this->_limit_amount)) 
		{
			$this->limitTagsCloud();
		}
		
		if (is_array($this->_tags_array)) 
		{
			$return = array();
			foreach ($this->_tags_array as $tag => $key) 
			{
				$key['range'] = $this->getClass(($key['size'] / $this->getMaxSize()) * 100);
				$key['colour'] = $this->_color[rand(1,count($this->_color)-1)];
				$return[$tag] = $key;
			}
			return $return;
		}

		return false;
	}
}