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
| 
**********************************************************
                ORIGINALLY BASED ON
---------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/

// Jeœli nie jest numeryczne to zostanie rzucony wyj¹tek.
if ( ! $_request->get('current')->isNum()) exit;

$_locale->load('news');

// Sprawdzanie, czy u¿ytkownik ma prawo do zobaczenia jakiegokolwiek newsa
$rows = $_pdo->getMatchRowsCount('SELECT `id` FROM [news] WHERE `access` IN ('.$_user->listRoles().') AND `draft` = 0');

if ($rows)
{
	# STRONICOWANIE #
	$items_per_page = intval($_user->get('itemnews') ? $_user->get('itemnews') : $_sett->get('news_per_page'));

	$rowstart = PAGING::getRowStart($_request->get('current')->show(), $items_per_page);

	# / STRONICOWANIE #
	$cache = $_system->cache('news,'.$_user->getCacheName().',page-'.$_request->get('current')->show(), NULL, 'news', 86700);
	if ($cache === NULL)
	{
		$query = $_pdo->getData('
			SELECT tn.`id` AS `news_id`, tn.`title`, tn.`link`, tn.`category`, tn.`content`, tn.`content_extended`, tn.`author`, tn.`source`, tn.`keyword`, tn.`breaks`, tn.`datestamp`, tn.`access`, tn.`reads`, tn.`draft`, tn.`sticky`, tn.`allow_comments`, tn.`allow_ratings`, tc.`id` AS `cat_id`, tc.`name`, tc.`image`, tu.`id` AS `user_id`,  tu.`username` AS `username`  FROM [news] tn
			LEFT JOIN [users] tu ON tn.`author`= tu.`id`
			LEFT JOIN [news_cats] tc ON tn.`category`= tc.`id`
			WHERE tn.`draft` = 0 AND tn.`access` IN ('.$_user->listRoles().')
			ORDER BY tn.`sticky` DESC, tn.`datestamp` DESC, tn.`title` ASC LIMIT :rowstart,:items_per_page',
			array(
				array(':rowstart', $rowstart, PDO::PARAM_INT),
				array(':items_per_page', $items_per_page, PDO::PARAM_INT),
			)
		);

		if ($_pdo->getRowsCount($query))
		{
			foreach ($query as $data)
			{

					$keyword = array();
					if (is_array($Keys = unserialize($data['keyword']))){
						foreach($Keys as $a){
							$keyword[] = array(
								'KeywordTitleName' => $a,
								'KeywordTitleLink' => HELP::Title2Link($a)
							);
						}
					}

					$cache[] = array(
						'ID' => $data['news_id'],
						'Access' => $data['access'],
						'TitleID' => $data['news_id'],
						'TitleName' => $data['title'],
						'TitleLink' => HELP::Title2Link($data['title']),
						'CategoryID' => $data['cat_id'],
						'CategoryName' => $data['name'],
						'CategoryLink' => HELP::Title2Link($data['name']),
						'AuthorID' => $data['user_id'],
						'AuthorName' => $_user->getUsername($data['user_id']),
						'AuthorLink' => HELP::Title2Link($data['username']),
						'Date' => HELP::showDate('shortdate', $data['datestamp']),
						'Source' => $data['source'],
						'Keyword' => $keyword,
						'Content' => stripslashes($data['content']),
						'ContentExt' => stripslashes($data['content_extended']),
						'Reads' => $data['reads'],
						//'NumComments' => $_pdo->getMatchRowsCount('SELECT `id` FROM [comments] WHERE `content_type` = "n" AND `content_id`='.$data['news_id']),
						'AllowComments' => $data['allow_comments'],
						'Sticky' => $data['sticky']
					);


			}

		}

		$_system->cache('news,'.$_user->getCacheName().',page-'.$_request->get('current')->show(), $cache, 'news');
	}

	foreach($cache as &$news)
	{
		if ($_user->hasAccess($news['Access']))
		{
			$news['NumComments'] = $_pdo->getMatchRowsCount('SELECT `id` FROM [comments] WHERE `content_type` = "n" AND `content_id`='.$news['ID']);
		}
		else
		{
			throw new systemException('B³¹d! U¿ytkownik posiada w cache\'u materia³u, do którego nie ma uprawnieñ. Czyszczenie cache\'u przy edycji newsa nie dzia³a poprawnie.');
		}
	}

	$_pagenav = new PageNav(new Paging($rows, $_request->get('current')->show(), $items_per_page), $_tpl, 5, array('news', 'page'));

	if ($_theme->tplExists('news_page_nav.tpl', 'templates'.DS.'paging'.DS))
	{
		$_pagenav->get($_pagenav->create(), 'news_page_nav', DIR_THEME.'templates'.DS.'paging'.DS);
	}
	else
	{
		$_pagenav->get($_pagenav->create(), 'page_nav');
	}

	$_tpl->assign('News', $cache);
}
