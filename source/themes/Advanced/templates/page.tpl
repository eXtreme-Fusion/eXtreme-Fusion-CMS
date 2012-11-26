<div style='width:{$THEME_WIDTH}' class='{$main_style}'>
	<div class='floatfix'>
		<div class='white-header right'>
			{$Menu}
		</div>
	</div>
	<div class='full-header'>
		<div class='left'>
			{$Banners}
		</div>
	</div>
</div>

{if $LEFT}<div id="side-border-left">{$LEFT}</div>{/if}
{if $RIGHT}<div id="side-border-right">{$RIGHT}</div>{/if}
<div id="main-bg" class="clearfix">
	<div id="container">{$CONTENT}</div>
</div>

<div class="copy-footer center">
	{if $Copyright}<p>{$Copyright}</p>{/if}
	{if $License}<p>{$License}</p>{/if}
	<div class="center">{$Footer}</div>
</div>