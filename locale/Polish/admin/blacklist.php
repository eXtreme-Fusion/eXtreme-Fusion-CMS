<?php defined('EF5_SYSTEM') || exit;

return array(
	//Tytuły
	'Blocked addresses' => 'Zablokowane adresy',
	'Blacklist' => 'Czarna lista',
	'Blocked address edition' => 'Edycja zablokowanego adresu',

	//Komunikaty
	'Address has been added to the blacklist.' => 'Dodano adres do czarnej listy.',
	'Blocked address has been edited.' => 'Edytowano zablokowany adres.',
	'Error! Blocked address has not been edited.' => 'Wystąpił błąd! Zablokowany adres nie został edytowany.',
	'Error! Address has not been added to the blacklist.' => 'Wystąpił błąd! Adres nie został dodany do czarnej listy.',
	'Error! Address has not been deleted from the blacklist.' => 'Wystąpił błąd! Adres nie został usunięty z czarnej listy.',
	'Address has been deleted from the blacklist.' => 'Usunięto adres z czarnej listy.',
	'Error! Specified address already is in database.' => 'Wystąpił błąd! Podany adres znajduje się już w bazie danych.',
	'Error! Entered data is incorrect. Please validate e-mail address or IP address.' => 'Wystąpił błąd! Nie wprowadzono poprawnych danych, sprawdź poprawność adresu email lub IP',
	'Error! Missing id.' => 'Wystąpił błąd! Brak identyfikatora :id dla tablicy blacklist.',
	'Error! E-mail address can\'t be accepted with IP address. You must add IP address or e-mail address.' => 'Wystąpił błąd! Nie można zatwierdzić adresu e-mail wraz z adresem IP, proszę dodać adres IP lub e-mail.',
	
	//Informacje
	'N/A' => 'Niedostępne',
	'Blacklist' => 'Czarna lista',
	'Blacklist description' => '<p>Dodanie adresu IP do czarnej listy uniemożliwi osobom o&nbsp;takim numerze IP odwiedzenie strony. Możesz podać cały numer IP, np. <em>123.123.123.123</em>, lub jego część, np. <em>123.123.123</em> lub <em>123.123</em>.</p><p>Pamiętaj! Adresy klasy IPv6 są zapisywane w ich pełnej formie, np. <em>ABCD:1234:5:6:7:8:9:FF</em> będzie pokazane jako <em>ABCD:1234:0005:0006:0007:0008:0009:00FF</em>.Mieszane adresy, częściowo zawierające dane z IPv6 i IPv4 nie będą sprawdzane.</p><p>Dodanie do czarnej listy adresu e-mail uniemożliwi użytkownikowi danego adresu rejestrację na stronie. Możesz podać cały adres e-mail, np. <em>foo@bar.com</em> lub domenę, której posiadaczom kont e-mail chcesz uniemożliwić rejestrację, np. <em>bar.com</em></p>',
	
	//Formularz
	'Block IP address:' => 'Adres IP do zablokowania: <br /><br /><strong>lub</strong>',
	'Block e-mail address:' => 'Adres e-mail do zablokowania:',
	'Block reason:' => 'Powód:',
	
	//Treść
	'Address:' => 'Adres:',
	'Blacklist is empty.' => 'Brak adresów na czarnej liście.'
	
);