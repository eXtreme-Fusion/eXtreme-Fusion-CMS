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
			if ( ! in_array($row['value'], $temp)) $temp[] = $row['value'];
		}
		
		if (is_array($keys = array_unique($temp)))
		{			
			foreach($keys as $tag)
			{
				$filter = new Edit($tag);
				$cache[] = array(
					'tag' => $tag,
					'url' => $_route->path(array('controller' => 'tags', 'action' => $filter->setTitleForLinks()))
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