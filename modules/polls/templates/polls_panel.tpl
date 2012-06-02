{if ! $ajax}
{php} openside(__('Polls')) {/php}
{/if}

<div id="poll_panel_html">
	{if $PanelData}
		{section=PanelData}
		<div class="tbl2">
			<div class="sep_1 grid_5 bold">{$PanelData.Question}</div>
		</div>
		<form id="This{$PanelData.ID}" action="{$FILE_SELF}" method="post" name="poll_panel">
			{section=PanelResponse}
				<div class="tbl1">
					<div class="grid_3"><label><input type="radio" name="response" value="{$PanelResponse.N}"/> {$PanelResponse.Val}</label></div>
				</div>
			{/section}
				{if $Login}
					<div class="tbl AdminButtons">
						<div class="button-l center grid_1 button-r">
							<input type="hidden" name="polls" value="{$PanelData.ID}" />
							<input type="hidden" name="vote_{$PanelData.ID}" value="yes" />
              <input type="submit" name="send" value="Vote" class="Save button" />
						</div>
					</div>
				{else}
					<div class="tbl AdminButtons">
						<div class="sep_2 grid_1">
							<a href="{$ADDR_SITE}login.html" class="button"><strong>Zaloguj się</strong></a>
						</div>
					</div>
				{/if}
		</form>
		<div class="tbl">
			<div class="grid_3">
				<div>Data rozpoczęcia: {$PanelData.DateStart}</div>
			</div>
		</div>
		{/section}
	{else}
		<div class="tbl">
			<div class="info">Nie ma ankiet do głosowania</div>
			<div><a href="{$ADDR_SITE}polls.html">Wyniki trwających ankiet</a></div>
		</div>
	{/if}
		<div class="tbl">
			<div><a href="{$ADDR_SITE}polls,archive.html">Archiwum ankiet</a></div>
		</div>
</div>
{if ! $ajax}
{php} closeside() {/php}
{/if}