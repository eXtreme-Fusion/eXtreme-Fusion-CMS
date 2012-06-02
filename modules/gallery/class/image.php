<?php defined('EF5_SYSTEM') || exit;
/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 eXtreme-Fusion Crew                	 |
| http://extreme-fusion.org/                               		 |
+----------------------------------------------------------------+
| This product is licensed under the BSD License.				 |
| http://extreme-fusion.org/ef5/license/						 |
+---------------------------------------------------------------*/
class Image {

	// Przechowuje obiekt ustawień galerii
	protected
		$_sett;

	/**
	 * Ładuje obiekt ustawień galerii, po czym wczytuje ustawienia do tablicy.
	 *
	 * @param   Sett  ustawienie galerii
	 * @return  void
	 */
	public function __construct($sett)
	{
		$this->_sett  = $sett;
		// Przeczowuje dozwolone rozszerzenie plików graficznych
		$this->_image_ext = array(
			'.jpg',
			'.png',
			'.gif'
		);
		// Ładuje domyśny znak wodny
		$this->setWatermark();
		// Ładuje domyśną ścieżkę załączanych plików graficznych
		$this->setInputPhoto();
		// Ładuje domyśną ścieżkę zapisu plików graficznych z znakiem wodnym
		$this->setOutputPhoto();
		  
	}

	/**
	 * Tworzy miniaturki plików graficznych
	 *
	 * @param   int  	identyfikator typu pliko graficznego
	 * @param   string  originalna nazwa pliku graficznego
	 * @param   string  nowa nazwa pliku graficznego
	 * @param   int  	szerokość miniaturki pliku graficznego
	 * @param   int  	wysokość miniaturki pliku graficznego
	 * @return  void
	 * @uses    Sett
	 */
	public function createThumbnail($origfile, $thumbfile, $new_w, $new_h)
	{
		if ($this->getPhotoExt(strtolower($origfile)) === '.gif') 
		{ 
			$origimage = imagecreatefromgif($origfile); 
		}
		elseif ($this->getPhotoExt(strtolower($origfile)) === '.jpg') 
		{ 
			$origimage = imagecreatefromjpeg($origfile); 
		}
		elseif ($this->getPhotoExt(strtolower($origfile)) === '.png') 
		{ 
			$origimage = imagecreatefrompng($origfile); 
		}

		$old_x = imagesx($origimage);
		$old_y = imagesy($origimage);

		$ratio_x = $old_x / $new_w;
		$ratio_y = $old_y / $new_h;
		if ($ratio_x > $ratio_y) 
		{
			$thumb_w = round($old_x / $ratio_x);
			$thumb_h = round($old_y / $ratio_x);
		} 
		else
		{
			$thumb_w = round($old_x / $ratio_y);
			$thumb_h = round($old_y / $ratio_y);
		};

		if ($this->_sett->get('thumb_compression') === 'gd1') 
		{
			$thumbimage = imagecreate($thumb_w, $thumb_h);
			$result = imagecopyresized($thumbimage, $origimage, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
		} 
		else
		{
			$thumbimage = imagecreatetruecolor($thumb_w, $thumb_h);
			if ($this->getPhotoExt(strtolower($origfile)) === '.png') 
			{
				imagealphablending($thumbimage, FALSE);
				imagesavealpha($thumbimage, TRUE);
			}
			$result = imagecopyresampled($thumbimage, $origimage, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y);
		}

		touch($thumbfile);

		if ($this->getPhotoExt(strtolower($origfile)) === '.gif')  
		{ 
			imagegif($thumbimage, $thumbfile); 
		}
		elseif ($this->getPhotoExt(strtolower($origfile)) === '.jpg')  
		{ 
			imagejpeg($thumbimage, $thumbfile); 
		}
		elseif ($this->getPhotoExt(strtolower($origfile)) === '.png')  
		{
			imagepng($thumbimage, $thumbfile); 
		}

		imagedestroy($origimage);
		imagedestroy($thumbimage);
	}

	/**
	 * Tworzy miniaturki plików graficznych
	 *
	 * @param   int  	identyfikator typu pliko graficznego
	 * @param   string  originalna nazwa pliku graficznego
	 * @param   string  nowa nazwa pliku graficznego
	 * @param   int  	nowa waga pliku graficznego
	 * @return  void
	 * @uses    Sett
	 */
	public function createSquareThumbnail($origfile, $thumbfile, $new_size) 
	{
		if ($new_size > $this->_sett->get('thumbnail_width'))
		{
			throw new systemException(__('Error: The image size (:new_size px) is greater than the set limit (:sett_thumbnail_width px).', array(':new_size' => $new_size, ':sett_thumbnail_width' => $this->_sett->get('thumbnail_width'))));
		}
	
		if ($this->getPhotoExt(strtolower($origfile)) === '.gif') 
		{ 
			$origimage = imagecreatefromgif($origfile); 
		}
		elseif ($this->getPhotoExt(strtolower($origfile)) === '.jpg') 
		{ 
			$origimage = imagecreatefromjpeg($origfile); 
		}
		elseif ($this->getPhotoExt(strtolower($origfile)) === '.png') 
		{ 
			$origimage = imagecreatefrompng($origfile); 
		}

		$old_x = imagesx($origimage);
		$old_y = imagesy($origimage);

		$x = 0; $y = 0;

		if ($old_x > $old_y) 
		{
			$x = ceil(($old_x - $old_y) / 2);
			$old_x = $old_y;
		} 
		elseif ($old_y > $old_x) 
		{
			$y = ceil(($old_y - $old_x) / 2);
			$old_y = $old_x;
		}
		$new_image = imagecreatetruecolor($new_size, $new_size);
		if ($this->getPhotoExt(strtolower($origfile)) === '.png' && $this->_sett->get('thumb_compression') !== 'gd1') 
		{
			imagealphablending($new_image, FALSE);
			imagesavealpha($new_image, TRUE);
		}
		imagecopyresampled($new_image, $origimage,0,0, $x, $y, $new_size, $new_size, $old_x, $old_y);

		if ($this->getPhotoExt(strtolower($origfile)) === '.gif') 
		{ 
			imagegif($new_image, $thumbfile,100); 
		}
		elseif ($this->getPhotoExt(strtolower($origfile)) === '.jpg') 
		{ 
			imagejpeg($new_image, $thumbfile,100); 
		}
		elseif ($this->getPhotoExt(strtolower($origfile)) === '.png')  
		{ 
			imagepng($new_image, $thumbfile,5); 
		}

		imagedestroy($origimage);
		imagedestroy($new_image);
	}
	
	/**
	 * Sprawdza czy plik graficzny istnieje w podanej lokalizacji
	 *
	 * @param   string  ścieżka bezwzględna do pliku
	 * @param   string  nazwa pliku wraz z rozszerzeniem
	 * @return  bool
	 */
	private function imageExists($dir, $image) 
	{
		return file_exists($dir.$image);
	}
	
	/**
	 * Sprawdza czy plik graficzny istnieje w podanej lokalizacji
	 *
	 * @param   string  ścieżka bezwzględna do pliku wraz z jego nazwą
	 * @param   bool  	TRUE/FALSE dla wyświetlania wyjątku
	 * @return  bool
	 */
	public function validWidthAndHight($image_src, $exc = FALSE) 
	{
		if ( ! file_exists($image_src))
		{
			throw new systemException(__('Error: The file (:image) has not been found.', array(':image' => $image_src)));
		}
		
		if ($this->getPhotoExt(strtolower($image_src)) === '.gif') 
		{ 
			$origimage = imagecreatefromgif($image_src); 
		}
		elseif ($this->getPhotoExt(strtolower($image_src)) === '.jpg') 
		{ 
			$origimage = imagecreatefromjpeg($image_src); 
		}
		elseif ($this->getPhotoExt(strtolower($image_src)) === '.png') 
		{ 
			$origimage = imagecreatefrompng($image_src); 
		} 
		else
		{
			if ($exc === TRUE)
			{
				throw new systemException(__('Error: Incorrect file extension: (:image).', array(':image' => $this->getPhotoExt(strtolower($image_src)))));
			}
			
			return FALSE;
		}

		$x = imagesx($origimage);
		$y = imagesy($origimage);
		
		imagedestroy($origimage);
		
		if ($exc === TRUE)
		{
			if ($x > $this->_sett->get('photo_max_width') || $y > $this->_sett->get('photo_max_hight'))
			{
				unlink($image_src);
				throw new systemException(__('Error: The file dimensions (:x/:y px) are greater than the limit set in settings (:photo_max_width/:photo_max_hight px).', array(':x' => $x, ':y' => $y, ':photo_max_width' => $photo_max_width, 'photo_max_hight' => $photo_max_hight)));
			}

			return TRUE;
		}
		
		if ($x > $this->_sett->get('photo_max_width') || $y > $this->_sett->get('photo_max_hight'))
		{
			unlink($image_src);
			return FALSE;
		}

		return TRUE;
	}
		
	/**
	 * Pobiera wysokość i szerokość pliku
	 *
	 * @param   string  ścieżka bezwzględna do pliku wraz z jego nazwą
	 * @param   bool  	TRUE/FALSE dla wyświetlania wyjątku
	 * @return  bool
	 */
	public function getWidthAndHight($image_src, $exc = FALSE) 
	{
		if ( ! file_exists($image_src))
		{
			throw new systemException(__('Error: The file (:image) has not been found.', array(':image' => $image_src)));
		}
		
		if ($this->getPhotoExt(strtolower($image_src)) === '.gif') 
		{ 
			$origimage = imagecreatefromgif($image_src); 
		}
		elseif ($this->getPhotoExt(strtolower($image_src)) === '.jpg') 
		{ 
			$origimage = imagecreatefromjpeg($image_src); 
		}
		elseif ($this->getPhotoExt(strtolower($image_src)) === '.png') 
		{ 
			$origimage = imagecreatefrompng($image_src); 
		} 
		else
		{
			if ($exc === TRUE)
			{
				throw new systemException(__('Error: Incorrect file extension: (:file_ext).', array(':file_ext' => $this->getPhotoExt(strtolower($image_src)))));
			}
			
			return FALSE;
		}

		$x = imagesx($origimage);
		$y = imagesy($origimage);
		
		imagedestroy($origimage);

		return array(
			'x' => $x,
			'y' => $y
		);
	}
	
	/**
	 * Tworzy miniaturki plików graficznych
	 *
	 * @param   array  	tablica z dostępnymi rozszerzeniemi plików
	 * @return  bool
	 */
	public function newImageExt(array $ext = array())
	{
		if (is_array($ext))
		{
			$new_ext = array();
			if (isset($this->_image_ext))
			{
				foreach($this->_image_ext as $var)
				{
					$new_ext[] = $var;
				}
			}
			
			foreach($ext as $foo)
			{
				$new_ext[] = $foo;
			}
			$this->_image_ext = $new_ext;
			
			return TRUE;
		}
		
		return FALSE;
	}	
	
	/**
	* Sprawdzanie rozszerzenia plików graficznych
	*
	* @param   string  nazwa pliku graficznego wraz z rozszerzeniem
	* @return  mixed
	*/
	public function validExt($var)
	{
		if ( ! in_array(strrchr($var, '.'), $this->_image_ext))
		{
			throw new systemException(__('Error: The file extension :var is not consisten with avaible extensions list.', array(':var' => $var)));
		}
		
		return TRUE;
	}
	
	/**
	* Filtrownie nazwy plików graficznych z nie obsługiwanych znaków specjalnych
	*
	* @param   string  nazwa pliku graficznego bez rozszerzenia
	* @return  mixed
	*/
	public function setPhotoName($var, $tmp = FALSE)
	{
		$var = $this->setString($var);
		
		if ($var === '') 
		{
			$var = time(); 
		}
		
		if($tmp === FALSE)
		{
			return $this->_new_photo_name = $var;
		}
		else
		{
			return $var;
		}
	}
	/**
	* Filtrownie stringów z nie obsługiwanych znaków specjalnych
	*
	* @param   string  nazwa pliku graficznego bez rozszerzenia
	* @return  mixed
	*/
	public function setString($string)
	{
		$string = strtolower(str_replace(
			array('ą','ś','ę','ó','ł','ż','ź','ć','ń','ü'), 
			array('a','s','e','o','l','z','z','c','n','u'), 
			$string
		));
		$string = str_replace(' ', '_', $string);
		$string = preg_replace('/[^a-zA-Z0-9_-]/', '', $string);
		$string = preg_replace('/^\W/', '', $string);
		$string = preg_replace('/([_-])\1+/', '$1', $string);
		
		return $string;
	}
	
	/**
	* Pobranie przefiltrowanej nazwy pliku graficznego
	*
	* @return  mixed
	*/
	public function getPhotoName()
	{
		return $this->_new_photo_name;
	}
	
	/**
	* Pobranie przefiltrowanego rozszerzenia do nazwy pliku graficznego
	*
	* @return  mixed
	*/
	public function getPhotoExt($var = NULL)
	{
		if ($var === NULL)
		{
			return strrchr($this->_input_photo['name'], '.');
		}
		else
		{
			return strrchr($var, '.');
		}
	}
	
	/**
	* Pobranie przefiltrowanego rozszerzenia do nazwy pliku graficznego
	*
	* @return  mixed
	*/
	public function getPhotoNameWithExtension($var = NULL)
	{
		if ($var === NULL)
		{
			return substr($this->_input_photo['name'], 0, strrpos($this->_input_photo['name'], '.'));
		}
		else
		{
			return substr($var, 0, strrpos($var, '.'));
		}
	}
	
	/**
	* Ustawienie ładowanego pliku graficznego który będzie użyty do stworzenia znaku wodnego.
	*
	* @param   string 	nazwa pliku wraz z rozszerzeniem
	* @param   string	ścieżka bezwzględna do pliku graficznego
	* @return  bool
	*/
	public function setWatermark($name = 'extreme-fusion-logo-light.png', $dir = DIR_IMAGES)
	{
		$this->_watermark = array(
			'name' => $name,
			'dir' => $dir,
			'path' => $dir.$name
		);
	}	
	
	/**
	* Alias funkcji mkdir, chmod. Tworzy katalog nadając mu podane uprawnienia.
	*
	* @param   string 	ścieżka bezwzględna do ostatniego katalogu
	* @param   string	nazwa katalogu do utworzenia
	* @param   int		CHMOD dla tworzonego katalogu
	* @return  bool
	*/
	public function createDir($path, $name, $chmod = 0644)
	{
		if ( ! file_exists($path))
		{
			throw new systemException(__('Error: Directory :path does not exist.', array(':path' => $path)));
		}
		
		if ( ! file_exists($path.$name.DS))
		{		
			if (mkdir($path.$name.DS))
			{
				if ( ! chmod($path.$name.DS, 0777))
				{
					throw new systemException(__('Error: Permissions for directory :path can not be set.', array(':path' => $path)));
				}
			}
			else
			{
				throw new systemException(__('Error: Directory :path can not be created.', array(':path' => $path)));
			}
		}
		
		return TRUE;
	}	
	
	/**
	* Alias funkcji mkdir, chmod. Tworzy katalog nadając mu podane uprawnienia.
	*
	* @param   string 	ścieżka bezwzględna do ostatniego katalogu
	* @param   string	nazwa katalogu do utworzenia
	* @param   int		CHMOD dla tworzonego katalogu
	* @return  bool
	*/
	
	public function removePhotos($path, $name)
	{
		if ( ! file_exists($path))
		{
			throw new systemException(__('Error: Directory :path does not exist.', array(':path' => $path)));
		}
		
		if (file_exists($path.'original'.DS.$name))
		{		
			if ( ! unlink($path.'original'.DS.$name))
			{
				throw new systemException(__('Error: File :path has not been deleted.', array(':path' => $path.'original'.DS.$name)));
			}
		}
		
		if (file_exists($path.'thumbnail'.DS.'_thumbnail_'.$name))
		{		
			if ( ! unlink($path.'thumbnail'.DS.'_thumbnail_'.$name))
			{
				throw new systemException(__('Error: File :path has not been deleted.', array(':path' => $path.'thumbnail'.DS.'_thumbnail_'.$name)));
			}
		}
		
		if (file_exists($path.'watermark'.DS.$name))
		{		
			if ( ! unlink($path.'watermark'.DS.$name))
			{
				throw new systemException(__('Error: File :path has not been deleted.', array(':path' => $path.'watermark'.DS.$name)));
			}
		}
		
		return TRUE;
	}	
	
	/**
	* Ustawienie ładowanego pliku graficznego który będzie użyty jako plik źródłowy.
	*
	* @param   string 	nazwa pliku wraz z rozszerzeniem
	* @param   string	ścieżka bezwzględna do pliku graficznego
	* @return  mixed
	*/
	public function setInputPhoto($name = NULL, $dir = NULL)
	{
		if ($name !== NULL)
		{
			$this->validExt($name);
		}
		
		$this->_input_photo = array(
			'name' => $name,
			'dir' => $dir,
			'path' => $dir.$name
		);
		
		$this->_new_photo_name = $this->setPhotoName(substr($this->_input_photo['name'], 0, strrpos($this->_input_photo['name'], '.')));
	}	
	
	/**
	* Ustawienie ładowanego pliku graficznego który będzie użyty jako plik wyjściowy.
	*
	* @param   string 	nazwa pliku wraz z rozszerzeniem
	* @param   string	ścieżka bezwzględna do pliku graficznego
	* @return  mixed
	*/
	public function setOutputPhoto($name = NULL, $dir = NULL)
	{
		if ($name !== NULL)
		{
			$this->validExt($name);
		}
		$this->_output_photo = array(
			'name' => $name === NULL ? $this->getPhotoName().$this->getPhotoExt() : $name,
			'dir' => $dir === NULL ? DIR_IMAGES : $dir,
			'path' => $dir.($name === NULL ? $this->getPhotoName().$this->getPhotoExt() : $name)
		);
		
		$this->_new_photo_name = $this->setPhotoName(substr($this->_output_photo['name'], 0, strrpos($this->_output_photo['name'], '.')));
	}
	
	/**
	* Tworzenie znaku wodnego.
	*
	* @return  mixed
	*/
	public function createWatermark()
	{
		if ( ! $this->imageExists($this->_input_photo['dir'], $this->_input_photo['name']))
		{
			throw new systemException(__('Error: Image :name does not exist.', array(':name' => $this->_input_photo['name'])));
		}
		
		if ( ! $this->imageExists($this->_watermark['dir'], $this->_watermark['name']))
		{
			throw new systemException(__('Error: Watermark :name does not exsist.', array(':name' => $this->_watermark['name'])));
		}

		if ($this->_output_photo['name'] === NULL && $this->_output_photo['dir'] === NULL)
		{
			$photo_new_dir = $this->_input_photo['dir'];
			
			if ($this->imageExists($this->_input_photo['dir'], $this->_input_photo['name']))
			{
				throw new systemException(__('Error: Output image in directory :name is already existing.', array(':name' => $this->_input_photo['name'])));
			}
		}
		else
		{
			$photo_new_dir = $this->_output_photo['dir'];
			
			if ($this->imageExists($this->_output_photo['dir'], $this->_output_photo['name']))
			{
				throw new systemException(__('Error: Output image in directory :name is already existing.', array(':name' => $this->_output_photo['name'])));
			}
		}
		
		$watermark = $this->_watermark['dir'].$this->_watermark['name'];		
		$photo = imagecreatefromjpeg($this->_input_photo['path']);
		$watermark = imagecreatefrompng($watermark);
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$size = getimagesize($this->_input_photo['path']);
		$dest_x = ($size[0] - $watermark_width);
		$dest_y = ($size[1] - $watermark_height);
		imagecopyresampled($photo, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $watermark_width, $watermark_height);
		imagejpeg($photo, $photo_new_dir.$this->_new_photo_name.$this->getPhotoExt());
		imagedestroy($photo);
		imagedestroy($watermark);
	}
	
	/**
	* Pobranie skonwertowanego rozmiaru z danych podanych w bitach
	* @param int $bytes
	* @return string
	*/
	public function getConvertedSizeFromBytes($bytes) 
	{
		$b = (int)$bytes;
		$s = array('B', 'kB', 'MB', 'GB', 'TB');

		if($b <= 0)
		{
			return "0 ".$s[0];
		}
		
		$con = 1024;
		$e = (int)(log($b,$con));
		
		return number_format($b/pow($con,$e),2,'.',',').' '.$s[$e];
	}
	
	/**
	* Pobranie rozmiaru pliku z nazwy pliku wraz z ścieżką
	* @param string $path
	* @return string
	*/
	public function getConvertedSizeFile($path) 
	{
		if ( ! file_exists($path))
		{
			throw new systemException(__('Error: The file (:path) does not exist.', array(':path' => $path)));
		}
		$b = filesize($path);
		$s = array('B', 'kB', 'MB', 'GB', 'TB');

		if($b <= 0)
		{
			return "0 ".$s[0];
		}
		
		$con = 1024;
		$e = (int)(log($b,$con));
		
		return number_format($b/pow($con,$e),2,'.',',').' '.$s[$e];
	}
	
	/**
	* Sprawdzanie rozmiaru pliku czy jest zgodny z ustawieniami
	*
	* @param   int  rozmiar pliku w bajtach
	* @return  mixed
	*/
	public function validSize($var)
	{
		if ($var > $this->_sett->get('max_file_size'))
		{
			throw new systemException(__('Error: File size (:var) exceeds allowed settings limit (:max_file_size).', array(':var' => $this->getConvertedSizeFromBytes($var), ':max_file_size' => $this->getConvertedSizeFromBytes($this->_sett->get('max_file_size')))));
		}
		
		return TRUE;
	}
}