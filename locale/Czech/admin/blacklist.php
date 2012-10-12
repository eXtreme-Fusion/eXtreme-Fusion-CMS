<?php defined('EF5_SYSTEM') || exit;

return array(
	//Tytuły
	'Blocked addresses' => 'Zablokované adresy',
	'Blacklist' => 'Černá listina',
	'Blocked address edition' => 'Úprava zablokované adresy',

	//Komunikaty
	'Address has been added to the blacklist.' => 'Adresa byla přidána do seznamu zakázaných adres.',
	'Blocked address has been edited.' => 'Adresa byla editována.',
	'Error! Blocked address has not been edited.' => 'Došlo k chybě! Zablokovaná adresa nebyla editována.',
	'Error! Address has not been added to the blacklist.' => 'Došlo k chybě! Adresa nebyla přidána do Černé listiny',
	'Error! Address has not been deleted from the blacklist.' => 'Došlo k chybě! Adresa nebyla smazána z Černé listiny.',
	'Address has been deleted from the blacklist.' => 'Adresa byla smazána z Černé listiny.',
	'Error! Specified address already is in database.' => 'Došlo k chybě! Adresa již je uložena v databázi.',
	'Error! Entered data is incorrect. Please validate e-mail address or IP address.' => 'Došlo k chybě! Nezadali jste správné údaje, zkontrolujte e-mailovou adresu nebo IP',
	'Error! Missing id.' => 'Došlo k chybě! Chyba identifikátora :id v tabulce Černé listiny.',
	'Error! E-mail address can\'t be accepted with IP address. You must add IP address or e-mail address.' => 'Došlo k chybě! Nelze potvrdit e-mailovou adresu, včetně vaší IP adresy, přidejte IP adresu nebo e-mail.',
	
	//Informacje
	'N/A' => 'Není k dispozici',
	'Blacklist' => 'Černá listina',
	'Blacklist description' => '<p>Přidáním IP adresy do seznamu Černé listiny zabráníte lidem se stejnou IP adresou možnost návštívit Vaše stránky. Můžete zadávat celé IP adresy, např. <em>123.123.123.123</em> nebo její část, např. <em>123.123.123</em> či <em>123.123</em>.</p><p>Pamatujte! IPv6 adresy jsou uloženy v plné formě, např. <em>ABCD:1234:5:6:7:8:9:FF</em> a budou zobrazeny jako <em>ABCD:1234:0005:0006:0007:0008:0009:00FF</em>.Smíšené adresy, částečně obsahující data z IPv6 a IPv4 nebudou kontrolovány.</p><p>Přidáním e-mailové adresu do Černé listiny zabráníte uživateli na tuto adresu se registrovat. Můžete zadat celou e-mailovou adresu, např. <em>foo@extreme-fusion.cz</em> nebo můžete zablokovat všechny e-mailové adresy na danou doménu, např. <em>extreme-fusion.cz</em></p>',
	
	//Formularz
	'Block IP address:' => 'Adresa IP na zablokování: <br /><br /><strong>nebo</strong>',
	'Block e-mail address:' => 'Adresa e-mailu na zablokování:',
	'Block reason:' => 'Důvod:',
	
	//Treść
	'Address:' => 'Adresa:',
	'Blacklist is empty.' => 'Černá listina je zatím prázdná.'
	
);