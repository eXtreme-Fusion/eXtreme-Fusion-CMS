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

{if $action && $action !== 'page'}
	{if $rows}
		{panel=i18n('News preview')}
			<article class="news dark border_top_other">
				<header>
					<p class="text_dark">{i18n('Date:')} <time datetime="{$news.datetime}" pubdate="pubdate">{$news.date}</time></p>
					<h3>
						<a href="{$news.url}" title="{$news.title_name}">{$news.title_name}</a>
						{if $access_edit}
							<a href="{$ADDR_ADMIN}pages/news.php?page=news&amp;action=edit&amp;id={$news.title_id}&amp;fromPage=true" class="admin-box" title="{i18n('Edit')}">[{i18n('Edit')}]</a>
						{/if}
					</h3>
					{if $news.allow_comments}<a href="#comments" class="news_comments" title="{i18n('Comments:')} {$news.num_comments}">{$news.num_comments}</a>{/if}
					<div class="light text_light box_shadow1">
						{if $news.category_id}<p>{i18n('Category:')} <a href="{$news.category_link}" rel="tag">{$news.category_name}</a></p>{/if}
						<p>{i18n('Author:')} <a href="{$news.author_link}" rel="author">{$news.author_name}</a></p>
						<p>{i18n('Reads:')} {$news.reads}</p>
					</div>
				</header>
				<div id="content_extended" class="formated_text clearfix">
					{if $news.content}
						{$news.content}
					{/if}
					{if $news.content_ext}
						{$news.content_ext}
					{/if}
				</div>
				<footer class="text_dark">
					{if $news.keyword}
						<p>
							<strong>{i18n('Tags:')}</strong>
							{$news.keyword}
						</p>
					{/if}
					{if $news.source}
						<p><strong>{i18n('Source')}:</strong> <a href="{$news.source}" target="_blank">{$news.source}</a></p>
					{/if}
				</footer>
			</article>
		{/panel}
		{$comments}
	{else}
		{panel=i18n('Error')}
			<p class="status">{i18n('No data!')}</p>
		{/panel}
	{/if}
{else}
	{panel=i18n('News')}
		{if $news}
			{section=news}
				<article class="news dark border_top_other">
					<header>
						<p class="text_dark">{i18n('Date:')} <time datetime="{$news.datetime}" pubdate="pubdate">{$news.date}</time></p>
						<h3><a href="{$news.url}" title="{$news.title_name}">{$news.title_name}</a></h3>
						{if $news.allow_comments}<a href="{$news.url}#comments" class="news_comments" title="{i18n('Comments:')} {$news.num_comments}">{$news.num_comments}</a>{/if}
						<div class="light text_light box_shadow1">
							{if $news.category_id}<p>{i18n('Category:')} <a href="{$news.category_link}" rel="tag">{$news.category_name}</a></p>{/if}
							<p>{i18n('Author:')} <a href="{$news.author_link}" rel="author">{$news.author_name}</a></p>
							<p>{i18n('Language:')} {$news.language}</p>
							<p>{i18n('Reads:')} {$news.reads}</p>
						</div>
					</header>
					<div class="formated_text clearfix">
						{$news.content}
					</div>
					<footer class="text_dark clearfix">
						{if $news.content_ext}
							<a href="{$news.url}#content_extended" class="button more">{i18n('Read more...')}</a>
						{/if}
						{if $news.keyword}
							<p>
								<strong>{i18n('Tags:')}</strong>
								{$news.keyword}
							</p>
						{/if}
						{if $news.source}
							<p><strong>{i18n('Source')}:</strong> <a href="{$news.source}" target="_blank">{$news.source}</a></p>
						{/if}
					</footer>
					</article>
			{/section}
		{$page_nav}
	{else}
		<p class="status">{i18n('No News has been posted yet')}</p>
	{/if}
{/panel}
{/if}