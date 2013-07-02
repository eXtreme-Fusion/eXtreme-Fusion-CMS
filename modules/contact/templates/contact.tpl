{if $contact}
	{php} $this->middlePanel(__('Contact with :contact', array(':contact' => $this->data['contact']['title']))); {/php}
	{if $error}
		<div class="error">
			{section=error}
				<p>
					{if $error == '1'}
						{i18n('Nie uzupełniono wymaganych pól.')}
					{elseif $error == '2'}
						{i18n('Podano niepoprawny kod bezpieczeństwa.')}
					{elseif $error == '3'}
						{i18n('Adres nadawcy wiadomości jest nieprawidłowy.')}
					{elseif $error == '4'}
						{i18n('Wystąpił błąd przy próie wysłania wiadomości. Należy o tym poinformować Administrację strony.')}
					{/if}
				</p>
			{/section}
		</div>
	{/if}

	{if $message}<div class="{$class}">{$message}</div>{/if}
		<form id="This" action="{$URL_REQUEST}" method="post">
			<div class="tbl center">
				<p>{$contact.description}</strong>
			</div>
			<div class="tbl">
				<div class="formLabel grid_2 sep_1"><label for="email">Twój adres e-Mail:</label></div>
				<div class="formField grid_7">
					{if $iUSER}
						<input type="text" name="email" id="email" class="textbox" style="width:368px;" value="{$email}" />
					{else}
						<input type="text" name="email" id="email" class="textbox" style="width:368px;" value="{$email}"/>
					{/if}
				</div>
			</div>
			<div class="tbl">
				<div class="formLabel grid_2 sep_1"><label for="subject">Temat:</label></div>
				<div class="formField grid_7"><input type="text" name="subject" id="subject" class="textbox" style="width:368px;" value="{$subject}"/></div>
			</div>
			<div class="tbl">
				<div class="formLabel grid_2 sep_1"><label for="message">Wiadomość:</label></div>
				<div class="formField grid_7"><textarea name="message" cols="50" rows="7" id="message" class="textbox">{if $form_message}{$form_message}{else}{$contact.value}{/if}</textarea></div>
			</div>
			<div class="tbl">
				<div class="formLabel grid_2 sep_1"><label for="message">Wyślij mi kopię:</label></div>
				<div class="formField grid_7"><input type="checkbox" name="sendme_copy" value="send"{if $sendme_copy} checked="checked"{/if} /></div>
			</div>

			{if $security}{$security}{/if}
			<div class="tbl center">
				<div class="grid_11 center">
					<input type="reset" value="Wyczyść" class="button1" />
					<input type="hidden" name="send_mail" value="{$contact.email}" />
					<input type="hidden" name="security" value="{$answer}" />
					<input class="Send" type="submit" name="send_message" value="{i18n('Send')}">
				</div>
			</div>
		</form>
	{php} $this->middlePanel(); {/php}
{else}
	{php} $this->middlePanel(__('Contact')); {/php}
	{if $contacts}
		<div class="sep_1 grid_4 tbl">
			{section=contacts}
				<div><img src="{$THEME_IMAGES}bullet.png"> <a href="{$contacts.link}">{$contacts.title}</a></div>
			{/section}
		</div>
	{else}
		<div class="info">Brak formularzy</div>
	{/if}
	{php} $this->middlePanel(); {/php}
{/if}