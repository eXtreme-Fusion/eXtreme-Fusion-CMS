<?php defined('EF5_SYSTEM') || exit;
/**
* Czech (UTF-8) Language Fileset for eXtreme-Fusion,
* based at translation for PHP-Fusion v7.01.xx.
*
* @author Czech Official PHP-Fusion's Support
* @see http://php-fusion.pl
* @license Affero GPL license
*/

/** TODO: Konfiguracja języka w osobnym pliku (`locale.php`) ***/
setlocale(LC_TIME, array('cs_CS.UTF-8', 'Czech'));

return array(
'Charset' => 'UTF-8',
'xml_lang' => 'cs',


	'An unexpected error occurred' => 'Došlo k neočekávané chybě',

	# Komunikaty analizy danych

	'Action error' => 'Došlo k chybě :( Tato akce nebyla provedena správně',
	'Action success' => 'Všechno je v pořádku :) Akce byla provedena správně',

	# 'Deleting error' => 'Došlo k neočekávané chybě: položka nebyla odstraněna',
	# 'Deleting success' => 'Vše je OK: položka byla úspěšně odstraněna',


	/* INDEKSY JUŻ GOTOWE DLA EXTREME-FUSION 5 */
	
	// Zarządzanie (edycja, usuń)
	'Management' => 'Správa',
	'Author' => 'Autor',
	'Title:' => 'Titulek:',
	'Content' => 'Obsah', 
	// userException class
	'Entered the wrong data type.' => 'Zadané data jsou nesprávného typu.',
	
	'Username' => 'Název uživatele',
	'Online' => 'přihlášen',
	'Offline' => 'odhlášen',
	
	// Dla listy wyboru ochrony formularza przed spamem
	'Without protection' => 'bez ochrany',
	
	
	'Statistics' => 'Statistiky',
	/* koniec indeksów EF5, poniżej indeksy z PF lub namieszane */
	
	'Description:' => 'Popis:',
	'Keywords:' => 'Klíčové slova:',
	'Select all' => 'Vybrat vše',
	'Group' => 'Skupina',
	'Name:' => 'Jméno:',
	'Login' => 'Přihlášení',
	'Username:' => 'Název uživatele:',
	'Password:' => 'Heslo:',
	'Confirm password:' => 'Potvrdit heslo:',
	'E-mail address:' => 'Adresa e-mailu:',
	'Hide e-mail:' => 'Skrýt e-mail:',
	'Remember me' => 'Zapamatovat si',
	'Settings' => 'Nastavení',

	'Register' => 'Registrace',
	'Forgot password' => 'Zapomenuté heslo',

	'Visible for:' => 'Viditelné pro:',
	'Order:' => 'Řazení:',
	'Error' => 'Chyba',
	'Options:' => 'Možnosti:',

	'Database error' => 'Chyba při aktualizaci dat: nastavení nebyla změněna!',
	
	//Informations
	'Author:' => 'Autor:',
	'Date:' => 'Datum přidání:',
	'Reads:' => 'Přečteno:',
	'Comments:' => 'Komentáře:',
	'User:' => 'Uživatel:',
	'Message:' => 'Zpráva:',

	'User Panel' => 'Panel uživatele',
	'Admin Panel' => 'Administrace',

	'Private messages' => 'Soukromé zprávy',

	'Failed to request account data. Error:' => 'Nepodařilo se načíst uživatelská data. Chyba:',
	'Failed to request report data. Error:' => 'Nepodařilo se načíst data hlášení. Chyba:',
	'Failed to authenticate user. Error:' => 'Nepodařilo se ověřit uživatele. Chyba:',
	'Invalid http interface defined. No such interface as' => 'Neplatný typ rozhraní http. Neexistuje takové rozhraní',
	'Request failed, fopen provides no further information' => 'Požadavek se nezdařil, fopen neposkytuje další informace',
	'No valid root parameter or aggregate metric called' => 'Nesprávný parametr root',
	'There is no valid property called' => 'Nesprávná vlastnost',
	'Data has been saved.' => 'Data byla uložena.',
	'Error! Data has not been saved.' => 'Došlo k chybě! Data nebyla uložena.',
	'There is no such function as' => 'Neexistuje funkce',

	// Full & Short Months
	'months' => '&nbsp|leden|únor|březen|duben|květen|červen|červenec||srpen|zaří|říjen|listopad|prosinec',
	'shortmonths' => '&nbsp|le.|ún.|bře.|du.|kvě.|čer.|červ.|spr.|zář.|říj.|lis.|pro..',
	
	//Paging
	'Go to first page' => 'Příjít na první stránku',
	'Go to page' => 'Přejít na stránku',
	'Go to last page' => 'Příjít na poslední stránku',
	

	// Standard User Levels
	'Guest' => 'Host',
	'User' => 'Uživatel',
	'Admin' => 'Administrátor',
	'hAdmin' => 'Hlavní administrátor',
	'Administration' => 'Administrace',
	'Site' => 'Webová stránka',
	'user_na' => 'Nd.',
	'user_anonymous' => 'Účet skryt',
	// Forum Moderator Level(s)
	'userf1' => 'Moderátor',
	// Navigation
	'global_001' => 'Navigace',
	'global_002' => 'Žádné odkazy\n',
	// Users Online
	'global_010' => 'Aktuálně online',
	'global_011' => 'Hosté online',
	'global_012' => 'Uživatelé online',
	'global_013' => 'Žádný uživatel online',
	'global_014' => 'Celkem uživatelů',
	'global_015' => 'Neaktivní uživatelé',
	'global_016' => 'Nejnovější uživatel',
	// Forum Side panel
	'global_020' => 'Poslední témata',
	'global_021' => 'Nejnovější témata',
	'global_022' => 'Žhavá témata',
	'global_023' => 'Žádné témata na fóru',
	// Articles Side panel
	'global_030' => 'Nejnovější články',
	'global_031' => 'Žádné články',
	// Welcome panel
	'global_035' => 'Vítejte',
	'global_036' => 'Aktualizuj stránku',
	'Welcome :username!' => 'Vítej :username!',
	// Latest Active Forum Threads panel
	'global_040' => 'Nedávno diskutovaná témata',
	'global_041' => 'Moje poslední témata',
	'global_042' => 'Moje poslední příspěvky',
	'global_043' => 'Nové příspěvky',
	'global_044' => 'Téma',
	'global_045' => 'Zobrazení',
	'global_046' => 'Odpovědi',
	'global_047' => 'Poslední příspěvek',
	'global_048' => 'Fórum',
	'global_049' => 'Napsal',
	'global_050' => 'Autor',
	'global_051' => 'Anketa',
	'global_052' => 'Přesunuto',
	'global_053' => 'Žádné téma jste ještě nezaložil.',
	'global_054' => 'Nenapsal/a jste žádný příspěvek',
	'global_055' => 'Nové příspěvky od Vaší poslední návštěvy: %u',
	'global_056' => 'Moje sledovaná témata',
	'global_057' => 'Volby',
	'global_058' => 'Przestań <br /> obserwować',
	'global_059' => 'Brak obserwowanych przez Ciebie tematów.',
	'global_060' => 'Przestać obserwować temat?',
	// News & Articles
	'global_070' => 'Napisane przez ',
	'global_071' => 'dnia ',
	'global_072' => 'Czytaj więcej',
	'global_073' => ' komentarzy',
	'global_073b' => ' komentarz',
	'global_074' => ' czytań',
	'global_075' => 'Drukuj',
	'global_076' => 'Edytuj',
	'News' => 'Aktualności',
	'No News has been posted yet' => 'Brak opublikowanych newsów',
	'global_079' => 'Kategoria: ',
	'global_080' => 'Bez kategorii',
	// Page Navigation
	'global_090' => 'Poprzednia',
	'global_091' => 'Następna',
	'global_092' => 'Strona ',
	'global_093' => ' z&nbsp;',

	// Member User Menu
	'Edit profile' => 'Upravit profil',//120
	'Messages' => 'Soukromé zprávy',
	'Users' => 'Uživatelé',
	'Logout' => 'Odhlásit',

	'global_128' => 'Váš avatar',
	'global_129' => 'Žádný avatar ',
	// Poll
	'global_130' => 'Anketa',
	'global_131' => 'Hlasovat',
	'global_132' => 'Musíte se přihlásit, abyste mohli hlasovat.',
	'global_133' => 'głos',
	'global_134' => 'głosów',
	'global_135' => 'Ogółem głosów: ',
	'global_136' => 'Rozpoczęto: ',
	'global_137' => 'Zakończono: ',
	'global_138' => 'Archiwum ankiet',
	'global_139' => 'Wybierz ankietę z&nbsp;listy:',
	'global_140' => 'Zobacz wyniki',
	'global_141' => 'Wyniki ankiety',
	'global_142' => 'Brak przeprowadzanych ankiet.',
	// Shoutbox
	'global_150' => 'Shoutbox',
	'global_151' => 'Nick:',
	'global_152' => 'Wiadomość:',
	'global_153' => 'Wyślij',
	'global_154' => 'Musisz zalogować się, aby móc dodać wiadomość.',
	'global_155' => 'Archiwum shoutboksa',
	'global_156' => 'Brak wiadomości. Może czas dodać własną?',
	'global_157' => 'Usuń',
	'global_158' => 'Kod potwierdzający:',
	'global_159' => 'Wpisz kod potwierdzający:',
	// Footer Counter
	'global_170' => 'unikalna wizyta',
	'global_171' => 'unikalnych wizyt',
	'global_172' => 'Wygenerowano w&nbsp;sekund: %s',
	'global_173' => 'zapytań MySQL',
	// Admin Navigation
	'global_180' => 'Powróć do panelu administracyjnego',
	'Go to homepage' => 'Przejdź do strony głównej',
	'global_182' => '<strong>Uwaga:</strong> Podane hasło administratora jest nieprawidłowe lub puste.',
	// Miscellaneous
	'global_190' => 'Aktywowano tryb serwisowy na serwerze.',
	'global_191' => 'Twoje IP jest zablokowane.',
	'global_192' => 'Wylogowuję jako: ',
	'global_193' => 'Loguję jako: ',
	'global_194' => 'Konto zostało zablokowane.',
	'global_195' => 'Konto nie jest aktywne.',
	'global_196' => 'Nieprawidłowa nazwa użytkownika lub hasło.',
	'global_197' => 'Proszę czekać na przekierowanie...<br /><br />
	[ <a href="index.html">Nie chcę czekać</a> ]',
	'global_198' => '<strong>Ostrzeżenie:</strong> Wykryto katalog Install, usuń go natychmiast.',
	//Titles
	'global_201' => ': ',
	'global_202' => 'Szukaj',
	'global_203' => 'FAQ',
	'global_204' => 'Forum',
	//Themes
	'global_210' => 'Przejdź do treści',
	// No themes found
	'global_300' => 'nie znaleziono skórki.',
	'global_301' => 'Nie można wyświetlić strony. Jest to spowodowane brakiem plików odpowiadających za wygląd strony. Jeśli jesteś administratorem strony, uruchom swojego klienta FTP i&nbsp;wgraj do katalogu <em>/themes</em> jakąkolwiek skórkę zaprojektowaną dla <em>PHP-Fusion v7</em>. Następnie sprawdź w&nbsp;<em>Głównych ustawieniach</em> w&nbsp;<em>Panelu administratora</em> oraz upewnij się, że wybrana tam skórka jest w&nbsp;Twoim katalogu <em>/themes</em>. Jeśli tak nie jest, sprawdź, czy wgrana skórka ma taką samą nazwę (wliczając w&nbsp;to wielkość znaków, ważne na serwerach uniksowych) jak ta wybrana w&nbsp;<em>Głównych ustawieniach</em>.<br /><br />Jeśli jesteś użytkownikiem tej strony, skontaktuj się z&nbsp;administracją strony poprzez wysłanie e-maila na adres :siteemail oraz poinformuj o&nbsp;istniejącym problemie.', // ".HELP::hide_email($_sett -> getData('siteemail'))."
	'global_302' => 'Wybrana przez Ciebie skórka nie istnieje lub jest niekompletna!',
	// JavaScript Not Enabled
	'global_303' => '<p>O&nbsp;nie! Ta strona potrzebuje włączonej obsługi języka <strong>JavaScript</strong>!</p>',
	/*'global_303' => '<p>O&nbsp;nie! Ta strona potrzebuje włączonej obsługi języka <strong>JavaScript</strong>!</p><p>Twoja przeglądarka nie obsługuje tego języka lub ma wyłączoną jego obsługę. <strong>Włącz wykonywanie kodu JavaScript</strong> w swojej przeglądarce internetowej, aby skorzystać ze wszystkich funkcji strony<br /> lub <strong>skorzystaj</strong> z&nbsp;programu obsługującego język JavaScript, np. <a href="http://firefox.com" rel='nofollow' title='Mozilla Firefox'>Mozilla Firefox</a>, <a href='http://apple.com/safari/' rel='nofollow' title='Apple Safari'>Apple Safari</a>, <a href='http://opera.com' rel='nofollow' title='Opera Web Browser'>Opera</a>, <a href='http://www.google.com/chrome' rel='nofollow' title='Google Chrome'>Google Chrome</a> lub <a href='http://www.microsoft.com/windows/internet-explorer/' rel='nofollow' title='Windows Internet Explorer'>Windows Internet Explorer</a> w&nbsp;wersji wyższej niż 6.</p>', /*
	// User Management
	// Member status
	'global_400' => 'zawieszone',
	'global_401' => 'zablokowane',
	'global_402' => 'wyłączone',
	'global_403' => 'konto wyłączone',
	'global_404' => 'konto anonimizowane',
	'global_405' => 'anonimowe',
	'global_406' => 'Konto zostało zbanowane z&nbsp;następujących powodów:',
	'global_407' => 'Konto jest zawieszone do ',
	'global_408' => ' z&nbsp;następujących powodów:',
	'global_409' => 'Konto zostało zablokowane ze względów bezpieczeństwa.',
	'global_410' => 'Powód: ',
	'global_411' => 'Konto zostało zablokowane zp. bezczynności',
	'global_412' => 'Konto zostało anonimizowane, prawdopodobnie z&nbsp;powodu bezczynności',
	// Banning due to flooding
	'global_440' => 'Automatyczne zbanowanie przez blokadę antyfloodową',

	/**** TODO: Pliki językowe powinny być logic-less, czyli bez kodu PHP w tłumaczeniach, tym zajmą się dynamiczne parametry *****/
	/**** TODO: Pliki językowe nie powinny zawierać szablonów wiadomości email, powinny znajdować się w tym katalogu, lecz w osobnych plikach które będą obsłużone przez OPY ****/

	'global_441' => 'Twoje konto na :sitename zostało zbanowane.', // ".$_sett -> getData('sitename')."
	'global_442' => 'Witaj :username!
	Z Twojego konta na :sitename wysłano zbyt wiele wiadomości w krótkim czasie z następującego IP: :ip, w związku z czym zostało zablokowane. Powyższe działanie zostało podjęte w celu ochrony strony przed botami dodającymi wiadomości w krótkim odstępie czasu.
	Skontaktuj się z administracją strony wysyłając e-mail na adres :siteemail, żeby odblokować konto lub wyjaśnić zaistniałą sytuację.
	Wszystkiego dobrego
	:siteusername
	------
	Niniejsza wiadomość została wysłana automatycznie. Nie odpowiadaj na nią.', // ".$_user -> getIP()."
	// Lifting of suspension
	'global_450' => 'Automatyczne zdjęcie zawieszenia konta.',
	'global_451' => 'Zdjęcie zawieszenia konta na :sitename', // .$_sett -> getData('sitename')
	'global_452' => 'Witaj :username!
	Zawieszenie Twojego konta na :siteurl zostało zdjęte. Zaloguj się korzystając z poniższych danych:
	Nazwa użytkownika: :username
	Hasło: ukryte ze względów bezpieczeństwa
	Jeśli nie pamiętasz hasła, zresetuj je korzystając z poniższego linka: :link
	Wszystkiego dobrego,
	:siteusername
	------
	Niniejsza wiadomość została wysłana automatycznie. Nie odpowiadaj na nią.',
	'global_453' => 'Witaj :username!
	Zdjęto zawieszenie Twojego konta na :siteurl.
	Wszystkiego dobrego,
	:siteusername
	------
	Niniejsza wiadomość została wysłana automatycznie. Nie odpowiadaj na nią.',
	'global_454' => 'Reaktywowano konto na :sitename', // ".$_sett -> getData('sitename');
	'global_455' => 'Witaj :username!
	Twoja ostatnia wizyta na :siteurl reaktywowała Twoje konto, w związku z czym, przestało ono być uznawane za bezczynne.
	Wszystkiego dobrego,
	:siteusername
	------
	Niniejsza wiadomość została wysłana automatycznie. Nie odpowiadaj na nią.',
	// Function parsebytesize()
	'global_460' => 'Plik pusty',
	'global_461' => 'Bajtów',
	'global_462' => 'kB',
	'global_463' => 'MB',
	'global_464' => 'GB',
	'global_465' => 'TB',
	//Safe Redirect
	'global_500' => 'Trwa przekierowanie na adres %s, proszę czekać. Naciśnij, jeśli Cię nie przekierowało.',
	//Czas sesji

	// Function showCopyrights()
	'Powered by :system' => 'Strona oparta na systemie CMS :system',
	
	'Powered by :system under :license License' => 'Strona oparta na systemie CMS :system na licencji :license',
	
	'Yes' => 'Tak',
	'No' => 'Nie',
	'Add' => 'Dodaj',
	'Remove' => 'Odejmij',
	'Save' => 'Zapisz',
	'Back' => 'Wróć',
	'Preview' => 'Podgląd',
	'Edit' => 'Edytuj',
	'Delete' => 'Usuń',
	'Send' => 'Wyślij',
	'Up' => 'W górę',
	'Down' => 'W dół',
	'Next' => 'Dalej',
	'Previous' => 'Wstecz',
	'Enable' => 'Włącz',
	'Disable' => 'Wyłącz',
	'Access denied' => 'Odmowa dostępu',
	'Options' => 'Opcje',
	'Done' => 'Gotowe',
	'Show all' => 'Bez sortowania',
	'Refresh' => 'Odśwież',
	'Management' => 'Zarządzanie',
	'Added by' => 'Dodane przez',
	'Date' => 'Data',
	'No data' => 'Brak danych',
	'or' => 'lub',
	'E-mail address has been hidden' => 'Adres e-mail został ukryty',
	'Groups' => 'Grupy',
	'Current password:' => 'Aktualne hasło:',
	'New password:' => 'Nowe hasło:',
	'Confirm new password:' => 'Potwierdź nowe hasło:',
	'Avatar:' => 'Awatar:',
	'Edit account' => 'Edytuj konto',
	'Language:' => 'Język:',
	
	'Polish' => 'Polski',
	'English' => 'Angielski',
	'Czech' => 'Czeski',
	
	'Modules under Development.' => 'Moduł w trakcie rozwoju.'
);
