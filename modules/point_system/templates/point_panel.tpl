{php} openside('Najaktywniejsi') {/php}
	<div class="points tbl2">
		<div>Na <strong>1.</strong> miejscu jest: <a href="{$ADDR_SITE}profile,{$user.id},{$user.link}.html">{$user.username}</a></div>
		<div>
			{if $user.avatar}
				<div class="left"><img src="{$ADDR_IMAGES}avatars/{$user.avatar}"></div>
			{else}
				<div class="left"><img src="{$ADDR_IMAGES}avatars/noavatar.jpg"></div>
			{/if}
			<div><small>{$user.role}</small></div>
			<div>Zdobył <strong>{$user.points}</strong> punktów</div>
		</div>
		<div>Ranga: {$user.rank}</div>
		<hr />
		<div>
			{section=users}
				<div>{$users.i}) <a href="{$ADDR_SITE}profile,{$users.id},{$users.link}.html">{$users.username}</a> ({$users.points} pkt)</div>
			{/section}
		</div>
	</div>
{php} closeside() {/php}