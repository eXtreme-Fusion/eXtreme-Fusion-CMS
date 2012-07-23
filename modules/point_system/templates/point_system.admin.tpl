<h3 class="ui-corner-all">{$SystemVersion} - {i18n('Points System')}</h3>
{if $message}<div class="{$class}">{$message}</div>{/if}

<div class="tbl AdminButtons">
	<div class="center grid_2 button-l">
		<span class="Cancel"><strong>{i18n('Back')}<img src="{$ADDR_ADMIN_ICONS}pixel/undo.png" alt="" /></strong></span>
	</div>
	<div class="center grid_2 button-c">
		{if $add_points == 1}
			<span class="Cancels"><strong>{i18n('Points')}<img src="{$ADDR_ADMIN_ICONS}pixel/bulb.png" alt="" /></strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=points"><strong>{i18n('Points')}<img src="{$ADDR_ADMIN_ICONS}pixel/bulb.png" alt="" /></strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-c">
		{if $add_ranks == 1}
			<span class="Cancels"><strong>{i18n('Ranks')}<img src="{$ADDR_ADMIN_ICONS}pixel/clipboard.png" alt="" /></strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=ranks"><strong>{i18n('Ranks')}<img src="{$ADDR_ADMIN_ICONS}pixel/clipboard.png" alt="" /></strong></a></span>
		{/if}
	</div>
	<div class="center grid_2 button-r">
		{if $add_bonus == 1}
			<span class="Cancels"><strong>{i18n('Bonus')}<img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></span>
		{else}
			<span><a href="{$FILE_SELF}?page=bonus"><strong>{i18n('Bonus')}<img src="{$ADDR_ADMIN_ICONS}pixel/plus.png" alt="" /></strong></a></span>
		{/if}
	</div>
</div>

<form id="This" action="{$URL_REQUEST}" method="post">
	{if $add_points}
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="points">{i18n('Points')}:</label></div>
			<div class="formField grid_7"><input type="text" id="points" name="points" class="num_3" value="{$points}" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="section">{i18n('For What')}:<br /><small>{i18n('(f.e. Shoutbox)')}</small></label></div>
			<div class="formField grid_7"><input type="text" id="section" name="section" class="num_64" value="{$section}" {if $edit_points} disabled="disabled" {/if} /></div>
		</div>
		<div class="tbl AdminButtons">
			<div class="sep_2 center grid_3 button-l button-r">
				<input type="hidden" name="save_points" value="yes" />
				<span id="SendForm_This" class="Save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	{elseif $add_ranks}
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="points">{i18n('Points')}:<br /><small>{i18n('How many points accessible rank?')}</small></label></div>
			<div class="formField grid_7"><input type="text" id="points" name="points" class="num_3" value="{$points}" /></div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="ranks">{i18n('Rank')}:</label></div>
			<div class="formField grid_7"><input type="text" id="ranks" name="ranks" class="num_100" value="{$ranks}"/></div>
		</div>
		<div class="tbl AdminButtons">
			<div class="sep_2 center grid_3 button-l button-r">
				<input type="hidden" name="save_ranks" value="yes" />
				<span id="SendForm_This" class="Save"><strong>{i18n('Save')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	{elseif $add_bonus}
		<h4>{i18n('Add Points')}</h4>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3">{i18n('User:')}</div>
			<div class="formField grid_7">
				<select name="bonus_user" class="textbox">
					{section=user_list}
						<option value="{$user_list.value}">{$user_list.entry}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="points">{i18n('Points')}:</label></div>
			<div class="formField grid_7"><input type="text" id="points" name="points_bonus"/></div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="Comment">{i18n('Comment:')}</label></div>
			<div class="formField grid_7"><input type="text" id="Comment" name="add_comment"/></div>
		</div>
		<div class="tbl AdminButtons">
			<div class="sep_2 center grid_3 button-l button-r">
				<input type="hidden" name="plus_points" value="yes" />
				<span id="SendForm_This" class="Save"><strong>{i18n('Add')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
		<h4>{i18n('Substract Points')}</h4>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3">{i18n('User:')}</div>
			<div class="formField grid_7">
				<select name="fine_user" class="textbox">
					{section=user_list}
						<option value="{$user_list.value}">{$user_list.entry}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl2">
			<div class="formLabel sep_1 grid_3"><label for="points">{i18n('Points')}:</label></div>
			<div class="formField grid_7"><input type="text" id="points" name="points_fine"/></div>
		</div>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3"><label for="Comment">{i18n('Comment:')}</label></div>
			<div class="formField grid_7"><input type="text" id="Comment" name="fine_comment"/></div>
		</div>
		<div class="tbl AdminButtons">
			<div class="sep_2 center grid_3 button-l button-r">
				<input type="hidden" name="minus_points" value="yes" />
				<span id="SendForm_This" class="Save"><strong>{i18n('Remove')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
		<h4>{i18n('Delete all user points')}</h4>
		<div class="tbl1">
			<div class="formLabel sep_1 grid_3">{i18n('User:')}</div>
			<div class="formField grid_7">
				<select name="user" class="textbox">
					{section=user_list}
						<option value="{$user_list.value}">{$user_list.entry}</option>
					{/section}
				</select>
			</div>
		</div>
		<div class="tbl AdminButtons">
			<div class="sep_2 center grid_3 button-l button-r">
				<input type="hidden" name="delete_user_points" value="yes" />
				<span id="SendForm_This" class="Save"><strong>{i18n('Delete')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
		<h4>{i18n('Delete all users points')}</h4>
		<div class="tbl1">
			<div class="formLabel grid_10 center sep_1"><small>{i18n('Can not be undone. Think before you make.')}</small></div>
		</div>
		<div class="tbl AdminButtons">
			<div class="sep_2 center grid_3 button-l button-r">
				<input type="hidden" name="delete_all" value="yes" />
				<span id="SendForm_This" class="Save"><strong>{i18n('Delete all')}<img src="{$ADDR_ADMIN_ICONS}pixel/diskette.png" alt="" /></strong></span>
			</div>
		</div>
	{/if}
</form>


<h4>{i18n('Points')}</h4>
{if $point}
	<div class="tbl2">
		<div class="sep_2 grid_4 bold">{i18n('Section')}</div>
		<div class="grid_2 bold">{i18n('Points')}</div>
		<div class="grid_2 bold">{i18n('Admin actions')}</div>
	</div>
	{section=point}
		<div class="tbl {$point.row_color}">
			<div class="sep_2 grid_4">{$point.section}</div>
			<div class="grid_2">{$point.points}</div>
			<div class="grid_2">
				<a href="{$FILE_SELF}?page=points&amp;action=edit&amp;id={$point.id}" class="tip" title="{i18n('Edit')}">
					<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
				</a>
				<a href="{$FILE_SELF}?page=points&amp;action=delete_points&id={$point.id}" class="tip" title="{i18n('Delete')}">
					<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
				</a>
			</div>
		</div>
	{/section}
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('Not defined points')}</div>
	</div>
{/if}
<h4>{i18n('Ranks')}</h4>
{if $rank}
	<div class="tbl2">
		<div class="sep_2 grid_4 bold">{i18n('Rank')}</div>
		<div class="grid_2 bold">{i18n('Points')}</div>
		<div class="grid_2 bold">{i18n('Admin actions')}</div>
	</div>
	{section=rank}
		<div class="tbl {$rank.row_color}">
			<div class="sep_2 grid_4">{$rank.ranks}</div>
			<div class="grid_2">{$rank.points}</div>
			<div class="grid_2">
				<a href="{$FILE_SELF}?page=ranks&amp;action=edit&amp;id={$rank.id}" class="tip" title="{i18n('Edit')}">
					<img src="{$ADDR_ADMIN_ICONS}edit.png" alt="{i18n('Edit')}" />
				</a>
				{if $rank.id != 1}
					<a href="{$FILE_SELF}?page=ranks&amp;action=delete_ranks&id={$rank.id}" class="tip" title="{i18n('Delete')}">
						<img src="{$ADDR_ADMIN_ICONS}delete.png" alt="{i18n('Delete')}" />
					</a>
				{/if}
			</div>
		</div>
	{/section}
{else}
	<div class="tbl2">
		<div class="sep_1 grid_10 center">{i18n('Not defined points')}</div>
	</div>
{/if}