<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Master Download')}- {i18n('Navigation')}</h3>
{if $config.development}<div class="error">{i18n($config.developmentMessage)}</div>{/if}
	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			{if $mdp_files == 1}
				<span class="Cancels"><strong>{i18n('Files')}</strong></span>
			{else}
				<span><a href="{$FILE_SELF}?page=mdp_files"><strong>{i18n('Files')}</strong></a></span>
			{/if}
		</div>
		<div class="center grid_2 button-c">
			{if $mdp_cats == 1}
				<span class="Cancels"><strong>{i18n('Categories')}</strong></span>
			{else}
				<span><a href="{$FILE_SELF}?page=mdp_cats"><strong>{i18n('Categories')}</strong></a></span>
			{/if}
		</div>
		<div class="center grid_2 button-c">
			{if $mdp_sub_cats == 1}
				<span class="Cancels"><strong>{i18n('Subcategories')}</strong></span>
			{else}
				<span><a href="{$FILE_SELF}?page=mdp_subcats"><strong>{i18n('Subcategories')}</strong></a></span>
			{/if}
		</div>
		<div class="center grid_2 button-r">
			{if $mdp_info == 1}
				<span class="Cancels"><strong>{i18n('Information')}</strong></span>
			{else}
				<span><a href="{$FILE_SELF}?page=mdp_info"><strong>{i18n('Information')}</strong></a></span>
			{/if}
		</div>
	</div>
	
{if $mdp_files}
	{if $message}<div class="{$class}">{$message}</div>{/if}
	
	<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Master Download')} - {i18n('Files')}</h3>

	<form id="This" action="{$URL_REQUEST}" method="post">
			<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="FileName">{i18n('File name:')}</label></div>
			<div class="formField grid_7"><input type="text" name="file_name" value="{$name}" id="FileName" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="FileDesc">{i18n('File description:')}</label></div>
			<div class="formField grid_7"><textarea name="file_desc" id="FileDesc" rows="3" class="resize">{$desc}</textarea></div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="FileSubcat">{i18n('Category:')}</label></div>
			<div class="formField grid_7">
				<select name="file_subcat" id="FileSubcat" class="textbox" />
					{section=cat_list}
						<option value="{$cat_list.value}"{if $cat_list.value == $subcat} selected="selected"{/if}>{$cat_list.entry}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="FileURL">{i18n('File URL:')}</label></div>
			<div class="formField grid_7"><input type="text" name="file_url" value="{$url}" id="FileURL" /></div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="FileIMG">{i18n('File image:')}</label></div>
			<div class="formField grid_7"><input type="text" name="file_img" value="{$img}" id="FileIMG" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="FileSize">{i18n('File size:')}<br /><small>{i18n('(in kb)')}</small></label></div>
			<div class="formField grid_7"><input type="text" name="file_size" value="{$size}" id="FileSize" class="num_8"/></div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="FileMirror"><div>{i18n('Mirrors:')}</div><div><small>{i18n('One mirror - one line.')}</small></div></label></div>
			<div class="formField grid_7"><textarea name="file_mirror" id="FileMirror" rows="3" class="resize">{$mirror}</textarea></div>
		</div>
		<div class="tbl Buttons">
			<div class="center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<input type="hidden" name="add_file" value="yes" />
				<span class="save" id="SendForm_This"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
			</div>
		</div>
	</form>
	
	<h4>{i18n('File list')}</h4>
	{if $file}
		<div class="tbl2">
			<div class="sep_1 grid_2 bold">{i18n('Name')}</div>
			<div class="grid_2 bold">{i18n('Category')}</div>
			<div class="grid_2 bold">{i18n('Size')}</div>
			<div class="grid_2 bold">{i18n('Date')}</div>
			<div class="grid_2 bold">{i18n('Options')}</div>
		</div>
		{section=file}
			<div class="tbl {$file.row_color}">
				<div class="sep_1 grid_2"><span class="tip" title="{i18n('Description:')} {$file.desc}">{$file.name}</span></div>
				<div class="grid_2">{$file.cat}</div>
				<div class="grid_2">{$file.size} kb</div>
				<div class="grid_2">{$file.date}</div>
				<div class="grid_2">
					<a href="{$FILE_SELF}?page=mdp_files&amp;action=edit&amp;file_id={$file.id}" class="tip" title="{i18n('Edit')}">
						<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
					</a>
					<a href="{$FILE_SELF}?page=mdp_files&amp;action=delete&amp;file_id={$file.id}" class="tip confirm_button" title="{i18n('Delete')}">
						<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
					</a>
				</div>
			</div>
		{/section}
		{$page_nav}
	{else}
		<div class="tbl2">
			<div class="sep_1 grid_10 center">{i18n('There are no files.')}</div>
		</div>
	{/if}
{/if}


{if $mdp_cats}
	{if $message}<div class="{$class}">{$message}</div>{/if}

	{if $mdp_cats_error}
		<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Master Download')} - {i18n('Delete error')}</h3>
			<div class="error">{i18n('You can not delete this category because it is not empty.')}</div>
	{else}

		<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Master Download')} - {i18n('Categories')}</h3>

		<form id="This" action="{$URL_REQUEST}" method="post">
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="CatName">{i18n('Category name:')}</label></div>
				<div class="formField grid_7"><input type="text" name="cat_name" value="{$name}" id="FileName" /></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_3"><label for="CatDesc">{i18n('Category description:')}</label></div>
				<div class="formField grid_7"><textarea name="cat_desc" id="CatDesc" rows="3" class="resize">{$desc}</textarea></div>
			</div>
			<div class="tbl Buttons">
				<div class="center grid_2 button-l">
					<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
				</div>
				<div class="center grid_2 button-r">
					<input type="hidden" name="add_cat" value="yes" />
					<span class="save" id="SendForm_This"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
				</div>
			</div>
		</form>
	
		<h4>{i18n('Category')}</h4>
		{if $cat}
			<div class="tbl2">
				<div class="sep_2 grid_4 bold">{i18n('Name')}</div>
				<div class="grid_2 bold">{i18n('Options')}</div>
			</div>
			{section=cat}
				<div class="tbl {$cat.row_color}">
					<div class="sep_2 grid_4"><span class="tip" title="{i18n('Description:')} {$cat.desc}">{$cat.name}</span></div>
					<div class="grid_2">
						<a href="{$FILE_SELF}?page=mdp_cats&amp;action=edit&amp;cat_id={$cat.id}" class="tip" title="{i18n('Edit')}">
							<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
						</a> 
						<a href="{$FILE_SELF}?page=mdp_cats&amp;action=delete&amp;cat_id={$cat.id}" class="tip confirm_button" title="{i18n('Delete')}">
							<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
						</a>
					</div>
				</div>
			{/section}
		{else}
			<div class="tbl2">
				<div class="sep_1 grid_10 center">{i18n('There are no categories.')}</div>
			</div>
		{/if}
	{/if}
{/if}


{if $mdp_sub_cats}
	{if $message}<div class="{$class}">{$message}</div>{/if}

	{if $mdp_sub_cats_error}
		<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Master Download')} - {i18n('Delete Error')}</h3>
			<div class="error">{i18n('You can not delete this subcategory, because it is not empty.')}</div>
	{else}

		<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Master Download')} - {i18n('Subcategories')}</h3>

		<form id="This" action="{$URL_REQUEST}" method="post">
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="Name">{i18n('Subcategory name:')}</label></div>
				<div class="formField grid_7"><input type="text" name="name" value="{$name}" id="Name" /></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_3"><label for="Desc">{i18n('Subcategory description:')}</label></div>
				<div class="formField grid_7"><textarea name="Desc" id="desc" rows="3" class="resize">{$desc}</textarea></div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="Cat">{i18n('Category:')}</label></div>
				<div class="formField grid_7">
					<select name="cat" id="Cat" class="textbox" />
						{section=cat_list}
							<option value="{$cat_list.value}"{if $cat_list.value == $cat} selected="selected"{/if}>{$cat_list.entry}</option>
						{/section}
				</select></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_3"><label for="ViewAccess"><div>{i18n('Visible for:')}</div><div><small>{i18n('Default <em>Guest</em>')}</small></div></label></div>
				<div class="formField grid_7">
					<select name="view_access[]" multiple id="ViewAccess" class="select-multi" size="5">
						{section=access_view}
							<option value="{$access_view.value}"{if $access_view.selected} selected="selected"{/if}>{$access_view.display}</option>
						{/section}
					</select>
				</div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="GetAccess"><div>{i18n('Avaible to download:')}</div><div><small>{i18n('Default <em>User</em>')}</small></div></label></div>
				<div class="formField grid_7">
					<select name="get_access[]" multiple id="GetAccess" class="select-multi" size="5">
						{section=access_get}
							<option value="{$access_get.value}"{if $access_get.selected} selected="selected"{/if}>{$access_get.display}</option>
						{/section}
					</select>
				</div>
			</div>
			<div class="tbl Buttons">
				<div class="center grid_2 button-l">
					<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
				</div>
				<div class="center grid_2 button-r">
					<input type="hidden" name="add_sub_cat" value="yes" />
					<span class="save" id="SendForm_This"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
				</div>
			</div>
		</form>
		
		<h4>{i18n('Subcategories list')}</h4>
		{if $subcat}
			<div class="tbl2">
				<div class="sep_1 grid_2 bold">{i18n('Name')}</div>
				<div class="grid_2 bold">{i18n('Category')}</div>
				<div class="grid_2 bold">{i18n('Access')}</div>
				<div class="grid_2 bold">{i18n('Downloading')}</div>
				<div class="grid_2 bold">{i18n('Options')}</div>
			</div>
			{section=subcat}
				<div class="tbl {$subcat.row_color}">
					<div class="sep_1 grid_2"><span class="tip" title="{i18n('Description:')} {$subcat.desc}">{$subcat.name}</span></div>
					<div class="grid_2">{$subcat.cat}</div>
					<div class="grid_2">{$subcat.view_access}</div>
					<div class="grid_2">{$subcat.get_access}</div>
					<div class="grid_2">
						<a href="{$FILE_SELF}?page=mdp_subcats&amp;action=edit&amp;subcat_id={$subcat.id}" class="tip" title="{i18n('Edit')}">
							<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
						</a> 
						<a href="{$FILE_SELF}?page=mdp_subcats&amp;action=delete&amp;subcat_id={$subcat.id}" class="tip confirm_button" title="{i18n('Delete')}">
							<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
						</a>
					</div>
				</div>
			{/section}
		{else}
			<div class="tbl2">
				<div class="sep_1 grid_10 center">{i18n('There are no subcategories.')}</div>
			</div>
		{/if}
	{/if}
{/if}

{if $mdp_info}
	<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Master Download')} - {i18n('Information')}</h3>
		<div class="tbl">
			<div class="sep_2 grid_8 center">{i18n('MD_Info')}</div>
		</div>

	<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Master Download')} - {i18n('Authors')}</h3>
		<div class="tbl">
			<div class="sep_2 grid_8 center"><strong>Master Download Panel 1.2.1</strong> (EF IV)<br /><br />
				Copyright &copy; 2006 by Bartłomiej (M@ster) Baron<br />
				<a href="http://www.bbproject.net" target="_blank">http://www.bbproject.net</a>
			</div>
		</div>
		<div class="tbl"><hr /></div>
		<div class="tbl">
			<div class="sep_2 grid_8 center"><strong>Master Download Panel 1.0</strong> (EF V)<br /><br />
				Copyright &copy; 2012 by eXtreme-Fusion<br />
				<a href="http://www.extreme-fusion.org" target="_blank">eXtreme Crew</a>
			</div>
		</div>
{/if}

{if $mdp_error_files}
	<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Master Download')} - {i18n('Files error')}</h3>
		<div class="error">{i18n('There are no subcategories.')}</div>
{/if}

{if $mdp_error_sub_cats}
	<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Master Download')} - {i18n('Subcategory error')}</h3>
		<div class="error">{i18n('There are no categories.')}</div>
{/if}