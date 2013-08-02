{literal}<style type="text/css">.DragBox {min-width:200px;}#Column_5 .DragBox {width:200px;margin-right:10px;float:left}</style>{/literal}

{*<h3>{$SystemVersion} - {i18n('Inactive panels')}</h3>*}
<h3>{$SystemVersion} - {i18n('Panels managment')}</h3>
<div class="tbl Buttons">
	<div class="center grid_4 button-c">
		<span><a href="panel_editor.php" class="save"><strong>{i18n('Add new panel')}<img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></a></span>
	</div>
</div>



<h4>{i18n('Inactive panels')}</h4>
<div class="tbl1">
	<div class="sep_1 grid_10 red center bold text-padding">{i18n('Moving here')}</div>
</div>
<div class="tbl2">
	<div id="inactive">
		<div id="Column_5" class="PanelColumn grid_11">
			{section=noact_panels}
					<div class="DragBox ui-corner-all" id="New_{$noact_panels.filename}">
						<h2>
							<span class="Title">{$noact_panels.title}</span>
						</h2>
						<div class="DragBoxContent" >
							<ul class="PanelType">
								<li>{i18n('Panel type:')} {i18n($noact_panels.type)}</li>
								<li>{i18n('Panel access:')} {$noact_panels.access}</li>
							</ul>
						</div>
					</div>
			{/section}
		</div>
	</div>
</div>

{*<h3>{$SystemVersion} - {i18n('Panels managment')}</h3>*}
<h4>{i18n('Panels on website')}</h4>


<div class="tbl">
	<span id="status-types" class="hidden">{i18n('Enable')}_{i18n('Disable')}</span>

	<div id="Column_1" class="PanelColumn grid_3" style="overflow:hidden">
		{section=panel}
			{if $panel.side == 1}
				<div class="DragBox ui-corner-all" id="Item_{$panel.id}">
					<h2>
						<{if $panel.status == 0}em{else}span{/if} class="Title">{$panel.name}</{if $panel.status == 0}em{else}span{/if}>
						<strong class="PanelSettings">
							<a href="panel_editor.php?action=edit&amp;id={$panel.id}&amp;panel_side=1" class="tipTip" rel="{i18n('Edit')}">
								<img src="{$ADDR_ADMIN_ICONS}pixel/edit.png" class="icon" alt="{i18n('Edit')}" />
							</a>
							{if $panel.status == 0}
								<a href="javascript:void(0)" id="Status_{$panel.status}_{$panel.id}" class="ChangeStatus tipTip" rel="{i18n('Enable')}">
									<img src="{$ADDR_ADMIN_ICONS}pixel/checkmark.png" class="icon" alt="{i18n('Enable')}" />
								</a>
							{else}
								<a href="javascript:void(0)" id="Status_{$panel.status}_{$panel.id}"  class="ChangeStatus tipTip" rel="{i18n('Disable')}">
									<img src="{$ADDR_ADMIN_ICONS}pixel/against.png" class="icon" alt="{i18n('Disable')}" />
								</a>
							{/if}
							{*<a href="javascript:void(0)" id="Delete_{$panel.id}"  class="Delete tipTip" title="{i18n('Delete')}" rel="{{i18n('Delete this panel?')}">
								<img src="{$ADDR_ADMIN_ICONS}pixel/x.png" class="icon" alt="{i18n('Delete')}" />
							</a>*}
						</strong>
					</h2>
					<div class="DragBoxContent" >
						<ul class="PanelType">
							<li>{i18n('Panel type:')} {$panel.type}</li>
							<li>{i18n('Panel access:')} {$panel.access}</li>
						</ul>
					</div>
				</div>
			{/if}
		{/section}
	</div>

	<div class="grid_6">
		<div id="Column_2" class="PanelColumn">
			{section=panel}
				{if $panel.side == 2}
					<div class="DragBox ui-corner-all" id="Item_{$panel.id}">
						<h2>
							<{if $panel.status == 0}em{else}span{/if} class="Title">{$panel.name}</{if $panel.status == 0}em{else}span{/if}>
							<strong class="PanelSettings">
								<a href="panel_editor.php?action=edit&amp;id={$panel.id}&amp;panel_side=2" class="tipTip" rel="{i18n('Edit')}">
									<img src="{$ADDR_ADMIN_ICONS}pixel/edit.png" class="icon" alt="{i18n('Edit')}" />
								</a>
								{if $panel.status == 0}
									<a href="javascript:void(0)" id="Status_{$panel.status}_{$panel.id}" class="ChangeStatus tipTip" rel="{i18n('Enable')}">
										<img src="{$ADDR_ADMIN_ICONS}pixel/checkmark.png" class="icon" alt="{i18n('Enable')}" />
									</a>
								{else}
									<a href="javascript:void(0)" id="Status_{$panel.status}_{$panel.id}"  class="ChangeStatus tipTip" rel="{i18n('Disable')}">
										<img src="{$ADDR_ADMIN_ICONS}pixel/against.png" class="icon" alt="{i18n('Disable')}" />
									</a>
								{/if}
								{*<a href="javascript:void(0)" id="Delete_{$panel.id}"  class="Delete tipTip" title="{i18n('Delete')}" rel="{{i18n('Delete this panel?')}">
									<img src="{$ADDR_ADMIN_ICONS}pixel/x.png" class="icon" alt="{i18n('Delete')}" />
								</a>*}
							</strong>
						</h2>
						<div class="DragBoxContent" >
							<ul class="PanelType">
								<li>{i18n('Panel type:')} {$panel.type}</li>
								<li>{i18n('Panel access:')} {$panel.access}</li>
							</ul>
						</div>
					</div>
				{/if}
			{/section}
		</div>

		<div id="SiteContent" class="ui-corner-all"></div>

		<div id="Column_3" class="PanelColumn">
			{section=panel}
				{if $panel.side == 3}
					<div class="DragBox ui-corner-all" id="Item_{$panel.id}">
						<h2>
							<{if $panel.status == 0}em{else}span{/if} class="Title">{$panel.name}</{if $panel.status == 0}em{else}span{/if}>
							<strong class="PanelSettings">
								<a href="panel_editor.php?action=edit&amp;id={$panel.id}&amp;panel_side=3" class="tipTip" rel="{i18n('Edit')}">
									<img src="{$ADDR_ADMIN_ICONS}pixel/edit.png" class="icon" alt="{i18n('Edit')}" />
								</a>
								{if $panel.status == 0}
									<a href="javascript:void(0)" id="Status_{$panel.status}_{$panel.id}" class="ChangeStatus tipTip" rel="{i18n('Enable')}">
										<img src="{$ADDR_ADMIN_ICONS}pixel/checkmark.png" class="icon" alt="{i18n('Enable')}" />
									</a>
								{else}
									<a href="javascript:void(0)" id="Status_{$panel.status}_{$panel.id}"  class="ChangeStatus tipTip" rel="{i18n('Disable')}">
										<img src="{$ADDR_ADMIN_ICONS}pixel/against.png" class="icon" alt="{i18n('Disable')}" />
									</a>
								{/if}
								{*<a href="javascript:void(0)" id="Delete_{$panel.id}"  class="Delete tipTip" title="{i18n('Delete')}" rel="{{i18n('Delete this panel?')}">
									<img src="{$ADDR_ADMIN_ICONS}pixel/x.png" class="icon" alt="{i18n('Delete')}" />
								</a>*}
							</strong>
						</h2>
						<div class="DragBoxContent" >
							<ul class="PanelType">
								<li>{i18n('Panel type:')} {$panel.type}</li>
								<li>{i18n('Panel access:')} {$panel.access}</li>
							</ul>
						</div>
					</div>
				{/if}
			{/section}
		</div>

	</div>


	<div id="Column_4" class="PanelColumn grid_3">
		{section=panel}
			{if $panel.side == 4}
				<div class="DragBox ui-corner-all" id="Item_{$panel.id}">
					<h2>
						<{if $panel.status == 0}em{else}span{/if} class="Title">{$panel.name}</{if $panel.status == 0}em{else}span{/if}>
						<strong class="PanelSettings">
							<a href="panel_editor.php?action=edit&amp;id={$panel.id}&amp;panel_side=4" class="tipTip" rel="{i18n('Edit')}">
								<img src="{$ADDR_ADMIN_ICONS}pixel/edit.png" class="icon" alt="{i18n('Edit')}" />
							</a>
							{if $panel.status == 0}
								<a href="javascript:void(0)" id="Status_{$panel.status}_{$panel.id}" class="ChangeStatus tipTip" rel="{i18n('Enable')}">
									<img src="{$ADDR_ADMIN_ICONS}pixel/checkmark.png" class="icon" alt="{i18n('Enable')}" />
								</a>
							{else}
								<a href="javascript:void(0)" id="Status_{$panel.status}_{$panel.id}"  class="ChangeStatus tipTip" rel="{i18n('Disable')}">
									<img src="{$ADDR_ADMIN_ICONS}pixel/against.png" class="icon" alt="{i18n('Disable')}" />
								</a>
							{/if}
							{*<a href="javascript:void(0)" id="Delete_{$panel.id}"  class="Delete tipTip" title="{i18n('Delete')}" rel="{{i18n('Delete this panel?')}">
								<img src="{$ADDR_ADMIN_ICONS}pixel/x.png" class="icon" alt="{i18n('Delete')}" />
							</a>*}
						</strong>
					</h2>
					<div class="DragBoxContent" >
						<ul class="PanelType">
							<li>{i18n('Panel type:')} {$panel.type}</li>
							<li>{i18n('Panel access:')} {$panel.access}</li>
						</ul>
					</div>
				</div>
			{/if}
		{/section}
	</div>
</div>
<div class="tbl Buttons">
	<div class="center grid_2 button-c">
		<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
	</div>
</div>