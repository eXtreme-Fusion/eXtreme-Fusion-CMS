<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Polls')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="Question">{i18n('Question:')}</label></div>
		<div class="formField grid_7"><input type="text" name="Question" value="{$Question}" id="Question" /></div>
	</div>
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3"><label for="Response"><div>{i18n('Possible answers:')}</div><div><small>{i18n('One answer each lane.')}</small></div></label></div>
		<div class="formField grid_7"><textarea name="Response" id="Response" rows="3" class="resize">{$Response}</textarea></div>
	</div>
	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span class="save" id="SendForm_This"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
		</div>
	</div>
</form>
	
<h4>{i18n('Active polls')}</h4>
	{if $Data}
		<div class="tbl2">
			<div class="sep_1 grid_4 bold">{i18n('Question:')}</div>
			<div class="grid_4 bold">{i18n('Start date:')}</div>
			<div class="grid_2 bold">{i18n('Options:')}</div>
		</div>
		{section=Data}
			<div class="tbl {$Data.RowColor}">
				<div class="sep_1 grid_4">{$Data.Question}</div>
				<div class="grid_4">{$Data.DateStart}</div>
				<div class="grid_2">
					<a href="{$FILE_SELF}?action=edit&amp;poll_id={$Data.ID}" class="tip" title="{i18n('Edit')}">
						<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
					</a>
					<a href="{$FILE_SELF}?action=end&amp;poll_id={$Data.ID}" class="tip confirm_button" title="{i18n('End')}">
						<img src="{$ADDR_ADMIN_ICONS}logout.png" alt="{i18n('End')}" />
					</a>
				</div>
			</div>
		{/section}
	{else}
		<div class="tbl2">
			<div class="sep_1 grid_10 center">{i18n('There are no polls.')}</div>
		</div>
	{/if}
	
<h4>{i18n('Ended polls')}</h4>
	{if $End}
		<div class="tbl2">
			<div class="sep_1 grid_4 bold">{i18n('Question:')}</div>
			<div class="grid_2 bold">{i18n('Start date:')}</div>
			<div class="grid_2 bold">{i18n('End date:')}</div>
			<div class="grid_2 bold">{i18n('Options:')}</div>
		</div>
		{section=End}
			<div class="tbl {$End.RowColor}">
				<div class="sep_1 grid_4">{$End.Question}</div>
				<div class="grid_2">{$End.DateStart}</div>
				<div class="grid_2">{$End.DateEnd}</div>
				<div class="grid_2">
					<a href="{$FILE_SELF}?action=delete&amp;poll_id={$End.ID}" class="tip confirm_button" title="{i18n('Delete')}">
						<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
					</a>
				</div>
			</div>
		{/section}
	{else}
		<div class="tbl2">
			<div class="sep_1 grid_10 center">{i18n('There are no polls.')}</div>
		</div>
	{/if}