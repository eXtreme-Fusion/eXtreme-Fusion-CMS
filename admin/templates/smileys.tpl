<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Smileys')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="code">{i18n('Smiley Code:')}</label></div>
		<div class="formField grid_7"><input type="text" name="code" value="{$code}" id="code" class="num6"></div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="text">{i18n('Description:')}</label></div>
		<div class="formField grid_7"><input type="text" name="text" value="{$text}" id="text" class="num20"></div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="image">{i18n('Smiley image:')}</label></div>
		<div class="formField grid_7">
			<select name="image" id="image">
				{section=image}
					<option value="{$image.value}"{if $image.selected} selected="selected"{/if}>{$image.display}</option>
				{/section}
			</select>
		</div>
	</div>
	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
</form>
<h4>{i18n('Existing smileys')}</h4>
{if $smiley}
	<div class="tbl2">
		<div class="sep_1 grid_4 bold">{i18n('Smiley Code:')}</div>
		<div class="grid_2 bold">{i18n('Description:')}</div>
		<div class="grid_2 bold">{i18n('Smiley image:')}</div>
		<div class="grid_2 bold">{i18n('Options:')}</div>
	</div>
	{section=smiley}
		<div class="tbl {$smiley.row_color}">
			<div class="sep_1 grid_4">{$smiley.code}</div>
			<div class="grid_2">{$smiley.text}</div>
			<div class="grid_2"><img src="{$ADDR_IMAGES}smiley/{$smiley.image}" alt="{$smiley.text}" /></div>
			<div class="grid_2">
				<a href="{$FILE_SELF}?action=edit&amp;id={$smiley.id}" class="tip" title="{i18n('Edit')}">
					<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
				</a> 
				<a href="{$FILE_SELF}?action=delete&amp;id={$smiley.id}" class="tip confirm_button" title="{i18n('Delete')}">
					<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
				</a>
			</div>
		</div>
	{/section}
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('There are no defined smiley.')}</div>
	</div>
{/if}