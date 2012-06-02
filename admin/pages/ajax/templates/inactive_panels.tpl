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
