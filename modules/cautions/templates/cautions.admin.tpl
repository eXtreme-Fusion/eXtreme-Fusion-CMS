<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Cautions')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<script>var addr_modules = '{$ADDR_MODULES}';</script>
<script src="{$ADDR_MODULES}cautions/templates/javascripts/users.js"></script>

{if ! $user && ! $edit}
	<div class="tbl">
		<div class="sep_1 grid_2">Do:</div>
		<div class="grid_7">
			<div class="grid_7"><input type="text" id="message_from" class="long_textbox" /></div>
			<div class="grid_7"><div id="message_from_result"></div></div>
		</div>
	</div>
{/if}

{if $user}
	<form id="This" action="{$URL_REQUEST}" method="post">
		{if $edit}
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="UserID">{i18n('User Name')}:</label></div>
				<div class="formField grid_7"><a href="{$ADDR_SITE}profile/{$user_id}.html">{$username}</a></div>
			</div>
		{else}
			<div class="tbl1">
				<div class="formLabel sep_1 grid_3"><label for="UserID">{i18n('User Name')}:</label></div>
				<div class="formField grid_7"><input type="text" class="textbox" value="{$username}" disabled="disabled"></div>
			</div>
		{/if}
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="Cautions">{i18n('Reason')}:</label></div>
			<div class="formField grid_7"><textarea name="cautions" id="Cautions" rows="3" class="resize">{$cautions}</textarea></div>
		</div>
		{if ! $edit}
			<div class="tbl1">
				<div class="formField grid_10 center"><label for="SendPM"><input type="checkbox" name="send_pm" value="1" id="SendPM">{i18n('Send a private message to user')}</label></div>
			</div>
		{/if}
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