{if $rows}
	{php} opentable(__('Member Profile')) {/php}
		<div class="profile">
			<div class="profile-avatar">
				{if $user.avatar}
					<img src="{$ADDR_IMAGES}avatars/{$user.avatar}" class="avatar">
				{else}
					<img src="{$ADDR_IMAGES}avatars/none.jpg" class="avatar">
				{/if}
			</div>
			<div class="profile-title"><img src="{$ADDR_IMAGES}profile/info.png"><span id="profile-username">{i18n('Member Profile :Username', array(':Username' => $user.username))}</span><span id="profile-status">{$user.role}</span></div>
			<div class="profile-info tbl2">
				<div><strong>{i18n('e-Mail')}:</strong>{$user.email}</div>
				<div><strong>{i18n('Joined')}:</strong>{$user.joined}</div>
				<div><strong>{i18n('Last Visit')}:</strong>{if $user.is_online == 1}<span>{i18n('Online')}</span><img src="{$ADDR_IMAGES}online.png" alt="{i18n('Online')}">{else}<span>{$user.last_visit_time}</span><img src="{$ADDR_IMAGES}offline.png" alt="{i18n('Offline')}">{/if}</div>
				<div><strong>{i18n('Groups')}:</strong>{$user.roles}</div>
			</div>
		</div>
		
		<div id='pettabs' class='indentmenu'>
			<ul>
				<li><a href="javascript:void(0)" id="tab_stats" class="tab">Statystyki</a></li>
				{section=cats}
					<li><a href="javascript:void(0)" id="tab_{$cats.id}" class="tab">{$cats.name}</a></li>
				{/section}
			</ul>
		</div>

		<div class="tab_cont profile-fields" id="tab_cont_stats">
			<div class="element col">
				<strong>Dodane newsy</strong>
				<p>{$user.news}</p>
				<div class="clear"></div>
			</div>
			<div class="element col">
				<strong>Napisane komentarze</strong>
				<p>{$user.comment}</p>
				<div class="clear"></div>
			</div>
			{if $chat != 0}
				<div class="element col">
					<strong>Posty w chacie</strong>
					<p>{$chat}</p>
					<div class="clear"></div>
				</div>
			{/if}
			{if $cautions != 0}
				<div class="element col">
					<strong><a href="{$cautions.link}">Ostrzeżenia</a></strong>
					<p>{$cautions.cautions}</p>
					<div class="clear"></div>
				</div>
			{/if}
			{if $points_stat}
				<div class="element col">
					<strong>Punkty</strong>
					<p>{$points_stat.points}</p>
					<div class="clear"></div>
				</div>
				<div class="element col">
					<strong>Ranga</strong>
					<p>{$points_stat.rank}</p>
					<div class="clear"></div>
				</div>
			{/if}
		</div>
		{section=cats}
			<div class="tab_cont profile-fields" id="tab_cont_{$cats.id}">
				{section=fields}
				{if $fields.type == 2}
					<div class="element_big">
						<strong>{i18n($fields.name)}</strong>
						<p>{if $fields.value}{$fields.value}{else}{i18n('No data')}{/if}</p>
						<div class="clear"></div>
					</div>
				{else}
					<div class="element">
						<strong>{i18n($fields.name)}</strong>
						<p>{if $fields.value}{$fields.value}{else}{i18n('No data')}{/if}</p>
						<div class="clear"></div>
					</div>
				{/if}
				{/section}
			</div>
		{/section}
	{php} closetable() {/php}
	
	{if $points}
		{php} opentable('Punktacja - admin') {/php}
		<div class="tbl">
			<form id="This" action="{$URL_REQUEST}" method="post">
				<h4>Dodaj punkty użytkownikowi</h4>1
				<div class="tbl2">
					<div class="formLabel grid_2"><label for="points">{i18n('Punkty:')}</label></div>
					<div class="formField grid_7"><input type="text" id="points" name="points_bonus" class="textbox" /></div>
				</div>
				<div class="tbl1">
					<div class="formLabel grid_2"><label for="comment">{i18n('Komentarz:')}</label></div>
					<div class="formField grid_7"><input type="text" id="comment" name="add_comment" class="textbox"/></div>
				</div>
				<div class="tbl">
					<div class="sep_1 center grid_5 button-l button-r">
						<input type="submit" name="plus_points" value="{i18n('Add')}" class="button" />
					</div>
				</div>
				
				<h4>Odejmij punkty użytkownikowi</h4>
				<div class="tbl2">
					<div class="formLabel grid_2"><label for="points">{i18n('Punkty:')}</label></div>
					<div class="formField grid_7"><input type="text" id="points" name="points_fine" class="textbox" /></div>
				</div>
				<div class="tbl1">
					<div class="formLabel grid_2"><label for="comment">{i18n('Komentarz:')}</label></div>
					<div class="formField grid_7"><input type="text" id="comment" name="fine_comment" class="textbox"/></div>
				</div>
				<div class="tbl">
					<div class="sep_1 center grid_5 button-l button-r">
						<input type="submit" name="minus_points" value="{i18n('Remove')}" class="button" />
					</div>
				</div>
				
				<h4>Usunięcie wszystkich punktów</h4>
				<div class="tbl">
					<div class="sep_1 center button-l button-r">
						<div class="formLabel grid_6"><small>Usuwa wszystkie punkty użytkownika, ale nie usuwa historii</small></div>
						<div class="formField grid_5"><input type="submit" name="delete_user_points" value="{i18n('Delete')}" class="button grid_4 sep_1" /></div>
					</div>
				</div>
			</form>
		</div>
		{php} closetable() {/php}
	{/if}
{else}
	{php} opentable(__('Error 404')) {/php}
		<div class='status'>{i18n('Nie znaleziono profilu.')}</div>
	{php} closetable() {/php}
{/if}