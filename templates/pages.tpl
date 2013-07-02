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
		{panel='Przegląd treści'}
			{if $data}
				<p class="center"><a href="{url('controller=>', 'pages', 'action=>', 'categories')}" title="Kategorie treści">Wyświetl wszystkie kategorie</a></p>
				<p>Na tej stronie znajdują się nastepujące rodzaje materiałów:</p>
				<ul class="margin-b-10">
					{section=data}
						<div class="tbl">
							<li><a href="{$data.link}" title="{$data.name}">{$data.name}</a></li>
						</div>
					{/section}
				</ul>
				<p class="center">Wybierz jeden z nich, by zobaczyć przypisane mu kategorie.</p>
			{else}
				Brak materiałów na stronie
			{/if}
		{/panel}
	{elseif $page == 'type'}
		{panel='Nawigacja'}
		<p>Jesteś tutaj: <a href="{url()}" title="Materiały na stronie">Materiały na stronie</a> &raquo; {$type} &raquo; Kategorie treści wybranego typu</p>
		{/panel}


			{if $data}
				{* Do przerobienia *}
				{php} $i = 0; {/php}
				{section=data}
					{php} $this->sidePanel(__('Kategoria &raquo; '.$this->data['data'][$i]['name'])); {/php}
						<div class="tbl formated_text clearfix">
							<section id="content">{if $data.thumbnail}<img src="{$ADDR_UPLOAD}images/{$data.thumbnail}">{/if}{$data.description}</section>
							<p><a href="{$data.link}" title="{$data.name}">Materiały w kategorii...</a></p>
						</div>
					{php} $i++; $this->sidePanel(); {/php}

				{/section}
			{else}
				{panel='Kategorie treści'}
					Brak materiałów dla tego typu treści
				{/panel}
			{/if}
		{* Skąd to się to wzięło? *}
		{php} $this->sidePanel(); {/php}
	{elseif $page == 'category'}
		{panel='Nawigacja'}
		<p>Jesteś tutaj: <a href="{url()}" title="Materiały na stronie">Materiały na stronie</a> &raquo; <a href="{$type.link}" title="{$type.name}">{$type.name}</a> &raquo; Materiały w kategorii: {$category}</p>
		{/panel}

			{if $data}
				{* Do przerobienia *}
				{php} $i = 0; {/php}
				{section=data}
					{php} $this->sidePanel(__('Materiał &raquo; '.$this->data['data'][$i]['title'])); {/php}
						<div class="tbl formated_text clearfix">
							<p class="margin-b-10"><small>Dodane przez {$data.author} dnia {$data.date}</small></p>
							<section id="preview">{$data.preview}</section>
							<div><a href="{$data.link}">Czytaj więcej...</a></div>
						</div>
					{php} $i++; $this->sidePanel(); {/php}
				{/section}
			{else}
				{* Do przerobienia *}
				{php} $this->sidePanel('Materiały w kategorii '.$this->data['category']); {/php}
					Brak materiałów w tej kategorii
				{php} $this->sidePanel(); {/php}
			{/if}
	{elseif $page == 'categories'}
		{panel='Nawigacja'}
		<p>Jesteś tutaj: <a href="{url()}" title="Materiały na stronie">Materiały na stronie</a> &raquo; Przeglądana kategoria: {$category}</p>
		{/panel}

			{if $data}
				{php} $i = 0; {/php}
				{section=data}
					{php} $this->sidePanel(__('Materiał &raquo; '.$this->data['data'][$i]['title'])); {/php}
						<div class="tbl formated_text clearfix">
							<p class="margin-b-10"><small>Dodane przez {$data.author} dnia {$data.date}</small></p>
							<section id="preview">{$data.preview}</section>
							<div><a href="{$data.link}">Czytaj więcej...</a></div>
						</div>
					{php} $i++; $this->sidePanel(); {/php}
				{/section}
			{else}
				{php} $this->sidePanel(__('Materiały w kategorii '.$this->data['category'])); {/php}
					Brak materiałów w tej kategorii
				{php} $this->sidePanel(); {/php}
			{/if}
	{elseif $page == 'categories_list'}
		{panel='Nawigacja'}
		<p>Jesteś tutaj: <a href="{url()}" title="Materiały na stronie">Materiały na stronie</a> &raquo; Przegląd kategorii</p>
		{/panel}

			{if $data}
				{php} $i = 0; {/php}
				{section=data}
					{php} $this->sidePanel(__('Kategoria &raquo; '.$this->data['data'][$i]['name'])); {/php}
						<div class="tbl formated_text clearfix">
							<section id="preview">{if $data.thumbnail}<img src="{$ADDR_UPLOAD}images/{$data.thumbnail}">{/if}{$data.description}</section>
							<div><a href="{$data.link}">Więcej...</a></div>
						</div>
					{php} $i++; $this->sidePanel(); {/php}
				{/section}
			{else}
				{php} $this->sidePanel('Kategorie treści'); {/php}
					Brak utworzonych kategorii
				{php} $this->sidePanel(); {/php}
			{/if}
	{elseif $page == 'entry'}
		{panel='Nawigacja'}
		<p>Jesteś tutaj: <a href="{url()}" title="Materiały na stronie">Materiały na stronie</a> &raquo; <a href="{$type.url}" title="{$type.name}">{$type.name}</a> &raquo; Czytasz: {$entry}</p>
		{/panel}

		{php} $this->sidePanel($this->data['data']['title']); {/php}
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
				<div class="formated_text clearfix">{if $data.thumbnail}<img src="{$ADDR_UPLOAD}images/{$data.thumbnail}">{/if}{$data.content}</div>
			{else}
				Wystąpił błąd. Przepraszamy za utrudnienia.
			{/if}
		{php} $this->sidePanel(); {/php}
		{if $user_allow_comments}
			{$comments}
		{/if}
	{/if}
{/if}