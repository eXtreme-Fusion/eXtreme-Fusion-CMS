<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Logs')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}
<div class="info">{if $active}{i18n('Logs are enabled.')}{else}{i18n('Logs are disabled.')}{/if}</div>
{if $logs}
	<h4>{i18n('Logs list')}</h4>
	<div>	
		<span class="box-right">
			<a href="{$FILE_SELF}?action=delete_all" class="tip confirm_button" title="{i18n('Delete all')}">{i18n('Delete all')}</a>
		</span>
	</div>
	<div class="clear"></div>
	<div class="tbl2">
		<div class="sep_1 grid_2 bold center">{i18n('Date:')}</div>
		<div class="grid_1 bold center">{i18n('Status:')}</div>
		<div class="grid_1 bold center">{i18n('Action:')}</div>
		<div class="grid_2 bold center">{i18n('File:')}</div>
		<div class="grid_2 bold center">{i18n('Message:')}</div>
		<div class="grid_1 bold center">{i18n('User:')}</div>
		<div class="grid_1 bold center">{i18n('IP:')}</div>
		<div class="grid_1 bold center">{i18n('Options:')}</div>
	</div>
	{section=logs}
		<div class="tbl {$logs.row_color}">
			<div class="sep_1 grid_2 center">{$logs.date}</div>
			<div class="grid_1 center">{$logs.action}</div>
			<div class="grid_1 center">{$logs.status}</div>
			<div class="grid_2 center">{$logs.file}</div>
			<div class="grid_2 center">{$logs.message}</div>
			<div class="grid_1 center">{$logs.user}</div>
			<div class="grid_1 center">{$logs.ip}</div>
			<div class="grid_1 center">
				<a href="{$FILE_SELF}?action=delete&amp;id={$logs.id}" class="tip confirm_button" title="{i18n('Delete')}">
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
		<div class="sep_1 grid_10 center">{i18n('There are no logs.')}</div>
	</div>
	<div class="tbl Buttons">
		<div class="center grid_2 button-c">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
	</div>
{/if}
