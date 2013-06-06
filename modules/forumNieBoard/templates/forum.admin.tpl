<h3>{$SystemVersion} - {i18n('SignProtection&trade;')} - {i18n('Settings')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<form id="This" action="{$URL_REQUEST}" method="post">
	<div class="tbl1">
		<div class="grid_3 sep_2 formLabel">{i18n('Typ kodu bezpieczeństwa:')}</div>
		<div class="grid_2 formField"><label><input type="radio" name="validation_type" value="0"{if $validation_type == 0} checked="checked"{/if} class="validation_type" /> {i18n('Podstawowy')}</label></div>
		<div class="grid_2 formField"><label><input type="radio" name="validation_type" value="1"{if $validation_type == 1} checked="checked"{/if} class="validation_type" /> {i18n('Zaawansowany')}</label></div>
	</div>
	<div class="tbl2">
		<div class="grid_5 formLabel">{i18n('Podgląd:')}</div>
		<div class="grid_7 formField">
			<div class="info_block hidden " id="info_0">
				<p>Nie licząc cyfr przepisz 3 znak od lewej strony, a następnie (nie licząc znaków) wszystkie cyfry od początku kodu</p>
				<p>Kod: ef9580680ba0b5</p>
			</div>
			<div class="info_block hidden" id="info_1">
				<p>Nie licząc cyfr przepisz 2 znak od lewej strony, a następnie (nie licząc znaków) wszystkie cyfry od początku kodu z pominieciem 2 pierwszych</p>
				<p>Kod: c4c5f2acade119</p>
			</div>
		</div>
	</div>
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

<script>
	{literal}
		$(function() {
			var value = $('.validation_type:checked').attr('value');	
			$('.info_block').fadeOut();
			$('#info_'+value).fadeIn();
			
			$('.validation_type').change(function() {
				var value = $(this).attr('value');
				$('.info_block').hide();
				$('#info_'+value).fadeIn(1000);
			});
			
		});
	{/literal}
</script>
