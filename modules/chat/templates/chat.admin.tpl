<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Chat')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$FormAction}" method="post">
	<div class="tbl1">
		<div class="formLabel sep_1 grid_3"><label for="refresh">{i18n('Refresh (seconds):')}</label></div>
		<div class="formField grid_7"><input type="text" name="refresh" id="refresh" value="{$refresh}" /></div>
	</div>
  
	<div class="tbl2">
		<div class="formLabel sep_1 grid_3">
      <label for="life_messages">{i18n('Life of messages (minutes):')}</label>
      <small>
      {i18n('Enter 0 if you don\'t want to delete messages.')}
      </small>
    </div>
		<div class="formField grid_7"><input type="text" name="life_messages" id="life_messages" value="{$life_messages}" />    </div>
	</div>

	<div class="tbl AdminButtons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="{i18n('Back')}" /></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span class="Save" id="SendForm_This"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="{i18n('Save')}" /></strong></span>
		</div>
	</div>
</form>