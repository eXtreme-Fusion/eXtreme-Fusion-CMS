<h3 class="ui-corner-all">{$SystemVersion} - {i18n('User Fields Categories')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="CatName">{i18n('Category name:')}</label></div>
		<div class="formField grid_7"><input type="text" name="cat_name" value="{$cat_name}" id="CatName" rows="1" /></div>
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
<h4>{i18n('Current User Fields Categories')}</h4>
{if $data}
	<div id='ResponseUserFieldsCats' class='valid'></div>
	<div id='ListUserFieldsCats'>
		<ul>
			{section=data}
				<li class='sort' id='ArrayOrderUserFieldsCats_{$data.id}'>
					<div class="tbl2">
						<div class="formField sep_2 grid_5">{$data.name}</div>
						<div class="formField grid_2">
							<a href="{$FILE_FILE_SELF}?action=edit&id={$data.id}" class="tip" title="{i18n('Edit')}">
								<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
							</a> 
							<a href="{$FILE_FILE_SELF}?action=delete&id={$data.id}&order={$data.order}" class="tip confirm_button" title="{i18n('Delete')}">
								<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
							</a>
						</div>
					</div>
				</li>
			{/section}
		</ul>
	</div>
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('There are no user field category.')}</div>
		<div class="clear"></div>
	</div>
{/if}