{php} opentable(__('Messages')) {/php}

PODSTRONA W TRAKCIE PRZYGOTOWANIA, PROSIMY NIE ZGŁASZAC BLEDOW JEJ DOTYCZACYCH ZA TO SLEDZIC AKTUALIZACJE SYSTEMU

{if $section == 'overview'}
	<a href="{$url_new_message}" class="button" id="button">{i18n('Write a message')}</a>
	
	{if $data}
		<p class="status">{i18n('Messages are deleted automatically after 60 days from the time they were sent.')}</p>
		
		<div class="tbl1">
			<div class="grid_2 bold">{i18n('Rozmówca:')}</div>
			<div class="grid_4 bold">{i18n('Temat:')}</div>
			<div class="grid_2 bold">{i18n('Status:')}</div>
			<div class="grid_2 bold">{i18n('Data:')}</div>
		</div>
		{section=data}
			<div class="tbl{if $data.read == 0}2{else}1{/if}">
				<div class="grid_2">{$data.user_link}</div>
				<div class="grid_4"><a href="{$data.msg_link}">{if $data.subject}{$data.subject}{else}{i18n('Bez tematu')}{/if}</a></div>
				<div class="grid_2">
					{if $data.read_status == '1'}
						Nowa
					{elseif $data.read_status == '2'}
						Przeczytana
					{elseif $data.read_status == '3'}
						Wysłana
					{elseif $data.read_status == '4'}
						Dostarczona
					{/if}
				</div>
				<div class="grid_2">{$data.datestamp}</div>
			</div>
		{/section}
	{else}
		<div class="status">{i18n('There are no messages.')}</div>
	{/if}
{elseif $section == 'new-by-search' || $section == 'new-by-user' || $section == 'entry'}
	<div id="messages_frame">

		{* Tutaj będzie wczytana przez Ajax treść wiadomości *}
		<section></section>

		{*Odpowiedź po wysłaniu wiadomości - co to? nie wiadomo więc xakomentuję ~Inscure*}
		{*<div id="form_request"></div>*}

		<div id="messages_form">
			<form action="{$URL_REQUEST}" method="post">
				{if $section == 'new-by-search' || $section == 'new-by-user'}
					<div class="tbl1">
						<label for="message_to">{i18n('To:')}</label>

						{if $section == 'new-by-user'}
							{$user.username}
							<input type="hidden" name="to" id="message_to" value={$user.id}>
						{else}
							<div class="message_to_menu">
								<input type="hidden" name="to" id="message_to">
								<input type="text" name="jQTo" id="send_to" required>
							</div>
						{/if}
					</div>
					<div class="tbl2">
						<div>{i18n('Subject:')}</div> 
						<input type="text" name="subject" value='{i18n('Enter a topic name')}...' onfocus="if(this.value=='{i18n('Enter a topic name')}...') this.value='' ; " onblur="if(this.value=='') this.value='{i18n('Enter a topic name')}...';"  id="message_subject" required /><br />
					</div>
				{else}
					<p>{i18n('To:')} {$user.username}</p>
					<input type="hidden" name="to" id="message_to" value={$user.id}>
				{/if}
				<div class="tbl1">
					<div>{i18n('Message:')}</div> 
					<input type="text" name="message" style="width: 70%;"  value='{i18n('Enter your message')}...' onfocus="if(this.value=='{i18n('Enter your message')}...') this.value='' ; " onblur="if(this.value=='') this.value='{i18n('Enter your message')}...';" wrap="phisical" id="message" autocomplete="off" />
				</div>
				<div class="tbl1">
					{section=bbcode}
						<button type="button" onClick="addText('{$bbcode.textarea}', '[{$bbcode.value}]', '[/{$bbcode.value}]');"><img src="{$bbcode.image}" title="{$bbcode.description}" alt="{$bbcode.value}" class="tip"></button>
					{/section}
				</div>
				<div class="tbl2">
					<div id="messages_buttons" >
						<a href="{$ADDR_SITE}messages.html" class="button">{i18n('Back')}</a>
						<input type="submit" name="send" class="button" value="{i18n('Send')}" />
					</div>
					{* Zmienna item_id istnieje tylko dla podstrony `entry`. Przy nowej konwersacji jest otrzymywane przez żądanie Ajax. *}
					<input type="hidden" value="{$item_id}" name="item_id" />
				</div>
			</form>
		</div>
	</div>
{else}
	<div class="status">{i18n('Nie ma takiej podstrony.')}</div>
{/if}

{php} closetable() {/php}
