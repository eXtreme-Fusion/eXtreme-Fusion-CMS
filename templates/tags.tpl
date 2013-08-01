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
**********************************************************
*}
{if $tag}
	{php} $this->middlePanel(__('Materials containing the keyword <span class="italic">:tag</span>', array(':tag' => $this->data['tag_name']))); {/php}
		<p class="tag_top dark text_dark">
			<a href="{$url_tag}">{i18n('Keywords')}</a> <img src="{$THEME_IMAGES}bullet.png" alt=""> <strong>{$tag_name}</strong> ({$tag_frequency})
		</p>
		<ul class="tagged_elements clearfix">
		{section=tag}
			<li><a href="{$tag.tag_url_item}" class="light">{$tag.tag_title_item}</a></li>
		{/section}
		</ul>
		<a href="{$url_tag}" class="button">{i18n('Back to keyword list')}</a>
	{php} $this->middlePanel(); {/php}
{elseif $tags}
	{php} $this->middlePanel(__('List of keywords')); {/php}
		<div class="tag_cloud">
			<ul>
				{section=tags}
					<li><a href="{$tags.tag_url}" rel="{if $tags.tag_frequency<30}{$tags.tag_frequency}{else}30{/if}">{$tags.tag_name}</a></li>
				{/section}
			</ul>
		</div>
	{php} $this->middlePanel(); {/php}
{else}
	{php} $this->middlePanel(__('Keywords')); {/php}
		<p class="status">{i18n('There are no keywords')}</p>
	{php} $this->middlePanel(); {/php}
{/if}