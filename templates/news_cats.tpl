{if $rows}
	{php} opentable(__('News categories')) {/php}
		<p class="cat_top dark text_dark">
			<a href="{$all_news_url}">Newsy</a> <img src="{$THEME_IMAGES}bullet.png" alt=""> <a href="{$all_news_cats_url}">Kategorie newsów</a> <img src="{$THEME_IMAGES}bullet.png" alt=""> <strong>{$category.cat_name}</strong> ({$category.cat_news_count})
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
	{php} closetable() {/php}
{elseif $i}
	{php} opentable(__('News categories')) {/php}
		<p class="cat_top dark text_dark">
			<a href="{$all_news_url}">Newsy</a> <img src="{$THEME_IMAGES}bullet.png" alt=""> <strong>Kategorie newsów</strong>
		</p>
		<ul class="cat_list">
		{section=i}
			<li>
				<a href="{$i.url}" title="{$i.cat_title_name}" class="dark">
					<span>
						<strong>{$i.cat_title_name}</strong>
						<img src="{$NEWS_CAT_IMAGES}{$i.cat_image}" alt="{$i.cat_title_name}">
						<small class="text_dark">{i18n('News:')} {$i.cat_count_news}</small>
					</span>
				</a>
			</li>
		{/section}
		</ul>
	{php} closetable() {/php}
{else}
	{php} opentable(__('There are no news categories')) {/php}
		<p class="status">{i18n('There are no news categories.')}</p>
	{php} closetable() {/php}
{/if}