	<h4>Zabezpieczenie rejestracji</h4>

	<div class="tbl center">
		<div class="center">{$message}</div>
	</div>

	<input type="hidden" name="user_ip" value="{$user_ip}"/>

	{if $answer}
		<div class="info center">System rozpoznał Cię jako bota. Rejestracja została przerwana.</div>
	{/if}