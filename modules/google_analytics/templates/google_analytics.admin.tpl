<h3>{i18n('Google Analytics ')} - {i18n('Navigation')}</h3>
<div class="capmain"></div>
<div class="tbl Buttons">
	<div class="center grid_2 button-l">
		{if $page === 'preview'}
			<span class="Cancels"><strong>{i18n('Podgląd')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=preview"><strong>{i18n('Podgląd')}</strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-r">
		{if $page === 'sett'}
			<span class="Cancels"><strong>{i18n('Ustawienia')}</strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=sett"><strong>{i18n('Ustawienia')}</strong></a></span>
		{/if}
	</div>
</div>

{if $page === 'preview'}
	{if ! $Error}
		<script type="text/javascript" charset="utf-8">
			{literal}
				$(document).ready(function() {
					$('.dataTable').dataTable({
						"aaSorting": [],
						"sAjaxSource": '../ajax/request.php',
						"bProcessing": true,
						"bDestroy": true,
						"bJQueryUI": true,
						"aLengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
						"sPaginationType": "full_numbers",
					} );
				});
			{/literal}
		</script>

		<h3>{i18n('Google Analytics ')} - {i18n('Report of')} {$updated_day} {i18n('hour')} {$updated_hour}</h3>
		<div class="grid_4"><strong>{i18n('Daily average visits:')}</strong> {$total_results}</div>
		<div class="grid_4"><strong>{i18n('Total visits:')}</strong> {$visits}</div>
		<div class="grid_4"><strong>{i18n('Total page views:')}</strong> {$page_views}</div>
		<div class="clear"></div><br />
		<table id="TableOPT" class="dataTable">
			<thead>
				<tr>
					<th style="width:17%">{i18n('Network domain')}</th>
					<th style="width:17%">{i18n('Source')}</th>
					<th style="width:15%">{i18n('Browser')}</th>
					<th style="width:15%">{i18n('Operating System')}</th>
					<th class="center" style="width:10%">{i18n('Country')}</th>
					<th class="center" style="width:15%">{i18n('Views')}</th>
					<th class="center" style="width:11%">{i18n('Visits')}</th>
				</tr>
			</thead>
			<tbody class="tbl2 border_bottom">
			</tbody>
		</table>
	{else}
		<h3>{i18n('Google Analytics ')} - {i18n('Error')}</h3>
		<div class="capmain"></div>
		<div class="error">
			{section=Error}
				{$Error}
			{/section}
		</div>
	{/if}
{/if}
{if $page === 'sett'}
	<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Google Analytics ')}</h3>
	{if $message}<div class="{$class}">{$message}</div>{/if}

	<form id="This" action="{$URL_REQUEST}" method="post">
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3">{i18n('Google Analytics module status')}:</div>
			<div class="formField grid_2"><label><input type="radio" name="status" value="1" {if $status == 1} checked="checked"{/if} > {i18n('On')}</label></div>
			<div class="formField grid_2"><label><input type="radio" name="status" value="0" {if $status == 0} checked="checked"{/if} > {i18n('Off')}</label></div>
			
		</div>
		
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="email">{i18n('Google username:')}</label></div>
			<div class="formField grid_7"><input type="text" id="email" name="email" class="num_255" value="{$email}" ></div>
		</div>
		
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="password">{i18n('Google password:')}</label></div>
			<div class="formField grid_7"><input type="text" id="password" name="password" class="num_255" value="{$password}" ></div>
		</div>
			
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="account_id">{i18n('Google Account ID:')}</label></div>
			<div class="formField grid_7"><input type="text" id="account_id" name="account_id" class="num_255" value="{$account_id}" >{i18n('Get Google ID')}</div>
		</div>
		
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="profile_id">{i18n('Google Profile ID:')}</label></div>
			<div class="formField grid_7"><input type="text" id="profile_id" name="profile_id" class="num_255" value="{$profile_id}" ></div>
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
{/if}