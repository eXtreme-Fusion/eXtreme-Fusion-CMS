<script>{literal}$(document).ready(function(){$('#chat_post').html({/literal}{$rows}{literal});});{/literal}</script>
{section=posts}
	<div class="InfoBoxPanel">
		<div class="InfoBoxTop"></div>
		<div class="InfoBoxCon">
			<span>{$posts.date}</span><br />
			{$posts.content}
		</div>
		<div class="InfoBoxEndd">
		{$posts.user} 
		{if $iAdmin}<div style="float:right;"><a href="{$ADDR_SITE}modules/chat/shoutbox_panel/ajax/delete.php?post={$posts.id}" class="shoutbox_delete_post">Usuń</a> <a href="{$ADDR_SITE}modules/chat/shoutbox_panel/ajax/edit.php?post={$posts.id}" class="shoutbox_edit_post">Edytuj</a></div>{/if}
		</div>
	</div>
{/section}