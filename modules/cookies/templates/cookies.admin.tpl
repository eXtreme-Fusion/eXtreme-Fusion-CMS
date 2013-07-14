<h3>{$SystemVersion} - {i18n('Cookies')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl1">
		<div class="grid_12 formLabel left">{i18n('Communique:')}</div>
		<div class="grid_12 formField"><textarea name="message" id="message">{$data}</textarea></div>
	</div>
	<div class="tbl2">
		<div class="grid_12 formLabel left">{i18n('Cookies policy:')}<small>{i18n('Cookies policy visibility', array(':url' => $learn_more_url, ':url_title' => $learn_more_url_title))}</small></div>
		<div class="grid_12 formField"><textarea name="policy" id="policy">{$policy}</textarea></div>
	</div>
    <script>
        {literal}
			CKEDITOR.replace('message', {
				uiColor: '#4B4B4B'
            });
			CKEDITOR.replace('policy', {
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