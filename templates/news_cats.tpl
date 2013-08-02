{*
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
---------------------------------------------------------
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+-------------------------------------------------------
| Author: Nick Jones (Digitanium)
+-------------------------------------------------------
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+-------------------------------------------------------*/
*}

{if $page == 'category'}
	{* Do przerobienia *}
	{php} $this->middlePanel(__('News categories').' &raquo; '.$this->data['category']['cat_name']); {/php}
		<p class="cat_top dark text_dark">
			<a href="{$all_news_url}">{i18n('News')}</a> <img src="{$THEME_IMAGES}bullet.png" alt=""> <a href="{$all_news_cats_url}">{i18n('News categories')}</a> <img src="{$THEME_IMAGES}bullet.png" alt=""> <strong>{i18n($category.cat_name)}</strong> ({$category.cat_news_count})
		</p>
		{if $rows}
			<div class="clearfix">
				{section=rows}
						<article class="news dark border_top_other">
						<header class="clearfix">
							<p class="text_dark">{i18n('Date:')} <time datetime="{$rows.news_datetime}" pubdate="pubdate">{$rows.news_datestamp}</time> | {i18n('Author:')} <a href="{$rows.profile_url}" rel="author">{$rows.news_author_name}</a></p>
							<h3><a href="{$rows.news_url}" title="{$rows.news_title_name}">{$rows.news_title_name}</a></h3>
						</header>
						<div class="formated_text clearfix">
							{$rows.news_content}
						</div>
						<footer class="clearfix">
							<a href="{$rows.news_url}" class="button more">{i18n('Read more...')}</a>
						</footer>
					</article>
				{/section}
			</div>
			{$page_nav}
		{else}
			<p class="status">{i18n('There are no news in the specified category.')}</p>
		{/if}
	{php} $this->middlePanel() {/php}
{elseif $page == 'overview'}
	{panel=i18n('News categories')}
		<p class="cat_top dark text_dark">
			<a href="{$all_news_url}">{i18n('News')}</a> <img src="{$THEME_IMAGES}bullet.png" alt=""> <strong>{i18n('News categories')}</strong>
		</p>
		{if $i}
			<ul class="cat_list">
				{section=i}
					<li>
						<a href="{$i.url}" title="{$i.cat_title_name}" class="dark">
							<span>
								<strong>{i18n($i.cat_title_name)}</strong>
								<img src="{$NEWS_CAT_IMAGES}{$i.cat_image}" alt="{$i.cat_title_name}">
								<small class="text_dark">{i18n('News:')} {$i.cat_count_news}</small>
							</span>
						</a>
					</li>
				{/section}
			</ul>
		{else}
			<p class="status">{i18n('There are no categories.')}</p>
		{/if}
	{/panel}
{/if}