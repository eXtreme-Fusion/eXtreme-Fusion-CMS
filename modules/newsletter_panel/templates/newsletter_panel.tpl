
	{php} openside(__('Newsletter Panel')) {/php}
	<div id="NewsLetterPanel">
		<div class="InfoBoxPanel" id="NewsletterPanel">
			<div class="InfoBoxTop"></div>
			<div class="InfoBoxCon">
				<div id="NewsletterIcon"></div>
				<div class="InfoBoxCenterRight">
					Chcesz być informowany o nowościach, postępie prac nad eXtreme-Fusion 5?
				</div>
			</div>
			<div class="InfoBoxEnd"></div>
		</div>
		Twój adres e-mail:
		<form id="NewsletterForm">
			<input type="text" name="UserEmail"/>
			<label><input class="input" type="checkbox" name="NewsletterZgoda" value="Yes" />Akceptuję <a href="">regulamin</a></label>
			<input type="submit" id="NewsletterZapisz" value="Zapisz się" />
		</form>
	</div>
	{php} closeside() {/php}
