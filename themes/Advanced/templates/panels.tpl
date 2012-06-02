{if $Begin}
	<div class='border'>
		{if $Collapse}
			<div class='scapmain right'>
				{$Collapse}
			</div>
		{/if}
		<div class='scapmain'>
			{$Title}
		</div>
		<div class='side-body floatfix'>
			{if $State}{$State}{/if}
{else}
			{if $Collapse}</div>{/if}
		</div>
	</div>
{/if}