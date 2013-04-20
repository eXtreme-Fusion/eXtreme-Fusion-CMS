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

# THIS CLASS IS A VIEW

/**
 * Dziêki Bogu, po ciêzkiej pracy, uda³o siê przebudowaæ stronicowanie.
 * HOW TO USE:
 * 		$ec->paging->setPagesCount($rows_count, $current_page, $per_page);
 *		$ec->pageNav->get($ec->pageNav->create($_tpl, $buttons_count), 'tpl_filename');
 */

interface PageNavIntf
{
	// Wyœwietla stronicowanie
	public function create($_tpl, $links_count, $show_go_to_first = TRUE, $show_go_to_last = TRUE);

	public function get(array $paging, $filename, $dir = NULL);

	// Zwraca tablicê z numerami podstron do wyœwietlenia
	public function getPagesNums();

	// Zwraca iloœæ numerów podstron, jakie zostan¹ wyœwietlone w nawigacji
	// z uwzglêdnieniem aktualnej pozycji i wyjœciowej iloœci
	public function getLinksCount();
}

class PageNav extends Observer implements PageNavIntf
{
	private
		$_paging,
		$_tpl;

	private
		$_links_count,					// Iloœæ podstron, jakie bêd¹ wyœwietlane w nawigacji
		$_pages_nums = array(), 		// Tablica z numerami podstron z uwzglêdnieniem $_links_count
		$_route,						// Tablica z formatem linków stronicowania
		$_default_ext = 'html';

	// Nalezy pamiêtaæ, aby parametr $links_count przes³any do konstruktora by³ parsowany przez funkcjê intval()!!
	public function __construct(Paging $paging)
	{
		$this->_paging = $paging;
	}

	private function createListToDisplay()
	{
		// Sprawdza, czy liczba numerów stron do wyœwietlenia jest parzysta
		if ($this->_links_count % 2 == 0)
		{
			/* Wyliczanie pierwszego i ostatniego numeru podstrony */
			$begin = $this->_paging->getCurrentPage()-($this->_links_count/2-1);
			$end = $this->_paging->getCurrentPage()+$this->_links_count/2;
		}
		else
		{
			/* Wyliczanie pierwszego i ostatniego numeru podstrony */
			$begin = $this->_paging->getCurrentPage()-floor($this->_links_count/2);
			$end = $this->_paging->getCurrentPage()+floor($this->_links_count/2);
		}

		// Sprawdzanie, czy ostatni numer reprezentuje istniej¹c¹ podstronê
		if ($end > $this->_paging->getLastPage())
		{
			// Skoro wyliczony numer  wykracza poza numery reprezentuj¹ce istniej¹ce podstrony,
			// to trzeba cofn¹æ do ty³u numer pierwszej, aby wyœwietli³o siê tyle numerów, ile chciano.
			$begin -= $end - $this->_paging->getLastPage();

			// Ostatni wyœwietlany numer podstrony bêdzie równy ilorazowi wszystkich materia³ów a ich iloœci na podstronê.
			$end = $this->_paging->getLastPage();
		}

		// Sprawdzanie, czy pierwszy numer nie jest mniejszy od 1
		if ($begin < 1)
		{
			// Skoro wyliczony numer wykracza poza numery reprezentuj¹ce istniej¹ce podstrony (jest mniejszy od 1, a taka podstrona przecie¿ nie istnieje),
			// to trzeba przesun¹æ do przodu numer ostatniej, aby wyœwietli³o siê tyle numerów, ile chciano.
			$end += 1 - $begin;

			// Pierwszy wyœwietlany numer podstrony bêdzie równy 1
			$begin = 1;

			// Sprawdzanie, czy w wyniku przesuwania do przodu ostatniej podstrony nie przekroczono zakresu istniej¹cych podstron
			if ($end > $this->_paging->getLastPage())
			{
				// Skoro zakres przekroczono, to ostatni wyœwietlany numer podstrony bêdzie równy ilorazowi wszystkich materia³ów a ich iloœci na podstronê.
				// Niestety, iloœæ wyœwietlanych numerów podstron bêdzie mniejsza ni¿ chciano, gdy¿ ich tyle nie istnieje.
				$end = $this->_paging->getLastPage();
			}
		}

		// Skoro znamy pocz¹tkowy i koñcowy numer podstron, trzeba je wyodrêbniæ.
		for($i = $begin; $i <= $end; $i++)
		{
			$this->_pages_nums[] = $i;
		}
	}

	public function create($_tpl, $links_count, $show_go_to_first = TRUE, $show_go_to_last = TRUE)
	{
		$this->_tpl = $_tpl;

		if ($links_count >= 1)
		{
			$this->_links_count = $links_count;
		}
		else
		{
			throw new systemException('B³¹d! Parametr czwarty nie mo¿e przyjmowaæ wartoœci mniejszej od <span class="italic">1</span>.');
		}

		$this->createListToDisplay();

		$page_nav['nums'] = $this->getPagesNums();

		// Nadawanie domyœlnej wartoœci
		$page_nav['first'] = NULL;
		$page_nav['last'] = NULL;

		if ($show_go_to_first && !in_array($this->_paging->getFirstPage(),  $this->getPagesNums()))
		{
			$page_nav['first'] = 1;
		}

		if ($show_go_to_last && !in_array($this->_paging->getLastPage(), $this->getPagesNums()))
		{
			$page_nav['last'] = $this->_paging->getLastPage();
		}

		$page_nav['current'] = $this->_paging->getCurrentPage();

		$page_nav['prev'] = $this->_paging->getPrevPage();
		$page_nav['next'] = $this->_paging->getNextPage();

		return $page_nav;
	}

	// W odrêbnym stosie OPT tworzy szablon, który przechwycony przez bufor danych jest zwracany przez metodê
	public function get(array $paging, $filename, $dir = NULL, $comments = TRUE)
	{
		if ($paging)
		{
			$_tpl = new pageNavParser(StaticContainer::get('route', NULL), StaticContainer::get('request', NULL));

			$_tpl->assignGroup(array(
				'nums' => count($paging['nums']) > 1 ? $paging['nums'] : NULL,
				'first' => $paging['first'],
				'last' => $paging['last'],
				'prev' => $paging['prev'],
				'next' => $paging['next'],
				'current' => $paging['current'],
				'ext' => isset($paging['route'][2]) ? $this->_route === FALSE ? '.'.$paging['route'][2] : '' : $this->_route === FALSE ? '.'.$this->_default_ext : ''
			));

			ob_start();

			$_tpl->template($filename, $dir);

			$out = ob_get_contents();

			ob_end_clean();

			if (isset(parent::$_obj))
			{
				parent::$_obj->assign('page_nav', $out);
			}
			$this->_tpl->assign('page_nav', $out);
		}
	}

	public function getComments(array $paging, $filename, $data)
	{
		$this->get($paging, $filename);
		$this->_tpl->assign('comments', $data);
	}

	public function getPagesNums()
	{
		return $this->_pages_nums;
	}

	public function getLinksCount()
	{
		return count($this->_pages_nums);
	}
}