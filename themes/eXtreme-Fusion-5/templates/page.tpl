<div id="MainWrap" class="box-center">
	<div class="floatfix">
		<div class="full-header floatfix">
			<a href="{$ADDR_SITE}" id="HomeLink" class="tip" title="{$Sitename}">
				<img src="{$THEME_IMAGES}logo.png" alt="{$Sitename}" />
			</a>
			<div id="RightHeaderBG">
				<a href="http://www.extreme-fusion.pl/downloads.php?action=get&amp;id=530" rel="blank" class="tip" title="Download eXtreme-Fusion 4.17">
					Download eXtreme-Fusion 4.17
				</a>
			</div>
		</div>
		<div class="sub-header floatfix">
			<div class="left">
				{if $Menu}
					<ul>
						{section=Menu}
							<li class="{$Menu.class}{if $Menu.selected} selected{/if}">{$Menu.sep} <a href="{$Menu.link}"{if $Menu.target} target="_blank"{/if}><span>{$Menu.name}</span></a></li>
						{/section}
					</ul>
				{/if}
			</div>
			<div id="fancyClock"></div>
		</div>
	</div>
	{if $LEFT}<div id="side-border-left">{$LEFT}</div>{/if}
	{if $RIGHT}<div id="side-border-right">{$RIGHT}</div>{/if}
	<div id="main-bg" class="clearfix">
		<div id="container">{$CONTENT}</div>
	</div>
	<div class="bottom floatfix">{$primiarymenu}</div>
	<div class="footer floatfix">
		<div class="center">
			{if $Copyright}
				<p>{$Copyright}</p>
				<p>{$License}</p>
			{/if}
			<p>
				Theme by <a href="http://nlds-group.com/" title="NLDS-Group.com"><img src="{$THEME_IMAGES}nlds.png" alt="NLDS-Group.com" /></a>
			</p>
			{if $AdminLinks}<p>{$AdminLinks}</p>{/if}
		</div>
		<!--<div class="right">{$Footer}</div>-->
	</div>
</div>