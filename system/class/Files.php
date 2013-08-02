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
class Files
{
	protected $_omit_ext = array();

	protected $_size_title = array();

	protected $_size = NULL;

	public function __construct()
	{
		$this->_omit_ext = array('..', '.', '.svn', '.gitignore', '.htaccess');
		$this->_size_title = array(__('B'), __('kB'), __('MB'), __('GB'), __('TB'));
		$this->_size = NULL;
	}

	/**
	 * Aktualizuje listę rozszerzeń które mają być pominięte.
	 *
	 *     Używanie:
	 *     $_files->setOmitExt(array('.foo', '.bar'));
	 *
	 * @param   array    	Tablica z rozszerzeniami plików
	 * @return  boolean
	 */
	public function setOmitExt(array $ext = array())
	{
		if ($ext)
		{
			$new_omit_ext = array();
			if ($this->_omit_ext)
			{
				foreach($this->_omit_ext as $var)
				{
					$new_omit_ext[] = $var;
				}
			}

			foreach($ext as $foo)
			{
				$new_omit_ext[] = $foo;
			}
			$this->_omit_ext = $new_omit_ext;

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Zwraca przetworzony rozmiar podany w bajtach.
	 *
	 *     Używanie:
	 *     $_files->getFileSize(123456);
	 *
	 * @param   int    		Rozmiar podany w bajtach
	 * @return  string  	Przetworzona wielkość B/kB/MB/GB/TB
	 */
	public function getFileSize($var)
	{
		$var = (int)$var;

		if($var <= 0)
		{
			return '0 '.$this->_size_title[0];
		}

		$con = 1024;
		$res = (int)(log($var, $con));

		return number_format($var/pow($con,$res),2,'.',',').' '.$this->_size_title[$res];
	}

	/**
	 * Zlicza rozmiar każdego pliku i folderu, również tych podrzędnych.
	 *
	 *     Zwróci wielkość podaną w bajtach, np.: 1234567.
	 *     $_files->getDirSize('c:path/to/dir/');
	 *
	 *     Zwróci wielkość podaną w jednostkach przeliczonych przez metodę $this->getFileSize(1234567), np.: 1.18 MB.
	 *     $_files->getDirSize('c:path/to/dir/', TRUE);
	 *
	 *
	 * @param   string    	Ścieżka do katalogu
	 * @param   boolean    	Opcjonalnie persowanie przez metodę getFileSize
	 * @return  int/string  W zależności czy w/w opcja włączona
	 */
	public function getDirSize($var, $titles = FALSE)
	{
		$this->_size = 0;
		foreach (new DirectoryIterator($var) as $file)
		{
			if ( ! in_array($file->getFilename(), $this->_omit_ext))
			{
				if (is_dir($file->getPathname()))
				{
					$this->_size = $this->_size + $this->getDirSize($file->getPathname().DS);
				}
				else
				{
					$this->_size = $this->_size + filesize($file->getPathname());
				}
			}
		}

		if ($titles)
		{
			return $this->getFileSize($this->_size);
		}

		return $this->_size;
	}

	/**
	 * Usuwa całą zawartość katalogu.
	 *
	 *     Usuwa pliki zawarte w katalogu oraz jego podkatalogach.
	 *     $_files->rmDirRecursive('c:path/to/dir/');
	 *	   Usuwa pliki zawarte w katalogach oraz ich podkatalogach.
	 *     $_files->rmDirRecursive(array('c:path/to/dir/', 'c:path/to/dir2/'));
	 *
	 *     Usuwa pliki z katalogu wraz z podkatalagami i ich zawartością.
	 *     $_files->rmDirRecursive('c:path/to/dir/', TRUE);
	 *	   Usuwa pliki z katalogów wraz z podkatalagami i ich zawartością.
	 *     $_files->rmDirRecursive(array('c:path/to/dir/', 'c:path/to/dir2/'), TRUE);

	 *     Usuwa pliki z katalogów wraz z podkatalagami i ich zawartością
	 *     oraz usuwa katalogi podane pierwszym parametrem.
	 *     $_files->rmDirRecursive(array('c:path/to/dir/', 'c:path/to/dir2/'), TRUE, TRUE);
	 *
	 *     Nie usunie katalogu, który zawiera plik o rozszerzeniu z tablicy $this->_omit_ext.
	 *
	 * @param   string/array    	Ścieżka/i do katalogu
	 * @param   boolean    			Opcjonalnie usuwanie pustych katalogów
	 * @param   boolean    			Opcjonalnie usuwanie katalogów podanych parametrem $_dir
	 * @return  boolean
	 */
	public function rmDirRecursive($_dir, $rm_dir = FALSE, $rm_main_dir = FALSE)
	{
		$error = FALSE;

		$_dir = (array) $_dir;
		foreach($_dir as $dir)
		{
			if (substr($dir, -1) === DS)
			{
				$dir = substr($dir, 0, -1);
			}

			if( ! file_exists($dir) || ! is_dir($dir))
			{
				$error = TRUE;
			}
			elseif (is_readable($dir))
			{
				foreach (new DirectoryIterator($dir) as $file)
				{
					if ( ! in_array($file->getFilename(), $this->_omit_ext))
					{
						if (is_dir($file->getPathname()))
						{
							$this->rmDirRecursive($file->getPathname().DS);
							if ($rm_dir)
							{
								if (! @rmdir($file->getPathname().DS))
								{
									$error = TRUE;
								}
							}
						}
						else
						{
							if (!  @unlink($file->getPathname()))
							{
								$error = TRUE;
							}
						}
					}
				}

				if ($rm_main_dir)
				{
					if (! @rmdir($dir))
					{
						$error = TRUE;
					}
				}
			}
		}

		return ! $error;
	}

	/**
	 * Tworzy katalog w wskazanej lokalizacji.
	 *
	 *     $_files->mkDirRecursive('dir'); // pojedynczy folder
	 *     $_files->mkDirRecursive(array('dir', 'subdir1', 'subdir2')); // tworzy katolog w katalogu
	 *	   $_files->mkDirRecursive(array('dir', 'subdir1', 'subdir2') DIR_SITE, 0777); // Określa domyślną ścieżkę główną, oraz jakie prawa nadaje katalogom
	 *
	 * @param   string/array    	Ścieżka do katalogu
	 * @param   boolean    			Opcjonalnie usuwanie pustych katalogów
	 * @return  boolean
	 */
	function mkDirRecursive($_dir, $root = NULL, $rights = 0777)
	{
		if ($root === NULL)
		{
			$root = DIR_SITE;
		}

		if (is_string($_dir))
		{
			if(file_exists($root.$var) || is_dir($root.$var))
			{
				return FALSE;
			}

			$_dir = array($_dir);
		}
		elseif( ! is_array($_dir))
		{
			return FALSE;
		}

		$dir = '';
		foreach($_dir as $subdir)
		{
			$dir .= $subdir.DS;
			if ( ! is_dir($root.$dir) && strlen($dir) > 0)
			{
				if ( ! @mkdir($root.$dir, $rights))
				{
					return FALSE;
				}
			}
		}

		return TRUE;
	}

	/**
	 * Tworzy listę plików/folderów ze wskazanej scieżki.
	 *
	 * @param   string    	Ścieżka do katalogu
	 * @param   array    	Filtr np.: array('..', '.', '.svn', '.gitignore')
	 * @param   boolean    	Sortowanie wyników
	 * @param   string    	Typ: pliki/katalogi
	 * @return  array  		Lista plików/katalogów
	 */
	public function createFileList($folder, array $filter = array(), $sort = TRUE, $type = 'files')
	{
		$res = array(); $this->setOmitExt($filter);
		foreach (new DirectoryIterator($folder) as $file)
		{
			if ($type === 'files' && ! in_array($file->getFilename(), $this->_omit_ext))
			{
				if ( ! is_dir($file->getPathname()))
				{
					$res[] = $file->getFilename();
				}
			}
			elseif ($type === 'folders' && ! in_array($file->getFilename(), $this->_omit_ext))
			{
				if (is_dir($file->getPathname()))
				{
					$res[] = $file->getFilename();
				}
			}
		}

		if ($sort)
		{
			sort($res);
		}

		return $res;
	}
}