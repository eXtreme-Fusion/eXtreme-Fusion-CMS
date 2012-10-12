{if $Begin}
	<div class='side-body-bg'>
		<div class='scapmain'>
			<h4>{$Collapse}{$Title}</h4>
		</div>
		<div class='side-body2 floatfix'>
			{if $State}{$State}{/if}
{else}
			{if $Collapse}</div>{/if}
		</div>
	</div>
{/if}

<!--{$panel_id}-->