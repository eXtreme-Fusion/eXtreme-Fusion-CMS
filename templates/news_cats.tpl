{if $preview}
	{if $rows}
		{php} opentable(__('News categories')) {/php}
			<div class="cat-info">
				<div class="center">
					<strong>{$c.cat_name}</strong> <br /> <small>{i18n('News:')} {$c.cat_news}</small>
				</div>
				<div class="center">
					<img src="{$NEWS_CAT_IMAGES}{$c.cat_image}" alt="{$c.cat_name}" title="{$c.cat_name}" class="tip">
				</div>
			</div>
			{if $n}
				<div class="tbl2">
					<div class="grid_2 bold">{i18n('Title:')}</div>
					<div class="grid_3 bold">{i18n('Fragment:')}</div>
					<div class="grid_1 bold">{i18n('Author:')}</div>
					<div class="grid_1 bold">{i18n('Reads')}</div>
					<div class="grid_2 bold">{i18n('Date')}</div>
				</div>
					{section=n}
						<div class="tbl {$n.row_color}">
							<div class="grid_2"><a href="{$n.news_url}">{$n.news_title_name}</a></div>
							<div class="grid_3">{$n.news_content}</div>
							<div class="grid_1"><a href="{$n.profile_url}">{$n.news_author_name}</a></div>
							<div class="grid_1">{$n.news_reads}</div>
							<div class="grid_2">{$n.news_datestamp}</div>
						</div>
					{/section}
			{else}
				<div class="tbl">
					<div class="status">{i18n('There are no news in the specified category.')}</div>
				</div>
			{/if}
		{php} closetable() {/php}
	{else}
		{php} opentable(__('There are no news categories')) {/php}
				<div class='status'>{i18n('There are no news categories.')}</div>
		{php} closetable() {/php}
	{/if}
{/if}
{if $i}
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
{/if}