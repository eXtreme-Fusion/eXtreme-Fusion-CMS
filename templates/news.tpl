{if $action && $action !== 'page'}
	{if $rows}
		{php} opentable(__('News preview')) {/php}
			<article class="news dark border_top_other">
				<header>
					<p class="text_dark">{i18n('Date:')} <time datetime="{$news.datetime}" pubdate="pubdate">{$news.date}</time></p>
					<h3>
						<a href="{$news.url}" title="{$news.title_name}">{$news.title_name}</a>
						{if $access_edit}
							<a href="javascript:void(0);" class="admin-box" rel="{$ADDR_ADMIN}pages/news.php?page=news&amp;action=edit&amp;id={$news.title_id}&amp;fromPage=true" title="{i18n('Edit')}">[{i18n('Edit')}]</a>
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
		{php} closetable() {/php}
		{$comments}
	{else}
		{php} opentable(__('Error')) {/php}
			<p class="status">{i18n('No data!')}</p>
		{php} closetable() {/php}
	{/if}
{else}
	{php} opentable(__('News')); {/php}
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
{php} closetable(); {/php}
{/if}