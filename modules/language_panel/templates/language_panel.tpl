{php} openside(__('Language Panel')) {/php}
	<form id="This" action="{$URL_REQUEST}" method="post" name="language">
		<div>
			<label for="language">{i18n('Language:')}</label>
			<select name="language" class="textbox" id="language">
				{section=locales}
					<option value="{$locales.value}" {if $locales.selected}selected="selected"{/if}>{$locales.display}</option>
				{/section}
			</select>
		</div>
		<div class="Buttons">
			<div class="center grid_2">
				<input type="hidden" name="set_language" value="yes" />
				<span id="SendForm_This" class="save button"><strong>{i18n('Change language')}</strong></span>
			</div>
		</div>
	</form>
{php} closeside() {/php}
