<?php defined('EF5_SYSTEM') || exit;

return array(
	'Contact' => 'Kontakt',
	'Contact Ways' => '
		Správce tohoto webu :site_name lze kontaktovat prostřednictvím: 
		<ul><li> odeslání e-mailu :hidemail.</li><li> Odesláním <a href="messages.php?msg_send=1">soukromé zprávy</a>.<li> Odesláním formuláře, který naleznete níže. Obsah formuláře bude doručen na e-mail hlavního administrátora.</li></ul>
	',
	'Name:' => 'Název uživatele:',
	'Email Address:' => 'Adresa e-mailu:',
	'Subject:' => 'Předmět:',
	'Message:' => 'Zpráva:',
	'Send Message' => 'Odeslat',
	'Validation Code:' => 'Potvrzovací kód:',
	'Enter Validation Code:' => 'Zadejte potvrzovací kód',
	// Błędy
	'You must specify a Name' => 'Musíte vyplnit své jméno',
	'You must specify an Email Address' => 'Musíte vyplnit svoji e-mailovou adresu',
	'You must specify a Subject' => 'Musíte uvést předmět',
	'You must specify a Message' => 'Musíte napsat text zprávy',
	'You must enter correct Validation Code' => 'Musíte vložit správný validační kód',
	'Internal error: cannot send your message.' => 'Vnitřní chyba! Nemůžete odesílat zprávy.',
	// Wiadomość wysłana
	'Your Message has been sent' => 'Tato zpráva byla odeslána.',
	'Thank You' => 'Děkujeme',
	'Message was not sent' => 'Zpráva nebyla odeslána, protože:'
);
