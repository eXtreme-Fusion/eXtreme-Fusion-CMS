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

/**
 * Klasa ta zawiera zbiór metod odpowiedzialnych
 * za ochronę formularzy przed botami.
 */
class sign_protection implements Security_Intf
{
	protected $_unique;

	protected $_nums = array();
	protected $_nums_count;

	protected $_non_nums = array();
	protected $_non_nums_count;

	// Dzielnik ilości znaków ograniczający długość liczenia
	protected $_divisor = 3;

	/**
	 * Pozycja znaku do przepisania w ciągu samych znaków - po usunięciu cyfr.
	 * Przy zwracaniu użytkownikaowi wartość tej zmiennej jest zwiększana o 1,
	 * co wynika z faktu, że kolejność indeksów w tablicy PHP jest liczona od 0.
	 */
	protected $_non_num_key;
	protected $_non_num_value;		// Wartość pod wyżej opisaną pozycją

	protected $_nums_to_rewrite;	// Ilość cyfr do przepisania
	protected $_nums_to_ommit;		// ilość cyfr do pominięcia
	protected $_nums_pos_to_ommit;	// Od której strony cyfry mając zostać pominięte? dotyczy zmiennej wyżej.

	protected $_answer;
	protected $_ommit = TRUE;		// Czy generować utrudnione zagadki?

	protected $_tpl;
	protected $_crypt;
	protected $_pdo;

	public function __construct()
	{
		$this->_crypt = new Crypt(CRYPT_KEY, CRYPT_IV);
	}

	public function getView_wrongAnswer()
	{
		return $this->getView();
	}

	public function getView()
	{
		$this->_tpl->assignGroup(array(
			'message' => __('Nie licząc cyfr przepisz :znak znak od lewej strony, a następnie (nie licząc znaków) wszystkie cyfry od początku kodu :warunek', array(':znak' => $this->getNonNumKey(), ':warunek' => ($this->getNumsToOmmit() ? ' z pominieciem '.$this->getNumsToOmmit().($this->getNumsPosToOmmit() ? ' ostatnich' : ' pierwszych'): ''))),
			'answer' => $this->_crypt->encrypt($this->getAnswer()),
			'code' => $this->getCode()
		));

		ob_start();
		$this->_tpl->template('view.tpl');
		$data = ob_get_contents();

		ob_end_clean();

		return $data;
	}

	public function isValidAnswer($answer)
	{
		return $this->_crypt->correctAnswer($answer[0], $answer[1]);
	}

	public function getResponseInputs()
	{
		return array(
			'sign_protection_code',
			'sign_protection_answer',
		);
	}

	public function setObjects($_tpl, $_pdo = NULL)
	{
		$this->_pdo = $_pdo;
		$this->_tpl = $_tpl;

		$this->_tpl->root = DIR_MODULES.'sign_protection'.DS.'templates'.DS;
		$this->_tpl->compile = DIR_CACHE;

		$this->_ommit = (bool) $this->_pdo->getField('SELECT `validation_type` FROM [sign_protection]');

		$this->genCode();
		$this->setNums();
		$this->setNonNums();
		$this->setRules();
		$this->setAnswer();
	}

	/**
	 * Generuje ciąg znaków, który zostanie zaprezentowany użytkownikowi
	 */
	protected function genCode()
	{
		$data = array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '-', '=', '+');

		$unique = sha1($data[rand(0, count($data)-1)].md5(uniqid(time(), TRUE)));
		$this->_unique = substr($unique, rand(0, 15), strlen($unique));

		if (strlen($this->_unique) > 20)
		{
			$this->_unique = substr($this->_unique, 0, 14);
		}
	}

	public function getCode()
	{
		return $this->_unique;
	}

	/**
	 * Zapisuje do zmiennej klasowej same cyfry oraz ich ilość z wygenerowanego wcześniej ciągu znaków
	 */
	protected function setNums()
	{
		for ($i = 0, $c = strlen($this->_unique); $i < $c; $i++)
		{
			if (is_numeric($this->_unique[$i]))
			{
				$this->_nums[] = $this->_unique[$i];
			}
		}

		$this->_nums_count = count($this->_nums);
	}

	public function getNums($implode = TRUE)
	{
		if ($implode)
		{
			return implode($this->_nums, '');
		}

		return $this->_nums;
	}

	public function getNumsCount()
	{
		return $this->_nums_count;
	}

	protected function setNonNums()
	{
		for ($i = 0, $c = strlen($this->_unique); $i < $c; $i++)
		{
			if ( ! is_numeric($this->_unique[$i]))
			{
				$this->_non_nums[] = $this->_unique[$i];
			}
		}

		$this->_non_nums_count = count($this->_non_nums);
	}

	public function getNonNums($implode = TRUE)
	{
		if ($implode)
		{
			return implode($this->_non_nums, '');
		}

		return $this->_non_nums;
	}

	public function getNonNumsCount()
	{
		return $this->_non_nums_count;
	}

	protected function setRules()
	{
		$this->_non_num_value = $this->_non_nums[$this->_non_num_key = ceil(HELP::randArrayKey($this->_non_nums)/$this->_divisor)];

		if ($this->_ommit)
		{
			// Losuje ilość cyfr do przepisania
			$this->_nums_to_rewrite = ceil(rand(1, $this->_nums_count)/$this->_divisor);

			// Sprawdza, czy wylosowano wszystkie cyfry do przepisania
			if ($this->_nums_to_rewrite === $this->_nums_count)
			{
				$this->_nums_to_ommit = 0;
			}
			else
			{
				// Wylicza ilość cyfr do pominięcia
				$this->_nums_to_ommit = ceil(($this->_nums_count - $this->_nums_to_rewrite)/$this->_divisor);
				// Losuje stronę pomijania cyfr (lewa/prawa)
				$this->_nums_pos_to_ommit = rand(0, 1);
			}
		}
	}

	public function getNonNumKey()
	{
		return $this->_non_num_key+1;
	}

	public function getNonNumValue()
	{
		return $this->_non_num_value;
	}

	public function getNumsToRewrite()
	{
		return $this->_nums_to_rewrite;
	}

	public function getNumsToOmmit()
	{
		return $this->_nums_to_ommit;
	}

	public function getNumsPosToOmmit()
	{
		return $this->_nums_pos_to_ommit;
	}

	protected function setAnswer()
	{
		$answer = $this->getNonNumValue();

		// Sprawdza, czy jakieś cyfry mają zostać pominięte
		if ($this->_ommit && $this->getNumsToOmmit())
		{
			if ($this->getNumsPosToOmmit() === 0)
			{
				for ($i = $this->getNumsToOmmit(); $i < $this->getNumsCount(); $i++)
				{
					$answer .= $this->_nums[$i];
				}
			}
			else
			{
				for ($i = 0, $c = $this->getNumsCount()-$this->getNumsToOmmit(); $i < $c; $i++)
				{
					$answer .= $this->_nums[$i];
				}
			}
		}
		else
		{
			$answer .= implode($this->_nums, '');
		}

		$this->_answer = $answer;
	}

	public function getAnswer()
	{
		return $this->_answer;
	}
}

/* Przykłąd
$c = new Security;
echo $c->getCode().'<br /><br />';
echo 'Nie licząc cyfr przepisz '.$c->getNonNumKey().' znak od lewej strony,<br />';
echo 'a nastepnie (nie liczac znaków) wszystkie cyfry od początku kodu'.($c->getNumsToOmmit() ? ' z pominieciem '.$c->getNumsToOmmit().($c->getNumsPosToOmmit() ? ' ostatnich' : ' pierwszych'): '').'<br /><br />';

echo 'Odpowiedz: '.$c->getAnswer();
*/