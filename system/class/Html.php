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

// Zwiera metody typowe dla PHP, bez htmla.
abstract class HtmlAbstract
{
	protected static function arraySelected($data, $selected, &$assign, $i)
	{
		foreach($data as $key => $value)
		{
			$assign[$i] = array(
				'value' => $key,
				'display' => $value,
				'selected' => ''
			);

			if (in_array($key, $selected))
			{
				$assign[$i]['selected'] = TRUE;
			}

			$i++;
		}
	}

	protected static function stringSelected($data, $selected, &$assign, $i)
	{
		foreach($data as $key => $value)
		{
			$assign[$i] = array(
				'value' => $key,
				'display' => $value,
				'selected' => NULL
			);

			if ((string)$key === $selected)
			{
				$assign[$i]['selected'] = '1';
			}

			$i++;
		}
	}


	// Return PHP: Tworzenie tablicy danych dla listy formularza
	// Parametr trzeci ustawiony na TRUE powoduje, że indeksy w zwróconej tablicy będą takie same, jak w źródłowej.
	// Ustawienie na FALSE powoduje, że indeksem stanie się wartość z tablicy źródłowej.
	public static function createSelectOpts($data, $selected = NULL, $key_value = FALSE, $no_select_option = FALSE, $default = Html::SELECT_NO_SELECTION)
	{
		$i = 0; $assign = array();

		// Dopisywanie opcji Brak wyboru
		if ($no_select_option)
		{
			$assign[$i] = array(
				'value' => '',
				'display' => __($default),
				'selected' => ''
			);

			$i++;
		}

		if ($key_value)
		{
			foreach($data as $key => $value)
			{
				$assign[$i] = array(
					'value' => $key,
					'display' => $value,
					'selected' => ''
				);


				if ($selected !== NULL && $key === $selected)
				{
					$assign[$i]['selected'] = TRUE;
				}

				$i++;
			}
		}
		else
		{
			foreach($data as $value)
			{
				$assign[$i] = array(
					'value' => $value,
					'display' => $value,
					'selected' => ''
				);

				if ($selected !== NULL && $value === $selected)
				{
					$assign[$i]['selected'] = TRUE;
				}

				$i++;
			}
		}

		// Oznaczanie opcji Brak wyboru jako selected jeżeli żadna inna nie jest selected.
		if ($no_select_option)
		{
			$selected = FALSE;
			foreach($assign as $opt)
			{
				if ($opt['selected'])
				{
					$selected = TRUE;
					break;
				}
			}

			if (!$selected)
			{
				$assign[0]['selected'] = TRUE;
			}
		}

		return $assign;
	}

	// Return PHP: LISTA MULTI SELECT
	public static function createMultiSelect($data, $selected = NULL, $show_default = TRUE)
	{
		if ($show_default)
		{
			HELP::arrayUnshift($data, '0', __('--Brak wyboru--'));
		}

		if (is_array($selected))
		{
			self::arraySelected($data, $selected, $assign, (int)$show_default);
		}
		else
		{
			self::stringSelected($data, $selected, $assign, (int)$show_default);
		}

		return $assign;
	}
}

// Helpery dla plików szablonu
class Html extends HtmlAbstract
{
	const SELECT_DEFAULT = '--Default--';
	const SELECT_NO_SELECTION = '--Brak wyboru--';

	// Opcje listy wyboru
	public static function getSelectOpts($data, $selected = NULL, $key_value = FALSE, $no_select_option = FALSE, $default = Html::SELECT_NO_SELECTION)
	{
		$ret = '';
		foreach(self::createSelectOpts($data, $selected, $key_value, $no_select_option, $default) as $opt)
		{
			$ret .= '<option value="'.$opt['value'].'"'.($opt['selected'] ? ' selected="selected"' : '').'>'.$opt['display'].'</option>';
		}

		return $ret;
	}

	// Konwertuje tablicę do postaci linków html
	public static function arrayToLinks($data, $implode = FALSE)
	{
		$is_array = is_array($data);

		if (!$data || ($is_array && (!isset($data[0]['name']) || !isset($data[0]['url']))))
		{
			return FALSE;
		}

		if (!$is_array)
		{
			$data = array($data);
		}

		$ret = array();
		foreach($data as $entity)
		{
			$ret[] = '<a href="'.$entity['url'].'"'.(isset($entity['title']) ? ' title="'.$entity['title'].'"' : '').' rel="tag">'.$entity['name'].'</a>';
		}

		if ($implode !== FALSE)
		{
			return implode($implode, $ret);
		}

		return $ret;
	}
}
