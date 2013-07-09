<?php

class Favourites extends Favourites_Abstract implements Favourites_Intf
{
	public function get($user_id, $shared = TRUE)
	{
		$this->_checkConfig();

		if ($shared && isset($this->_shared['get']))
		{
			return $this->_shared['get'];
		}

		$user_id = (int) $user_id;

		// Pobieranie ulubionych podstron zalogowanego admina
		$fav = $this->_pdo->getData('SELECT a.`image`, a.`title`, a.`link`, a.`page`, a.`permissions`, f.`id` FROM ['.$this->_config('fav_table').'] f LEFT JOIN ['.$this->_config('data_table').'] a ON f.`page_id` = a.`id` WHERE f.`user_id` = :id ORDER BY f.`count` DESC LIMIT 0,'.$this->_config('limit').'', array(':id', $user_id, PDO::PARAM_INT));

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
}