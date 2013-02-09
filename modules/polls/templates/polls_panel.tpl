{if ! $ajax}
{php} openside(__('Polls')) {/php}
{/if}

<div id="poll_panel_html">
	{if $polls_data}
		{section=polls_data}
		<div class="tbl2">
			<div class="sep_1 grid_5 bold">{$polls_data.question}</div>
		</div>
		<form id="This{$polls_data.id}" action="{$FILE_SELF}" method="post" name="poll_panel">
			{section=polls_response}
				<div class="tbl1">
					<div class="grid_3"><label><input type="radio" name="response" value="{$polls_response.n}"/> {$polls_response.val}</label></div>
				</div>
			{/section}
				{if $login}
					<div class="tbl Buttons">
						<div class="button-l center grid_1 button-r">
							<input type="hidden" name="polls" value="{$polls_data.id}" />
							<input type="hidden" name="vote_{$polls_data.id}" value="yes" />
							<input type="submit" name="send" value="{i18n('Vote')}" class="Save button" />
						</div>
					</div>
				{else}
					<div class="tbl Buttons">
						<div class="sep_2 grid_1">
							<a href="{$ADDR_SITE}login.html" class="button"><strong>{i18n('Log in')}</strong></a>
						</div>
					</div>
				{/if}
		</form>
		<div class="tbl">
			<div class="grid_3">
				<div>{i18n('Start date:')} {$polls_data.date_start}</div>
			</div>
		</div>
		{/section}
	{else}
		<div class="tbl">
			<div class="info">{i18n('There are no polls where you can vote')}</div>
			<div><a href="{$ADDR_SITE}polls.html">{i18n('Results from polls')}</a></div>
		</div>
	{/if}
		<div class="tbl">
			<div><a href="{$polls_archive}">{i18n('Polls archive')}</a></div>
		</div>
</div>
{if ! $ajax}
{php} closeside() {/php}
{/if}