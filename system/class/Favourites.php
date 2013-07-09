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
}