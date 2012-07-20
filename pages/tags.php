<?php defined('EF5_SYSTEM') || exit;
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
$_locale->load('tags');

$_head->set($_tpl->getHeaders());

! class_exists('Tag') || $_tag = New Tag($_system, $_pdo);

if ($_route->getAction())
{
	$cache = $_system->cache('tags,supplement-'.$_route->getAction().','.$_user->getCacheName(), NULL, 'tags', $_sett->getUns('cache', 'expire_tags'));
	if ($cache === NULL)
	{
		if ($keys = $_tag->getTagFromValueLink($_route->getAction()))
		{
			foreach($keys as $var)
			{
				//$param = $_route->getParamVoid();
				
				// Sprawdzanie czy ID odpowiada wartosci tagu
				//if($var['value'] === $param[1])
				//{
					$cache[] = array(
						'tag_name' => $var['value'],
						'tag_title_iteam' => $var['title'],
						'tag_url_iteam' => $_route->path(array('controller' => strtolower($var['supplement']), 'action' => $var['supplement_id'], HELP::Title2Link($var['title'])))
					);
				//}
			}
			$_system->cache('tags,supplement-'.$_route->getAction().','.$_user->getCacheName(), $cache, 'tags');
		}
	}

	$theme = array(
		'Title' => __('Tag').' &raquo; '.$_route->getAction().' &raquo; '.$_sett->get('site_name'),
		'Keys' => 'Tag '.$_route->getAction().', słowo kluczowe'.$_route->getAction().', '.$_route->getAction(),
		'Desc' => 'Lista elementów przypisanych do słowa kluczowego '.$_route->getAction()
	);
	
	$_tpl->assign('url_tag', $_route->path(array('controller' => 'tags')));
	$_tpl->assign('tag', $cache);
}
else
{
	$cache = $_system->cache('tags,'.$_user->getCacheName(), NULL, 'tags', $_sett->getUns('cache', 'expire_tags')); $k = array();
	if ($cache === NULL)
	{
		
		if ($keys = $_tag->getAllTag())
		{
			foreach($keys as $var)
			{
				$cache[] = array(
					'tag_name' => $var['value'],
					'tag_url' => $_route->path(array('controller' => 'tags', 'action' => $var['value_for_link'])),
					'tag_title_iteam' => $var['title'],
					'tag_url_iteam' => $_route->path(array('controller' => strtolower($var['supplement']), 'action' => $var['supplement_id'], HELP::Title2Link($var['title'])))
				);
			}
			
			$_system->cache('tags,'.$_user->getCacheName(), $cache, 'tags');
		}
	}
	
	foreach($cache as $vk)
	{
		$k[] = $vk['tag_name'];
	}
	
	$k = implode(', ', $k);
	
	$theme = array(
		'Title' => __('Tagi').' &raquo; '.$_sett->get('site_name'),
		'Keys' => $k,
		'Desc' => 'Lista najpopularniejszych tagów na '.$_sett->get('site_name').'.'
	);
	
	$_tpl->assign('tags', $cache);
}
