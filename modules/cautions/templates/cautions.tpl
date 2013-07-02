{php} $this->middlePanel(__('Cautions')); {/php}
	<div class="tbl">
		<div class="grid_10 red">{i18n('You have :how warnings', array(':how' => $rows))}</div>
	</div>
	{if $caution}
		<div class="tbl2">
			<div class="grid_2 bold">{i18n('Date')}</div>
			<div class="grid_2 bold">{i18n('Added by')}</div>
			<div class="grid_4 bold">{i18n('Reason')}</div>
			{if $permission}
				<div class="grid_2 bold">{i18n('Management')}</div>
			{/if}
		</div>
		{section=caution}
			<div class="{$caution.row_color}">
				<div class="grid_2">{$caution.date}</div>
				<div class="grid_2">{if $caution.donor}{$caution.donor}{else}{i18n('No data')}{/if}</div>
				<div class="grid_4">{$caution.content}</div>
				{if $permission}
					<div class="grid_2">
						<a href="javascript:void(0);" class="tip admin-box" rel="{$ADDR_MODULES}cautions/admin/cautions.php?user={$caution.user_id}&action=edit&caution_id={$caution.ID}&fromPage=true" title="{i18n('Edit')}">
							<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
						</a>
						<a href="javascript:void(0);" class="tip admin-box" rel="{$ADDR_MODULES}cautions/admin/cautions.php?action=delete&caution_id={$caution.ID}&fromPage=true" title="{i18n('Delete')}">
							<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
						</a>
					</div>
				{/if}
			</div>
		{/section}
		<div class="right"><< {i18n('Back to profile')} {$profile}</div>
	{/if}
{php} $this->middlePanel(); {/php}