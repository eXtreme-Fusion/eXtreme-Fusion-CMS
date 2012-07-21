{php} opentable(__('Messages')) {/php}

{*PODSTRONA W TRAKCIE PRZYGOTOWANIA, PROSIMY NIE ZGŁASZAC BLEDOW JEJ DOTYCZACYCH ZA TO SLEDZIC AKTUALIZACJE SYSTEMU*}

{if $section == 'overview'}
	<div id="messages_page">
		<p><a href="{$url_new_message}" class="button">{i18n('Write a message')}</a></p>
		
		{if $data}
			<p class="info">{i18n('Messages are deleted automatically after 60 days from the time they were sent.')}</p>
			
			{section=data}
				<article class="read_{if $data.read_status == 1}0{else}1{/if} clearfix">
					<span class="arrow"></span>
					{if $data.user_avatar}
						<img src="{$ADDR_IMAGES}avatars/{$data.user_avatar}" alt="Avatar" class="avatar">
					{else}
						<img src="{$ADDR_IMAGES}avatars/none.gif" alt="No Avatar" class="avatar">
					{/if}
					<div class="pw_cont">
						<span class="interlocutor">{$data.user_link}</span>
						<h4><a href="{$data.msg_link}" title="{if $data.subject}{$data.subject}{else}{i18n('Bez tematu')}{/if}" class="tip">{if $data.subject}{$data.subject}{else}{i18n('Bez tematu')}{/if}</a></h4>
						<time datetime="{$data.datetotime}">{$data.datestamp}</time>
						{if $data.read_status == '1'}
							<p class="new_mes">Nowa</p>
						{elseif $data.read_status == '2'}
							<p class="read_mes">Przeczytana</p>
						{elseif $data.read_status == '3'}
							<p class="sent_mes">Wysłana</p>
						{elseif $data.read_status == '4'}
							<p class="del_mes">Dostarczona</p>
						{/if}
					</div>
				</article>
			{/section}
		{else}
			<p class="status">{i18n('There are no messages.')}</p>
		{/if}
	</div>
{elseif $section == 'new-by-search' || $section == 'new-by-user' || $section == 'entry'}
	<div id="messages_page">
		{* Tutaj będzie wczytana przez Ajax treść wiadomości *}
		<div id="ajax_messages"></div>

		{*Odpowiedź po wysłaniu wiadomości - co to? nie wiadomo więc xakomentuję ~Inscure*}
		{*<div id="form_request"></div>*}

		<form action="{$URL_REQUEST}" method="post">
			{if $section == 'new-by-search' || $section == 'new-by-user'}
				<div class="line">
					{if $section == 'new-by-user'}
						<span class="label">{i18n('To:')}</span>
						<span id="defender_user">{$user.username}</span>
						<input type="hidden" name="to" id="message_to" value="{$user.id}">
					{else}
						<label for="send_to">{i18n('To:')}</label>
						<div class="message_to_menu">
							<input type="text" name="jQTo" id="send_to" autocomplete="off" required>
							<input type="hidden" name="to" id="message_to">
						</div>
					{/if}
				</div>
				<div class="line">
					<label for="message_subject">{i18n('Subject:')}</label> 
					<input type="text" name="subject" value="{i18n('Enter a topic name')}..." id="message_subject" required>
				</div>
			{else}
				<div class="line">
					<span class="label">{i18n('To:')}</span>
					<span id="defender_user">{$user.username}</span>
					<input type="hidden" name="to" id="message_to" value="{$user.id}">
				</div>
			{/if}
			<div class="line">
				<label for="message">{i18n('Message:')}</label> 
				<textarea name="message" rows="5" id="message" required>{i18n('Enter your message')}...</textarea>
			</div>
			<div class="line center">
				{section=bbcode}
					<button type="button" onClick="addText('{$bbcode.textarea}', '[{$bbcode.value}]', '[/{$bbcode.value}]');"><img src="{$bbcode.image}" title="{$bbcode.description}" alt="{$bbcode.value}" class="tip"></button>
				{/section}
			</div>
			<div class="line center">
				<a href="{$ADDR_SITE}messages.html" class="button">{i18n('Back')}</a>
				<input type="submit" name="send" class="button" value="{i18n('Send')}">
				{* Zmienna item_id istnieje tylko dla podstrony `entry`. Przy nowej konwersacji jest otrzymywane przez żądanie Ajax. *}
				<input type="hidden" value="{$item_id}" name="item_id">
			</div>
		</form>
	</div>
{else}
	<p class="status">{i18n('Nie ma takiej podstrony.')}</p>
{/if}

{php} closetable() {/php}