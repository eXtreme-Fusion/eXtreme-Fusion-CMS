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
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
| Co-Author: Christian Damsgaard J�rgensen (PMM)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

class Modules
{
	protected
		$_pdo,
		$_sett,
		$_user,
		$_tag,
		$_system,
		$_locale;

	protected $_categories = array();

	/**
	 * Przypisuje zmiennym referencje do obiektów klas zewnętrznych
	 *
	 * @param   Database  obiekt klasy bazy danych
	 * @param   Sett	  obiekt klasy ustawień systemu
	 * @param   User	  obiekt klasy użytkownika
	 * @return  void
	 */
	public function __construct(Data $pdo, Sett $sett, User $user, Tag $tag, Locales $locale, System $system, Request $request)
	{
		$this->_pdo   = $pdo;
		$this->_sett  = $sett;
		$this->_user  = $user;
		$this->_tag   = $tag;
		$this->_locale   = $locale;
		$this->_system   = $system;
		$this->_request   = $request;
		// Kategorie, do których można przypisywać moduły
		$this->_categories = array(
			'security',
			'comments',
			'forum'
		);
	}

	/**
	 * Pobiera z plików katalogu MODULES nazwy poszczególnych modułów oraz nazwę katalogu, w którym się dany moduł znajduje.
	 *
	 * @return  array	dwuwymiarowa tablica o dwóch indeksach [nazwa katalogu modułu, nazwa modułu]
	 */
	public function getItems()
	{
		foreach (new DirectoryIterator(DIR_MODULES) as $file)
		{
			if ( ! in_array($file->getFilename(), array('..', '.', '.svn', '.gitignore')))
			{
				if (is_dir($file->getPathname()) && file_exists($file->getPathname().DS.'config.php'))
				{
					if (file_exists($file->getPathname().DS.'locale'.DS.$this->_sett->get('locale').DS.'admin'.DS.'config.php'))
					{
						$this->_locale->moduleLoad('config', $file->getFilename());
					}

					include $file->getPathname().DS.'config.php';

					if (isset($mod_info))
					{
						$modules[] = array($mod_info['dir'], $mod_info['title']);
					}
					unset($mod_info);
				}
			}
		}

		sort($modules);

		return $modules;
	}

	public function getConfig($path)
	{
		if (file_exists($path))
		{
			include $path;
			if (isset($mod_info))
			{
				return $mod_info;
			}
		}

		return array();
	}

	public function getModuleBootstrap($_system, $cache_expire = 43200)
	{
		$this->_cache['autoloadModulesList'] = $this->_system->cache('__autoloadModulesList', NULL, 'system', $cache_expire);
		if ($this->_cache['autoloadModulesList'] === NULL)
		{
			$this->_cache['autoloadModulesList'] = array();
			if ($installed = $this->getInstalled())
			{
				foreach($this->getInstalled() as $dir)
				{
					if (file_exists(DIR_MODULES.$dir) && is_dir(DIR_MODULES.$dir.DS))
					{
						if (file_exists(DIR_MODULES.$dir.DS.'autoload'.DS.'__autoload.php'))
						{
							$this->_cache['autoloadModulesList'][] = $dir;
						}
					}
				}

				sort($this->_cache['autoloadModulesList']);
			}
			$this->_system->cache('__autoloadModulesList', $this->_cache['autoloadModulesList'], 'system');
		}

		return $this->_cache['autoloadModulesList'];
	}

	/**
	 * Pobiera z bazy danych nazwy katalogów zainstalowanych modułów
	 *
	 * @return  array	[nazwa katalogu modułu]
	 */
	public function getInstalled()
	{
		$this->_cache['modules'] = $this->_system->cache('modules', NULL, 'system');
		if ($this->_cache['modules'] === NULL)
		{
			$this->_cache['modules'] = array();
			
			$query = $this->_pdo->getData('SELECT `folder` FROM [modules]');
			foreach($query as $d)
			{
				$this->_cache['modules'][] = $d['folder'];
			}
	
			$this->_system->cache('modules', $this->_cache['modules'], 'system');
		}

		return $this->_cache['modules'];
	}
	
	/**
	 * Sprawdza dane tworzonych uprawnień
	 *
	 * @return  void
	 */
	public function checkToCreatePermission($data, $dir)
	{
		foreach($this->_user->getPerms() as $d)
		{
			$perms[] = $d['name'];
		}

		foreach($this->_user->getPermsSections() as $d)
		{
			$sections[] = $d['name'];
		}

		if (in_array('module.'.$dir, $sections))
		{
			throw new systemException('Sekcja uprawnień <strong>module.'.$dir.'</strong> już istnieje w bazie.');
		}

		$new_perm = array();
		for ($i = 1, $c = count($data); $i <= $c; $i++)
		{
			$new_perm[$i] = 'module.'.$dir.'.'.$data[$i]['name'];

			if (in_array($new_perm[$i], $perms))
			{
				throw new systemException('Uprawnienie <strong>'.$new_perm[$i].'</strong> już istnieje w bazie.');
			}
		}
	}

	/**
	 * Tworzy i zapisuje w bazie uprawnienia dostarczone z modułem
	 *
	 * @return  bool
	 */
	protected function createPermission($data, $dir, $name)
	{
		$this->_pdo->exec("INSERT INTO [permissions_sections] (`name`, `description`, `is_system`) VALUES ('module.".$dir."', '".__('Module').' - '.$name."', 1)");
		$id = $this->_pdo->lastInsertId();

		for ($i = 1, $c = count($data); $i <= $c; $i++)
		{
			$this->_pdo->exec("INSERT INTO [permissions] (`name`, `description`, `section`, `is_system`) VALUES ('module.".$dir.".".$data[$i]['name']."', '{$data[$i]['desc']}', ".$id.", 1)");
		}
	}

	/**
	 * Sprawdza dane tworzonych podstron Panelu Admina
	 *
	 * @return  void
	 */
	public function checkToCreateAdminPage($admin, $dir)
	{
		$r = $this->_pdo->getData("SELECT `link` FROM [admin]");

		if ($this->_pdo->getRowsCount($r))
		{
			$links = array();
			foreach($r as $d)
			{
				$links[] = $d['link'];
			}

			// Sprawdzanie, czy wszystkie podstrony mogą zostać utworzone
			for ($i = 1, $c = count($admin); $i <= $c; $i++)
			{
				$addr[$i] = $dir.'/'.$admin[$i]['page'];
				if (in_array($addr[$i], $links))
				{
					throw new systemException('Podstrona o adresie <strong>'.$addr[$i].'</strong> już istnieje w Panelu Admina');
					exit;
				}
			}
		}
	}

	/**
	 * Tworzy nowe podstrony Panelu Admina
	 *
	 * @return  bool
	 */
	protected function createAdminPage($admin, $dir)
	{
		for ($i = 1, $c = count($admin); $i <= $c; $i++)
		{
			$perm = '';
			if (isset($admin[$i]['perm']) && $admin[$i]['perm'])
			{
				$perm = 'module.'.$dir.'.'.$admin[$i]['perm'];
			}
			$addr[$i] = $dir.'/'.$admin[$i]['page'];
			$this->_pdo->exec("INSERT INTO [admin] (`permissions`, `image`, `title`, `link`, `page`) VALUES ('{$perm}', '".$dir.'/'.$admin[$i]['image']."', '{$admin[$i]['title']}', '{$addr[$i]}', 5)");
		}
	}

	/**
	 * Sprawdza dane tworzonych tabel
	 *
	 * @return  void
	 */
	public function checkToCreateTable($tables)
	{
		foreach($tables as $table)
		{
			if ($this->_pdo->tableExists($table[0]))
			{
				throw new systemException('Tabela <strong>'.$table[0].'</strong> jest już w bazie danych.');
				exit;
			}
		}
	}

	/**
	 * Sprawdza dane tabel do aktualizacji
	 *
	 * @return  void
	 */
	public function checkToUpdateTable($tables)
	{
		foreach($tables as $table)
		{
			if ($this->_pdo->tableExists($table[0]))
			{
				throw new systemException('Tabela <strong>'.$table[0].'</strong> nie istnieje w bazie danych.');
				exit;
			}
		}
	}

	/**
	 * Sprawdza poprawność kategorii, do której ma zostać przypisany moduł
	 *
	 * @return  void
	 */
	public function checkCategory($cat)
	{
		if ( ! in_array($cat, $this->_categories))
		{
			throw new systemException('Kategoria <strong>'.$cat.'</strong> (do której próbowano przypisać moduł) nie istnieje.');
			exit;
		}
	}

	public function install($modules)
	{
		$modules = HELP::strip($modules);

		if (file_exists(DIR_MODULES.$modules.DS.'config.php'))
		{
			if (file_exists(DIR_MODULES.$modules.DS.'locale'.DS.$this->_sett->get('locale').DS.'admin'.DS.'config.php'))
			{
				$this->_locale->moduleLoad('config', $modules);
			}

			include DIR_MODULES.$modules.DS.'config.php';

			// Kontrola tworzonych tabel
			if (isset($new_table) && is_array($new_table))
			{
				$this->checkToCreateTable($new_table);
				$do['create_table'] = TRUE;
			}

			// Kontrola tworzonych uprawnień
			if (isset($perm) && is_array($perm))
			{
				$this->checkToCreatePermission($perm, $mod_info['dir']);
				$do['create_perm'] = TRUE;
			}

			// Kontrola tworzonych podstron Panelu Admina
			if (isset($admin_page) && is_array($admin_page))
			{
				$this->checkToCreateAdminPage($admin_page, $mod_info['dir']);
				$do['create_page'] = TRUE;
			}

			// Kontrola istniejących tabel, które mają zostać zaktualizowane przez zmianę nazw kolumn
			if (isset($update_table) && is_array($update_table))
			{
				foreach($update_table as $val)
				{
					$this->checkToUpdateTable($val[0]);
				}

				$do['update_table'] = TRUE;
			}

			/**
			 * Kontrola istniejących tabel, które mają zostać zaktualizowane przez zmianę
			 * nazw kolumn przez dodanie nowych kolumn.
			 */
			if (isset($add_field) && is_array($add_field))
			{
				foreach($add_field as $val)
				{
					$this->checkToUpdateTable($val[0]);
				}

				$do['add_field'] = TRUE;
			}

			if (isset($mod_info['category']))
			{
				$this->checkCategory($mod_info['category']);

				if ($mod_info['category'] === 'security')
				{
					$_security = new Security($this->_pdo, $this->_request, $this->_locale);
					$_security->checkModuleBeforeInstall($mod_info['dir']);
				}
			}
			else
			{
				$mod_info['category'] = '';
			}



			//TODO:: sprawdzanie, czy plik/klasa modułu security istnieje

			## ZAPIS MODUŁU W BAZIE:

			// Tworzenie linków nawigacyjnych
			if (isset($menu_link) && is_array($menu_link))
			{
				foreach($menu_link as $val)
				{
					$link_order = $this->_pdo->getMaxValue('SELECT MAX(`order`) FROM [navigation]');
					$this->_pdo->exec("INSERT INTO [navigation] (`name`, `url`, `visibility`, `position`, `window`, `order`) VALUES ('{$val['title']}', '{$val['url']}".$this->_sett->getUns('routing', 'url_ext')."', '{$val['visibility']}', '1', '0', '{$link_order}')");
				}
			}

			// Tworzenie tabel
			if (isset($do['create_table']))
			{
				foreach($new_table as $val)
				{
					$this->_pdo->exec("CREATE TABLE [{$val[0]}] {$val[1]}");
				}
			}

			// Zapis danych do tabel
			if (isset($new_row) && is_array($new_row))
			{
				foreach($new_row as $val)
				{
					$this->_pdo->exec("INSERT INTO [{$val[0]}] {$val[1]}");
				}
			}

			// Tworzenie nowych uprawnień
			if (isset($do['create_perm']))
			{
				$this->createPermission($perm, $mod_info['dir'], $mod_info['title']);
			}

			// Tworzenie podstron Panelu Admina
			if (isset($do['create_page']))
			{
				$this->createAdminPage($admin_page, $mod_info['dir']);
			}

			// Aktualizacja istniejących tabel przez zmianę kolumn
			if (isset($do['update_table']))
			{
				foreach($update_table as $val)
				{
					$this->_pdo->exec("ALTER TABLE [{$val[0]}] {$val[1]}");
				}
			}

			// Aktualizacja istniejących tabel przez dodanie nowych kolumn
			if (isset($do['add_field']))
			{
				foreach($add_field as $val)
				{
					$this->_pdo->exec("ALTER TABLE [{$val[0]}] ADD {$val[1]}");
				}
			}

			// Zapis danych w bazie w głównej tabeli modułów
			return $this->_pdo->exec("INSERT INTO [modules] (`title`, `folder`, `version`, `category`) VALUES ('{$mod_info['title']}', '{$mod_info['dir']}', '{$mod_info['version']}', '{$mod_info['category']}')");
		}

		return FALSE;
	}

	public function update($modules)
	{
		$modules = HELP::strip($modules);
		if (file_exists(DIR_MODULES.$modules.DS.'config.php'))
		{
			if (file_exists(DIR_MODULES.$modules.DS.'locale'.DS.$this->_sett->get('locale').DS.'admin'.DS.'config.php'))
			{
				$this->_locale->moduleLoad('config', $modules);
			}

			include DIR_MODULES.$modules.DS.'config.php';

			$data = $this->_pdo->getRow("SELECT `id`, `version` FROM [modules] WHERE `folder`='{$inf_folder}'");
			if ($data)
			{
				if ($inf_version > $data['version'])
				{
					if (isset($inf_altertable) && is_array($inf_altertable) && count($inf_altertable))
					{
						for ($i = 1; $i <= count($inf_altertable); $i++)
						{
							$result = $this->_pdo->exec('ALTER TABLE ['.$inf_altertable[$i].']');
						}
					}
					$result = $this->_pdo->exec("UPDATE [modules] SET `version`='{$inf_version}' WHERE `id`='{$data['inf_id']}'");
					if ($result)
					{
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	public function isToUpdate($modules)
	{
		$modules = HELP::strip($modules);
		if (file_exists(DIR_MODULES.$modules.DS.'config.php'))
		{
			if (file_exists(DIR_MODULES.$modules.DS.'locale'.DS.$this->_sett->get('locale').DS.'admin'.DS.'config.php'))
			{
				$this->_locale->moduleLoad('config', $modules);
			}

			include DIR_MODULES.$modules.DS.'config.php';

			$data = $this->_pdo->getRow("SELECT `version` FROM [modules] WHERE `folder`='{$mod_info['dir']}'");
			if ($data)
			{
				if ($mod_info['version'] > $data['version'])
				{
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	public function getItemVersion($folder, $version)
	{
		$data = $this->_pdo->getRow("SELECT `version` FROM [modules] WHERE `folder`='{$folder}'");
		if ($data)
		{
			return $data['version'];
		}
		return $version;
	}

	public function uninstall($folder)
	{
		$folder = HELP::strip($folder);
		$data = $this->_pdo->getRow("SELECT `folder` FROM [modules] WHERE `folder`='{$folder}'");
		if ($data)
		{
			if (file_exists(DIR_MODULES.$folder.DS.'locale'.DS.$this->_sett->get('locale').DS.'admin'.DS.'config.php'))
			{
				$this->_locale->moduleLoad('config', $folder);
			}

			include DIR_MODULES.$folder.DS.'config.php';

			// Usuwanie podstron
			if (isset($admin_page) && is_array($admin_page))
			{
				foreach($admin_page as $val)
				{
					$result = $this->_pdo->exec("DELETE FROM [admin] WHERE `title`='{$val['title']}'");
				}
			}

			// Usuwanie linków
			if (isset($menu_link) && is_array($menu_link))
			{
				foreach($menu_link as $val)
				{
					$data = $this->_pdo->getRow("SELECT `id`, `order` FROM [navigation] WHERE `url`='".$val['url'].$this->_sett->getUns('routing', 'url_ext')."'");
					if ($data)
					{
						$result = $this->_pdo->exec("UPDATE [navigation] SET `order`=`order`-1 WHERE `order`>{$data['order']}");
						$result = $this->_pdo->exec("DELETE FROM [navigation] WHERE `id`={$data['id']}");
					}
				}
			}

			// Usuwanie tabel
			if (isset($drop_table) && is_array($drop_table))
			{
				foreach($drop_table as $val)
				{
					$result = $this->_pdo->exec("DROP TABLE [{$val}]");
				}
			}

			// Usuwanie wierszy
			if (isset($del_row) && is_array($del_row))
			{
				foreach($del_row as $val)
				{
					$result = $this->_pdo->exec("DELETE FROM [{$val[0]}] WHERE {$val[1]}");
				}
				return TRUE;
			}

			// Aktualizacja tabeli
			if (isset($drop_field) && is_array($drop_field))
			{
				foreach($drop_field as $val)
				{
					$this->_pdo->exec("ALTER TABLE [{$val[0]}] DROP {$val[1]}");
				}
			}

			// Usuwanie uprawnień i ich sekcji
			if (isset($perm) && is_array($perm))
			{
				$r = $this->_pdo->getRow("SELECT `id` FROM [permissions_sections] WHERE `name` = 'module.".$mod_info['dir']."'");
				if ($r)
				{
					$result = $this->_pdo->exec("DELETE FROM [permissions_sections] WHERE `id` = '{$r['id']}'");
					$result = $this->_pdo->exec("DELETE FROM [permissions] WHERE `section` = '{$r['id']}'");
					$this->_user->cleanPerms();
				}
			}

			// Usuwanie tagów
			if (isset($tag_supplement) && is_array($tag_supplement))
			{
				foreach($tag_supplement as $val)
				{
					$this->_tag->delTagFromSupplement($val);
				}
			}

			$result = $this->_pdo->exec("DELETE FROM [modules] WHERE `folder`='{$mod_info['dir']}'");


		}
	}

	/**
	 * Pobiera z bazy danych nazwy katalogów zainstalowanych modułów
	 * Porównuje parametr w listą zainstalowanych modułów.
	 *
	 * @return bool
	 */
	public function isInstalled($name)
	{
		$this->_cache['modules'] = $this->_system->cache('modules', NULL, 'system');
		if ($this->_cache['modules'] === NULL)
		{
			$this->_cache['modules'] = array();
			
			$query = $this->_pdo->getData('SELECT `folder` FROM [modules]');
			foreach($query as $d)
			{
				$this->_cache['modules'][] = $d['folder'];
			}
	
			$this->_system->cache('modules', $this->_cache['modules'], 'system');
		}
		
		return in_array($name, $this->_cache['modules']);
	}
}