
	{php} openside(__('Newsletter Panel')) {/php}
	<div id="NewsLetterPanel">
		<div class="InfoBoxPanel" id="NewsletterPanel">
			<div class="InfoBoxTop"></div>
			<div class="InfoBoxCon">
				<div id="NewsletterIcon"></div>
				<div class="InfoBoxCenterRight">
					{i18n('Do you want to be informed about progress of the eXtreme-Fusion 5?')}
				</div>
			</div>
			<div class="InfoBoxEnd"></div>
		</div>
		{i18n('Your e-mail:')}
		<form id="NewsletterForm">
			<input type="text" name="UserEmail"/>
			<label><input class="input" type="checkbox" name="NewsletterZgoda" value="Yes" />{i18n('I accept')} <a href="{$rules}">{i18n('rules')}</a></label>
			<input type="submit" id="NewsletterZapisz" value="{i18n('Save')}" />
		</form>
	</div>
	{php} closeside() {/php}
