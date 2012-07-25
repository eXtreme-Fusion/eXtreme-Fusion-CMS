{if $action}
	{if $rows}
	{php} opentable(__('News preview')) {/php}
		<div class="news-content">
			<div class="news">
				<h4>{$news.title_name} <span class="right small">{i18n('Date:')} {$news.date}</span></h4>
				{if $access_edit}
					<div class="clear"></div>
					<div class="right">
						<a href="javascript:void(0);" class="tip admin-box" rel="{$ADDR_ADMIN}pages/news.php?page=news&action=edit&id={$news.title_id}&fromPage=true" title="{i18n('Edit')}">
						<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
					</div>
				{/if}
				<div class="left small">
					<div>{if $news.category_id}{i18n('Category:')} <a href="{$news.category_link}">{$news.category_name}</a>,{/if} {i18n('Author:')} <a href="{$news.author_link}">{$news.author_name}</a></div>
					<div>
						{if $news.source}
							<a href="{$news.source}" target="_blank">{i18n('Source')}</a>,
						{/if}
						{if $news.keyword}
							{i18n('Tags:')}
							{foreach=$news.keyword; value}
								<a href="{@value.tag_url}">{@value.keyword_name}</a>,
							{/foreach}
						{/if}
					</div>
				</div>
				<div class="right small">{i18n('Reads:')} {$news.reads}, {i18n('Comments:')} {$news.num_comments}</div>
			</div>
			<div class="Content">
				{if $news.content}
					<a name='content'></a>
					{$news.content}
				{/if}
				{if $news.content_ext}
					<p>
					<a name='content_extended'></a>
					{$news.content_ext}
					</p>
				{/if}
			</div>
			<div class="ContentFooter">
				<hr /><!--
				{* Share it *}
					<div class="addthis_toolbox addthis_default_style ">
					<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
					<a class="addthis_button_tweet"></a>
					<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
					<a class="addthis_counter addthis_pill_style"></a>
					</div>
					<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e6dcb8a3de24922"></script>
				{* (end of) Share it *}-->
			</div>
		</div>
		
	{php} closetable() {/php}
	<div id="comments"></div>
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
				<article class="news">
					<header>
						<h3><a href="{$news.url}" title="{$news.title_name}">{$news.title_name}</a></h3>
						<p>{i18n('Date:')} <time datetime="{$news.datetime}" pubdate="pubdate">{$news.date}</time></p>
						<p>
							{if $news.category_id}{i18n('Category:')}<a href="{$news.category_link}" rel="tag">{$news.category_name}</a>,{/if}
							{i18n('Author:')} <a href="{$news.author_link}" rel="author">{$news.author_name}</a>,
							{i18n('Language:')} {$news.language}
						</p>
						<p>
							{if $news.source}
								<a href="{$news.source}" target="_blank">{i18n('Source')}</a>,
							{/if}
							{if $news.keyword}
								{i18n('Tags:')}
								{foreach=$news.keyword; value}
									<a href="{@value.tag_url}" rel="tag">{@value.keyword_name}</a>,
								{/foreach}
							{/if}
						</p>
						<p>{i18n('Reads:')} {$news.reads}, {if $news.allow_comments} <a href="{$news.url}#comments">{i18n('Comments:')} {$news.num_comments}</a>{/if}</p>
					</header>
					<div class="news_content">
						{$news.content}
					</div>
					{if $news.content_ext}
						<footer>
							<a href="{$news.url}{if $news.content_ext}#content_extended{else}#content{/if}" class="button">{i18n('Read more...')}</a>
						</footer>
					{/if}
				</article>
			{/section}	
			{$page_nav}
		{else}
			<p class="status">{i18n('No News has been posted yet')}</p>
		{/if}
	{php} closetable(); {/php}
{/if}