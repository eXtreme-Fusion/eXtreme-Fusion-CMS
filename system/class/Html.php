<?php

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
	public static function createSelectOpts($data, $selected = NULL, $key_value = FALSE, $no_select_option = FALSE)
	{
		$i = 0; $assign = array();

		// Dopisywanie opcji Brak wyboru
		if ($no_select_option)
		{
			$assign[$i] = array(
				'value' => '',
				'display' => __('--Brak wyboru--'),
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
	// Opcje listy wyboru
	public static function getSelectOpts($data, $selected = NULL, $key_value = FALSE, $no_select_option = FALSE)
	{
		$ret = '';
		foreach(self::createSelectOpts($data, $selected, $key_value, $no_select_option) as $opt)
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
