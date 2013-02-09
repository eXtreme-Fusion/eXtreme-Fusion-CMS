<h3>{$SystemVersion} - {i18n('SFSProtection&trade;')} - {i18n('Logs')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

{if $data}
	<h4>{i18n('Logs list')}</h4>
	<div class="clear"></div>
	<div>	
		<span class="box-right">
			<a href="{$FILE_SELF}?action=delete_all" class="tip confirm_button" title="{i18n('Delete all')}">{i18n('Delete all')}</a>
		</span>
	</div>
	<div class="tbl2">
		<div class="sep_1 grid_2 bold center">{i18n('Date:')}</div>
		<div class="grid_2 bold center">{i18n('Name:')}</div>
		<div class="grid_2 bold center">{i18n('Email:')}</div>
		<div class="grid_2 bold center">{i18n('IP:')}</div>
		<div class="grid_1 bold center">{i18n('Options')}</div>
	</div>
	{section=data}
		<div class="tbl {$data.row_color}">
			<div class="sep_1 grid_2 center">{$data.datestamp}</div>
			<div class="grid_2 center">{$data.name}</div>
			<div class="grid_2 center">{$data.email}</div>
			<div class="grid_2 center">{$data.ip}</div>
			<div class="grid_1 center">
				<a href="{$FILE_SELF}?action=delete&amp;id={$data.id}" class="tip confirm_button" title="{i18n('Delete')}">
					<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
				</a>
			</div>
		</div>
	{/section}
	<div class="tbl Buttons">
		<div class="center grid_2 button-c">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
	</div>
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('There are no data.')}</div>
	</div>
	<div class="tbl Buttons">
		<div class="center grid_2 button-c">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
	</div>
{/if}
