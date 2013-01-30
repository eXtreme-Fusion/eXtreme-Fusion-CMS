<h3>{$SystemVersion} - {i18n('News')} || {i18n('Section')} - {i18n($page)}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<div class="tbl AdminButtons">
	<div class="center grid_2 button-l">
		{if $page === 'news'}
			<span class="Cancels"><strong>{i18n('News')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=news"><strong>{i18n('News')}</strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-r">
		{if $page === 'cats'}
			<span class="Cancels"><strong>{i18n('Cats')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=cats"><strong>{i18n('Cats')}</strong></a></span>
		{/if}
	</div>
</div>
<hr />
{if $page === 'news'}
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

	<form id="This" class="tagform-full" action="{$URL_REQUEST}" method="post">
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="Title">{i18n('News title:')}</label></div>
			<div class="formField grid_4">
				<input type="text" id="Title" name="title" maxlength="200" class="num_200" value="{$title}" />
			</div>
			<div class="formField grid_2">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="access">{i18n('Visible for:')}</label></div>
			<div class="formField grid_4">
				<select name="access[]" multiple id="access" class="select-multi" size="5">
					{section=access}
						<option value="{$access.value}"{if $access.selected} selected="selected"{/if}>{$access.display}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="Category">{i18n('News category:')}</label></div>
			<div class="formField grid_4">
				<select name="category" class="textbox">
					{section=category}
						<option value="{$category.value}"{if $category.selected} selected="selected"{/if}>{$category.display}</option>
					{/section}
				</select>
			</div>
			<div class="formField grid_2">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="language">{i18n('Language:')}</label></div>
			<div class="formField grid_4">
				<select name="language" class="textbox">
					{section=language}
						<option value="{$language.value}"{if $language.selected} selected="selected"{/if}>{i18n($language.display)}</option>
					{/section}
				</select>
			</div>
			<div class="formField grid_2">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<div class="tbl1">
			<div class="formField grid_12">{i18n('News content:')}</div>
			<div class="formField grid_12"><textarea cols="80" name="content" id="ContentCKE" rows="3">{$content}</textarea></div>
			<div class="formField grid_2">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<script type="text/javascript">
			{literal}
			var editor = CKEDITOR.replace( 'ContentCKE',{
					extraPlugins : 'docprops',
					uiColor: '#4B4B4B'
				});
			{/literal}
		</script>
		<div class="tbl2">
			<div class="formField grid_12">{i18n('Extended content:')}</div>
			<div class="formField grid_12"><textarea cols="80" name="content_extended" id="ContentExtendedCKE" rows="3">{$content_extended}</textarea></div>
			<div class="formField grid_1">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<script type="text/javascript">
			{literal}
				var editor = CKEDITOR.replace( 'ContentExtendedCKE',{
					extraPlugins : 'docprops',
					uiColor: '#4B4B4B'
				});
			{/literal}
		</script>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="Keyword">{i18n('Keywords:')}</label></div>
			<div class="formField grid_4">
			<p>
				{section=keyword}
					<input type="text" name="tag[]" id="Keyword" value="{$keyword}" class="tag">
				{sectionelse}
					<input type="text" name="tag[]" id="Keyword" value="{$keyword}" class="tag">
				{/section}
			</p></div>
			<div class="formField grid_2">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="Description">{i18n('Description:')}</label></div>
			<div class="formField grid_4"><textarea cols="5" name="description" id="Description" rows="3">{$description}</textarea></div>
			<div class="formField grid_2">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<div class="tbl2">
			<div class="formLabel grid_6"><label for="Source">{i18n('News source:')}</label></div>
			<div class="formField grid_4"><input type="text" id="Source" name="source" maxlength="200" class="num_200" value="{$source}" /></div>
			<div class="formField grid_2">&nbsp;</div>
			<div class="clear"></div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="Comments">{i18n('Enable comments')}</label></div>
			<div class="formField grid_1"><label><input type="checkbox" name="allow_comments" value="1" {if $allow_comments == 1} checked="checked"{/if} /></label></div>
			<div class="clear"></div>
		</div>
		<div class="tbl12">
			<div class="formLabel grid_6"><label for="Draft">{i18n('Save as a draft')}</label></div>
			<div class="formField grid_1"><label><input type="checkbox" name="draft" value="1" {if $draft == 1} checked="checked"{/if} /></label></div>
			<div class="clear"></div>
		</div>
		<div class="tbl1">
			<div class="formLabel grid_6"><label for="Sticky">{i18n('Make sticky')}</label></div>
			<div class="formField grid_1"><label><input type="checkbox" name="sticky" value="1" {if $sticky == 1} checked="checked"{/if} /></label></div>
			<div class="clear"></div>
		</div>
		<div class="tb1 AdminButtons">
			<div class="center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<input type="hidden" name="save" value="yes" />
				<span id="SendForm_This" class="Save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	</form>
	<div class="clear"></div><br />

	<h3 class="ui-corner-all">{i18n('Current news')}</h3>
	<div class="tbl2">
		<div class="sep_1 grid_3 bold">{i18n('News title:')}</div>
		<div class="grid_3 bold">{i18n('Publication date:')}</div>
		<div class="grid_2 bold">{i18n('Author:')}</div>
		<div class="grid_3 bold">{i18n('Options')}</div>
	</div>
	{section=news_list}
		<div class="tbl {$news_list.row_color}">
			<div class="sep_1 grid_3 bold">{$news_list.title}</div>
			<div class="grid_3 bold">{$news_list.date}</div>
			<div class="grid_2 bold">{$news_list.author}</div>
			<div class="grid_3 bold">
				<a href="{$FILE_SELF}?page=news&action=edit&id={$news_list.id}" class="tip" title="{i18n('Edit')}"><img src="{$ADDR_ADMIN_IMAGES}icons/edit.png" alt="{i18n('Edit')}" /></a>
				<a href="{$FILE_SELF}?page=news&action=delete&id={$news_list.id}" class="tip confirm_button" title="{i18n('Delete')}"><img src="{$ADDR_ADMIN_IMAGES}icons/delete.png" alt="{i18n('Delete')}" /></a></div>
		</div>
	{/section}
	{$page_nav}
{/if}

{if $page === 'cats'}
	<form id="This" action="{$URL_REQUEST}" method="post">
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="cat_name">{i18n('Category name:')}</label></div>
			<div class="formField grid_7"><input type="text" name="cat_name" value="{$cat_name}" id="cat_name" rows="1" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="cat_image">{i18n('Category image:')}</label></div>
			<div class="formField grid_7">
				<select name="cat_image" id="cat_image">
					{section=cat_image}
						<option value="{$cat_image.value}"{if $cat_image.selected} selected="selected"{/if}>{$cat_image.display}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl1">
			<div class="grid_4 right">{i18n('Category language:')}</div>
			<div class="grid_6 left">{$language}<br /><small>{i18n('Same as a site language.')}</small></div>
		</div>
		<div class="tb2 AdminButtons">
			<div class="center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<input type="hidden" name="save" value="yes" />
				<span id="SendForm_This" class="Save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	</form>
	<h4>{i18n('Existing news categories')}</h4>
	{if $cat}
		<div class="tbl2">
			<div class="sep_2 grid_4 bold">{i18n('Category name:')}</div>
			<div class="grid_2 bold">{i18n('Category image:')}</div>
			<div class="grid_2 bold">{i18n('Options:')}</div>
		</div>
		{section=cat}
			<div class="tbl {$cat.row_color}">
				<div class="sep_2 grid_4">{$cat.name}</div>
				<div class="grid_2"><img src="{$ADDR_IMAGES}news_cats/{$cat.locale}/{$cat.image}" alt="{$cat.name}"></div>
				<div class="grid_2">
					<a href="{$FILE_SELF}?page=cats&action=edit&amp;id={$cat.id}" class="tip" title="{i18n('Edit')}">
						<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
					</a>
					<a href="{$FILE_SELF}?page=cats&action=delete&id={$cat.id}" class="tip confirm_button" title="{i18n('Delete')}">
						<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
					</a>
				</div>
			</div>
		{/section}
	{else}
		<div class="tbl2">
			<div class="sep_1 grid_10 center">{i18n('There are no existing news categories.')}</div>
		</div>
	{/if}
{/if}
