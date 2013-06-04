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

<h3>{$SystemVersion} - {i18n('Content Pages')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

{if $page == 'types'}
	{if $action == 'add' || $action == 'edit'}
		<div class="tbl2 text-padding-h3">
			<span class="bold">{i18n('You are here:')} </span>
			<a href="{$FILE_SELF}">{i18n('Main menu')}</a> &raquo;
			<a href="{$FILE_SELF}?page=types">{i18n('Type of contents')}</a> &raquo; {i18n('Creating a new type of content')}
		</div>

		<form action="{$URL_REQUEST}" method="post" id="This">
			<div class="tbl1">
				<div class="grid_6 formLabel"><label for="Name">{i18n('Name:')}</label></div>
				<div class="grid_4 formField"><input type="text" name="name" value="{$name}" id="Name" class="num_40" maxlength="40" /></div>
			</div>
			<div class="center text-padding">
				<p class="bold">{i18n('Default settings for material set to this type:')}</p>
				<small>{i18n('(each entry can have individual settings, independent of those below)')}</small>
			</div>
			<div style="display:none">
			<div class="tbl2">
				<div class="grid_6 formLabel">{i18n('Display material as a news:')}<small>{i18n('If you select this option, the material will be displayed with the news. This is a good way to differentiate the most interesting entries.')}</small></div>
				<div class="grid_1 formField"><label><input type="radio" name="for_news_page" value="1"{if $for_news_page == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
				<div class="grid_5 formField"><label><input type="radio" name="for_news_page" value="0"{if $for_news_page == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
			</div>
			<div class="tbl1">
				<div class="grid_6 formLabel">{i18n('Allow users to modify their own entries:')}<small>{i18n('Only applies to materials sent by a particular user and those which became the author. Publication of the modified version of the material takes place only after the approval of the Administrator.')}</small></div>
				<div class="grid_1 formField"><label><input type="radio" name="user_allow_edit_own" value="1"{if $user_allow_edit_own == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
				<div class="grid_5 formField"><label><input type="radio" name="user_allow_edit_own" value="0"{if $user_allow_edit_own == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
			</div></div>
			<div class="tbl2">
				<div class="grid_6 formLabel">
					<label for="InsightGroups">{i18n('Which groups can have access to the material:')}</label>
					<small>{i18n('Hold down CTRL to select a few options.')}</small>
					<small>{i18n('In case of no selection the material will not be visible outside the Admin Panel.')}</small>
				</div>
				<div class="grid_4 formField">
					<select name="insight_groups[]" multiple id="InsightGroups" class="select-multi" size="5" >
						{section=insight_groups}
							<option value="{$insight_groups.value}"{if $insight_groups.selected} selected="selected"{/if}>{$insight_groups.display}</option>
						{/section}
					</select>
				</div>
			</div>
			<div style="display:none">
			<div class="tbl1">
				<div class="grid_6 formLabel">
					<label for="EditingGroups">{i18n('Which groups can modify the material:')}</label>
					<small>{i18n('Hold down CTRL to select a few options.')}</small>
				</div>
				<div class="grid_4 formField">
					<select name="editing_groups[]" multiple id="EditingGroups" class="select-multi" size="5" >
						{section=editing_groups}
							<option value="{$editing_groups.value}"{if $editing_groups.selected} selected="selected"{/if}>{$editing_groups.display}</option>
						{/section}
					</select>
				</div>
				{*Widoczne tylko w przypadku zaznaczenua którejś pozycji na liscie wyżej *}
				<div id="wysiwyg" class="hidden">
					<div class="grid_6 formLabel">{i18n('Allow users to use the graphical content editor:')}<small>{i18n('Selecting this option can have a significant impact on the appearance of materials sent in or modified by users. They will be able to use HTML on the visual development of their entries.')}</small></div>
					<div class="grid_1 formField"><label><input type="radio" name="user_allow_use_wysiwyg" value="1"{if $user_allow_use_wysiwyg == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
					<div class="grid_5 formField"><label><input type="radio" name="user_allow_use_wysiwyg" value="0"{if $user_allow_use_wysiwyg == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
				</div>
			</div>
			<div class="tbl2">
				<div class="grid_6 formLabel">
					<label for="SubmittingGroups">{i18n('What groups can send new materials:')}</label>
					<small>{i18n('Hold down CTRL to select a few options.')}</small>
					<small>{i18n('For each category, this setting can be set individually.')}</small>
					<small>{i18n('Publication of submitted material requires Admin approval.')}</small>
				</div>
				<div class="grid_4 formField">
					<select name="submitting_groups[]" multiple id="SubmittingGroups" class="select-multi" size="5">
						{section=submitting_groups}
							<option value="{$submitting_groups.value}"{if $submitting_groups.selected} selected="selected"{/if}>{$submitting_groups.display}</option>
						{/section}
					</select>
				</div>
			</div>
			<div class="tbl1">
				<div class="grid_6 formLabel">{i18n('Show a preview of the material:')}<small>{i18n('Applies to review the material outside of the Admin Panel.')}</small></div>
				<div class="grid_1 formField"><label><input type="radio" name="show_preview" value="1"{if $show_preview == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
				<div class="grid_5 formField"><label><input type="radio" name="show_preview" value="0"{if $show_preview == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
			</div>
			<div class="tbl2">
				<div>
					<div class="grid_6 formLabel">{i18n('Do the autor of publication should be changed after editing material:')}<small>{i18n('It applies to modifying material by admin and user.')}</small></div>
					<div class="grid_1 formField"><label><input type="radio" name="change_author" value="1"{if $change_author == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
					<div class="grid_5 formField"><label><input type="radio" name="change_author" value="0"{if $change_author == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
				</div>
				<div class="clear"></div>
				{*Jeśli pole wyzej ma wartość 1, to trzeba to ukryć *}
				<div id="author" class="hidden">
					<div class="grid_6 formLabel">{i18n('Do the new autors should be added after editing material:')}<small>{i18n('It applies to modifying material by admin and user.')}</small></div>
					<div class="grid_1 formField"><label><input type="radio" name="add_author" value="1"{if $add_author == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
					<div class="grid_5 formField"><label><input type="radio" name="add_author" value="0"{if $add_author == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
				</div>
			</div>
			<div class="tbl1">
				<div>
					<div class="grid_6 formLabel">{i18n('Do the publication date should be changed after editing material:')}<small>{i18n('It applies to modifying material by admin and user.')}</small></div>
					<div class="grid_1 formField"><label><input type="radio" name="change_date" value="1"{if $change_date == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
					<div class="grid_5 formField"><label><input type="radio" name="change_date" value="0"{if $change_date == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
				</div>
				<div class="clear"></div>
				{*Jeśli pole wyzej ma wartość 1, to trzeba to ukryć *}
				<div id="date" class="hidden">
					<div class="grid_6 formLabel">{i18n('Do the editon date should be added after editing material:')}<small>{i18n('It applies to modifying material by admin and user.')}</small></div>
					<div class="grid_1 formField"><label><input type="radio" name="add_last_editing_date" value="1"{if $add_last_editing_date == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
					<div class="grid_5 formField"><label><input type="radio" name="add_last_editing_date" value="0"{if $add_last_editing_date == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
				</div>
			</div>
			<p class="center bold text-padding">Opcje dla systemowych funkcji widoku HTML, które mogą być użyte jako API Stron przy tworzeniu szablonów:</p>
			<div class="tbl2">
				<div class="grid_6 formLabel">{i18n('Wyświetlać informację o autorze/autorach materiału?')}</div>
				<div class="grid_1 formField"><label><input type="radio" name="show_author" value="1"{if $show_author == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
				<div class="grid_5 formField"><label><input type="radio" name="show_author" value="0"{if $show_author == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
			</div>
			<div class="tbl1">
				<div class="grid_6 formLabel">{i18n('Wyświetlać datę publikacji/daty modyfikacji materiału?')}</div>
				<div class="grid_1 formField"><label><input type="radio" name="show_date" value="1"{if $show_date == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
				<div class="grid_5 formField"><label><input type="radio" name="show_date" value="0"{if $show_date == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
			</div>
			<div class="tbl2">
				<div class="grid_6 formLabel">{i18n('Wyświetlać kategorie, do których materiał należy?')}</div>
				<div class="grid_1 formField"><label><input type="radio" name="show_category" value="1"{if $show_category == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
				<div class="grid_5 formField"><label><input type="radio" name="show_category" value="0"{if $show_category == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
			</div>
			<div class="tbl1">
				<div class="grid_6 formLabel">{i18n('Wyświetlać słowa kluczowe przypisane do materiału?')}</div>
				<div class="grid_1 formField"><label><input type="radio" name="show_tags" value="1"{if $show_tags == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
				<div class="grid_5 formField"><label><input type="radio" name="show_tags" value="0"{if $show_tags == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
			</div>
			<div class="tbl2">
				<div class="grid_6 formLabel">{i18n('Wyświetlać typ materiału (nazwę z pierwszego pola)?')}</div>
				<div class="grid_1 formField"><label><input type="radio" name="show_type" value="1"{if $show_type == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
				<div class="grid_5 formField"><label><input type="radio" name="show_type" value="0"{if $show_type == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
			</div>
			<div class="tbl1">
				<div class="grid_6 formLabel">{i18n('Pozwolić na komentarze?')}</div>
				<div class="grid_1 formField"><label><input type="radio" name="user_allow_comments" value="1"{if $user_allow_comments == 1} checked="checked"{/if} /> {i18n('Yes')}</label></div>
				<div class="grid_5 formField"><label><input type="radio" name="user_allow_comments" value="0"{if $user_allow_comments == 0} checked="checked"{/if} /> {i18n('No')}</label></div>
			</div>
			</div>
			<div class="tbl Buttons">
				<div class="grid_2 center button-l">
					<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
				</div>
				<div class="grid_2 center button-r">
					<input type="hidden" name="save" value="yes" />
					<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
				</div>
			</div>
		</form>
		<script src="{$ADDR_ADMIN_PAGES_JS}pages.js"></script>
	{else}
		<script>
			{literal}
				$(document).ready(function() {
					$('.dataTable').dataTable({
						"bProcessing": true,
						"bServerSide": true,
						"sAjaxSource": "ajax/pages.php?page=types",
						"bJQueryUI": true,
						"aLengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]]
					} );
				});
			{/literal}
		</script>
		<div class="tbl2 text-padding-h3">
			<span class="bold">Jesteś tutaj: </span>
			<a href="{$FILE_SELF}">Menu główne</a> &raquo;
			<a href="{$FILE_SELF}?page=types" class="underline">Typy materiałów</a>
			<a href="{$URL_REQUEST}&amp;action=add" class="box-right"><strong>{i18n('Nowy typ materiałów')}</strong></a>
		</div>
		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:20%">{i18n('Id:')}</th>
					<th style="width:20%">{i18n('Name:')}</th>
					<th style="width:25%">{i18n('Wyświetlanie jako news:')}</th>
					<th style="width:25%">{i18n('Options:')}</th>
				</tr>
			</thead>
			<tbody class="tbl1 center border_bottom">
			</tbody>
		</table>
	{/if}
{elseif $page == 'categories'}
	{if $action == 'add' || $action == 'edit'}
		<div class="tbl2 text-padding-h3">
			<span class="bold">Jesteś tutaj: </span>
			<a href="{$FILE_SELF}">Menu główne</a> &raquo;
			<a href="{$FILE_SELF}?page=categories">Kategorie wpisów</a> &raquo; Tworzenie nowej
		</div>

		<form action="{$URL_REQUEST}" method="post" id="This" enctype="multipart/form-data">
			<div class="tbl1">
				<div class="grid_6 formLabel"><label for="Name">{i18n('Name:')}</label></div>
				<div class="grid_4 formField"><input type="text" name="name" value="{$name}" id="Name" class="num_40" maxlength="40" /></div>
			</div>
			<div class="tbl2">
				<div class="grid_6 formLabel"><label for="Description">{i18n('Description:')}</label></div>
				<div class="grid_4 formField"><textarea name="description" id="Description">{$description}</textarea></div>
			</div>
			<div class="tbl1">
				<div class="grid_6 formLabel">
					<label for="SubmittingGroups">{i18n('Którym grupom umożliwić nadsyłanie nowych materiałów?')}</label>
					<small>Przytrzymaj klawiasz CTRL przy zaznaczaniu, aby wybrać kilka opcji</small>
					<small>Dla każdej kategorii to ustawienie można określić indywidualnie.</small>
					<small>Opublikowanie nadesłanego materiału wymaga akceptacji Administratora.</small>
				</div>
				<div class="grid_4 formField">
					<select name="submitting_groups[]" multiple id="SubmittingGroups" class="select-multi" size="5">
						{section=submitting_groups}
							<option value="{$submitting_groups.value}"{if $submitting_groups.selected} selected="selected"{/if}>{$submitting_groups.display}</option>
						{/section}
					</select>
				</div>
			</div>
			<div class="tbl2">
				<div class="grid_6 formLabel"><label for="Thumbnail">{i18n('Category thumbnail:')}</label></div>
				{if ! $thumbnail}
				<div class="grid_4 formField"><input type="file" name="thumbnail" value="{$thumbnail}" id="Thumbnail" /></div>
				{else}
				<div class="grid_4 formField"><img src="{$ADDR_UPLOAD}images/{$thumbnail}">
					<p><input type="checkbox" name="delete_thumbnail" />Usuń aktualną miniaturkę</p>
					<input type="hidden" name="thumbnail" value="{$thumbnail}" />
				</div>
				{/if}
			</div>
			<div class="tbl Buttons">
				<div class="grid_2 center button-l">
					<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
				</div>
				<div class="grid_2 center button-r">
					<input type="hidden" name="save" value="yes" />
					<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
				</div>
			</div>
		</form>
		<script src="{$ADDR_ADMIN_PAGES_JS}pages.js"></script>
	{else}
		<script>
			{literal}
				$(document).ready(function() {
					$('.dataTable').dataTable({
						"bProcessing": true,
						"bServerSide": true,
						"sAjaxSource": "ajax/pages.php?page=categories",
						"bJQueryUI": true,
						"aLengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]]
					} );
				});
			{/literal}
		</script>
		<div class="tbl2 text-padding-h3">
			<span class="bold">Jesteś tutaj: </span>
			<a href="{$FILE_SELF}">Menu główne</a> &raquo;
			Kategorie wpisów
			<a href="{$URL_REQUEST}&amp;action=add" class="box-right"><strong>{i18n('Nowa kategoria')}</strong></a>
		</div>

		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:20%">{i18n('Id:')}</th>
					<th style="width:20%">{i18n('Name:')}</th>
					<th style="width:25%">{i18n('Opis:')}</th>
					<th style="width:25%">{i18n('Options:')}</th>
				</tr>
			</thead>
			<tbody class="tbl1 center border_bottom">
			</tbody>
		</table>
	{/if}
{elseif $page == 'entries'}
	{if $types_error}
		<div class="error">Nie utworzono jeszcze żadnych Typów treści. Należy nadrobić zaległości. W tym celu przejdź do <a href="{$FILE_SELF}?page=types&amp;action=add">tej podstrony</a>.</div>
	{else}
		{if $action == 'add' || $action == 'edit'}
			<div class="tbl2 text-padding-h3">
				<span class="bold">Jesteś tutaj: </span>
				<a href="{$FILE_SELF}">Menu główne</a> &raquo;
				<a href="{$FILE_SELF}?page=entries">Wpisy</a> &raquo; Tworzenie nowego wpisu
			</div>

			<form action="{$URL_REQUEST}" method="post" id="This" class="tagform-full" enctype="multipart/form-data">
				<div class="tbl1">
					<div class="grid_6 formLabel"><label for="Title">{i18n('Title:')}</label></div>
					<div class="grid_4 formField"><input type="text" name="title" value="{$title}" id="Title" class="num_80" maxlength="80" /></div>
				</div>
				<div class="tbl2">
					<div class="grid_6 formLabel"><label for="Description">{i18n('Description:')}</label></div>
					<div class="grid_4 formField"><textarea name="description" id="Description">{$description}</textarea></div>
				</div>
				<div style="display:none">
				<div class="tbl2">
					<div class="grid_6 formLabel">
						<label for="URL">{i18n('Adres do podstrony:')}</label>
						<small>Przykładowy adres: {$ADDR_SITE}nowy_system_cms.html</small>
					</div>
					<div class="grid_4 formField"><input type="text" name="url" value="{if $url}{$url}{else}{$ADDR_SITE}{/if}" id="URL" class="num_500" maxlength="500" /></div>
				</div>
				</div>
				<div class="tbl1">
					<div class="grid_6 formLabel">
						<label for="Categories">{i18n('Kategorie przynależności wpisu:')}</label>
						<small>Przytrzymaj klawiasz CTRL przy zaznaczaniu, aby wybrać kilka opcji.</small>
						<small>Materiał będzie widoczny w kategoriach, które wybierzesz.</small>
					</div>
					<div class="grid_4 formField">
						<select name="categories[]" multiple id="Categories" class="select-multi" size="5">
							{section=categories}
								<option value="{$categories.value}"{if $categories.selected} selected="selected"{/if}>{$categories.display}</option>
							{/section}
						</select>
					</div>
				</div>
				<div class="tbl2">
					<div class="grid_6 formLabel">
						<label for="Type">{i18n('Typ wpisu:')}</label>
						<small>Od wybranego typu treści zależą ustawienia wpisu, o ile nie określisz niestandardowych.</small>
					</div>
					<div class="grid_4 formField">
						<select name="type" id="Type">
							{section=types}
								<option value="{$types.value}"{if $types.selected} selected="selected"{/if}>{$types.display}</option>
							{/section}
						</select>
					</div>
				</div>
				
				<script>
					{literal}
						$(function() {
							$( ".tagform-full" ).find('input.tag').tagedit({
								autocompleteURL: 'ajax/modules.php',
								texts: {
									removeLinkTitle: '{/literal}{i18n('Delete from list.')}{literal}',
									saveEditLinkTitle: '{/literal}{i18n('Save changes.')}{literal}',
									deleteLinkTitle: '{/literal}{i18n('Delete this entry from database.')}{literal}',
									deleteConfirmation: '{/literal}{i18n('Are you sure?')}{literal}',
									deletedElementTitle: '{/literal}{i18n('This element has been deleted.')}{literal}',
									breakEditLinkTitle: '{/literal}{i18n('Cancel')}{literal}'
								}
							});
						});
					{/literal}
				</script>
				
				<div class="tbl2">
					<div class="formLabel grid_6">{i18n('Keywords:')}</div>
					<div class="formField grid_4">
						{section=keywords}
							<input type="text" name="keywords[]" value="{$keywords}" class="tag">
						{sectionelse}
							<input type="text" name="keywords[]" value="{$keywords}" class="tag">
						{/section}
					</div>
					<div class="formField grid_2">&nbsp;</div>
					<div class="clear"></div>
				</div>
				<div class="tbl2">
					<div class="grid_6 formLabel"><label for="Thumbnail">{i18n('Thumbnail:')}</label></div>
					{if ! $thumbnail}
					<div class="grid_4 formField"><input type="file" name="thumbnail" value="{$thumbnail}" id="Thumbnail" /></div>
					{else}
					<div class="grid_4 formField"><img src="{$ADDR_UPLOAD}images/{$thumbnail}">
						<p><input type="checkbox" name="delete_thumbnail" />Usuń aktualną miniaturkę</p>
						<input type="hidden" name="thumbnail" value="{$thumbnail}" />
					</div>
					{/if}
				</div>
				<div class="tbl1">
					<div class="formField grid_12">{i18n('Preview:')}</div>
					<div class="formField grid_12"><textarea cols="80" name="preview" id="PreviewCKE" rows="3">{$preview}</textarea></div>
					<div class="formField grid_1">&nbsp;</div>
					<div class="clear"></div>
				</div>
				<script>
					{literal}
						var editor = CKEDITOR.replace('PreviewCKE', {
							extraPlugins : 'docprops',
							uiColor: '#4B4B4B'
						});
					{/literal}
				</script>
				<div class="tbl2">
					<div class="formField grid_12">{i18n('Content:')}</div>
					<div class="formField grid_12"><textarea cols="80" name="content" id="ContentCKE" rows="3">{$content}</textarea></div>
					<div class="formField grid_1">&nbsp;</div>
					<div class="clear"></div>
				</div>
				<script>
					{literal}
						var editor = CKEDITOR.replace('ContentCKE', {
							extraPlugins : 'docprops',
							uiColor: '#4B4B4B'
						});
					{/literal}
				</script>
				<div class="tbl Buttons">
					<div class="grid_2 center button-l">
						<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
					</div>
					<div class="grid_2 center button-r">
						<input type="hidden" name="save" value="yes" />
						<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
					</div>
				</div>
			</form>
			<script src="{$ADDR_ADMIN_PAGES_JS}pages.js"></script>
		{else}
			<script>
				{literal}
					$(document).ready(function() {
						$('.dataTable').dataTable({
							"bProcessing": true,
							"bServerSide": true,
							"sAjaxSource": "ajax/pages.php?page=entries",
							"bJQueryUI": true,
							"aLengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]]
						} );
					});
				{/literal}
			</script>
			<div class="tbl2 text-padding-h3">
				<span class="bold">Jesteś tutaj: </span>
				<a href="{$FILE_SELF}">Menu główne</a> &raquo; Wpisy
				<a href="{$FILE_SELF}?page=entries&amp;action=add" class="box-right"><strong>{i18n('Nowy wpis')}</strong></a>
			</div>

			<table id="TableOPT" class="dataTable">
				<thead>
					<tr>
						<th style="width:20%">{i18n('Id:')}</th>
						<th style="width:20%">{i18n('Name:')}</th>
						<th style="width:25%">{i18n('Opis:')}</th>
						<th style="width:25%">{i18n('Options:')}</th>
					</tr>
				</thead>
				<tbody class="tbl1 center border_bottom">
				</tbody>
			</table>
		{/if}
	{/if}
{else}
	<p class="red center" style="display:none">Moduł Stron treści nie został jeszcze skończony. Niektóre funkcje jak miniaturki czy zaawansowane ustawienia nie zostały jeszcze wdrożone poza Panelem admina. Mimo tego, publikacja treści w podstawowej formie powinna działać.</p>
	<div>
		<ul class="margin-l-10 margin-t-10">
			<li><a href="{$FILE_SELF}?page=entries" title="Zarządzanie wpisami">Zarządzanie wpisami</li>
			<li><a href="{$FILE_SELF}?page=categories" title="Zarządzanie kategoriami wpisów">Zarządzanie kategoriami wpisów</li>
			<li><a href="{$FILE_SELF}?page=types" title="Zarządzanie typami wpisów">Zarządzanie typami wpisów</a></li>
		</ul>
	</div>
{/if}