{php} openside(__('Top Users')) {/php}
	<div class="points tbl2">
		<div>{i18n('On the <strong>1st</strong> place is')}: <a href="{$user.link}">{$user.username}</a></div>
		<div>
			<div class="left"><img src="{$ADDR_IMAGES}avatars/{$user.avatar}"></div>
			<div><small>{$user.role}</small></div>
			<div>{i18n('Scored <strong>:point</strong> points', array(':point' => $user.points))}</div>
		</div>
		<div>{i18n('Rank')}: {$user.rank}</div>
		<hr />
		<div>
			{section=users}
				<div>{$users.i}) <a href="{$users.link}">{$users.username}</a> ({$users.points} {i18n('points')})</div>
			{/section}
		</div>
	</div>
{php} closeside() {/php}