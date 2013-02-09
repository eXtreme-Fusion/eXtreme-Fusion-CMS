<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Frequently Asked Questions')}</h3>
{if $config.development}<div class="error">{i18n($config.developmentMessage)}</div>{/if}
{if $message}<div class="{$class}">{$message}</div>{/if}

{if $manage} 
	<h4>{i18n('Questions list')}</h4>
		{if $data}
			<div class="tbl2">
				<div class="grid_1 bold">{i18n('No.')}</div>
				<div class="grid_2 bold">{i18n('Quick edit')}</div>
				<div class="grid_7 bold">{i18n('Question')}</div>
				<div class="grid_2 bold">{i18n('Options')}</div>
			</div>
			
			{section=data}
			<div class="tbl1 {$data.row_color}">
				<div class="grid_1">{$data.no}.</div>
				<div class="grid_2">
					<a href="{$FILE_SELF}?page=manage&action=status&id={$data.id}&val={if $data.status == 1}0{else}1{/if}" class="tip" title="{if $data.status == 1}{i18n('Change to: Visible for all')}{else}{i18n('Change to: Visible only for users')}{/if}">
						<img src="{$ADDR_ADMIN_ICONS}pixel/note.png" {if $data.status == 0}class="icon" style="vertical-align:baseline"{/if} alt="{i18n('Visibility')}">
					</a>
					<a href="{$FILE_SELF}?page=manage&action=sticky&id={$data.id}&val={if $data.sticky == 1}0{else}1{/if}" class="tip" title="{if $data.sticky == 1}{i18n('Make normal')}{else}{i18n('Make sticky')}{/if}">
						<img src="{$ADDR_ADMIN_ICONS}pixel/paper-clip.png" {if $data.sticky == 0}class="icon" style="vertical-align:baseline"{/if} alt="{i18n('Make sticky')}">
					</a>
					<a href="{$FILE_SELF}?page=manage&action=comments&id={$data.id}&val={if $data.comments == 1}0{else}1{/if}" class="tip" title="{if $data.comments == 1}{i18n('Block comments')}{else}{i18n('Allow comments')}{/if}">
						<img src="{$ADDR_ADMIN_ICONS}pixel/quote.png" {if $data.comments == 0}class="icon" style="vertical-align:baseline"{/if} alt="{i18n('Comments')}">
					</a>
				&nbsp;
				</div>
				<div class="grid_7"><a href="{$FILE_SELF}?page=add&action=edit&id={$data.id}" title="{$data.question_long}">{$data.question_short}</a></div>
				<div class="grid_2"><a href="{$FILE_SELF}?page=add&action=edit&id={$data.id}" class="tip" title="{i18n('Edit')}">
							<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
						</a>
						<a href="{$FILE_SELF}?page=manage&action=delete&id={$data.id}" class="tip" title="{i18n('Delete')}">
							<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
						</a></div>
			</div>
			{/section}
		{else}

	
		<div class="tbl1">
			<div class="center">{i18n('There are no questions.')}</div>
		</div>
		
		{/if}
	
		<div class="tbl Buttons">
			<div class="center grid_2 button-l">
				<span class="Cancel" href="{$FILE_SELF}"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<span><a href="{$FILE_SELF}?page=add"><strong>{i18n('Add question')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></a></span>
			</div>
		</div>
{elseif $add}
	<form id="This" action="{$URL_REQUEST}" method="post">
		
		<h4>{i18n('Add question')}</h4>
		
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="question">{i18n('Question:')}</label></div>
			<div class="formField grid_7"><input type="text" name="question" id="question" value="{$faq.question}" class="num_400"/></div>
		</div>
		
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="answer">{i18n('Answer:')}</label></div>
			<div class="formField grid_7">
				<textarea name="answer" id="answer" rows="3" class="resize" >{$faq.answer}</textarea>
				{section=bbcode}
					<button type="button" onClick="addText('{$bbcode.textarea}', '[{$bbcode.value}]', '[/{$bbcode.value}]', 'This');" title="{$bbcode.description}" class="tip"><img src="{$bbcode.image}"></button>
				{/section}
			</div>
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
{else}
	<form id="This" action="{$URL_REQUEST}" method="post">
		
		<h4>{i18n('General')}</h4>

		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="Title">{i18n('Title:')}</label></div>
			<div class="formField grid_7"><input type="text" id="Title" name="title" class="num_128" value="{$title}" ></div>
		</div>
		
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="Description">{i18n('Description:')}</label></div>
			<div class="formField grid_7"><textarea name="description" id="Description" rows="5" class="resize" >{$description}</textarea></div>
		</div>
		
		<div class="tbl Buttons">
			<div class="center grid_4 button-l button-r">
				<span><a href="{$FILE_SELF}?page=manage"><strong>{i18n('Manage questions')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></a></span>
			</div>
		</div>
		
		<h4>{i18n('Display settings')}</h4>
		
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3">{i18n('Display style:')}</div>
			<div class="formField grid_7">
			
				<div class="tbl1"><div class="formField grid_7">
					<label><input type="radio" name="display" value="1" {if $display == 1}checked="checked"{/if} id="Display">{i18n(' Questions inline')}</label></div></div>
				<div class="tbl1"><div class="formField grid_7">
					<label><input type="radio" name="display" value="2" {if $display == 2}checked="checked"{/if} id="Display">{i18n(' Clicking on question takes user to answer further down the page')}</label></div></div>
				<div class="tbl1"><div class="formField grid_7">
					<label><input type="radio" name="display" value="3" {if $display == 3}checked="checked"{/if} id="Display">{i18n(' Clicking on question opens/hides answer under question')}</label></div></div>
				<div class="tbl1"><div class="formField grid_7">	
					<label><input type="radio" name="display" value="4" {if $display == 4}checked="checked"{/if} id="Display">{i18n(' Clicking on question opens the answer in a new page')}</label></div></div>
			
			</div>
		</div>
		
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3">{i18n('Listing style:')}</div>
			<div class="formField grid_2"><label><input type="radio" name="listing" value="1" {if $listing == 1} checked="checked"{/if} > {i18n('Numbered list')}</label></div>
			<div class="formField grid_4"><label><input type="radio" name="listing" value="0" {if $listing == 0} checked="checked"{/if} > {i18n('Unnumbered list')}</label></div>
		</div>
		
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3">{i18n(' Show links below answers:')}<small>{i18n('This enable the displays of links "Read more" and "Add comment" below answer.')}</small></div>
			<div class="formField grid_1"><label><input type="radio" name="links" value="1" {if $links == 1} checked="checked"{/if} > {i18n('Yes')}</label></div>
			<div class="formField grid_6"><label><input type="radio" name="links" value="0" {if $links == 0} checked="checked"{/if} > {i18n('No')}</label></div>
		</div>
		
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="Back">{i18n('"Back to Top" link text:')}</label></div>
			<div class="formField grid_7"><input type="text" id="Back" name="back" class="num_32" value="{$back}" ></div>
		</div>
		
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="Sorting">{i18n('Default sorting for unnumbered list:')}</label></div>
			<div class="formField grid_7">
				<select name="sorting" id="Sorting" class="textbox" />
				<option value='asc' {if $sorting == "asc"} selected="selected"{/if}>{i18n('ascending')}</option>
				<option value='desc' {if $sorting == "desc"} selected="selected"{/if}>{i18n('descending')}</option>
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
{/if}