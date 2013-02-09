<h3 class="ui-corner-all">{$SystemVersion} - {$NavigationTitle}</h3>
<div class="tbl">
	{section=AdminLink}
		<div class="EF-Nav ui-corner-all grid_2">
			<a href="{if !$Modules}{$ADDR_ADMIN}pages/{/if}{$AdminLink.Link}">
				<img src="{if !$Modules}{$ADDR_ADMIN_IMAGES}pages/{/if}{$AdminLink.Image}" alt="{$AdminLink.Title}" />
				<span>{$AdminLink.Title}</span>
			</a>
		</div>
	{sectionelse}
		<div class="status">{i18n('There are no navigation links.')}</div>
	{/section}
</div>
{if $Modules}
	<div class="tbl Buttons">
		<div class="center grid_2 button-c">
			<span class="save" id="SendForm_This" ><a href="{$ADDR_ADMIN_PAGES}modules.php"><strong>Zarządzanie</strong></a></span>
		</div>
	</div>
{/if}