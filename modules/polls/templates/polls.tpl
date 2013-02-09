{if $Archive}
	{php} opentable(__('Polls archive')) {/php}
		{if $Data}
			{section=Data}
				<div class="tbl">
					<div class="sep_1 grid_10 bold">{$Data.Question}</div>
				</div>
					{section=Response}
						<div class="tbl1">
							<div class="grid_8">
								<label>{$Response.Val} - {$Response2.L} {i18n('votes')} - {$Response.P}%</label>
								<div style="width:{$Response.P}px" class="poll tbl2"></div>
							</div>
						</div>
					{/section}
				<div class="tbl">
					<div class="grid_8">
						<div>{i18n('Date of start:')} {$Data.DateStart}</div>
						<div>{i18n('Date of end:')} {$Data.DateEnd}</div>
						<div>{i18n('Votes:')} {$Data.Votes}</div>
					</div>
				</div>
			{/section}
		{else}
			<div class="tbl">
				<div class="error">{i18n('Error!')}</div>
			</div>
		{/if}
	{php} closetable() {/php}
	
{elseif $page == 'my_votes'}
	{php} opentable(__('Ended polls')) {/php}
		{if $Data2}
			{section=Data2}
				<div class="tbl2">
					<div class="sep_1 grid_10 bold">{$Data2.Question}</div>
				</div>
					{section=Response2}
						<div class="tbl1">
							<div class="grid_8">
								<label>{$Response2.Val} - {$Response2.L} {i18n('votes')} - {$Response2.P}%</label>
								<div style="width:{$Response2.P}px" class="poll tbl2"></div>
							</div>
						</div>
					{/section}
				<div class="tbl">
					<div class="grid_8">
						<div>{i18n('Start date:')} {$Data2.DateStart}</div>
						<div>{i18n('Votes:')} {$Data2.Votes}</div>
					</div>
				</div>
			{/section}
		{else}
			<div class="tbl">
				<div class="info">{i18n('Nie ma żadnych zakończonych ankiet, w których głosowałeś/aś.')}</div>
			</div>
		{/if}
		
	{php} closetable() {/php}
	
	{php} opentable(__('Trwające ankiety')) {/php}
		{if $Data}
			{section=Data}
				<div class="tbl2">
					<div class="sep_1 grid_10 bold">{$Data.Question}</div>
				</div>
					{if $Data.ShowResults}
						{section=Response}
							<div class="tbl1">
								<div class="grid_8">
									<label>{$Response.val} - {$Response.L} {i18n('votes')} - {$Response.P}%</label>
									<div style="width:{$Response.P}px" class="poll tbl2"></div>
								</div>
							</div>
						{/section}
					{else}
						{section=Response}
							<div class="tbl1">
								<div class="grid_8">
									<p>&raquo; {$Response.val}</p>
								</div>
							</div>
						{/section}
					{/if}
				<div class="tbl">
					<div class="grid_8">
						<div>{i18n('Start date:')} {$Data.DateStart}</div>
						<div>{i18n('Votes:')} {$Data.Votes}</div>
					</div>
				</div>
				<br /><br />
			{/section}
		{else}
			<div class="tbl">
				<div class="info">{i18n('Nie ma żadnych zakończonych ankiet, w których głosowałeś/aś.')}</div>
			</div>
		{/if}
		
	{php} closetable() {/php}
{elseif $page == 'active'}	
	{php} opentable(__('Active polls')) {/php}
		{if $Data}
			{section=Data}
				<div class="tbl2">
					<div class="sep_1 grid_10 bold">{$Data.Question}</div>
				</div>
				<form id="This{$Data.ID}" action="{$FILE_SELF}" method="post" name="poll_vote">
					{section=Response}
						<div class="tbl1">
							<div class="grid_8"><label><input type="radio" name="response" value="{$Response.N}"/> {$Response.Val}</label></div>
						</div>
					{/section}
						<div class="tbl Buttons">
							<div class="button-l center grid_2 button-r">
								<input type="hidden" name="polls" value="{$Data.ID}" />
								<input type="hidden" name="vote_{$Data.ID}" value="yes" />
								<span class="Save button" id="SendForm_This{$Data.ID}"><strong>{i18n('Vote')}</strong></span>
							</div>
						</div>
				</form>
				<div class="tbl">
					<div class="grid_8">
						<div>{i18n('Start date:')} {$Data.DateStart}</div>
					</div>
				</div>
			{/section}
		{else}
		<div class="tbl">
				<div class="info">{i18n('There are no polls.')}</div>
			</div>
		{/if}
	{php} closetable() {/php}
{elseif $page == 'closed'}
{php} opentable(__('Ended polls')) {/php}
		{if $Data2}
			{section=Data2}
				<div class="tbl2">
					<div class="sep_1 grid_10 bold">{$Data2.Question}</div>
				</div>
					{section=Response2}
						<div class="tbl1">
							<div class="grid_8">
								<label>{$Response2.name} - {$Response2.count} {i18n('votes')} - {$Response2.percent}%</label>
								<div style="width:{$Response2.percent}px" class="poll tbl2"></div>
							</div>
						</div>
					{/section}
				<div class="tbl">
					<div class="grid_8">
						<div>{i18n('Start date:')} {$Data2.DateStart}</div>
						<div>{i18n('Votes:')} {$Data2.Votes}</div>
					</div>
				</div>
			{/section}
		{else}
			<div class="tbl">
				<div class="info">{i18n('Nie głosowałeś w żadnych zakończonych ankietach.')}</div>
			</div>
		{/if}
			<div class="tbl">
				<div><a href="{$FILE_NAME},archive.html">{i18n('Polls archive')}</a></div>
			</div>
	{php} closetable() {/php}
{/if}

