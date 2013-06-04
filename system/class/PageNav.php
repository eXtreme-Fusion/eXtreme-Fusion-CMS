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
 * Dzięki Bogu, po cięzkiej pracy, udało się przebudować stronicowanie.
 * HOW TO USE:
 * 		$ec->paging->setPagesCount($rows_count, $current_page, $per_page);
 *		$ec->pageNav->get($ec->pageNav->create($_tpl, $buttons_count), 'tpl_filename');
 */

interface PageNavIntf
{
	// Wyświetla stronicowanie
	public function create($_tpl, $links_count, $show_go_to_first = TRUE, $show_go_to_last = TRUE);

	public function get(array $paging, $filename, $dir = NULL);

	// Zwraca tablicę z numerami podstron do wyświetlenia
	public function getPagesNums();

	// Zwraca ilość numerów podstron, jakie zostaną wyświetlone w nawigacji
	// z uwzględnieniem aktualnej pozycji i wyjściowej ilości
	public function getLinksCount();
}

class PageNav extends Observer implements PageNavIntf
{
	private
		$_paging,
		$_tpl;

	private
		$_links_count,					// Ilość podstron, jakie będą wyświetlane w nawigacji
		$_pages_nums = array(), 		// Tablica z numerami podstron z uwzględnieniem $_links_count
		$_route,						// Tablica z formatem linków stronicowania
		$_default_ext = 'html';

	// Nalezy pamiętać, aby parametr $links_count przesłany do konstruktora był parsowany przez funkcję intval()!!
	public function __construct(Paging $paging)
	{
		$this->_paging = $paging;
	}

	private function createListToDisplay()
	{
		// Sprawdza, czy liczba numerów stron do wyświetlenia jest parzysta
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

		// Sprawdzanie, czy ostatni numer reprezentuje istniejącą podstronę
		if ($end > $this->_paging->getLastPage())
		{
			// Skoro wyliczony numer  wykracza poza numery reprezentujące istniejące podstrony,
			// to trzeba cofnąć do tyłu numer pierwszej, aby wyświetliło się tyle numerów, ile chciano.
			$begin -= $end - $this->_paging->getLastPage();

			// Ostatni wyświetlany numer podstrony będzie równy ilorazowi wszystkich materiałów a ich ilości na podstronę.
			$end = $this->_paging->getLastPage();
		}

		// Sprawdzanie, czy pierwszy numer nie jest mniejszy od 1
		if ($begin < 1)
		{
			// Skoro wyliczony numer wykracza poza numery reprezentujące istniejące podstrony (jest mniejszy od 1, a taka podstrona przecież nie istnieje),
			// to trzeba przesunąć do przodu numer ostatniej, aby wyświetliło się tyle numerów, ile chciano.
			$end += 1 - $begin;

			// Pierwszy wyświetlany numer podstrony będzie równy 1
			$begin = 1;

			// Sprawdzanie, czy w wyniku przesuwania do przodu ostatniej podstrony nie przekroczono zakresu istniejących podstron
			if ($end > $this->_paging->getLastPage())
			{
				// Skoro zakres przekroczono, to ostatni wyświetlany numer podstrony będzie równy ilorazowi wszystkich materiałów a ich ilości na podstronę.
				// Niestety, ilość wyświetlanych numerów podstron będzie mniejsza niż chciano, gdyż ich tyle nie istnieje.
				$end = $this->_paging->getLastPage();
			}
		}

		// Skoro znamy początkowy i końcowy numer podstron, trzeba je wyodrębnić.
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
			throw new systemException('Błąd! Parametr czwarty nie może przyjmować wartości mniejszej od <span class="italic">1</span>.');
		}

		$this->createListToDisplay();

		$page_nav['nums'] = $this->getPagesNums();

		// Nadawanie domyślnej wartości
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

	// W odrębnym stosie OPT tworzy szablon, który przechwycony przez bufor danych jest zwracany przez metodę
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