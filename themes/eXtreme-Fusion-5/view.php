<?php

/**
 * W tym pliku nie zamieszczamy kodu HTML, jedynie PHP.
 * Poprzez $this->assign('nazwa', 'wartość'), można przekazać
 * dane z bazy lub innego źródła do pliku *.tpl.
 *
 * Legenda:
 * 	THEME - katalog, w którym znajduje się ten plik
 * 	BASE  - katalog, w którym znjduje się cały system i plik config.php
 */
class Theme extends Themes implements Theme_Intf
{
	/**
	 * Funkcja odpowiedzialana za sposób prezentowania twojej strony.
	 * To tutaj określasz jak są ustawione kolumny z panelami bocznymi:
	 * czy po lewej i prawej stronie, czy obie kolumny po tej samej.
	 *
	 * W tym miejscu załączane jest również menu poziome,
	 * nagłówek strony oraz stopka.
	 *
	 * Poszczególne elementy szablonu mogą być wyłączone z poziomu modułu, dzięki parametrom.
	 * Z tego powodu zalecamy korzystać z warunków if ($parametr).
	 *
	 * THEME/templates/page.tpl
	 */
	public function page($logo = TRUE, $menu = TRUE, $left = TRUE, $right = TRUE, $footer = TRUE)
	{
		// Przekazywanie logo do szablonu
		if ($logo) $this->assign('logo', trim($this->showBanners()));

		// Przekazywanie menu do szablonu
		if ($menu) $this->assign('menu', $this->showSubLinks('', 'menu'));

		/**
		 * Przekazywanie paneli do szablonu
		 * Kolejność ustawiasz w panelu admina, Zarządzanie stroną -> Panele.
		 */

		// Przekazywanie kolumny z panelami z lewej strony
		if ($left)  $this->assign('LEFT', LEFT);

		// Przekazywanie kolumny z panelami z prawej strony
		if ($right) $this->assign('RIGHT', RIGHT);

		// Część środkowa strony - panele górne, treść podstrony, panele dolne
		$this->assign('CONTENT', TOP_CENTER.CONTENT.BOTTOM_CENTER);

		// Stopka - pobieranie treści z ustawień i przekazywanie do szablonu
		if ($footer) $this->assign('footer', $this->obj('sett')->get('footer'));

		// Wymagane informacje o autorach systemu i licencji
		$this->assign('copyright', $this->showCopyright(TRUE));

		// Link do Panelu Admina - widoczny tylko dla osób uprawnionych
		$this->assign('admin_panel_link', $this->showAdminPanelLink());

		/**
		 * Licznik unikalnych odwiedzin - o ile jest włączony.
		 * Aby włączyć/wyłączyć, przejdź do Panel admina -> Ustawienia -> Różne.
		 */

		// Sprawdzanie, czy licznik unikalnych odwiedzin jest włączony.
		// Jak w przypadku stopki, korzystamy z obiektu ustawień.
		if ($this->obj('sett')->get('visits_counter_enabled'))
		{
			// Przekazywanie ilości unikalnych wizyt do szablonu
			$this->assign('visits_count', $this->getVisitsCount());
		}

		// Wyświetlanie szablonu: THEME/templates/page.tpl
		$this->render('page');

	}

	/**
	 * Pojedynczy panel boczny.
	 *
	 * THEME/templates/side_panel.tpl
	 */
	public function sidePanel($title = NULL)
	{
		// Przekazywanie nazwy panelu do szablonu
		$this->assignGroup(array(
			'title' => $title,
			'open' => (bool) $title
		));

		// Wyświetlanie szablonu: THEME/templates/side_panel.tpl
		$this->render('side_panel');
	}

	/**
	 * Pojedynczy panel środkowy.
	 *
	 * THEME/templates/middle_panel.tpl
	 */
	public function middlePanel($title = NULL)
	{
		// Przekazywanie nazwy panelu do szablonu
		$this->assignGroup(array(
			'title' => $title,
			'open' => (bool) $title
		));

		// Wyświetlanie szablonu: THEME/templates/middle_panel.tpl
		$this->render('middle_panel');
	}

	/**
	 * Dodatki do newsów.
	 *
	 * Funkcja ta spełnia inną rolę, niż w wersji EF 4.
	 * Jest opcjonalna, ale użyteczna dla osób,
	 * które chciałyby przekazać dodatkowe dane do szablonu newsów.
	 *
	 * BASE/templates/news.tpl
	 * THEME/templates/news.tpl
	 */
	public function news()
	{
		// Assign addons for news (optional).
		// $this->assign(...
	}
}