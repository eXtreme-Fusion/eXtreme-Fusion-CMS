{if $question}
	{php} opentable(__('Question preview')) {/php}
		<div class="tbl2">
			<div class="sep_1 grid_8"><strong>{$question.question}</strong></div>
			<div class="grid_4">
				{if $question.sticky === '1'}<img src="{$ADDR_MODULES}faq/images/important.png" title="{i18n('Important')}" class="tip"> <span style="color:red; font-weight:bold">{i18n('Important')}</span>{/if}
				{i18n('Comments:')} <a href="{$question.url_answer}">{$question.comments}</a>
			</div>
		</div>
		<div class="tbl1">
			<div class="sep_1 grid_12">{$question.answer}</div>
		</div>
		<div class="right"><a href="{$question.url_faq}"><< Wróć</a></div>
	{php} closetable() {/php}
	{if $question.comment === '1'}
		{php} opentable(__('Comments')) {/php}
			{$comments}
		{php} closetable() {/php}
	{/if}
{else}
	{php} opentable(__('FAQ')) {/php}
	{if $questions}
		{section=questions}
			{if $setting.display === '1'}
				<div class="{$questions.row_color}">
					<div class="sep_1 grid_3">{if $setting.listing === '1'}{$questions.nr}. {/if}<a href="{$questions.url_answer}">{$questions.question}</a></div>
					{if $setting.links === '1'}
						<div class="grid_3 right">
							{if $questions.sticky === '1'}<img src="{$ADDR_MODULES}faq/images/important.png" title="{i18n('Important')}" class="tip"> <span style="color:red; font-weight:bold">{i18n('Important')}</span>{/if}
							<a href="{$questions.url_answer}"><img src="{$ADDR_MODULES}faq/images/comment.png" title="{i18n('Read more')}" class="tip"></a> | 
							{i18n('Comments:')} <a href="{$questions.url_answer}">{$questions.comments}</a>
						</div>
					{/if}
					<div class="grid_6">{$questions.answer}</div>
				</div>
			{elseif $setting.display === '2'}
				<div class="{$questions.row_color}">
					<div class="sep_1 grid_9">{if $setting.listing === '1'}{$questions.nr}. {/if}<a href="{$questions.url_answer}.html">{$questions.question}</a></div>
					{if $setting.links === '1'}
						<div class="grid_3 right">
							{if $questions.sticky === '1'}<img src="{$ADDR_MODULES}faq/images/important.png" title="{i18n('Important')}" class="tip"> <span style="color:red; font-weight:bold">{i18n('Important')}</span>{/if}
							<a href="{$questions.url_answer}"><img src="{$ADDR_MODULES}faq/images/comment.png" title="{i18n('Read more')}" class="tip"></a> | 
							{i18n('Comments:')} <a href="{$questions.url_answer}">{$questions.comments}</a>
						</div>
					{/if}
				</div>
			{elseif $setting.display === '3'}
				<div class="tbl2 faq_question" id="faq{$questions.id}">
					<div class="sep_1 grid_9">{if $setting.listing === '1'}{$questions.nr}. {/if}<strong><a href="{$questions.url_answer}">{$questions.question}</a></strong></div>
					{if $setting.links === '1'}
						<div class="grid_3 right">
							{if $questions.sticky === '1'}<img src="{$ADDR_MODULES}faq/images/important.png" title="{i18n('Important')}" class="tip"> <span style="color:red; font-weight:bold">{i18n('Important')}</span>{/if}
							<a href="{$questions.url_answer}"><img src="{$ADDR_MODULES}faq/images/comment.png" title="{i18n('Read more')}" class="tip"></a> | 
							{i18n('Comments:')} <a href="{$questions.url_answer}">{$questions.comments}</a>
						</div>
					{/if}
				</div>
				<div class="tbl1 faq_answer" id="answer_faq{$questions.id}">
					<div class="grid_12">{$questions.answer}</div>
				</div>
			{elseif $setting.display === '4'}
				<div class="{$questions.row_color}">
					<div class="sep_1 grid_9">{if $setting.listing === '1'}{$questions.nr}. {/if}<a href="{$questions.url_answer}" target="_blank">{$questions.question}</a></div>
					{if $setting.links === '1'}
						<div class="grid_3 right">
							{if $questions.sticky === '1'}<img src="{$ADDR_MODULES}faq/images/important.png" title="{i18n('Important')}" class="tip"> <span style="color:red; font-weight:bold">{i18n('Important')}</span>{/if}
							<a href="{$questions.url_answer}"><img src="{$ADDR_MODULES}faq/images/comment.png" title="{i18n('Read more')}" class="tip"></a> | 
							{i18n('Comments:')} <a href="{$questions.url_answer}">{$questions.comments}</a>
						</div>
					{/if}
				</div>
			{/if}
		{/section}
	{else}
		<div class="info">{i18n('There are no questions.')}</div>
	{/if}
	{php} closetable() {/php}
{/if}