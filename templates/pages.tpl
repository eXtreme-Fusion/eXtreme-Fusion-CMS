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

{if $message}
	<div class="{$class}">{$message}</div>
{else}

	{if $page == 'main'}
		{php} opentable('Przegląd treści na stronie'); {/php}
			{if $data}
			
				<p>Rodzaje materiałów zamieszczanych na stronie:</p>
				<ul class="margin-b-10">
					{section=data}
						<div class="tbl">
							<li><a href="{$data.link}" title="{$data.name}">{$data.name}</a></li>
						</div>
					{/section}
				</ul>
				
				<p class="center"><a href="{url('controller=>', 'pages', 'action=>', 'categories')}" title="Kategorie treści">Wszystkie kategorie treści</a></p>
			
			{else}
				Brak materiałów na stronie
			{/if}
		{php} closetable(); {/php}
	{elseif $page == 'type'}
		{php} opentable('Nawigacja'); {/php}
		<p>Jesteś tutaj: <a href="{url()}" title="Materiały na stronie">Materiały na stronie</a> &raquo; {$type} &raquo; Kategorie treści wybranego typu</p>
		{php} closetable(); {/php}
		
		
			{if $data}
				{php} $i = 0; {/php}
				{section=data}
					{php} opentable('Kategoria &raquo; '.$this->data['data'][$i]['name']); {/php}
						<div class="tbl">
							
							{if $data.thumbnail}
								<img src="{$data.thumbnail}" />
							{/if}
							<section id="content">{$data.description}</section>
							
							<p><a href="{$data.link}" title="{$data.name}">Materiały w kategorii...</a></p>
						</div>
					{php} $i++; closetable(); {/php}
				
				{/section}
			{else}
				{php} opentable('Kategorie treści'); {/php}
					Brak materiałów dla tego typu treści
				{php} closetable(); {/php}
			{/if}
		{php} closetable(); {/php}
	{elseif $page == 'category'}
		{php} opentable('Nawigacja'); {/php}
		<p>Jesteś tutaj: <a href="{url()}" title="Materiały na stronie">Materiały na stronie</a> &raquo; <a href="{$type.link}" title="{$type.name}">{$type.name}</a> &raquo; Materiały w kategorii: {$category}</p>
		{php} closetable(); {/php}
			
			{if $data}
				{php} $i = 0; {/php}
				{section=data}
					{php} opentable('Materiał &raquo; '.$this->data['data'][$i]['title']); {/php}
						<div class="tbl">
							<p class="margin-b-10"><small>Dodane przez {$data.author} dnia {$data.date}</small></p>
							<section id="preview">{$data.preview}</section>
							<div><a href="{$data.link}">Czytaj więcej...</a></div>
						</div>
					{php} $i++; closetable(); {/php}
				{/section}
			{else}
				{php} opentable('Materiały w kategorii '.$this->data['category']); {/php}
					Brak materiałów w tej kategorii
				{php} closetable(); {/php}
			{/if}
	{elseif $page == 'categories'}
		{php} opentable('Nawigacja'); {/php}
		<p>Jesteś tutaj: <a href="{url()}" title="Materiały na stronie">Materiały na stronie</a> &raquo; Przeglądana kategoria: {$category}</p>
		{php} closetable(); {/php}
			
			{if $data}
				{php} $i = 0; {/php}
				{section=data}
					{php} opentable('Materiał &raquo; '.$this->data['data'][$i]['title']); {/php}
						<div class="tbl">
							<p class="margin-b-10"><small>Dodane przez {$data.author} dnia {$data.date}</small></p>
							<section id="preview">{$data.preview}</section>
							<div><a href="{$data.link}">Czytaj więcej...</a></div>
						</div>
					{php} $i++; closetable(); {/php}
				{/section}
			{else}
				{php} opentable('Materiały w kategorii '.$this->data['category']); {/php}
					Brak materiałów w tej kategorii
				{php} closetable(); {/php}
			{/if}
	{elseif $page == 'categories_list'}
		{php} opentable('Nawigacja'); {/php}
		<p>Jesteś tutaj: <a href="{url()}" title="Materiały na stronie">Materiały na stronie</a> &raquo; Przegląd kategorii</p>
		{php} closetable(); {/php}
			
			{if $data}
				{php} $i = 0; {/php}
				{section=data}
					{php} opentable('Kategoria &raquo; '.$this->data['data'][$i]['name']); {/php}
						<div class="tbl">
							<section id="preview">{$data.description}</section>
							<div><a href="{$data.link}">Więcej...</a></div>
						</div>
					{php} $i++; closetable(); {/php}
				{/section}
			{else}
				{php} opentable('Kategorie treści'); {/php}
					Brak utworzonych kategorii
				{php} closetable(); {/php}
			{/if}
	{elseif $page == 'entry'}
		{php} opentable('Nawigacja'); {/php}
		<p>Jesteś tutaj: <a href="{url()}" title="Materiały na stronie">Materiały na stronie</a> &raquo; <a href="{$type.url}" title="{$type.name}">{$type.name}</a> &raquo; Czytasz: {$entry}</p>
		{php} closetable(); {/php}
		
		{php} opentable($this->data['data']['title']); {/php}
			<div class="margin-b-10">
				<small>Dodane przez {$data.author} dnia {$data.date}</small>
				
					{if $keyword}
						<small>Słowa kluczowe: </small> 
						{section=keyword}
							<a href="{$keyword.url}" title="{$keyword.name}" class="small">{$keyword.name}</a>,
						{/section}
					{/if}
					{if $cats}
						<small>Kategorie: </small> 
						{section=cats}
							<a href="{$cats.url}" title="{$cats.name}" class="small">{$cats.name}</a>,
						{/section}
					{/if}
				
			</div>
			{if $data}
				<div>{$data.content}</div>
			{else}
				Wystąpił błąd. Przepraszamy za utrudnienia.
			{/if}
		{php} closetable(); {/php}
		
		{php} opentable('Komentarze'); {/php}
			{$comments}
		{php} closetable(); {/php}
	{/if}
{/if}