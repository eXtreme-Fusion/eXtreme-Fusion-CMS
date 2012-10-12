<?php defined('EF5_SYSTEM') || exit;

return array(
	'Contact' => 'Kontakt',
	'Contact Ways' => '
		Z administracją :site_name można skontaktować się poprzez:
		<ul><li>Bezpośrednie wysłanie e-maila na adres :hidemail.</li><li>Wysłanie <a href="messages.php?msg_send=1">prywatnej wiadomości</a>.<li>Wysłanie znajdującego się poniżej formularza. Jego treść zostanie dostarczona za pośrednictwem e-maila.</li></ul>
	',
	'Name:' => 'Nazwa użytkownika:',
	'Email Address:' => 'Adres e-mail:',
	'Subject:' => 'Temat:',
	'Message:' => 'Treść wiadomości:',
	'Send Message' => 'Wyślij',
	'Validation Code:' => 'Kod potwierdzający:',
	'Enter Validation Code:' => 'Wpisz kod potwierdzający',
	// Błędy
	'You must specify a Name' => 'Nie podano nicku',
	'You must specify an Email Address' => 'Nie podano poprawnego adresu e-mail',
	'You must specify a Subject' => 'Nie podano tematu',
	'You must specify a Message' => 'Nie podano treści wiadomości',
	'You must enter correct Validation Code' => 'Nie podano poprawnego kodu potwierdzającego',
	'Internal error: cannot send your message.' => 'Błąd wewnętrzny! Nie można wysłać wiadomości.',
	// Wiadomość wysłana
	'Your Message has been sent' => 'Wiadomość została wysłana.',
	'Thank You' => 'Dziękujemy',
	'Message was not sent' => 'Wiadomość nie została wysłana, ponieważ:'
);