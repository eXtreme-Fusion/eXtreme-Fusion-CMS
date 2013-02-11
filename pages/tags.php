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

if ( ! $_theme->tplExists())
{
	$_head->set('<link href="'.ADDR_TEMPLATES.'stylesheet/tags.css" media="screen" rel="stylesheet">');
	$_head->set('<script src="'.ADDR_TEMPLATES.'javascripts/jquery.tagsphere.min.js"></script>');
	$_head->set('
					<script>
						$(function(){
							$(".tag_cloud").tagcloud({centrex:250, centrey:250, min_font_size:10, max_font_size:32, zoom:100, init_motion_x: 100, init_motion_y: 100});
						});
					</script>');
}

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
						'tag_title_item' => $var['title'],
						'tag_url_item' => $_route->path(array('controller' => strtolower($var['supplement']), 'action' => $var['supplement_id'], HELP::Title2Link($var['title'])))
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
	$_tpl->assign('tag_name', $_route->getAction());
	$_tpl->assign('tag_frequency', count($cache));
	$_tpl->assign('tag', $cache);
}
else
{
	$cache = $_system->cache('tags,'.$_user->getCacheName(), NULL, 'tags', $_sett->getUns('cache', 'expire_tags')); $k = array();
	$f = array(); $g = array();
	if ($cache === NULL)
	{
		if ($keys = $_tag->getAllTag())
		{
			foreach($keys as $var)
			{
				if (!array_key_exists($var['value'], $f)) {
					$f[$var['value']] = 1;
				} else {
					$f[$var['value']] = $f[$var['value']] + 1;
				}
			}
			
			foreach($keys as $var)
			{
				if (!array_key_exists($var['value'], $g)) {
					$cache[] = array(
						'tag_name' => $var['value'],
						'tag_url' => $_route->path(array('controller' => 'tags', 'action' => $var['value_for_link'])),
						'tag_frequency' => $f[$var['value']]
					);
					$g[$var['value']] = TRUE;
				}
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
