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

<h3 class="ui-corner-all">{$SystemVersion} - {i18n('User Fields')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="Name">{i18n('Field name:')}</label></div>
		<div class="formField grid_7"><input type="text" name="name" value="{$name}" id="Name" /></div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="Index">{i18n('Field id:')}<br /><small>{i18n('ID of field in the database.')}</small></label></div>
		<div class="formField grid_7"><input type="text" name="index" value="{$index}" id="Index" /></div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="Cat">{i18n('Field cat:')}</label></div>
		<div class="formField grid_7">
			<select name="cat" class="textbox">
				{section=cat_list}
					<option value="{$cat_list.value}"{if $cat_list.selected} selected="selected"{/if}>{$cat_list.display}</option>
				{/section}
			</select>
		</div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="Type">{i18n('Field type:')}</label></div>
		<div class="formField grid_7">
			<select name="type" id="Type" class="textbox" />
				{section=type_list}
					<option value="{$type_list.value}"{if $type_list.selected} selected="selected"{/if}>{$type_list.display}</option>
				{/section}
			</select>
		</div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="Register">{i18n('Display at registration:')}</label></div>
		<div class="formField grid_7"><input type="checkbox" name="register" value="1" id="Register" {if $register == 1}checked="checked"{/if}> {i18n('Yes')}</div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="Hide">{i18n('Hidden field')}:<small>{i18n('Visible only for administrators.')}</small></label></div>
		<div class="formField grid_7"><input type="checkbox" name="hide" value="1" id="Hide" {if $hide == 1}checked="checked"{/if}> {i18n('Yes')}</div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="Edit">{i18n('Disable editing')}:<small>{i18n('Checking this field causes that users will not be able to edit data entered in this field.')}</small></label></div>
		<div class="formField grid_7"><input type="checkbox" name="edit" value="1" id="Edit" {if $edit == 1}checked="checked"{/if}> {i18n('Yes')}</div>
	</div>
	<div class="tbl2">
		<div class="formField sep_1 grid_10 center"><small><strong>{i18n('Attention!')}</strong> {i18n('These two fields should be filled only if you selected')} <em>{i18n('Select List.')}</em></small></div>
	</div>
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="Option">{i18n('Field options:')}<br /><small>{i18n('(each option in new line)')}</small></label></div>
		<div class="formField grid_7"><textarea name="option" id="Option" rows="3" class="resize">{$option}</textarea></div>
	</div>
	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></a>
		</div>
	</div>
</form>

<h4>{i18n('Current User Fields')}</h4>
{if $field}
	<div class="tbl2">
		<div class="grid_2 bold">{i18n('Field name:')}</div>
		<div class="grid_2 bold">{i18n('Field cat:')}</div>
		<div class="grid_2 bold">{i18n('Field type:')}</div>
		<div class="grid_2 bold">{i18n('Display at registration:')}</div>
		<div class="grid_1 bold"><span class="tip" title="{i18n('Visible only for administrators.')}"><u>{i18n('Hidden field')}</u></span></div>
		<div class="grid_1 bold"><span class="tip" title="{i18n('Checking this field causes that users will not be able to edit data entered in this field.')}"><u>{i18n('Disable editing')}</u></span></div>
		<div class="grid_2 bold">{i18n('Options:')}</div>
	</div>
	{section=field}
		<div class="tbl {$field.row_color}">
			<div class="grid_2">{$field.name}</div>
			<div class="grid_2">{$field.cat}</div>
			<div class="grid_2">{$field.type}</div>
			<div class="grid_2">
				{if $field.register == 1}
					{i18n('Yes')}
				{else}
					{i18n('No')}
				{/if}
			</div>
			<div class="grid_1">
				{if $field.hide == 1}
					{i18n('Yes')}
				{else}
					{i18n('No')}
				{/if}
			</div>
			<div class="grid_1">
				{if $field.edit == 1}
					{i18n('Yes')}
				{else}
					{i18n('No')}
				{/if}
			</div>
			<div class="grid_2">
				<a href="{$FILE_SELF}?action=edit&id={$field.id}" class="tip" title="{i18n('Edit')}">
					<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
				</a> 
				<a href="{$FILE_SELF}?action=delete&id={$field.id}" class="tip confirm_button" title="{i18n('Delete')}">
					<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
				</a>
			</div>
		</div>
	{/section}
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('There are no fields created.')}</div>
	</div>
{/if}
