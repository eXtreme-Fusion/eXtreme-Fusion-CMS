{php} opentable(__('Message')); {/php}
{if $message}
	<div class="{$class}">{$message}</div>
{else}
	<div class="tbl">
		<div class="sep_1 grid_2">Do:</div>
		<div class="grid_7">
			<div class="grid_7"><input type="text" id="message_from" class="long_textbox" /></div>
			<div class="grid_7"><div id="message_from_result"></div></div>
		</div>
	</div>
	<script src="{$ADDR_JS}users.js"></script>
{/if}
{php} closetable(); {/php}