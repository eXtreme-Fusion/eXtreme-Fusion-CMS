{if $rows}
	{php} opentable(__('News categories')) {/php}
		<div class="cat-info">
			<div class="center">
				<strong>{$category.cat_name}</strong> <br /> <small>{i18n('News:')} {$category.cat_news_count}</small>
			</div>
			<div class="center">
				<img src="{$NEWS_CAT_IMAGES}{$category.cat_image}" alt="{$category.cat_name}" title="{$category.cat_name}" class="tip">
			</div>
		</div>
		{if $rows}
			<div class="tbl2">
				<div class="grid_2 bold">{i18n('Title:')}</div>
				<div class="grid_3 bold">{i18n('Fragment:')}</div>
				<div class="grid_1 bold">{i18n('Author:')}</div>
				<div class="grid_1 bold">{i18n('Reads')}</div>
				<div class="grid_2 bold">{i18n('Date')}</div>
			</div>
				{section=rows}
					<div class="tbl {$rows.row_color}">
						<div class="grid_2"><a href="{$rows.news_url}">{$rows.news_title_name}</a></div>
						<div class="grid_3">{$rows.news_content}</div>
						<div class="grid_1"><a href="{$rows.profile_url}">{$rows.news_author_name}</a></div>
						<div class="grid_1">{$rows.news_reads}</div>
						<div class="grid_2">{$rows.news_datestamp}</div>
					</div>
				{/section}
				{$page_nav}
		{else}
			<div class="tbl">
				<div class="status">{i18n('There are no news in the specified category.')}</div>
			</div>
		{/if}
	{php} closetable() {/php}
{elseif $i}
	{php} opentable(__('News categories')) {/php}
		{section=i}
			<div class="cat-info">
				<div class="center">
					<a href="{$i.url}">
						<strong>{$i.cat_title_name}</strong> <br /> <small>{i18n('News:')} {$i.cat_count_news}</small>
					</div>
					<div class="center">
						<img src="{$NEWS_CAT_IMAGES}{$i.cat_image}" alt="{$i.cat_title_name}" title="{$i.cat_title_name}" class="tip">
						</a>
				</div>
			</div>
		{/section}
	{php} closetable() {/php}
{else}
	{php} opentable(__('There are no news categories')) {/php}
		<div class='status'>{i18n('There are no news categories.')}</div>
	{php} closetable() {/php}
{/if}