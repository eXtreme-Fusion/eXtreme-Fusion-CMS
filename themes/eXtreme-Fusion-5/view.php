<?php

class Theme extends Themes implements Theme_Intf
{
	// Nagłówek strony, ustawienia paneli, stopka
	public function page($logo = TRUE, $menu = TRUE, $left = TRUE, $right = TRUE, $footer = TRUE)
	{
		if ($logo) $this->assign('logo', trim($this->showBanners()));
		if ($menu) $this->assign('menu', $this->showSubLinks('', 'menu'));

		// Panele - o ile istnieją w danym położeniu
		if ($left)  $this->assign('LEFT', LEFT);
		if ($right) $this->assign('RIGHT', RIGHT);

		// Część środkowa strony - panele górne, treść, panele dolne
		$this->assign('CONTENT', TOP_CENTER.CONTENT.BOTTOM_CENTER);

		// Stopka - pobieranie treści przez obiekt Sett
		if ($footer) $this->assign('footer', $this->obj('sett')->get('footer'));

		// Wymagane informacje o autorach systemu i licencji
		$this->assign('copyright', $this->showCopyright(TRUE));

		// Link do Panelu Admina - widoczny tylko dla osób uprawnionych
		$this->assign('admin_panel_link', $this->showAdminPanelLink());

		// Licznik unikalnych odwiedzin - o ile jest właczony w Panelu Admina
		if ($this->obj('sett')->get('visits_counter_enabled'))
		{
			$this->assign('visits_count', $this->getVisitsCount());
		}

		// Wyświetlanie szablonu
		$this->render('page');
	}

	// Pojedynczy panel boczny
	public function sidePanel($title = NULL)
	{
		$this->assignGroup(array(
			'title' => $title,
			'open' => (bool) $title
		));

		// Wyświetlanie szablonu
		$this->render('side_panel');
	}

	// Pojedynczy panel środkowy
	public function middlePanel($title = NULL)
	{
		$this->assignGroup(array(
			'title' => $title,
			'open' => (bool) $title
		));

		// Wyświetlanie szablonu
		$this->render('middle_panel');
	}

	// Dodatki do newsów
	public function news()
	{
		// Addons to assign as variables for /pages/news.php -> news.tpl.
	}
}