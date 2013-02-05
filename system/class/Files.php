<?php
/***********************************************************
| eXtreme-Fusion 5.0 Beta 5
| Content Management System       
|
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 
| http://extreme-fusion.org/                               		 
|
| This product is licensed under the BSD License.				 
| http://extreme-fusion.org/ef5/license/						 
***********************************************************/
class Files
{
	protected $_omit_ext = array();
	
	protected $_size_title = array();
	
	protected $_size = NULL;

	public function __construct()
	{
		$this->_omit_ext = array('..', '.', '.svn', '.gitignore');
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
	 * @return  boolean  	TRUE/FALSE
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
	 * @param   bool    	Opcjonalnie persowanie przez metodę getFileSize
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
	 * Usuwa katalog oraz całą jego zawartość.
	 *
	 *     Usuwa zawartość katalogu oraz jego podkatalogów.
	 *     $_files->rmDirRecursive('c:path/to/dir/');
	 *     $_files->rmDirRecursive(array('c:path/to/dir/', 'c:path/to/dir2/'));
	 *
	 *     Usuwa puste katalogi jeśli parametr 2 jest oznaczone na TRUE.
	 *     $_files->rmDirRecursive('c:path/to/dir/', TRUE);
	 *     $_files->rmDirRecursive(array('c:path/to/dir/', 'c:path/to/dir2/'), TRUE);
	 *
	 *     
	 * @param   string/array    	Ścieżka/i do katalogu
	 * @param   bool    			Opcjonalnie usuwanie pustych katalogów
	 * @return  boolean  			TRUE/FALSE
	 */
	public function rmDirRecursive($_dir, $rmdir = FALSE)
	{
		if (is_string($_dir))
		{
			$_dir = array($_dir);
		}
		elseif( ! is_array($_dir))
		{
			return FALSE;
		}
		
		foreach($_dir as $dir)
		{
			if (substr($dir, -1) === DS)
			{
				$dir = substr($dir, 0, -1);
			}

			if( ! file_exists($dir) || ! is_dir($dir))
			{
				return FALSE;
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
						}
						else
						{
							if ( ! @unlink($file->getPathname()))
							{
								return FALSE;
							}
						}
					}
				}
				
				if($rmdir && ! @rmdir($dir))
				{
					return FALSE;
				}
			}
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Tworzy katalog w wskazanej lokalizacji.
	 *
	 *     $_files->mkDirRecursive('dir'); // pojedynczy folder
	 *     $_files->mkDirRecursive(array('dir', 'subdir1', 'subdir2')); // tworzy katolog w katalogu
	 *	   $_files->mkDirRecursive(array('dir', 'subdir1', 'subdir2') DIR_SITE, 0777); // Określa domyślną ścieżkę główną, oraz jakie prawa nadaje katalogom
	 *     
	 * @param   string/array    	Ścieżka do katalogu
	 * @param   bool    			Opcjonalnie usuwanie pustych katalogów
	 * @return  boolean  			TRUE/FALSE
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
	 *     
	 * @param   string    	Ścieżka do katalogu
	 * @param   array    	Filtr np.: array('..', '.', '.svn', '.gitignore')
	 * @param   bool    	Sortowanie wyników
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