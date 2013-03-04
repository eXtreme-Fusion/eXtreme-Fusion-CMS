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
| Co Author: WEC
| Author: Hans Kristian Flaatten {Starefossen}
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
// todo: przepisać klasę z użyciem cache.

class Panels
{
    protected
        $_pdo,
		$_mod,
        $_type = 'php',
        $_dir,
		// Panels - admin page
		$active_panels = array(),
		$inactive_panels = array(),
		$panels = array(),
		$panel = array();

    public function __construct(Data $pdo, $dir = './')
    {
        $this->_pdo = $pdo;
        $this->_dir = $dir;
    }

	public function setModulesInst($_mod)
	{
		$this->_mod = $_mod;
	}

	// Pobiera z bazy danych dane wszystkich zainstalowanych modułów i zapisuje do zmiennej klsowej
	public function adminGetDataPanels($_user)
	{
		$query = $this->_pdo->getData('SELECT * FROM [panels] ORDER BY `side`, `order`');
		foreach($query as $data)
		{
			$this->panel[] = array(
				'id' => $data['id'],
				'name' => $data['name'],
				'file_name' => $data['filename'],
				'side' => $data['side'],
				'order' => $data['order'],
				'type' => $data['type'] === 'file' ? __('File') : __('Code'),
				'access' => $_user->groupsStrIDsToNames($data['access']),
				'status' => $data['status']
			);

			$this->active_panels[] = $data['filename'];
		}
	}

	// Tworzy listę wszystkich paneli dostępnych na FTP poza modułami (samodzielnie)
	// na podstawie prefiksu katalogu "_panel"
	public function adminGetFtpPanels()
	{
		$this->panels = HELP::getFileList(DIR_MODULES, array('.', '..', '.svn', '.gitignore'), TRUE, 'folders', '_panel');
	}

	// Tworzy listę wszystkich paneli dostępnych w modułach (niesamodzielnie)
	// Podanie drugiego parametru jako TRUE spowoduje, że uwzględnione zostaną tylko zainstalowane moduły.
	public function adminGetModulesPanels($check_if_installed = FALSE)
	{
		// Tworzy listę wszystkich folderów zawartych w katalogu "modules"
		$modules = HELP::getFileList(DIR_MODULES, array('.', '..', '.svn', '.gitignore'), TRUE, 'folders');

		$list = array(); $mod = array();
		foreach ($modules as $module)
		{
			// Tworzy listę paneli dostępnych w $module rozpoznając je po prefiksie "_panel" katalogu.
			$module_panel = HELP::getFileList(DIR_MODULES.$module.DS, array('.', '..', '.svn', '.gitignore'), TRUE, 'folders', '_panel');

			foreach($module_panel as $mpanel)
			{
				$list[] = $module.'/'.$mpanel;
				$mod[] = $module;
			}
		}

		if ($check_if_installed)
		{
			if ($this->_mod)
			{
				$installed = $this->_mod->getInstalled();

				foreach($mod as $key => $dir)
				{
					if (in_array($dir, $installed))
					{
						$this->panels[] = $list[$key];
					}
				}
			}
			else
			{
				throw new systemException('Instance of Modules has not been set');
			}
		}
		else
		{
			$this->panels = array_merge($list, $this->panels);
		}
	}

	// Tworzy listę (z danymi z plików "config.php") nieaktywnych paneli, których można użyć.
	// Nie zostały użyte lub moduł, z którego pochodzą jest zainstalowany lub występują samodzielnie.
	public function adminMakeListPanels($_user, $check_if_installed = FALSE)
	{
		$this->adminGetDataPanels($_user);
		$this->adminGetFtpPanels();
		$this->adminGetModulesPanels($check_if_installed);

		foreach($this->adminGetPanels() as $panel)
		{
			if ( ! in_array($panel, $this->adminGetActivePanels()))
			{
				include_once DIR_MODULES.$panel.DS.'config.php';

				$panel_info['filename'] = $panel;
				$panel_info['type'] = 'File';
				if (is_array($panel_info['access']))
				{
					$panel_info['access'] = $_user->groupArrIDsToNames($panel_info['access']);
				}
				else
				{
					$panel_info['access'] = $_user->getRoleName($panel_info['access']);
				}

				$this->inactive_panels[] = $panel_info;
			}
		}
	}

	// Zwraca tablicę z listą nazw folderów paneli dostępnych do instalacji
	public function getInactivePanelsDir($_user, $check_if_installed)
	{
		$this->adminGetDataPanels($_user);
		$this->adminGetFtpPanels();
		$this->adminGetModulesPanels($check_if_installed);

		return $this->panels;
	}

	// Zwraca tablicę z danymi (z plików "config.php") paneli dostępnych do instalacji
	public function adminGetInactivePanels()
	{
		return $this->inactive_panels;
	}

	public function adminGetActivePanels()
	{
		return $this->active_panels;
	}

	public function adminGetPanels()
	{
		return $this->panels;
	}

	public function adminGetPanel()
	{
		return $this->panel;
	}

	// Aktualizacja panelu przez edytor.
    public function updatePanel($id, array $access, $name, $content = NULL)
    {
        if ( ! isNum($id))
        {
            throw new systemException('Błąd! Parametr metody jest nieprawidłowego typu.');
        }

		$data = $this->get($id);

		if ($data['type'] !== 'file')
		{
			return $this->_pdo->exec('
				UPDATE [panels] SET `name` = :name, `content` = :content, `access` = :access
				WHERE `id` = :id',
				array(
					array(':name', $name, PDO::PARAM_STR),
					array(':content', $content, PDO::PARAM_STR),
					array(':access', HELP::implode($access), PDO::PARAM_STR),
					array(':id', $id, PDO::PARAM_INT)
				)
			);
		}

		return $this->_pdo->exec('
			UPDATE [panels] SET `name` = :name, `access` = :access WHERE `id` = :id',
			array(
				array(':name', $name, PDO::PARAM_STR),
				array(':access', HELP::implode($access), PDO::PARAM_STR),
				array(':id', $id, PDO::PARAM_INT)
			)
		);
	}

	// Zapis panelu przez edytor.
    public function insertPanel($name, $content, $side, array $access)
    {
        if ( ! isNum($side))
        {
            throw new systemException('Błąd! Parametr metody jest nieprawidłowego typu.');
        }

		$this->_pdo->exec("UPDATE [panels] SET `order`=`order`+1 WHERE `side`='{$side}'");

        return $this->_pdo->exec('
			INSERT INTO [panels] (`name`, `content`, `side`, `type`, `access`, `order`)
			VALUES (:name, :content, :side, \''.$this->_type.'\', :access, 1)',
			array(
				array(':name', $name, PDO::PARAM_STR),
				array(':content', $content, PDO::PARAM_STR),
				array(':side', $side, PDO::PARAM_INT),
				array(':access', HELP::implode($access), PDO::PARAM_STR)
			)
		);
    }

    public function get($id)
    {
        if ( ! isNum($id))
        {
            return FALSE;
        }

        $r = $this->_pdo->getRow('SELECT * FROM [panels] WHERE `id` = :id', array(':id', $id, PDO::PARAM_INT));

        $r['content'] = $this->closePHPOut($r['content']);
		$r['access'] = HELP::explode($r['access']);

        return $r;
    }

    public function closePHPOut($val = NULL, $check = TRUE)
    {
        if ($val === NULL || ! $val)
        {
            return FALSE;
        }

        if ($val[0] != '?' && $val[1] != '>' && $check)
        {
            return $val;
        }

        return substr($val, 2, strlen($val)-2);
    }

    public function closePHPSet($val = NULL, $check = FALSE)
    {
        if ($val === NULL || ! $val)
        {
            return FALSE;
        }
        if ($val[0] == '?' && $val[1] == '>' && $check)
        {
            return $val;
        }

        return '?>'.$val;
    }

	/** Wyświetlanie paneli poza Panelem Admina **/

	public function checkState($side)
	{

	}

	public function getPanelsList($_user)
	{
		$data = $this->_pdo->getData('SELECT `id`, `name`, `filename`, `side`, `access`, `type`, `content` FROM [panels] WHERE `status` = 1 ORDER BY `side`, `order` ASC');

		$panels = array();
		foreach($data as $panel)
		{
			if ($_user->hasAccess($panel['access']))
			{
				$panels[$panel['id']] = array('name' => $panel['name'], 'filename' => $panel['filename'], 'side' => $panel['side'], 'type' => $panel['type'], 'content' => $panel['content']);
			}
		}

		return $panels;
	}

	public function loadPanel($type, $filename, $content = NULL)
	{
		if ($type === 'file')
		{
			if (file_exists(DIR_MODULES.$filename.DS.'panel'.DS.$filename.'.php'))
			{
				//$_panel = new Panel($_route, DIR_MODULES.$filename.DS.'templates'.DS);
				//include DIR_MODULES.$filename.DS.'panel'.DS.$filename.'.php';
				//$_panel->template($filename.'_panel.tpl');

				return array(
					DIR_MODULES.$filename.DS.'templates'.DS,
					DIR_MODULES.$filename.DS.'panel'.DS.$filename.'.php',
					$filename.'_panel.tpl'
				);

				//unset($_panel);
			}
			elseif (file_exists(DIR_MODULES.$filename.DS.$filename.'.php'))
			{
				if (file_exists(DIR_MODULES.$filename.DS.'templates'.DS.$filename.'.tpl'))
				{
					//$_panel = new Panel($_route, DIR_MODULES.$filename.DS.'templates'.DS);


					//include DIR_MODULES.$filename.DS.$filename.'.php';
					//$_panel->template($filename.'.tpl');
					//unset($_panel);

					return array(
						DIR_MODULES.$filename.DS.'templates'.DS,
						DIR_MODULES.$filename.DS.$filename.'.php',
						$filename.'.tpl'
					);
				}
				else
				{
					//include DIR_MODULES.$filename.DS.$filename.'.php';
					return array(
						NULL,
						DIR_MODULES.$filename.DS.$filename.'.php',
						NULL
					);
				}
			}
			elseif (preg_match('/\//', $filename))
			{
				$path = explode('/', $filename);

				if (file_exists(DIR_MODULES.$path[0].DS.'templates'.DS.$path[1].'.tpl'))
				{
					//$_panel = new Panel($_route, DIR_MODULES.$path[0].DS.'templates'.DS);
					//include DIR_MODULES.$path[0].DS.$path[1].DS.$path[1].'.php';
					//$_panel->template($path[1].'.tpl');
					//unset($_panel);

					return array(
						DIR_MODULES.$path[0].DS.'templates'.DS,
						DIR_MODULES.$path[0].DS.$path[1].DS.$path[1].'.php',
						$path[1].'.tpl'
					);
				}
				else
				{
					//include DIR_MODULES.$path[0].DS.$path[1].DS.$path[1].'.php';

					return array(
						NULL,
						DIR_MODULES.$path[0].DS.$path[1].DS.$path[1].'.php',
						NULL
					);
				}

			}
		}
		else
		{
			if ($content !== NULL)
			{
				//eval($_pnl->closePHPSet($content, TRUE));

				return array();
			}
		}
	}

}