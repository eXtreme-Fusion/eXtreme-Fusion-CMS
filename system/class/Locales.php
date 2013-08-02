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

class Locales
{
	protected
		$_lang,						// Aktualny język w systemie
		$_dir,						// Katalog z plikami językowymi
		$_global = 'global',		// Plik z wyrażeniami systemowymi
		$_locales = array(),		// Załadowane pliki
		$_sub_dir = '',				// Podkatalog wczytywania plików
		$_file_ext = '.php';		// Rozszerzenie plików językowych

	// Załadowane ciągi tekstu
	protected static $_strings = array();

	// Predefiniowane dynamiczne parametry
	protected static $_params = array();

	/**
	 * Ustawia język, aby odpowiednie metody mogły pobrać właściwe
	 * pliki językowe, zawierające dane ciągi tekstowe.
	 *
	 * @param   string  aktualnie ustawiony język
	 * @return  void
	 * @throws  systemException
	 */
	public function __construct($lang, $dir = '', array $params = array())
	{
		// Zmienia język na aktualny
		$this->_lang = $lang;

		// Zapisuje predefiniowane dynamiczne parametry
		Locales::$_params = $params;

		// Katalog z plikami językowymi
		$this->_dir = $dir;

		// Ładuje globalnie występujące ciągi tekstowe
		$this->load($this->_global);
	}

	/**
	 * Ładuje plik lub tablicę plików językowych, tworząc jedną tablice
	 * z której pobierany jest dany ciąg tekstu. Wymagany jest format pliku!
	 *
	 *     // Załaduje jeden plik
	 *     $_locale->load('foobar.php')
	 *
	 *     // Załaduje pliki z tablicy
	 *     $_locale->load(array('foo.php', 'bar.php'));
	 *
	 * @param   mixed  plik(i) językowe do załadowania
	 * @return  array
	 */
	public function load($locales, $dir = NULL)
	{
		if (is_array($locales))
		{
			foreach ($locales as $locale)
			{
				$this->load($locale);
			}
		}
		else
		{
			$path = ($dir ? $dir : $this->_dir).$this->_lang.DS.$this->_sub_dir.$locales.$this->_file_ext;

			if ( ! file_exists($path))
			{
				throw new systemException('Plik językowy <strong>'.$path.'</strong> nie istnieje.');
			}

			if (file_exists($path) && ! in_array($path, get_included_files()))
			{
				$strings = Locales::$_strings = array_merge(Locales::$_strings, include $path);
				$this->_locales[] = $locales;
			}
		}

		return Locales::$_strings;
	}

	/**
	 * Ładuje plik językowy z katalogu modułu.
	 *
	 *		// Załaduje jeden plik językowy foo.php z modułu "book"
	 *     	$_locale->moduleLoad('foo', 'book');
	 *
	 * Metoda umożliwia ładowanie kilku plików jednocześnie.
	 * W tym celu należy podać je w tablicy.
	 *
	 *		// Załaduje pliki foo.php i bar.php z modułu "polls"
	 *     	$_locale->moduleLoad(array('foo', 'bar'), 'polls');
	 *
	 * @param   mixed 	nazwa pliku lub nazwy kilku podane w tablicy
	 * @param   string 	nazwa modułu, z którego pliki językowe mają być wczytane
	 * @return  array
	 */
	public function moduleLoad($locales, $module)
	{
		return $this->load($locales, DIR_MODULES.$module.DS.'locale'.DS);
	}

	/**
	 * Pobiera dany ciąg tekstu, jeżeli nie istnieje zwraca wartość `$key`.
	 * Brak pierwszego argumentu powoduje zwrócenie wszystkich załadowanych
	 * ciągów tekstowych, podanie drugiego argumentu skutkuje podmianą
	 * dynamicznych parametrów.
	 *
	 * @param   string  szukany ciąg tekstu
	 * @param   array   dynamiczne parametry
	 * @return  string
	 */
	public static function get($key = NULL, array $params = array())
	{
		if ($key !== NULL)
		{
			// Wyszukuje ciągu tekstowego
			$string = isset(Locales::$_strings[$key]) ? Locales::$_strings[$key] : $key;

			// Łączy dynamiczne parametry w jedną tablicę
			$params = array_merge(Locales::$_params, $params);

			// Zwraca szukany ciąg tekstowy
			return empty($params) ? $string : strtr($string, $params);
		}

		// Zwraca wszystkie ciągi tekstowe
		return Locales::$_strings;
	}

	/**
	 * Zmienia ścieżkę, skąd pobierane są pliki językowe
	 * Może być używane zamiast drugiego parametru Locale::load()
	 *
	 * @param   string  ściezka typu DIR do katalogu językowego
	 */
	public function setDir($dir)
	{
		$this->_dir = $dir;
	}

	/**
	 * Podaje subkatalog do aktualnie ustawionej ścieżki wczytywania plików językowych
	 *
	 * @param   string  nazwa katalogu, który ma zostać dołaczony do ścieżki wczytywania
	 */
	public function setSubDir($sub_dir)
	{
		$this->_sub_dir = $sub_dir.DS;
	}

	/**
	 * Zmienia używane rozszerzenie wczytywanych plików językowych
	 *
	 * @param   string  rozszerzenie plików
	 */
	public function setFileExt($ext)
	{
		$this->_file_ext = $ext;
	}

	/**
	 * Zmienia używany język oraz wczytuje na nowo pliki językowe danej strony
	 *
	 * @param   string  nazwa katalogu plików językowych
	 */
	public function setLang($lang)
	{
		if (is_string($lang))
		{
			$this->_lang = HELP::strip($lang);
			if ($this->_locales)
			{
				Locales::$_strings = array();
				$this->load($this->_locales);
			}
		}

		return FALSE;
	}

	/**
	 * Zwraca używany język.
	 *
	 * @return   string
	 */
	public function getLang()
	{
		return $this->_lang;
	}
}

/**
 * Klasa może być wczytywana wielokrotnie, więc warunek
 * zabezpiecza aplikację przed próbą nadpisania funkcji.
 */
if ( ! function_exists('__'))
{
	/**
	 * Wyszukuje oraz podmienia wartości w danym ciągu tekstowym.
	 *
	 *     echo __('Welcome :username!', array(':username' => 'foobar'));
	 *
	 * @uses    Locale::get
	 * @param   string  szukany ciąg tekstu
	 * @param   array   dynamiczne parametry
	 * @return  string
	 */
	function __($key, array $params = array())
	{
		return Locales::get($key, $params);
	}
}