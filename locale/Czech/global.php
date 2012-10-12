<?php defined('EF5_SYSTEM') || exit;
/**
 * Czech (UTF-8) Language Fileset for eXtreme-Fusion,
 * based at translation for PHP-Fusion v7.01.xx.
 *
 * @author   Czech Official PHP-Fusion's Support - Czech locale to eXtreme-Fusion 5 by LynX - http://www.extreme-fusion.cz , http://cz.extreme-fusion.org
 * @see      http://php-fusion.pl
 * @license  Affero GPL license
 */

/** TODO: Konfiguracja języka w osobnym pliku (`locale.php`) ***/
setlocale(LC_TIME, array('cs_CS.UTF-8', 'Czech'));

return array(
	'Charset' => 'UTF-8',
	'xml_lang' => 'cs',
	'phpmailer' => 'cs',


	'An unexpected error occurred' => 'Došlo k neočekávané chybě',

	# Komunikaty analizy danych

	'Action error' => 'Došlo k chybě :( tato akce nebyla provedena správně',
	'Action success' => 'Všechno je v pořádku :) Akce byla provedena správně',

	# 'Deleting error' => 'Došlo k neočekávané chybě: položka nebyla odstraněna',
	# 'Deleting success' => 'Vše je OK: položka byla úspěšně odstraněna',


	/* INDEKSY JUŻ GOTOWE DLA EXTREME-FUSION 5 */
	
	// Zarządzanie (edycja, usuń)
	'Management' => 'Administrace',
	'Author' => 'Autor',
	'Title:' => 'Titulek:',
	'Content' => 'Obsah', 
	// userException class
	'Entered the wrong data type.' => 'Vložený nesprávný typ dat',
	
	'Username' => 'Název uživatele',
	'Online' => 'Připojen',
	'Offline' => 'Odpojen',
	
	// Dla listy wyboru ochrony formularza przed spamem
	'Without protection' => 'bez ochrany',
	
	
	'Statistics' => 'Statistiky',
	/* koniec indeksów EF5, poniżej indeksy z PF lub namieszane */
	
	'Description:' => 'Popis:',
	'Keywords:' => 'Klíčové slova:',
	'Select all' => 'Vybrat všechno',
	'Group' => 'Skupina',
	'Name:' => 'Název:',
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

	'Database error' => 'Chyba během aktualizace databáze: Nastavení nebylo změněno!',
	
	//Informations
	'Author:' => 'Autor:',
	'Date:' => 'Datum přidání:',
	'Reads:' => 'Přečteno:',
	'Comments:' => 'Komentáře:',
	'User:' => 'Uživatel:',
	'Message:' => 'Zprávy:',

	'User Panel' => 'Panel uživatele',
	'Admin Panel' => 'Panel administrátora',

	'Private messages' => 'Soukr. zprávy',

	'Failed to request account data. Error:' => 'Nelze získat informace o účtu. Chyba:',
	'Failed to request report data. Error:' => 'Nepodařilo se získat data reportu. Chyba:',
	'Failed to authenticate user. Error:' => 'Nepodařilo se ověřit uživatele. Chyba:',
	'Invalid http interface defined. No such interface as' => 'Vloženo neplatné rozhraní http. Není zde rozhraní jako',
	'Request failed, fopen provides no further information' => 'Žádost nebyla úspěšná, fopen neposkytuje informace pro příště',
	'No valid root parameter or aggregate metric called' => 'Neplatný parametr root',
	'There is no valid property called' => 'Neexistuje platná vlastnost nazvaná',
	'Data has been saved.' => 'Data byly uloženy.',
	'Error! Data has not been saved.' => 'Došlo k chybě! Data nebyly uloženy.',
	'There is no such function as' => 'Neexistuje funkce jako',

	// Full & Short Months
	'months' => '&nbsp|Leden|Únor|Březen|Duben|Květen|Červen|Červenec|Srpen|Září|Říjen|Listopad|Prosinec',
	'shortmonths' => '&nbsp|Leden|Únor|Březen|Duben|Květen|Červen|Červenec|Srpen|Září|Říjen|Listopad|Prosinec',

	//Paging
	'Go to first page' => 'Jít na první podstránku',
	'Go to page' => 'Jít na podstránku',
	'Go to last page' => 'Jít na poslední podstránku',
	
	
	// Standard User Levels
	'Guest' => 'Host',
	'User' => 'Uživatel',
	'Admin' => 'Administrátor',
	'hAdmin' => 'Hlavní administrátor',
	'Administration' => 'Administrace',
	'Site' => 'Stránka',
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
	'global_020' => 'Poslední ve fóru',
	'global_021' => 'Nejnovější témata',
	'global_022' => 'Nejoblíbenější témata',
	'global_023' => 'Žádné témata ve fóře',
	// Articles Side panel
	'global_030' => 'Nejnovější články',
	'global_031' => 'Žádné články',
	// Welcome panel
	'global_035' => 'Vítejte',
	'global_036' => 'Obnovit tuto stránku',
	'Welcome :username!' => 'Vítejte :username!',
	// Latest Active Forum Threads panel
	'global_040' => 'Poslední diskutované témata',
	'global_041' => 'Moje nové témata',
	'global_042' => 'Moje nové příspěvky',
	'global_043' => 'Nové příspěvky',
	'global_044' => 'Téma',
	'global_045' => 'Zobrazení',
	'global_046' => 'Odpovědí',
	'global_047' => 'Poslední příspěvek',
	'global_048' => 'Fórum',
	'global_049' => 'Napsal/a',
	'global_050' => 'Autor',
	'global_051' => 'Anketa',
	'global_052' => 'Přesunout',
	'global_053' => 'Nejsou zde žádné témata od Vás.',
	'global_054' => 'Nejsou zde žádné příspěvky napsané Vámi.',
	'global_055' => 'Nové příspěvky od poslední návštěvy: %u',
	'global_056' => 'Moje sledované témata',
	'global_057' => 'Možnosti',
	'global_058' => 'Przestań <br /> obserwować',
	'global_059' => 'Žádné ze sledovaných témat.',
	'global_060' => 'Przestać obserwować temat?',
	// News & Articles
	'global_070' => 'Napsal/a ',
	'global_071' => 'dne ',
	'global_072' => 'Číst více',
	'global_073' => ' komentáře',
	'global_073b' => ' komentář',
	'global_074' => ' czytań',
	'global_075' => 'Tisknout',
	'global_076' => 'Upravit',
	'News' => 'Novinky',
	'No News has been posted yet' => 'Žádné novinky nebyly napsány',
	'global_079' => 'Kategorie: ',
	'global_080' => 'Bez kategorie',
	// Page Navigation
	'global_090' => 'Předchozí',
	'global_091' => 'Další',
	'global_092' => 'Stráka ',
	'global_093' => ' z&nbsp;',

		// Member User Menu
	'Edit profile' => 'Upravit profil',//120
	'Messages' => 'Zprávy',
	'Users' => 'Uživatelé',
	'Logout' => 'Odhlásit',

	'global_128' => 'Tvůj avatar',
	'global_129' => 'Bez avataru',
	// Poll
	'global_130' => 'Anketa',
	'global_131' => 'Hlasovat',
	'global_132' => 'Musíte být přihlášeni hlasovat.',
	'global_133' => 'hlas',
	'global_134' => 'hlasů',
	'global_135' => 'Celkový počet hlasů: ',
	'global_136' => 'Založeno: ',
	'global_137' => 'Ukončeno: ',
	'global_138' => 'Archív anket',
	'global_139' => 'Vyberte si anketu ze seznamu:',
	'global_140' => 'Zobrazit výsledky',
	'global_141' => 'Výsledky ankety',
	'global_142' => 'Žádná anketa neexistuje.',
	// Shoutbox
	'global_150' => 'Shoutbox',
	'global_151' => 'Nick:',
	'global_152' => 'Zpráva:',
	'global_153' => 'Odeslat',
	'global_154' => 'Musíte být přihlášeni, abyste mohli přidat zprávu.',
	'global_155' => 'Archív shoutboxu',
	'global_156' => 'Žádné zprávy. Je ideální čas nějakou napsat, ne?',
	'global_157' => 'Vymazat',
	'global_158' => 'Potvrzovací kód:',
	'global_159' => 'Zadejte potvrzovací kód:',
	// Footer Counter
	'global_170' => 'unikátní návštěvy',
	'global_171' => 'unikátních návštěv',
	'global_172' => 'Vygenerováno w&nbsp;sekund: %s',
	'global_173' => 'dotaz MySQL',
	// Admin Navigation
	'global_180' => 'Návrat do panelu administrátora',
	'Go to homepage' => 'Přejít na hlavní stránku',
	'global_182' => '<strong>Pozor:</strong> Heslo administrátora je neplatné nebo prázdné.',
	// Miscellaneous
	'global_190' => 'Aktivováno způsobem servisu na serveru.',
	'global_191' => 'Tvoje IP je zaBANována.',
	'global_192' => 'Odhlašujete se jako: ',
	'global_193' => 'Přihlašujete se jako: ',
	'global_194' => 'Účet byl zablokován.',
	'global_195' => 'Účet není aktivován',
	'global_196' => 'Neplatné uživatelské jméno nebo heslo.',
	'global_197' => 'Počkejte prosím na přesměrování ...<br /><br />
	[ <a href="index.html">Nechci čekat</a> ]',
	'global_198' => '<strong>Varování:</strong> Instalační adresář není smazán, ihned jej smažte!',
	//Titles
	'global_201' => ': ',
	'global_202' => 'Hledat',
	'global_203' => 'FAQ',
	'global_204' => 'Fórum',
	//Themes
	'global_210' => 'Přejít na obsah',
	// No themes found
	'global_300' => 'nenalezena šablona.',
	'global_301' => 'Nelze zobrazit stránku. Je to kvůli nedostatku souborům odpovídajícím vzhledu webu. Pokud jste administrátor webu, spustěte FTP klienta a nahrajte do adresáře<em>/themes</em> jakoukoliv šablonu (theme) určenou pro <em>eXtreme-Fusion PHP-Fusion v7</em>. Następnie sprawdź w&nbsp;<em>Hlavních nastaveních</em> w&nbsp;<em>Panelu administrátora</em> oraz upewnij się, że wybrana tam skórka jest w&nbsp;Twoim katalogu <em>/themes</em>. Jeśli tak nie jest, sprawdź, czy wgrana skórka ma taką samą nazwę (wliczając w&nbsp;to wielkość znaków, ważne na serwerach uniksowych) jak ta wybrana w&nbsp;<em>Głównych ustawieniach</em>.<br /><br />Jeśli jesteś użytkownikiem tej strony, skontaktuj się z&nbsp;administracją strony poprzez wysłanie e-maila na adres :siteemail oraz poinformuj o&nbsp;istniejącym problemie.', // ".HELP::hide_email($_sett -> getData('siteemail'))."
	'global_302' => 'Vybráná šablona neexistuje nebo je nekompletní!',
	// JavaScript Not Enabled
	'global_303' => '<p>O&nbsp;nie! Tato stránka vyžaduje možnost používání jazyka<strong>JavaScript</strong>!</p>',
	/*'global_303' => '<p>O&nbsp;nie! Tato stránka vyžaduje možnost používání jazyka<strong>JavaScript</strong>!</p><p>Twoja przeglądarka nie obsługuje tego języka lub ma wyłączoną jego obsługę. <strong>Włącz wykonywanie kodu JavaScript</strong> w swojej przeglądarce internetowej, aby skorzystać ze wszystkich funkcji strony<br /> lub <strong>skorzystaj</strong> z&nbsp;programu obsługującego język JavaScript, np. <a href="http://firefox.com" rel='nofollow' title='Mozilla Firefox'>Mozilla Firefox</a>, <a href='http://apple.com/safari/' rel='nofollow' title='Apple Safari'>Apple Safari</a>, <a href='http://opera.com' rel='nofollow' title='Opera Web Browser'>Opera</a>, <a href='http://www.google.com/chrome' rel='nofollow' title='Google Chrome'>Google Chrome</a> lub <a href='http://www.microsoft.com/windows/internet-explorer/' rel='nofollow' title='Windows Internet Explorer'>Windows Internet Explorer</a> w&nbsp;wersji wyższej niż 6.</p>', /*
	// User Management
	// Member status
	'global_400' => 'pozastaveno',
	'global_401' => 'zablokováno',
	'global_402' => 'wyłączone',
	'global_403' => 'účet wyłączone',
	'global_404' => 'účet anonymní',
	'global_405' => 'anonymní',
	'global_406' => 'Účet je zakázán z těchto následujících důvodů:',
	'global_407' => 'Účet je pozastaven do ',
	'global_408' => ' z&nbsp;następujących důvodů:',
	'global_409' => 'Účet zostało zablokowane ze względów bezpieczeństwa.',
	'global_410' => 'Důvod: ',
	'global_411' => 'Účet zostało zablokowane zp. bezczynności',
	'global_412' => 'Účet zostało anonimizowane, prawdopodobnie z&nbsp;powodu bezczynności',
	// Banning due to flooding
	'global_440' => 'Automatické zablokování přes blokaci antizaplavovací',

	/**** TODO: Pliki językowe powinny być logic-less, czyli bez kodu PHP w tłumaczeniach, tym zajmą się dynamiczne parametry *****/
	/**** TODO: Pliki językowe nie powinny zawierać szablonów wiadomości email, powinny znajdować się w tym katalogu, lecz w osobnych plikach które będą obsłużone przez OPY ****/

	'global_441' => 'Tvůj Účet na :site_name byl zaBANován.', // ".$_sett -> getData('site_name')."
	'global_442' => 'Vítej :username!
	Z Vašeho účtu na :site_name bylo odesláno příliš mnoho zpráv v krátkém časovém intervalu z IP: :ip, proto byl Váš účet zablokován. Tato akce byla nastavena na ochranu proti spambot-ům přidávající zprávy v krátkém časovém intervalu.
	Obraťte se na administátora stránky e-Mailem na :siteemail s prosbou, že potřebujete odblokovat účet nebo vysvětlite vzniklou situaci.
	Všechno nejlepší
	:siteusername
	------
	Tato zpráva Vám byla zaslána automaticky. Nereagujte prosím na ni.', // ".$_user -> getIP()."
	// Lifting of suspension
	'global_450' => 'Automatické foto pozastavení účtu.',
	'global_451' => 'Zdjęcie zawieszenia účtu na :site_name', // .$_sett -> getData('site_name')
	'global_452' => 'Vítej :username!
	Pozastavení vašeho účtu na :site_url bylo odstraněn. Přihlašte se pomocí následujících údajů:
	Název uživatele: :username
	Heslo: skryté z bezpečnostních důvodů
	Pokud jste zapomněli své heslo, tak využijte resetování pomocí následujícího odkazu: :link
	Všechno nejlepší,
	:siteusername
	------
	Tato zpráva byla odeslána automaticky. Nereagují na ni.',
	'global_453' => 'Vítej :username!
	Byla vyřazena z pozastavení vašeho účtu na :site_url.
	Všechno nejlepší,
	:siteusername
	------
	Tato zpráva byla odeslána automaticky. Nereagují na ni.',
	'global_454' => 'Reaktivován účet na :site_name', // ".$_sett -> getData('site_name');
	'global_455' => 'Vítej :username!
	Vaše poslední návštěva na :site_url reaktivovat si učet, proto přestaly být považovány za volnoběhu.
	Všechno nejlepší,
	:siteusername
	------
	Tato zpráva Vám byla zaslána automaticky. Nereagujte prosím na ni.',
	// Function parsebytesize()
	'global_460' => 'Soubor prázdný',
	'global_461' => 'Bajtů',
	'global_462' => 'kB',
	'global_463' => 'MB',
	'global_464' => 'GB',
	'global_465' => 'TB',
	//Safe Redirect
	'global_500' => 'Je přesměrována na adresu %s, počkejte prosím. Stiskněte toto, pokud nechcete čekat.',
	//Czas sesji
	
	// Function showCopyrights()
	'Powered by :system' => 'Stránka založena na systému CMS :system',

	'Powered by :system under :license License' => 'Stránka založena na systému CMS :system na licenci :license',

	'Yes' => 'Ano',
	'No' => 'Ne',
	'Add' => 'Přidat',
	'Remove' => 'Přemístit',
	'Save' => 'Uložit',
	'Back' => 'Zpět',
	'Preview' => 'Náhled',
	'Edit' => 'Upravit',
	'Delete' => 'Smazat',
	'Send' => 'Odeslat',
	'Up' => 'Nahoru',
	'Down' => 'Dolů',
	'Next' => 'Další',
	'Previous' => 'Předchozí',
	'Enable' => 'Povolit',
	'Disable' => 'Nepovolit',
	'Access denied' => 'Přístup odmítnut',
	'Options' => 'Možnosti',
	'Done' => 'Hotovo',
	'Show all' => 'Ukázat vše',
	'Refresh' => 'Aktualizace',
	'Management' => 'Administrace',
	'Added by' => 'Přidal/a',
	'Date' => 'Data',
	'No data' => 'Žádná data',
	'or' => 'nebo',
	'E-Mail address has been hidden' => 'Adresa e-mailu byla skryta',
	'Groups' => 'Skupiny',
	'Current password:' => 'Aktuální heslo:',
	'New password:' => 'Nové heslo:',
	'Confirm new password:' => 'Potvrdit nové heslo:',
	'Avatar:' => 'Avatar:',
	'Edit account' => 'Upravit účet',
	'Language:' => 'Jazyk:',

	'Polish' => 'Polský',
	'English' => 'Anglický',
	'Czech' => 'Český'

	'Modules under Development.' => 'Moduł w trakcie rozwoju.'
	);