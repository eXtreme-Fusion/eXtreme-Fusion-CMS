<h3>{$SystemVersion} - {i18n('Settings')} &raquo; {i18n('Banners')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form action="{$URL_REQUEST}" method="post" id="This">
	<div class="tbl1">
		<div class="grid_2 formLabel"><label for="site_banner1">{i18n('1. banner:')}</label></div>
		<div class="grid_10 formField"><textarea name="site_banner1" id="site_banner1">{$site_banner1}</textarea></div>
	</div>
    <script>
        {literal}
            var editor = CKEDITOR.replace('site_banner1', {
				extraPlugins : 'docprops',
				uiColor: '#4B4B4B'
            });
        {/literal}
	</script>
	<div class="tbl1">
		<div class="grid_2 formLabel"><label for="site_banner2">{i18n('2. banner:')}</label></div>
		<div class="grid_10 formField"><textarea name="site_banner2" id="site_banner2">{$site_banner2}</textarea></div>
	</div>
    <script>
        {literal}
			var editor = CKEDITOR.replace('site_banner2', {
				extraPlugins : 'docprops',
				uiColor: '#4B4B4B'
            });
		{/literal}
	</script>
	<div class="tbl Buttons">
		<div class="center grid_2 button-l">
			<span class="Cancel"><strong>{i18n('Back')}<img alt="" src="{$ADDR_ADMIN_ICONS}pixel/undo.png"></strong></span>
		</div>
		<div class="center grid_2 button-r">
			<input type="hidden" name="save" value="yes" />
			<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
		</div>
	</div>
</form>