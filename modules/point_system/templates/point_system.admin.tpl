<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Points System')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}
{if $config.development}<div class="error">{i18n($config.developmentMessage)}</div>{/if}

<div class="tbl Buttons">
	<div class="center grid_2 button-l">
		<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
	</div>
	<div class="center grid_2 button-c">
		{if $point == 1}
			<span class="Cancels"><strong>{i18n('Points')}<img src="{$ADDR_ADMIN_ICONS}pixel/bulb.png" alt="" /></strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=points"><strong>{i18n('Points')}<img src="{$ADDR_ADMIN_ICONS}pixel/bulb.png" alt="" /></strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-c">
		{if $rank == 1}
			<span class="Cancels"><strong>{i18n('Ranks')}<img src="{$ADDR_ADMIN_ICONS}pixel/clipboard.png" alt="" /></strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=ranks"><strong>{i18n('Ranks')}<img src="{$ADDR_ADMIN_ICONS}pixel/clipboard.png" alt="" /></strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-r">
		{if $bonus == 1}
			<span class="Cancels"><strong>{i18n('Bonus')}<img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=bonus"><strong>{i18n('Bonus')}<img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></a></span>
		{/if}
	</div>
</div>

<form id="This" action="{$URL_REQUEST}" method="post">
	{if $point}
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="points">{i18n('Points')}:</label></div>
			<div class="formField grid_7"><input type="text" id="points" name="points" class="num_4" value="{$points}" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="section">{i18n('For What')}:<br /><small>{i18n('(f.e. Shoutbox)')}</small></label></div>
			<div class="formField grid_7"><input type="text" id="section" name="section" class="num_64" value="{$section}" {if $edit} disabled="disabled" {/if} /></div>
		</div>
		<div class="tbl Buttons">
			<div class="sep_2 center grid_3 button-l button-r">
				<input type="hidden" name="save_points" value="yes" />
				<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	{elseif $rank}
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="points">{i18n('Points')}:<br /><small>{i18n('How many points accessible rank?')}</small></label></div>
			<div class="formField grid_7"><input type="text" id="points" name="points" class="num_3" value="{$points}" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="ranks">{i18n('Rank')}:</label></div>
			<div class="formField grid_7"><input type="text" id="ranks" name="ranks" class="num_100" value="{$ranks}"/></div>
		</div>
		<div class="tbl Buttons">
			<div class="sep_2 center grid_3 button-l button-r">
				<input type="hidden" name="save_ranks" value="yes" />
				<span id="SendForm_This" class="save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	{elseif $bonus}
		{if $point_user.user_bonus}
			<h4>{$user.username} :: Zarządzanie punktami</h4>
			<div class="tbl">
				<div class="grid_2 right bold">Punkty:</div>
				<div class="grid_2">{$point_user.points}</div>
				<div class="grid_2 bold">Ranga:</div>
				<div class="grid_3">{$point_user.rank}</div>
			</div>
			
			<h4>Ostatnia historia punktacji</h4>
			{if $history}
				<div class="tbl2">
					<div class="sep_1 grid_3 bold">Data</div>
					<div class="grid_2 bold">Punkty</div>
					<div class="grid_5 bold">Treść</div>
				</div>
				<div style="max-height:50px;overflow:auto;">
					{section=history}
						<div class="tbl">
							<div class="sep_1 grid_3">{$history.date}</div>
							<div class="grid_2">{$history.points}</div>
							<div class="grid_5">{$history.text}</div>
						</div>
					{/section}
				</div>
			{else}
				<div class="info">Brak wpisów w histori</div>
			{/if}
			
			<h3>Zarządzanie</h3>
				<h4>{i18n('Add Points')}</h4>
				<div class="tbl2">
					<div class="formLabel sep_1 grid_3"><label for="points">{i18n('Points')}:</label></div>
					<div class="formField grid_7"><input type="text" id="points" name="points_bonus"/></div>
				</div>
				<div class="tbl1">
					<div class="formLabel sep_1 grid_3"><label for="Comment">{i18n('Comment:')}</label></div>
					<div class="formField grid_7"><input type="text" id="Comment" name="add_comment"/></div>
				</div>
				<div class="tbl Buttons">
					<div class="sep_2 center grid_3 button-l button-r">
						<input type="hidden" name="bonus_user" value="{$user.id}" />
						<input type="hidden" name="plus_points" value="yes" />
						<span id="SendForm_This" class="save"><strong>{i18n('Add')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
					</div>
				</div>
				
				<h4>{i18n('Substract Points')}</h4>
				<div class="tbl2">
					<div class="formLabel sep_1 grid_3"><label for="points">{i18n('Points')}:</label></div>
					<div class="formField grid_7"><input type="text" id="points" name="points_fine"/></div>
				</div>
				<div class="tbl1">
					<div class="formLabel sep_1 grid_3"><label for="Comment">{i18n('Comment:')}</label></div>
					<div class="formField grid_7"><input type="text" id="Comment" name="fine_comment"/></div>
				</div>
				<div class="tbl Buttons">
					<div class="sep_2 center grid_3 button-l button-r">
						<input type="hidden" name="fine_user" value="{$user.id}" />
						<input type="hidden" name="minus_points" value="yes" />
						<span id="SendForm_This" class="save"><strong>{i18n('Remove')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
					</div>
				</div>
				
				<h4>{i18n('Delete all user points')}</h4>
				<div class="tbl Buttons">
					<div class="sep_2 center grid_3 button-l button-r">
						<input type="hidden" name="user" value="{$user.id}" />
						<input type="hidden" name="delete_user_points" value="yes" />
						<span id="SendForm_This" class="save"><strong>{i18n('Delete')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
					</div>
				</div>
		{else}
			<div class="tbl">
				<div class="sep_1 grid_2">{i18n('Find user:')}</div>
				<div class="grid_7"><input type="text" id="search_user" style="width:300px;" /></div>
			</div>
			<div class="tbl">
				<div id="search_user_result" class="sep_3 grid_7"></div>
			</div>
			
			<div class="buttons-bg">&nbsp;</div>
			
			<h4>{i18n('Delete all users points')}</h4>
			<div class="tbl1">
				<div class="formLabel grid_10 center sep_1"><small>{i18n('Can not be undone. Think before you make.')}</small></div>
			</div>
			<div class="tbl Buttons">
				<div class="sep_2 center grid_3 button-l button-r">
					<input type="hidden" name="delete_all" value="yes" />
					<span id="SendForm_This" class="save"><strong>{i18n('Delete all')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
				</div>
			</div>
		{/if}
	{/if}
</form>

{if !$bonus}
	<h4>{i18n('Points')}</h4>
	{if $point_list}
		<div class="tbl2">
			<div class="sep_2 grid_4 bold">{i18n('Section')}</div>
			<div class="grid_2 bold">{i18n('Points')}</div>
			<div class="grid_2 bold">{i18n('Admin actions')}</div>
		</div>
		{section=point_list}
			<div class="tbl {$point_list.row_color}">
				<div class="sep_2 grid_4">{$point_list.section}</div>
				<div class="grid_2">{$point_list.points}</div>
				<div class="grid_2">
					<a href="{$FILE_SELF}?page=points&amp;action=edit&amp;id={$point_list.id}" class="tip" title="{i18n('Edit')}">
						<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
					</a>
					<a href="{$FILE_SELF}?page=points&amp;action=delete_points&id={$point_list.id}" class="tip" title="{i18n('Delete')}">
						<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
					</a>
				</div>
			</div>
		{/section}
	{else}
		<div class="tbl2">
			<div class="sep_1 grid_10 center">
				<div class="info">{i18n('Not defined points')}</div>
			</div>
		</div>
	{/if}
	<h4>{i18n('Ranks')}</h4>
	{if $rank_list}
		<div class="tbl2">
			<div class="sep_2 grid_4 bold">{i18n('Rank')}</div>
			<div class="grid_2 bold">{i18n('Points')}</div>
			<div class="grid_2 bold">{i18n('Admin actions')}</div>
		</div>
		{section=rank_list}
			<div class="tbl {$rank_list.row_color}">
				<div class="sep_2 grid_4">{$rank_list.ranks}</div>
				<div class="grid_2">{$rank_list.points}</div>
				<div class="grid_2">
					<a href="{$FILE_SELF}?page=ranks&amp;action=edit&amp;id={$rank_list.id}" class="tip" title="{i18n('Edit')}">
						<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
					</a>
					{if $rank_list.id != 1}
						<a href="{$FILE_SELF}?page=ranks&amp;action=delete_ranks&id={$rank_list.id}" class="tip" title="{i18n('Delete')}">
							<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
						</a>
					{/if}
				</div>
			</div>
		{/section}
	{else}
		<div class="tbl2">
			<div class="sep_1 grid_10 center">
				<div class="info">{i18n('Not defined points')}</div>
			</div>
		</div>
	{/if}
{/if}

<script src="{$ADDR_MODULES}point_system/templates/javascripts/users.js"></script>