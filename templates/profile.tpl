{if $profile}
    {panel=i18n('Member Profile')}
        <div id="profile" class="dark">
            <div class="profile_avatar">
                <img src="{$user.avatar}" alt="Avatar" class="light border_light">
                {if $user.last_visit_time}<p>{if $user.is_online == 1}<img src="{$ADDR_IMAGES}profile/online.png" alt="{i18n('Online')}"><span class="online">{i18n('Online')}</span>{else}<img src="{$ADDR_IMAGES}profile/offline.png" alt="{i18n('Offline')}">{$user.last_visit_time}{/if}</p>{/if}
            </div>
            <div class="profile_title">
				<img src="{$ADDR_IMAGES}profile/info.png" alt="Profile"><span id="profile_username">{i18n('Member Profile :Username', array(':Username' => $user.username))}</span>{if $user.myid > 0 && $user.id != $user.myid}<a href="{$user.pm_link}" title="{i18n('Send a message')}" class="text_dark">[ {i18n('Send a message')} ]</a>{/if}<span id="profile_status" class="text_dark">{$user.role}</span>
			</div>
            <ul class="light border_top_other">
                {if ! $user.hide_email || $iAdmin}<li><strong>{i18n('e-Mail')}:</strong><span>{$user.email}</span></li>{/if}
                <li><strong>{i18n('Joined')}:</strong><time datetime="{$user.joined_datetime}">{$user.joined}</time></li>
                <li><strong>{i18n('Groups')}:</strong><span>{$user.roles}</span></li>
            </ul>
        </div>
        
        <nav class="tab_menu">
            <ul>
                <li><a href="javascript:void(0)" id="tab_stats" class="tab">{i18n('Statistics')}</a></li>
                {section=cats}
                    {if $cats.has_fields == 1}
                        <li><a href="javascript:void(0)" id="tab_{$cats.id}" class="tab">{$cats.name}</a></li>
                    {/if}
                {/section}
            </ul>
        </nav>

        <div class="tab_cont" id="tab_cont_stats">
            <p class="element light clearfix">
                <strong class="text_other">{i18n('Posted news')}</strong>
                <span>{$user.news}</span>
            </p>
            <p class="element light clearfix">
                <strong class="text_other">{i18n('Posted comments')}</strong>
                <span>{$user.comment}</span>
            </p>
            {if $chat != 0}
                <p class="element light clearfix">
                    <strong class="text_other">{i18n('Posted chat')}</strong>
                    <span>{$chat}</span>
                </p>
            {/if}
            {if $cautions != 0}
                <p class="element light clearfix">
                    <strong class="text_other"><a href="{$cautions.link}">{i18n('Warnings')}</a></strong>
                    <span>{$cautions.cautions}</span>
                </p>
            {/if}
            {if $points_stat}
                <p class="element light clearfix">
                    <strong class="text_other">{i18n('Points')}</strong>
                    <span>{$points_stat.points}</span>
                </p>
                <p class="element light clearfix">
                    <strong class="text_other">{i18n('Rank')}</strong>
                    <span>{$points_stat.rank}</span>
                </p>
            {/if}
        </div>
        
        {section=cats}
            {if $cats.has_fields == 1}
                <div class="tab_cont" id="tab_cont_{$cats.id}">
                    {section=fields}
                        {if $fields.value && ($fields.type != 2)}
                            <p class="element light clearfix">
                                <strong class="text_other">{i18n($fields.name)}</strong>
                                <span>{$fields.value}</span>
                            </p>
                        {/if}
                    {/section}
                    {section=fields}
                        {if $fields.value && $fields.type == 2}
                            <div class="element_big">
                                <strong class="light text_other">{i18n($fields.name)}</strong>
                                <p>{$fields.value}</p>
                            </div>
                        {/if}
                    {/section}
                </div>
            {/if}
        {/section}

    {/panel}
    
    {if $points}
        {panel=i18n('Points - Admin Section')}
        <div class="tbl">
            <form id="This" action="{$URL_REQUEST}" method="post">
                <h4>{i18n('Add points this user')}</h4>
                <div class="tbl2">
                    <div class="formLabel grid_2"><label for="points">{i18n('Points:')}</label></div>
                    <div class="formField grid_7"><input type="text" id="points" name="points_bonus" class="textbox" /></div>
                </div>
                <div class="tbl1">
                    <div class="formLabel grid_2"><label for="comment">{i18n('Comment:')}</label></div>
                    <div class="formField grid_7"><input type="text" id="comment" name="add_comment" class="textbox"/></div>
                </div>
                <div class="tbl">
                    <div class="sep_1 center grid_5 button-l button-r">
                        <input type="submit" name="plus_points" value="{i18n('Add')}" class="button" />
                    </div>
                </div>
                
                <h4>{i18n('Take away points of user')}</h4>
                <div class="tbl2">
                    <div class="formLabel grid_2"><label for="points">{i18n('Points:')}</label></div>
                    <div class="formField grid_7"><input type="text" id="points" name="points_fine" class="textbox" /></div>
                </div>
                <div class="tbl1">
                    <div class="formLabel grid_2"><label for="comment">{i18n('Comment:')}</label></div>
                    <div class="formField grid_7"><input type="text" id="comment" name="fine_comment" class="textbox"/></div>
                </div>
                <div class="tbl">
                    <div class="sep_1 center grid_5 button-l button-r">
                        <input type="submit" name="minus_points" value="{i18n('Remove')}" class="button" />
                    </div>
                </div>
                
				<h4>{i18n('Deleting all points')}</h4>
                <div class="tbl">
                    <div class="sep_1 center button-l button-r">
                        <div class="formLabel grid_6"><small>{i18n('Deletes all points, but does not delete history')}</small></div>
                        <div class="formField grid_5"><input type="submit" name="delete_user_points" value="{i18n('Delete')}" class="button grid_4 sep_1" /></div>
                    </div>
                </div>
            </form>
        </div>
        {/panel}
    {/if}
{/if}