<?php

class Image
{
	// Ścieżka do obrazka wraz z nazwą i rozszerzeniem
	protected $file;

	// Rozszerzenie pliku źródłowego
	protected $ext;

	// Identyfikator zasobu obrazu
	protected $image;

	// Wysokość obrazu źródłowego
	protected $height;

	// Długość obrazu źródłowego
	protected $width;

	// Katalog do zapisu nowych obrazów
	protected $destination_dir;

	// Ustawienie odpowiadające za niszczenie identyfikatora zasobu po wykonaniu zadania
	protected $destroy_source;

	public function __construct($file, $destination_dir = '', $destroy_source = FALSE)
	{
		if (is_file($file))
		{
			$this->file = $file;
			$this->setExtension();
			if ($this->isAllowedExtension())
			{
				$this->destination_dir = $destination_dir;
				$this->destroy_source = $destroy_source;
				$this->setImage();
				$this->setOrginalSize();
			}
			else
			{
				throw new Exception('Błąd klasy Image: Rozszerzenie pliku jest nieprawidłowe.');
			}
		}
		else
		{
			throw new Exception('Błąd klasy Image: podana ścieżka nie prowadzi do pliku.');
		}
	}

	// Metoda statyczna: nie używać w kontekście konstruktora, ale jako samodzielną metodę
	public static function sendFile($file, $dest, $prefix = '', $check_exists = TRUE)
	{
		// Zwrócenie pustej wartości w bazie jeśli nie wybrano obrazka do uploadu
		if ($file == NULL) return TRUE;
		
		if (!file_exists($dest))
		{
			throw new systemException('Żądany katalog uploadu pliku nie istnieje');
		}

		if (is_uploaded_file($file['tmp_name']))
		{
			// Zamiana znaków na małe w nazwie
			$file['name'] = strtolower($file['name']);

			$ext = substr(strstr($file['name'], '.'), 1);
			$name = substr($file['name'], 0, strpos('.', $file['name']));

			if (in_array($ext, $extensions_allowed = array('gif', 'jpg', 'jpeg', 'png')))
			{
				// Nowa nazwa pliku
				$new_name = $prefix.str_replace(' ', '-', $name).'['.time().'].'.$ext;
				// Nowa nazwa pliku wraz ze ścieżką
				$new_path_name = $dest.$new_name;

				if (! file_exists($new_path_name) || ! $check_exists)
				{
					if (move_uploaded_file($file['tmp_name'], $new_path_name))
					{
						return $new_name;
					}

					@unlink($file['tmp_name']);
					return FALSE;
				}
				else
				{
					throw new userException('Plik o podanej nazwie już istnieje.');
				}
			}
			else
			{
				throw new userException('You must upload file with one of the following extensions: :extensions', array(':extensions' => implode(', ', $extensions_allowed)));
			}
		}
	}
	
	public static function delFile($file, $exception = TRUE)
	{
		
		if (file_exists($file))
		{
			unlink($file);
			if ( ! $exception) 
			{
				return TRUE;
			}
		}
		else
		{
			if ($exception)
			{
				throw new userException('Plik o podanej nazwie('.$file.') nie istnieje.');
			}
			
			return FALSE;
		}
	}

	// Ustawia identyfikator zasobu obrazu
	protected function setImage()
	{
		if ($this->ext === 'png')
		{
			$this->image = imagecreatefrompng($this->file);
		}
		elseif ($this->ext === 'jpg' || $this->ext === 'jpeg')
		{
			$this->image = imagecreatefromjpeg($this->file);
		}
		elseif ($this->ext === 'gif')
		{
			$this->image = imagecreatefromgif($this->file);
		}
	}

	protected function setOrginalSize()
	{
		$this->height = imagesy($this->image);
		$this->width = imagesx($this->image);
	}

	// Wyodrębnia rozszerzenie pliku graficznego
	protected function setExtension()
	{
		$this->ext = strtolower(str_replace('.', '', strrchr($this->file, '.')));
	}

	protected function isAllowedExtension()
	{
		return in_array($this->ext, array('gif', 'jpg', 'jpeg', 'png'));
	}

	protected function createHeightRatio($destination_height)
	{
		return $this->height/$destination_height;
	}

	protected function createWidthRatio($destination_width)
	{
		return $this->width/$destination_width;
	}

	public function resize($destination_height, $destination_width, $new_name)
	{
		$height_ratio = $this->createHeightRatio($destination_height);
		$width_ratio = $this->createWidthRatio($destination_width);

		$new_height = round($this->height/$height_ratio);
		$new_width = round($this->width/$width_ratio);

		$new_image = imagecreatetruecolor($new_width, $new_height);

		$this->setAlpha($new_image);

		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->width, $this->height);

		$this->saveNewImage($new_image, $new_name);

		imagedestroy($new_image);
		if ($this->destroy_source)
		{
			imagedestroy($this->image);
		}
	}

	protected function setAlpha(&$new_image)
	{
		if ($this->ext === 'gif' || $this->ext === 'png')
		{
			$alpha = imagecolortransparent($this->image);
			$colors = array(
				'green' => 255,
				'blue' => 255,
				'red' => 255
			);

			if ($alpha >= 0)
			{
				$colors = imagecolorsforindex($this->image, $alpha);
			}

			$alpha = imagecolorallocate($new_image, $colors['red'], $colors['green'], $colors['blue']);
			imagefill($new_image, 0, 0, $alpha);
			imagecolortransparent($new_image, $alpha);

		}
		/*elseif ($this->ext === 'png')
		{
			imagealphablending($new_image, false);
			$color = imagecolorallocatealpha($new_image, 0,0, 0, 127);
			imagefill($new_image, 0, 0, $color);
			imagesavealpha($new_image, true);
		}*/
	}

	protected function saveNewImage($image_resource, $name)
	{
		$name = $this->destination_dir.$name.'.'.$this->ext;

		if ($this->ext === 'png')
		{
			imagepng($image_resource, $name);
		}
		elseif ($this->ext === 'jpg' || $this->ext === 'jpeg')
		{
			imagejpeg($image_resource, $name);
		}
		elseif ($this->ext === 'gif')
		{
			imagegif($image_resource, $name);
		}
	}
}