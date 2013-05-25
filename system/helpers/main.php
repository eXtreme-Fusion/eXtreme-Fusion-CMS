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
|
**********************************************************
 	Some open-source code comes from
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

defined('EF5_SYSTEM') || exit;

// Function for AJAX response
function _e($val)
{
	echo $val; exit;
}

// Sprawdza, czy element istnieje w tablicy dwuwymiarowej
// o indeksie drugiego poziomu podanym trzecim parametrem
function inArray($value, $array, $id = NULL)
{
	if ( ! $id)
	{
		return in_array($value, $array);
	}
	if ($array)
	{
		foreach($array as $a_val)
		{
			if (isset($a_val[$id]) && $a_val[$id] === $value)
			{
				return TRUE;
			}
		}
	}

	return FALSE;
}

function isNum($var, $exception = TRUE, $return_value = TRUE)
{
	$_validator = new Edit($var);

	return $_validator->isNum($return_value, $exception);
}

function __autoload($class_name)
{
	$data = explode('_', $class_name);
	if (count($data) > 1)
	{
		$path = implode(DIRECTORY_SEPARATOR, $data);
	}
	else
	{
		$path = $class_name;
	}

	$path = DIR_CLASS.$path.'.php';

    if (file_exists($path))
	{
		include $path;
	}
	else
	{
		throw new systemException("Unable to load $class_name.");
	}
}

// Helper class
Class HELP
{
	protected static
		$_pdo,
		$_sett,
		$_user,
		$_url;

	public static function init($pdo, $sett, $user, $url)
	{
		self::$_pdo  = $pdo;
		self::$_sett = $sett;
		self::$_user = $user;
		self::$_url = $url;
	}

	public static function wordsProtect($string)
	{
		if (self::$_sett->get('bad_words_enabled'))
		{
			$to_replace = explode(PHP_EOL, self::$_sett->get('bad_words'));
			foreach($to_replace as $key => $val)
			{
				if ($val)
				{
					$new = '';
					if ($val[strlen($val)-1] === ' ')
					{
						$new = ' ';
					}

					$to_replace[$key] = trim($val).$new;
				}
			}

			return str_replace($to_replace, self::$_sett->get('bad_word_replace'), $string);
		}

		return $string;
	}



	public static function cleanSelectOptions($data)
	{
		// Rzutowanie typów w celu konwersji stringa na tablicę.
		$data = (array) $data;

		// Zapisywanie typu danych wejściowych w celu zwrócenia return w odpowiednim.
		$type = (int) is_array($data);

		$option_new = array();
		foreach($data as $val)
		{
			$val = trim($val);
			if ($val)
			{
				// Usuwanie spacji pomiędzy wyrazami

				$val_new = array();
				for($i = 0, $c = strlen($val); $i < $c; $i++)
				{
					if ($val[$i] === ' ' && isset($val[$i+1]) && $val[$i+1] === ' ')
					{
						continue;
					}

					$val_new[] = $val[$i];
				}

				$option_new[] = implode('', $val_new);
			}
		}

		if ($type === 1)
		{
			return $option_new;
		}

		return isset($option_new[0]) ? $option_new[0] : array();
	}

	/** METODY NAPISANE PRZEZ EF TEAM: **/
	public static function daysToSeconds($time, $conv = FALSE)
	{
		if (isNum($time, TRUE))
		{
			if ( ! $conv)
			{
				return 60*60*24*$time;
			}

			return $time/(60*60*24);
		}
	}

	// Zwraca tę część stringa, która wystąpi przed $needle.
	public static function strstr_before($haystack, $needle) {
		$haystack = strrev($haystack);
		$haystack = strrev(strstr($haystack, '.'));
		return substr($haystack, 0, -1);
	}

	public static function truncate($data, $limit = 20)
	{
		if (str_word_count($data) > $limit)
		{
			$body = explode(' ', $data);
			$short_content = array();
			for ($n = 0; $n < $limit; $n++)
			{
				$short_content[] = $body[$n];
			}

			return implode(' ', $short_content);
		}

		return $data;
	}
	// TODO:: Przerobic na metodę routera
	public static function createNaviLink($url, $not_parse = FALSE)
	{
		if (!preg_match('/^http:/i', $url))
		{
			if ($url)
			{
				return ADDR_SITE.self::$_url->getPathPrefix($not_parse).$url;
			}

			return ADDR_SITE;
		}

		return $url;
	}

	public static function hoursToSeconds($time, $conv = FALSE)
	{
		if (isNum($time, TRUE))
		{
			if ( ! $conv)
			{
				return 60*60*$time;
			}

			return $time/(60*60);
		}
	}

	public static function minutesToSeconds($time, $conv = FALSE)
	{
		if (isNum($time, TRUE))
		{
			if ( ! $conv)
			{
				return 60*$time;
			}

			return $time/60;
		}
	}

	public static function implode(array $data, $sep = DBS)
	{
		return implode($sep, $data);
	}

	public static function explode($data, $sep = DBS)
	{
		return explode($sep, $data);
	}

	public static function arrayUnshift(&$array, $key, $value)
	{
		$data[$key] = $value;
		foreach($array as $x => $y)
		{
			$data[$x] = $y;
		}

		$array = $data;
	}

	// Usuwa z tablicy element o numerycznym indeksie, po czym przesuwa pozostałe indeksy
	public static function arrayRemoveElement($key, &$array)
	{
		if (isNum($key))
		{
			unset($array[$key]);
			for ($i = $key + 1, $c = count($array); $i < $c; $i++)
			{
				if (isset($array[$i]))
				{
					$array[$i-1] = $array[$i];
					$to_remove = $i;
				}
			}

			if (isset($to_remove))
			{
				unset($array[$to_remove]);
			}
		}

		return FALSE;
	}

	// Konwertuje znaki językowe w nazwach miesięcy i innych wyrażeniach na UTF8
	public static function strfTimeToUTF($keys, $time)
	{
		return iconv('ISO-8859-2', 'UTF-8', strftime($keys, $time));
	}

	//==================================
	//PL: Ustalenie dni
	//EN: Determin day
	//==================================
	public static function DayExport($di_value)
	{
		$day_export = 60 * 60 * 24 * $di_value;
		return $day_export;
	}
	public static function DayImport($de_value)
	{
		$day_import = (($de_value / 24) / 60) / 60;
		return $day_import;
	}

	//==================================
	// Strip Input public static function, prevents HTML in unwanted places
	//==================================
	public static function strip(&$text)
	{
		if ($text)
		{
			if (!is_array($text))
			{
				$search = array("&", "\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
				$replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
				$text = str_replace($search, $replace, $text);
			}
			else
			{
				foreach($text as $key => $val)
				{
					$text[$key] = HELP::strip($val);
				}
			}
		}
		return $text;
	}

	//==================================
	//PL: Przeładowanie strony
	//EN: Site reload
	//==================================
	public static function reload($time)
	{
		if (isNum($time))
		{
			header('Refresh: '.$time);
			exit;
		}
	}

	/**
	 * Wyświetlanie linku do profilu użytkownika
	 *
	 * @return bool
	 */
	public static function profileLink($username = NULL, $user = NULL, $text = NULL)
	{
		if ($user === NULL && $username === NULL)
		{
			$link = '<a href="'.self::$_url->path(array('controller' => 'profile', 'action' => self::$_user->get('id'), HELP::Title2Link(self::$_user->get('username')))).'">'.($text ? $text : self::$_user->getUsername()).'</a>';
		}
		elseif ($username === NULL)
		{
			$username = self::$_user->getByID($user, 'username');
			$link = '<a href="'.self::$_url->path(array('controller' => 'profile', 'action' => $user, HELP::Title2Link($username))).'">'.($text ? $text : self::$_user->getUsername($user)).'</a>';
		}
		elseif ($user === NULL)
		{
			$user = self::$_user->getByUsername($username, 'id');
			$link = '<a href="'.self::$_url->path(array('controller' => 'profile', 'action' => $user, HELP::Title2Link($username))).'">'.($text ? $text : self::$_user->getUsername($user)).'</a>';
		}
		else
		{
			$link = '<a href="'.self::$_url->path(array('controller' => 'profile', 'action' => $user, HELP::Title2Link($username))).'">'.($text ? $text : self::$_user->getUsername($user)).'</a>';
		}

		return $link;
	}

	public static function randArrayKey($array)
	{
		return rand(0, count($array)-1);
	}

	//==================================
	//PL: Oznaczenie kolorem znalezionego wyrażenia w ciągu
	//==================================
	public static function highlight($text, $search, $color = '#99bb00')
	{
		$txt = str_ireplace($search, '<span style="background: '.$color.'; font-weight: bold;">'.$search.'</span>', $text);

		return $txt;
	}

	//==================================
	//PL: Rozkodowywanie adresów URL
	//EN: Decoding URL
	//==================================
	public static function decodingURL($text)
	{
		$coding = array(
			'%C4%85', '%C4%84', '%C4%87', '%C4%86', '%C4%99', '%C4%98', '%C5%82', '%C5%81', '%C5%84', '%C5%83',
			'%C3%B3', '%C3%93', '%C5%9B', '%C5%9A', '%C5%BA', '%C5%B9', '%C5%BC', '%C5%BB', '%20', '%22',
			'%3C', '%3E', '%7B', '%7D', '%7C', '%60', '%5E', '%E2%82%AC', '%E2%80%B0', '%C6%92',
			'%CE%94', '%CE%A0', '%CE%A9', '%CE%B1', '%CE%B2', '%C2%A3', '%C2%A7', '%C2%A9', '%C2%B5', '%E2%88%9E'
		);
		$encoding = array(
			'ą', 'Ą', 'ć', 'Ć', 'ę', 'Ę', 'ł', 'Ł', 'ń', 'Ń',
			'ó', 'Ó', 'ś', 'Ś', 'ź', 'Ź', 'ż', 'Ż', ' ', '"',
			'<', '>', '{', '}', '|', '`', '^', '€', '‰', 'ƒ',
			'Δ', 'Π', 'Ω', 'α', 'β', '£', '§', '©', 'µ', '∞'
		);

		$txt = str_replace($coding, $encoding, $text);

		return $txt;
	}

	//==================================
	//PL: Aliasy dla klas parsującej BBCode
	//==================================
	public static function parseBBCode($text)
	{
		return self::$_sbb->parseBBCode($text);
	}

	//==================================
	//PL: Aliasy dla klas parsującej Uśmieszki
	//==================================
	public static function parseSmiley($text)
	{
		return self::$_sbb->parseSmiley($text);
	}

	//==================================
	//PL: Aliasy dla klas parsującej BBCode i Uśmieszki
	//==================================
	public static function parseAllTags($text)
	{
		return self::$_sbb->parseAllTags($text);
	}

	/** koniec METODY NAPISANE PRZEZ EF TEAM **/


	// Javascript email encoder by Maurits van der Schee
	// http://www.maurits.vdschee.nl/php_hide_email/
	public static function hide_email($email)
	{
		if (strpos($email, "@"))
		{
			$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
			$key = str_shuffle($character_set);
			$cipher_text = '';
			$id = 'e'.rand(1,999999999);
			for ($i=0;$i<strlen($email);$i+=1)
			$cipher_text.= $key[strpos($character_set, $email[$i])];
			$script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
			$script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
			$script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
			$script = "eval(\"".str_replace(array("\\", '"'),array("\\\\", '\"'), $script)."\")";
			$script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';

			return '<span id="'.$id.'">[N/A]</span>'.$script;
		}
		else
		{
			return $email;
		}
	}

	// Create a list of files or folders and store them in an array
	// You may filter out extensions by adding them to $extfilter as:
	// $ext_filter = "gif|jpg"
	public static function getFileList($folder, $filter = array(), $sort = TRUE, $type = 'files', $ext_filter = '')
	{
		$res = array();

		if ($type === 'files' && ! empty($ext_filter))
		{
			$ext_filter = explode('|', strtolower($ext_filter));
		}

		$temp = opendir($folder);
		while ($file = readdir($temp))
		{
			if ($type === 'files' && ! in_array($file, $filter))
			{
				if (! empty($ext_filter))
				{
					if (!in_array(substr(strtolower(stristr($file, '.')), +1), $ext_filter) && ! is_dir($folder.$file))
					{
						$res[] = $file;
					}
				}
				else
				{
					if (!is_dir($folder.$file))
					{
						$res[] = $file;
					}
				}
			}
			elseif ($type === 'folders' && !in_array($file, $filter))
			{
        if (! empty($ext_filter))
        {
        	if (strstr($file, $ext_filter) && is_dir($folder.$file))
          {
            $res[] = $file;
          }
        }
        else
        {
					if (is_dir($folder.$file))
					{
						$res[] = $file;
					}
        }
			}
		}
		closedir($temp);
		if ($sort)
		{
			sort($res);
		}
		return $res;
	}


	// This public static function sanitises news & article submissions
	public static function descript($text, $striptags = TRUE)
	{
	// Convert problematic ascii characters to their TRUE values
	$search = array("40","41","58","65","66","67","68","69","70",
		"71","72","73","74","75","76","77","78","79","80","81",
		"82","83","84","85","86","87","88","89","90","97","98",
		"99","100","101","102","103","104","105","106","107",
		"108","109","110","111","112","113","114","115","116",
		"117","118","119","120","121","122"
		);
	$replace = array("(",")",":","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z"
		);
	$entities = count($search);
	for ($i=0; $i < $entities; $i++)
	{
		$text = preg_replace("#(&\#)(0*".$search[$i]."+);*#si", $replace[$i], $text);
	}
	$text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);
	$text = preg_replace('#(<[^>]+[/\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onfocus|onload|xmlns)[^>]*>#iU', ">", $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
	$text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
	if ($striptags)
	{
		do
		{
			$thistext = $text;
			$text = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text);
		} while ($thistext != $text);
	}
	return $text;
	}

	//==================================
	//PL: Konwertowanie sekund na czas
	//EN: Convert seconds to time
	//==================================
	public static function sec2hms($num_secs)
	{
		$str = '';
		$hours = intval(intval($num_secs) / 3600);
		$str .= $hours.':';
		$minutes = intval(((intval($num_secs) / 60) % 60));
		if ($minutes < 10) $str .= '0';
		$str .= $minutes.':';
		$seconds = intval(intval(($num_secs % 60)));
		if ($seconds < 10) $str .= '0';
		$str .= $seconds;
		return($str);
	}

	public static function arrDisplay($array)
	{
		echo "<pre>";
			print_r($array);
		echo "</pre>";
	}

	//==================================
	//PL: Funkcja pobierania danych z XML
	//EN: Fetches data from XML
	//==================================
	public static function GetXmlValue($str,$parse)
	{
		$str1 = explode('<'.$parse.'>',$str);
		$str2 = explode('</'.$parse.'>',$str1[1]);
		return $str2[0];
	}

	//==================================
	//PL: Przekierowania
	//EN: Redirects
	//==================================
	public static function redirect($location)
	{
		header('Location: '.$location);
		exit;
	}

	//==================================
	//PL: Usuwanie sesji o podanej nazwie
	//EN: Removing session by name
	//==================================
	public static function removeSession()
	{
		foreach(func_get_args() as $key)
		{
			if (isset($_SESSION[$key]))
			{
				unset($_SESSION[$key]);
			}
		}
	}

	//==================================
	//PL: Usuwanie ciasteczka po nazwie
	//EN: Removing cookie by name
	//==================================
	public static function removeCookie()
	{
		foreach(func_get_args() as $key)
		{
			if (isset($_COOKIE[$key]))
			{
				unset($_COOKIE[$key]);
				setcookie($key, NULL, -1, '/', '', '0');
			}
		}
	}

	//==================================
	//PL: Tworzenie nowego ciasteczka
	//EN: Cookie setter
	//==================================
	public static function setCookie($name, $value, $time)
	{
		setcookie($name, $value, $time, '/', '', '0');
	}

	//==================================
	//PL: Pobieranie obrazków ze wskazanego folderu
	//EN: Downloading images from the folder
	//==================================
	public static function GetImages($GetDir = '')
	{
		$HomeDIR = DIR_SITE.'templates'.DS.'images'.$GetDir ? $GetDir.DS : '';
		$Images = array();
		if ($DirHandle = opendir($HomeDIR))
		{
			while (($File = readdir($DirHandle)) !== FALSE)
			{
				if (!is_dir($File) && preg_match("/\.(bmp|jpe?g|gif|png)$/", $File))
				{
					array_push($Images, $File);
				}
			}
			closedir($DirHandle);
		}
		return $Images;
	}

	//==================================
	//PL: Skracanie linków
	//EN: Shortening links
	//==================================
	public static function trimlink($text, $length)
	{
		$dec = array("&", "\"", "'", "\\", '\"', "\'", "<", ">");
		$enc = array("&amp;", "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
		$text = str_replace($enc, $dec, $text);
		if (strlen($text) > $length) $text = mb_substr($text, 0, ($length-3))."...";
		$text = str_replace($dec, $enc, $text);
		return $text;
	}

	//==================================
	// Clean URL public static function, prevents entities in server globals
	//==================================
	public static function cleanurl($url)
	{
		$bad_entities = array("&", "\"", "'", '\"', "\'", "<", ">", "(", ")", "*");
		$safe_entities = array("&amp;", "", "", "", "", "", "", "", "", "");
		$url = str_replace($bad_entities, $safe_entities, $url);
		return $url;
	}

	//==================================
	// Strip Input public static function, prevents HTML in unwanted places for Login fields
	//==================================
	public static function striplogin($text)
	{
		$search = array("&", "\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
		$replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
		$text = str_replace($search, $replace, $text);
		return $text;
	}

	//==================================
	// Prevent any possible XSS attacks via $_GET.
	//==================================
	public static function stripget($_dbconfig)
	{
		$request = strtolower(urldecode($_SERVER['QUERY_STRING']));
		$protarray = array('union','drop','select','into','where','update','from','/*','set ',$_dbconfig['prefix'].'users',$_dbconfig['prefix'].'users(',$_dbconfig['prefix'].'groups','phpinfo','escapeshellarg','exec','fopen','fwrite','escapeshellcmd','passthru','proc_close','proc_get_status','proc_nice','proc_open','proc_terminate','shell_exec','system','telnet','ssh','cmd','mv','chmod','chdir','locate','killall','passwd','kill','script','bash','perl','mysql','~root','.history','~nobody','getenv');
		$check = str_replace($protarray, '*', $request);
		if ($request !== $check)
		{
			throw new systemException(die('Podejrzewany atak XSS!'));
		}
	}

	public static function quotes_gpc()
	{
		if (ini_get('magic_quotes_gpc'))
		{
			return TRUE;
		}

		return FALSE;
	}

	//==================================
	// htmlentities is too agressive so we use this public static function
	//==================================
	public static function phpentities($text)
	{
		$search = array("&", "\"", "'", "\\", "<", ">");
		$replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&lt;", "&gt;");
		$text = str_replace($search, $replace, $text);
		return $text;
	}

	//==================================
	//PL: Zmiana polskich znaków
	//EN: Change of Polish characters
	// Mordak " Maybe remove this in UK Verison or move in the polish lang file. "
	//==================================
	public static function PLznaki($text)
	{
		$a = array("Ą","Ś","Ę","Ó","Ł","Ż","Ź","Ć","Ń","ą","ś","ę","ó","ł","ż","ź","ć","ń");
		$b = array("A","S","E","O","L","Z","Z","C","N","a","s","e","o","l","z","z","c","n");
		$text = str_replace($a,$b,$text);
		return $text;
	}

	public static function PL($text)
	{
		$text = iconv('utf-8', 'iso-8859-2', $text);
		return $text;
	}
	public static function PL2($text)
	{
		$text = iconv('iso-8859-2', 'utf-8', $text);
		return $text;
	}

	//==================================
	//PL: Polskie miesiące
	//EN: Polish months
	// Mordak " Maybe remove this in UK Verison or move in the polish lang file. "
	//==================================
	public static function MonthsPL($months)
	{
		$en = array("January","February","March","April","May","June","July","August","September","October","November","December","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
		$pl = array("Styczeń","Luty","Marzec","Kwiecień","Maj","Czerwiec","Lipiec","Sierpień","Wrzesień","Październik","Listopad","Grudzień","poniedziałek","wtorek","środa","czwartek","piątek","sobota","niedziela");
		$months = str_replace($en,$pl,$months);
		return $months;
	}

	//==================================
	//PL: Zmiana tytułów dla linków
	//EN: Changing the title for links
	//==================================
	public static function Title2Link($text)
	{
		if( ! is_array($text))
		{
			$a = array("Ą","Ś","Ę","Ó","Ł","Ż","Ź","Ć","Ń","ą","ś","ę","ó","ł","ż","ź","ć","ń","ü","&quot"," - "," ",".","!",";",":","(",")","[","]","{","}","|","?",",","+","=","#","@","$","%","^","&","*");
			$b = array("A","S","E","O","L","Z","Z","C","N","a","s","e","o","l","z","z","c","n","u","","-","_","","","","","","","","","","","","","","","","","","","","","","");
			$c = array("--","---","__","___");
			$d = array("-","-","_","_");
			$textreplaced = strtolower(str_replace($a,$b,$text));
			$textdoublereplaced = str_replace($c,$d,$textreplaced);
			return $textdoublereplaced;
		}

		throw new systemException('Przesłana tablica nie może być przetworzona.');
	}

	// Strip file name
	public static function stripfilename($filename)
	{
		$a = array("Ą","Ś","Ę","Ó","Ł","Ż","Ź","Ć","Ń","ą","ś","ę","ó","ł","ż","ź","ć","ń","ü","&quot"," - "," ",".","!",";",":","(",")","[","]","{","}","|","?",",","+","=","#","@","$","%","^","&","*");
		$b = array("A","S","E","O","L","Z","Z","C","N","a","s","e","o","l","z","z","c","n","u","","-","_","","","","","","","","","","","","","","","","","","","","","","");
		$filename = strtolower(str_replace($a,$b,$filename));
		$filename = preg_replace("/[^a-zA-Z0-9_-]/", "", $filename);
		$filename = preg_replace("/^\W/", "", $filename);
		$filename = preg_replace('/([_-])\1+/', '$1', $filename);
		if ($filename == "") { $filename = time(); }

		return $filename;
	}


	// Check that site or user theme exists
	public static function theme_exists($theme)
	{
		if ( ! file_exists(DIR_THEMES) || ! is_dir(DIR_THEMES))
		{
			return FALSE;
		}
		elseif (file_exists(DIR_THEMES.$theme.DS.'core'.DS.'theme.php'))
		{
			defined('ADDR_THEME') || define('ADDR_THEME', ADDR_THEMES.$theme.'/');
			defined('DIR_THEME') || define('DIR_THEME', DIR_THEMES.$theme.DS);
			defined('THEME_CSS') || define('THEME_CSS', ADDR_THEME.'templates/stylesheet/');
			defined('THEME_JS') || define('THEME_JS', ADDR_THEME.'templates/javascripts/');
			defined('THEME_IMAGES') || define('THEME_IMAGES', ADDR_THEME.'templates/images/');
			return TRUE;
		}
		else
		{
			$dh = opendir(DIR_THEMES);
			while (FALSE !== ($entry = readdir($dh)))
			{
				if ($entry != "." && $entry != ".." && is_dir(DIR_THEMES.$entry))
				{
					if (file_exists(DIR_THEMES.$entry.DS.'core'.DS.'theme.php'))
					{
						if ($entry === $theme)
						{
							defined('ADDR_THEME') || define('ADDR_THEME', ADDR_THEMES.$theme.'/');
							defined('DIR_THEME') || define('DIR_THEME', DIR_THEMES.$theme.DS);
							defined('THEME_CSS') || define('THEME_CSS', ADDR_THEME.'templates/stylesheet/');
							defined('THEME_JS') || define('THEME_JS', ADDR_THEME.'templates/javascripts/');
							defined('THEME_IMAGES') || define('THEME_IMAGES', ADDR_THEME.'templates/images/');
							return TRUE;
						}
						else
						{
							return FALSE;
						}
					}
				}
			}
			closedir($dh);

			if ( ! defined('DIR_THEME'))
			{
				return FALSE;
			}
		}
	}

	public static function formatOrphan($content)
	{
		return $content = preg_replace("/\s([aiouwzAIOUWZ])\s/", " $1&nbsp;", $content);

		$content = explode(chr(32), $content);
		$count = count($content);
		$output = '';
		for($i=0; $i < $count; $i++)
		{
			$res = $content[$i];
			if (strlen($res) === 1)
			{
				return $output=$output.$res."&nbsp;";
			}
			else
			{
				return $output.$res." ";
			}
		}
	}

	// Format the date & time accordingly
	public static function showDate($format, $val)
	{
		$val += intval(self::$_sett->get('offset_timezone')) * 3600;
		if ($format === 'shortdate' || $format == 'longdate')
		{
			return iconv('ISO-8859-2', 'UTF-8', strftime(self::$_sett->get($format), $val));
		}
		else
		{
			return iconv('ISO-8859-2', 'UTF-8', strftime('shortdate', $val));
		}
	}

	// Przetwarza ciąg znaków, który ma trafić do meta tagu Desciption
	// Usuwanie białych znaków
	public static function cleanDescription($data, $length = 135)
	{
		$data = str_replace(array("\r\n", "\ht"), ' ', trim($data));
		$clean = array();
		foreach(explode(' ', $data) as $val)
		{
			if ($val !== '')
			{
				$clean[] = $val;
			}
		}

		$return = implode(' ', $clean);

		if (strlen($return) > $length)
		{
			$break = $length;
			for ($i = $length, $c = $length+80; $i < $c; $i++)
			{
				if ($return[$i] === '.')
				{
					$break = $i+1;
					break;
				}
			}

			if ($break === $length)
			{
				for ($i = $length, $c = $length+80; $i < $c; $i++)
				{
					if ($return[$i] === ' ')
					{
						$break = $i;
						break;
					}
				}
			}

			return substr($return, 0, $break);
		}

		return $return;
	}
}
