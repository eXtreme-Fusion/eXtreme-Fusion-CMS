{if $isInstalled}
		{php} $this->middlePanel(__('Latest Active Threads')); {/php}
		{if $threads}
			<table class="tbl2">
				<thead>
					<tr>
						<th class="grid_4 bold">{i18n('Thread title')}</th>
						<th class="grid_2 bold">{i18n('Author')}</th>
						<th class="grid_3 bold">{i18n('Replies')}</th>
						<th class="grid_3 bold">{i18n('Last entry')}</th>
					</tr>
				</thead>
				<tbody>
					{section=threads}
					<tr class="{$threads.row}">
						<td>{if $threads.is_locked}<img src="{$ADDR_MODULES}forum/templates/images/mini/lock.png" alt="{i18n('Thread locked')}" />&nbsp;{/if}<a href="{$threads.link}" class="text-link">{$threads.title}</a></td>
						<td>{$threads.autor}</td>
						<td>{$threads.entries}</td>
						<td>{$threads.entry_user}</td>
					</tr>
					{/section}
				</tbody>
			</table>
		{else}
			<div class="info">{i18n('No threads have been found')}</div>
		{/if}
		{php} $this->middlePanel(); {/php}
		</div>
{/if}