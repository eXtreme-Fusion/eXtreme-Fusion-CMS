{if ! $only_comments}
	{if $can_comment}
		<div id="comment_form">
			<form method="post" action="{$URL_REQUEST}" class="center">
				{if $iGUEST}
					<div><input id="author" type="text" name="author" value="Your nick" /></div>
				{/if}
				<textarea id="post" rows="4" class="cm_textarea" name="post"></textarea><br />
					{section=bbcode}
						<button type="button" onClick="addText('{$bbcode.textarea}', '[{$bbcode.value}]', '[/{$bbcode.value}]', 'This');" title="{$bbcode.description}" class="tip"><img src="{$bbcode.image}"></button>
					{/section}
				<input id="item" type="hidden" name="item" value="{$item}" />
				<input id="type" type="hidden" name="type" value="{$type}" />
				<div class="center"><span id="send" class="pointer underline">{i18n('Save')}</span></div>
			</form>
			<p id="loading" class="center hide">{i18n('Dodawanie komentarza')}</p>
			<p id="added" class="center hide">{i18n('Komentarz został dodany.')}</p>
		</div>
	{else}
		<p class="center">{i18n('Komentowanie zostało wyłączone dla Twojej grupy uprawnień.')}</p>
	{/if}
	<div id="comment-block">
{/if}


{if $comment}
	<div id="comments">
		{section=comment}
			<div class="comment" id="body_{$comment.id}">
				<div class="cm_avatar center">
					
						<img src="{$ADDR_AVATARS}{$comment.avatar}" alt="none_avatar" width="60px" />
					
				</div>
				<div class="cm_content">
					<div class="cm_content2">
						<div class="details">
							{$comment.author}, {$comment.datestamp}
							{if $comment.edit}
								<a href="{$ADDR_AJAX}comments.php?id={$comment.id}&amp;action=edit&amp;request=get" id="{$comment.id}" rel="facebox" class="facebox">[Edycja]</a>
							{/if}
							{if $comment.delete}
								<a href="{$ADDR_AJAX}comments.php?id={$comment.id}&amp;action=delete&amp;request=get" id="{$comment.id}" rel="facebox">[Usuń]</a>
							{/if}
						</div>
						<div class="cm_post" id="content_{$comment.id}">
							{$comment.post}
						</div>
					</div>
				</div>
			</div>
		{/section}
	</div>
{/if}

{if ! $only_comments}</div>{/if}