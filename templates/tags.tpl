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
*}

{if $tag}
	{panel=i18n('Materiały zawierające słowo kluczowe <span class="italic">:tagTitle</span>', array(':tagTitle' => $this->data['tag_name']))}
		<p class="tag_top dark text_dark">
			<a href="{$url_tag}">Słowa kluczowe</a> <img src="{$THEME_IMAGES}bullet.png" alt=""> <strong>{$tag_name}</strong> ({$tag_frequency})
		</p>
		<ul class="tagged_elements clearfix">
		{section=tag}
			<li><a href="{$tag.tag_url_item}" class="light">{$tag.tag_title_item}</a></li>
		{/section}
		</ul>
		<a href="{$url_tag}" class="button">Wróć do listy słów kluczowych</a>
	{/panel}
{elseif $tags}
	{panel=i18n('Lista słów kluczowych')}
		<div class="tag_cloud">
			<ul>
				{section=tags}
					<li><a href="{$tags.tag_url}" rel="{if $tags.tag_frequency<30}{$tags.tag_frequency}{else}30{/if}">{$tags.tag_name}</a></li>
				{/section}
			</ul>
		</div>
	{/panel}
{else}
	{panel=i18n('Słowa kluczowe')}
		<p class="status">{i18n('Brak utworzonych słów kluczowych')}</p>
	{/panel}
{/if}