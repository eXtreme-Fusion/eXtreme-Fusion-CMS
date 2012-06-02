{php} opentable(__('Messages')) {/php}

{if $action == ''}
	{if $data}
		<div class="status" style="width: 70%;">{i18n('Messages are deleted automatically after 60 days from the time they were sent.')}</div>
			<a href="{$url_new_message}" class="button" id="button">{i18n('Write a message')}</a>
		<div class="tbl1" style="clear:both">
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
		<a href="{$url_new_message}" class="button" id="button">{i18n('Write a message')}</a>
	{/if}
{elseif $action == 'view'}
		  <div id="messages_frame">
			<section></section>



		 {*Odpowiedź po wysłaniu wiadomości*}
		  <div id="form_request"></div>
		{*}	{*} <div id="messages_form">
			<form action="{$URL_REQUEST}" method="post" style="width: 100%;">
				{if $new_discuss}
				<div>{i18n('Subject:')}</div> <input type="text" name="subject"  style="width: 70%;" value='Podaj nazwe tematu...' onfocus="if(this.value=='Podaj nazwe tematu...') this.value='' ; " onblur="if(this.value=='') this.value='Podaj nazwe tematu...';"  id="message_subject" required /><br />
				{/if}
				   <div>{i18n('Treść:')}</div> <input type="text" name="message" style="width: 70%;"  value='Podaj treść wiadomości...' onfocus="if(this.value=='Podaj treść wiadomości...') this.value='' ; " onblur="if(this.value=='') this.value='Podaj treść wiadomości...';" wrap="phisical" id="message" autocomplete="off" />
				 {*}<textarea cols="70" rows="20" id="message" name="message" style="width: 70%;"  value}='Podaj treść wiadomości...' onfocus="if(this.value=='Podaj treść wiadomości...') this.value='' ; " onblur="if(this.value=='') this.value='Podaj treść wiadomości...';" > </textarea>{*}
			  <div id="messages_buttons" >
				<a href="{$ADDR_SITE}messages.html" class="button">{i18n('Back')}</a>
				<input type="submit" name="send" class="button" value="{i18n('Send')}" />

				<input type="hidden" value="{$get_item_id}" name="item_id" />
				<input type="hidden" value="{$to}" name="to"/></div>
			</form>
		  </div>
		  </div>

{elseif $action == 'new'}
		<div class="tbl">
		<div class="sep_1 grid_2">
		<div class="grid_7">
			<div class="grid_7">{i18n('Do:')} <input type="text" id="message_from" class="long_textbox" /></div>
			<div class="grid_7"><div id="message_from_result"></div></div>
		</div>
		</div>
	</div>
	<script src="{$ADDR_JS}users.js"></script>
{else}
		  <div class="status">{i18n('Nie ma takiej podstrony.')}</div>
{/if}

{php} closetable() {/php}
