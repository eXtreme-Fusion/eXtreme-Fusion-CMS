<h3>{$SystemVersion} - {i18n('Warning')} || {i18n('Section')} - {i18n($page)}</h3>
{if $config.development}<div class="error">{i18n($config.developmentMessage)}</div>{/if}
{if $message}<div class="{$class}">{$message}</div>{/if}

<div class="tbl AdminButtons">
	<div class="center grid_2 button-l">
		{if $page === 'cats'}
			<span class="Cancels"><strong>{i18n('Categories')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=cats"><strong>{i18n('Categories')}</strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-c">
		{if $page === 'warnings'}
			<span class="Cancels"><strong>{i18n('warnings')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=warnings"><strong>{i18n('warnings')}</strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-r">
		{if $page === 'sett'}
			<span class="Cancels"><strong>{i18n('Settings')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=sett"><strong>{i18n('Settings')}</strong></a></span>
		{/if}
	</div>
</div>
<hr />

{if $page === 'cats'}
	<form id="This" action="{$URL_REQUEST}" method="post">
		<div class="tbl1">
			<div class="formLabel sep_1 grid_2"><label for="title">{i18n('Title:')}</label></div>
			<div class="formField grid_6"><input type="text" name="title" value="{$title}" id="title" rows="1" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_2"><label for="description">{i18n('Description:')}</label></div>
			<div class="formField grid_6"><textarea name="description" id="description" rows="3" class="resize">{$description}</textarea></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_2"><label for="period">{i18n('Period:')}</label></div>
			<div class="formField grid_6">
				<select name="period" class="textbox" id="period">
					{section=periods}
					 asdasdad {$periods.value} / {$period}
						<option value="{$periods.value}" {if $periods.value == $period}selected="selected"{/if}>{$periods.description}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl AdminButtons">
			<div class="center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<input type="hidden" name="save" value="yes" />
				{if $id}<input type="hidden" name="edit" value="{$id}" />{/if}
				<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	</form>
	<h4>{i18n('Existing categories')}</h4>
	{if $cats_list}
		<div class="tbl2">
			<div class="sep_1 grid_2 bold">{i18n('Title:')}</div>
			<div class="grid_5 bold">{i18n('Description:')}</div>
			<div class="grid_2 bold">{i18n('Period:')}</div>
			<div class="grid_2 bold">{i18n('Options:')}</div>
		</div>
		<div lass="tbl1">
			{section=cats_list}
				<div class="tbl2">
					<div class="sep_1 grid_2">
						{$cats_list.title}
					</div>
					<div class="grid_5">
						{$cats_list.description}
					</div>
					<div class="grid_2">
						{$cats_list.period}
					</div>
					<div class="grid_2">
						<a href="{$FILE_SELF}?page=cats&amp;action=edit&amp;id={$cats_list.id}" class="tip" title="{i18n('Edit')}">
							<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
						</a> 
						<a href="{$FILE_SELF}?page=cats&amp;action=delete&amp;id={$cats_list.id}" class="tip confirm_button" title="{i18n('Delete')}">
							<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
						</a>
					</div>
				</div>
			{/section}
		</div>
	{else}
		<div class="tbl2">
			<div class="info">{i18n('There are no categories.')}</div>
		</div>
	{/if}
{/if}

{if $page === 'warnings'}
	<div class="center red">
		<p>W poniższym polu możesz wpisać login, adres e-mail, adres IP lub ID użytkownika, którego kontem chcesz zarządzać.</p>
		<p>Wyświetli się lista podpowiedzi, z której należy wybrać właściwy element.</p>
	</div>
	<div class="buttons-bg">&nbsp;</div>
	<div class="tbl">
		<div class="center-box"><label for="search_user">{i18n('Pole wyszukiwania użytkownika')}</label></div>
		<div class="formField center-box">
			<input type="text" id="search_user" name="search_user" class="width_3"/>
			<span id="search_user_result"></span>
		</div>
		
	</div>

	<div class="buttons-bg">&nbsp;</div>

	{if $action === users}
		<script>
			{literal}
				$(function()
				{
					$("#expiry").datepicker(
					{
						showWeek: true,
						numberOfMonths: 2,
						showButtonPanel: true,
						dateFormat: 'dd.mm.yy',
						firstDay: 1,
						autoSize: true,
						closeText: "{/literal}{i18n('Done')}{literal}",
						currentText: "{/literal}{i18n('Today')}{literal}",
						weekHeader: "{/literal}{i18n('Week')}{literal}",
						dayNamesMin: ["{/literal}{i18n('Su')}{literal}", "{/literal}{i18n('Mo')}{literal}", "{/literal}{i18n('Tu')}{literal}", "{/literal}{i18n('We')}{literal}", "{/literal}{i18n('Th')}{literal}", "{/literal}{i18n('Fr')}{literal}", "{/literal}{i18n('Sa')}{literal}"],
						monthNames: ["{/literal}{i18n('Januar')}{literal}", "{/literal}{i18n('Februar')}{literal}", "{/literal}{i18n('Marts')}{literal}", "{/literal}{i18n('April')}{literal}", "{/literal}{i18n('Maj')}{literal}", "{/literal}{i18n('Juni')}{literal}", "{/literal}{i18n('Juli')}{literal}", "{/literal}{i18n('August')}{literal}", "{/literal}{i18n('September')}{literal}", "{/literal}{i18n('Oktober')}{literal}", "{/literal}{i18n('November')}{literal}", "{/literal}{i18n('December')}{literal}"]					
					});
				});
			{/literal}
		</script>
		
		<form id="This" action="{$URL_REQUEST}" method="post">
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="sender">{i18n('Sender:')}</label></div>
				<div class="formField grid_6">{$sender.name}<input type="hidden" name="sender" value="{$sender.id}" id="sender"/></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="guilty">{i18n('Quilty:')}</label></div>
				<div class="formField grid_6"><input type="text" name="guilty" value="{$guilty}" id="sender" rows="1" /></div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="cat">{i18n('Category:')}</label></div>
				<div class="formField grid_6">
					{if $cats}
					<select name="cat" id="cat">
						{section=cats}
							<option value="{$cats.id}"{if $cat == $cats.id} selected="selected"{/if}>{$cats.title} - {$cats.period}</option>
						{/section}
					</select>
					{else}
						<select name="cat" id="cat">
								<option>{i18n('Category was not created')}</option>
						</select>
					{/if}
				</div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="title">{i18n('Title:')}</label></div>
				<div class="formField grid_6"><input type="text" name="title" value="{$title}" id="title" rows="1" /></div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="description">{i18n('Description:')}</label></div>
				<div class="formField grid_6"><textarea name="description" id="description" rows="3" class="resize">{$description}</textarea></div>
			</div>
			<div class="tbl2">
				<div class="formLabel sep_1 grid_2"><label for="expiry">{i18n('Expiry:')}</label></div>
				<div class="formField grid_6"><input type="text" id="expiry" name="expiry" value="{$expiry}" rows="1" /></div>
			</div>
			<div class="tbl1">
				<div class="formLabel sep_1 grid_2"><label for="notification">{i18n('Notification:')}</label></div>
				<div class="formField grid_6">
					<input type="checkbox" name="notification" value="1" {if $notification == 1}checked=checked{/if} id="notification" />
				</div>
			</div>
			<div class="tbl AdminButtons">
				<div class="center grid_2 button-l">
					<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
				</div>
				<div class="center grid_2 button-r">
					<input type="hidden" name="save" value="yes" />
					{if $id}<input type="hidden" name="edit" value="{$id}" />{/if}
					<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
				</div>
			</div>
		</form>
		<h4>{i18n('Existing warnings')}</h4>
		{if $warnings_list}
			<div class="tbl2">
				<div class="sep_1 grid_1 bold">{i18n('Guilty:')}</div>
				<div class="grid_2 bold">{i18n('Title:')}</div>
				<div class="grid_3 bold">{i18n('Datestamp:')}</div>
				<div class="grid_3 bold">{i18n('Expiry:')}</div>
				<div class="grid_2 bold">{i18n('Options:')}</div>
			</div>
			<div lass="tbl1">
				{section=warnings_list}
					<div class="tbl2">
						<div class="sep_1 grid_1">
							{$warnings_list.guilty}
						</div>
						<div class="grid_2">
							{$warnings_list.title}
						</div>
						<div class="grid_3">
							{date("d.m.Y", $warnings_list.datestamp)}
						</div>
						<div class="grid_3">
							{date("d.m.Y", $warnings_list.expiry)}
						</div>
						<div class="grid_2">
							<a href="{$FILE_SELF}?page=warnings&amp;action=edit&amp;id={$warnings_list.id}" class="tip" title="{i18n('Edit')}">
								<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
							</a> 
							<a href="{$FILE_SELF}?page=warnings&amp;action=delete&amp;id={$warnings_list.id}" class="tip confirm_button" title="{i18n('Delete')}">
								<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
							</a>
						</div>
					</div>
				{/section}
			</div>
		{else}
			<div class="tbl2">
				<div class="info">{i18n('There are no warnings.')}</div>
			</div>
		{/if}
	{/if}
{/if}

{if $page === 'sett'}
	<form id="This" action="{$URL_REQUEST}" method="post">
		<div class="tbl1">
			<div class="info">{i18n('This section is under construction.')}</div>
		</div>
			
		<div class="tbl AdminButtons">
			<div class="center grid_2 button-l">
				<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
			</div>
			<div class="center grid_2 button-r">
				<input type="hidden" name="save" value="yes" />
				{if $id}<input type="hidden" name="edit" value="{$id}" />{/if}
				<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	</form>
{/if}

<script>var ADDR_SITE = '{$ADDR_SITE}';</script>
<script src="{$ADDR_ADMIN_PAGES_JS}users.js"></script>