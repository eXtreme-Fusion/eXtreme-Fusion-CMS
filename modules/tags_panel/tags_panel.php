<?php defined('EF5_SYSTEM') || exit;
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
$_locale->moduleLoad('lang', 'tags_panel');

! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);

$cache = $_system->cache('tags,'.$_user->getCacheName(), NULL, 'tags_panel', 0);

if ($cache === NULL)
{
	if ($query = $_tag->getTagFromSupplement('NEWS'))
	{
		$temp = array();
		foreach ($query as $row)
		{
			if ( ! in_array($row['value'], $temp)) $temp[] = array('title' => $row['value'], 'link' => $row['value_for_link']);
		}
		
		if (is_array($temp))
		{
			foreach($temp as $tag)
			{		
				$cache[] = array(
					'tag' => $tag['title'],
					'url' => $_route->path(array('controller' => 'tags', 'action' => $tag['link']))
				);
			}
		}
	}
	
	$_system->cache('tags,'.$_user->getCacheName(), $cache, 'tags_panel');
}

if ($cache !== NULL)
{
	// Inicjalizacja obiektu
	$tags_cloud = new TagsCloud();
	
	foreach($cache as $tag)
	{
		$tags_cloud->addTag(array('tag' => $tag['tag'], 'url' => $tag['url']));
	}
	
	if ($row = $tags_cloud->showTagsCloud())
	{
		$tags = array();
		foreach ($row as $key => $value)
		{
			$tags[] = $value;
		}
		$_panel->assign('tags', $tags);
	}
}