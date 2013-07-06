<h3>{$SystemVersion} - {i18n('Cookies')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl1">
		<div class="grid_12 formField"><textarea name="message" id="message">{$data}</textarea></div>
	</div>
    <script>
        {literal}
			var editor = CKEDITOR.replace('message', {
				uiColor: '#4B4B4B'
            });
		{/literal}
	</script>
	<div class="tbl Buttons">
		<div class="grid_2 center button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
		</div>
		<div class="grid_2 center button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
</form>