	<h4>{i18n('Forms protection')}</h4>

	<div class="tbl center">
		<div class="center">{$info}</div>
	</div>

	<input type="hidden" name="user_ip" value="{$user_ip}"/>

	{if $answer}
		<div class="info center">{i18n('Detected as a bot')}</div>
	{/if}