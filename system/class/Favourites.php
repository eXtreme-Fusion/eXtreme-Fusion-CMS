<?php
interface Favourites_Intf
{
	public function extend(array $config);
	public function get($user_id, $shared = TRUE);
	public function setFavByLink($link, $user_id);
	public function setFavByID($fav_id, $user_id);
}

class Favourites implements Favourites_Intf
{
	protected $_pdo;
	protected $_config;
	protected $_shared;
	protected $_config_checked;
	
	public function __construct($pdo)
	{
		$this->_pdo = $pdo;
		$this->_config = array(
			'fav_table' => '',
			'data_table' => '',
			'limit' => 18,
		);
	}
	
	public function extend(array $config)
	{
		$this->_config = array_merge($this->_config, $config);
	}
	
	public function get($user_id, $shared = TRUE)
	{
		$this->_checkConfig();
		
		if ($shared && isset($this->_shared['get']))
		{
			return $this->_shared['get'];
		}

		$user_id = (int) $user_id;
		
		// Pobieranie ulubionych podstron zalogowanego admina
		$fav = $this->_pdo->getData('SELECT a.`image`, a.`title`, a.`link`, a.`page` FROM ['.$this->_config('fav_table').'] f LEFT JOIN ['.$this->_config('data_table').'] a ON f.`page_id` = a.`id` WHERE f.`user_id` = :id ORDER BY f.`count` DESC LIMIT 0,'.$this->_config('limit').'', array(':id', $user_id, PDO::PARAM_INT));

		if ($shared)
		{
			return $this->_shared['get'] = $fav;
		}
		
		return $fav;
	}
	
	public function setFavByLink($link, $user_id)
	{
		$fav_id = (int) $this->_pdo->getField('SELECT `id` FROM ['.$this->_config('data_table').'] WHERE `link` = :link', array(':link', $link, PDO::PARAM_STR));
		
		return $this->_setFav($fav_id, $user_id);
	}
	
	public function setFavByID($fav_id, $user_id)
	{
		return $this->_setFav($fav_id, $user_id);
	}
	
	protected function _setFav($fav_id, $user_id)
	{	
		// Konwertowanie danych do liczb całkowitych
		$fav_id = (int) $fav_id; 
		$user_id = (int) $user_id;
		
		// Sprawdzanie, czy użytkownik jest na innej niż poprzednio podstronie
		if (! $this->_hasPermToUpdate($fav_id, $user_id))
		{
			return 1;
		}
		
		if ($fav_id && $user_id)
		{
			$item_id = (int) $this->_pdo->getField('SELECT `id` FROM ['.$this->_config('fav_table').'] WHERE `page_id` = '.$fav_id.' AND `user_id` = '.$user_id);
			if ($item_id)
			{
				$this->_pdo->exec('UPDATE ['.$this->_config('fav_table').'] SET `count` = `count` + 1, `time` = '.time().' WHERE `id` = '.$item_id.' AND `user_id` = '.$user_id);
				$this->_pdo->exec('UPDATE ['.$this->_config('fav_table').'] SET `count` = `count` - 1 WHERE `id` != '.$item_id.' AND `user_id` = '.$user_id);
			}
			else
			{
				$this->_pdo->exec('INSERT INTO ['.$this->_config('fav_table').'] (`page_id`, `user_id`, `count`, `time`) VALUES ('.$fav_id.', '.$user_id.', 1, '.time().')');
			}
		}
		
		return TRUE;
	}
	
	protected function _hasPermToUpdate($fav_id, $user_id)
	{
		$latest = (int) $this->_pdo->getField('SELECT `page_id` FROM ['.$this->_config('fav_table').'] WHERE `user_id` = '.$user_id.' ORDER BY `time` DESC LIMIT 0,1');
		
		return $latest !== $fav_id;
	}
	
	protected function _checkConfig()
	{
		if (! $this->_config_checked)
		{
			$this->_config_checked = TRUE;
			
			if (! strlen($this->_config('fav_table')) || ! strlen($this->_config('data_table')) || ! is_numeric($this->_config('limit')))
			{
				throw new systemException('Invalid config data.');
			}
		}
	}
	
	protected function _config($key)
	{
		if (isset($this->_config[$key]))
		{
			return $this->_config[$key];
		}
		
		throw new systemException('Array key does not exists.');
	}
}