<?php defined('EF5_SYSTEM') || exit;
/**
* Czech (UTF-8) Language Fileset for eXtreme-Fusion,
* based at translation for PHP-Fusion v7.01.xx.
*
* @author Polish Official PHP-Fusion's Support
* @see http://php-fusion.pl
* @license Affero GPL license
*/
// Official SVN Trunk Rev: 2364
/*English Language Fileset
Produced by Nick Jones (Digitanium)
Email: digitanium@php-fusion.co.uk
Web: http://www.php-fusion.co.uk

Polish (UTF-8) Language Fileset for PHP-Fusion v7.02.03, based at translation for PHP-Fusion v7.01.xx
Translations from english, modifications and tests:
PHP-Fusion po Polsku (PHP-Fusion in Polish)
https://launchpad.net/pf-pl
Main translator: Tomasz Jankowski (jantom)
Translator and tester: Michał Pospieszyński (sony)
This program is released as free software under the
Affero GPL license.*/

/** TODO: Konfiguracja języka w osobnym pliku (`locale.php`) ***/
setlocale(LC_TIME, array('cs_CS.UTF-8', 'Czech'));

return array(

// Tutaj zamieszczać locale pisane tylko przez EF Team, tak by przepisać ten plik na własny
'html_charset' => 'UTF-8',
// Koniec locali EF Team

'An unexpected error occurred' => 'Došlo k neočekávané chybě',

# Komunikaty analizy danych

'Action error' => 'Došlo k chybě :( Tato akce nebyla provedena správně',
'Action success' => 'Všechno je v pořádku :) Akce byla provedena správně',


/* INDEKSY JUŻ GOTOWE DLA EXTREME-FUSION 5 */

// Zarządzanie (edycja, usuń)
'Management' => 'Administrace',
'Author' => 'Autor',
'Title:' => 'Titulek:',
'Content' => 'Obsah',
'Subject:' => 'Předmět:',
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
'E-mail address' => 'Adresa e-mailu',

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

'--No selection--' => '--Žádná volba--',
'--Default--' => '--Výchozí--',

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
'shortmonths' => '&nbsp|le.|ún.|bře.|du.|kvě.|čer.|červ.|spr.|zář.|říj.|lis.|pro.',

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
'user_na' => 'Uživatel n/a.',
'user_anonymous' => 'Uživatel anonymní',
// Forum Moderator Level(s)
'userf1' => 'Moderátor',
// Navigation
'global_001' => 'Navigace',
'global_002' => 'Žádné odkazy\n',
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
'global_058' => 'Začít <br /> sledovat',
'global_059' => 'Nesledujete žádné téma',
'global_060' => 'Přestat sledovat téma',
// News & Articles
'global_070' => 'Napsal ',
'global_071' => 'dne ',
'global_072' => 'Číst více',
'global_073' => ' komentáře',
'global_073b' => ' komentář',
'global_074' => ' přečteno',
'global_075' => 'Vytisknout',
'global_076' => 'Upravit',
'News' => 'Novinky',
'No News has been posted yet' => 'Nebyla napsána žádná novinka',
'global_079' => 'Kategorie: ',
'global_080' => 'Bez kategorie',
// Page Navigation
'global_090' => 'Předchozí',
'global_091' => 'Další',
'global_092' => 'Webová stránka ',
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
'global_133' => 'hlas',
'global_134' => 'hlasů',
'global_135' => 'Celkem hlasů: ',
'global_136' => 'Založeno: ',
'global_137' => 'Ukončeno: ',
'global_138' => 'Archív anket',
'global_139' => 'Vybrat anketu z&nbsp;listy:',
'global_140' => 'Zobrazit výsledky',
'global_141' => 'Výsledky hlasování',
'global_142' => 'Žádná anketa',
// Shoutbox
'global_150' => 'Shoutbox',
'global_151' => 'Nick:',
'global_152' => 'Zpráva:',
'global_153' => 'Odeslat',
'global_154' => 'Musíte být přihlášeni, abyste mohli přidat zprávu.',
'global_155' => 'Archív shoutboxu',
'global_156' => 'Nejsou žádné zprávy. Možná je na čase přidat nějakou.',
'global_157' => 'Smazat',
'global_158' => 'Potvrzovací kód:',
'global_159' => 'Zadejte ověřovací kód:',
// Footer Counter
'global_170' => 'unikátní návštěva',
'global_172' => 'Vygenerováno w&nbsp;sekund: %s',
'global_173' => 'dotazy MySQL',
// Admin Navigation
'global_180' => 'Návrat do administračního panelu',
'Go to homepage' => 'Přejít na domovskou stránku',
'global_182' => '<strong>Pozor:</strong> Zadané heslo administrátora je nesprávné nebo prázdné.',
// Miscellaneous
'global_190' => 'Mód údržby je aktivován na webu.',
'global_191' => 'Vaše IP je zablokována.',
'global_192' => 'Odhlašuje jako: ',
'global_193' => 'Přihlašuje jako: ',
'global_194' => 'Váš účet byl zablokován.',
'global_195' => 'Účet není aktivní.',
'global_196' => 'Neplatné uživatelské jméno nebo heslo.',
'global_197' => 'Počkejte prosím na přesměrování...<br /><br />
[ <a href="index.html">Nechci čekat</a> ]',
'global_198' => '<strong>Upozornění:</strong> Zjištěn adresář Install, okamžitě jej smažte.',
//Titles
'global_201' => ': ',
'global_202' => 'Hledat',
'global_203' => 'FAQ',
'global_204' => 'Fórum',
//Themes
'global_210' => 'Přejít na obsah',
// No themes found
'global_300' => 'nenalezena šablona skórki.',
'global_301' => 'Stránka nemůže být zobrazena. Je to díky nedostatku odpovídajících souborů na webu. Pokud jste administrátor webu, spusťte klienta FTP a přesuňte do katalogu <em>/themes</em> jakoukoliv šablonu určenou pro <em>PHP-Fusion v7</em>. Pak klikněte na <em>Hlavní nastavení</em> v <em>panelu administrátora</em> a vyberte šablonu, kterou jste předali na adresáře <em>/themes</em>. Pokud tam není, zkontrolujte, zda vložena šablona má správný název (včetně velkých a malých písmen) jako jste zvolili v<em>Hlavním nastavení</em>.<br /><br />Pokud jste uživatelem tohoto webu, kontaktujte prosím administrátory zasláním e-mailu na :siteemail a informujte o stávajícím problému.', // ".HELP::hide_email($_sett -> getData('siteemail'))."
'global_302' => 'Vybraná šablona neexistuje nebo není kompletní!',
// JavaScript Not Enabled
'global_303' => '<p>O&nbsp;ne! Tato stránka potřebuje povolení jazyka <strong>JavaScriptu</strong>!</p>',


/**** TODO: Pliki językowe powinny być logic-less, czyli bez kodu PHP w tłumaczeniach, tym zajmą się dynamiczne parametry *****/
/**** TODO: Pliki językowe nie powinny zawierać szablonów wiadomości email, powinny znajdować się w tym katalogu, lecz w osobnych plikach które będą obsłużone przez OPY ****/

'global_441' => 'Váš účet na :sitename byl zabanován.', // ".$_sett -> getData('sitename')."
'global_442' => 'Vítejte :username!
Z Vašeho účtu na :sitename bylo odesláno příliš soukromých zpráv v krátké době z IP: :ip, proto byl Váš účet zablokován. Tato funkce byla zapnuta na ochranu stránky proti robotům, kteří píšou soukromé zprávy v krátkém časovém období.
Obraťte se na administrátory zasláním e-mailu na :siteemail, aby Vám účet odemkli.
Hodně štěstí,
:siteusername
------
Tato zpráva byla zaslána automaticky. Neodpovídejte na ní.', // ".$_user -> getIP()."
// Lifting of suspension
'global_450' => 'Automatyczne zdjęcie zawieszenia konta.',
'global_451' => 'Zdjęcie zawieszenia konta na :sitename', // .$_sett -> getData('sitename')
'global_452' => 'Vítejte :username!
Pozastavení Vašeho účtu na :siteurl bylo odstraněno. Přihlašte se pod svými údaji:
Název uživatele: :username
Heslo: skryté z bezpečnostních důvodů
Pokud jste zapomněli své heslo, resetujte jej pomocí následujícího odkazu: :link
Hodně štěstí,
:siteusername
------
Tato zpráva byla zaslána automaticky. Neodpovídejte na ní.',
'global_453' => 'Vítej :username!
Odstraněno pozastavení účtu na :siteurl.
Ať se daří,
:siteusername
------
Tato zpráva byla zaslána automaticky. Neodpovídejte na ní.',
'global_454' => 'Reaktivován účet na :sitename', // ".$_sett -> getData('sitename');
'global_455' => 'Vítejte :username!
Vaše poslední návštěva na :siteurl Vám aktivovala Váš účet.
Hodně štěstí,
:siteusername
------
Tato zpráva byla zaslána automaticky. Neodpovídejte prosím na ni.',
// Function parsebytesize()
'global_460' => 'Soubor prázdný',
'global_461' => 'Bajtů',
'global_462' => 'kB',
'global_463' => 'MB',
'global_464' => 'GB',
'global_465' => 'TB',
//Safe Redirect
'global_500' => 'Probíha přesměrování na adresu %s, prosíme počkejte. Stiskněte tlačítko, pokud nechcete čekat.',
//Czas sesji

// Function showCopyrights()
'Powered by :system' => 'Web běží na systému :system.<br />Copyright 2002-2013 <a href="http://php-fusion.co.uk/">PHP-Fusion</a>. Released as free software without warranties under <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html">aGPL v3</a>.',

'Powered by :system under :license License' => 'Web běží na systému :system.<br />Copyright 2002-2013 <a href="http://php-fusion.co.uk/">PHP-Fusion</a>. Released as free software without warranties under <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html">aGPL v3</a>.',

'Yes' => 'Ano',
'No' => 'Ne',
'Add' => 'Přidat',
'Remove' => 'Odebrat',
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
'Disable' => 'Zakázat',
'Access denied' => 'Přístup zamítnut',
'Options' => 'Volby',
'Done' => 'Připravený',
'Show all' => 'Bez třídění',
'Refresh' => 'Aktualizovat',
'Management' => 'Správa',
'Added by' => 'Přidal',
'Date' => 'Datum',
'No data' => 'Žádná data',
'or' => 'nebo',
'E-mail address has been hidden' => 'Adresa e-mailu byla skryta',
'Groups' => 'Skupiny',
'Current password:' => 'Aktuální heslo:',
'New password:' => 'Nové heslo:',
'Confirm new password:' => 'Potvrďte nové heslo:',
'Edit account' => 'Upravit účet',
'Language:' => 'Jazyk:',

'Never logged in' => 'Nikdy nepřihlášen',

'Polish' => 'Polský',
'English' => 'Anglický',
'Czech' => 'Český',

'Modules under Development.' => 'Modul ve fázi vývoje.',

// Footer
'Unique visits:' => 'Unikátních návštěv:',

// Error exceptions

// Uload
'The uploaded file exceeds the upload_max_filesize directive in php.ini.' => 'Wysyłany plik przekracza maksymalny rozmiar ustawiony w dyrektywie \'upload_max_filesize\' w pliku php.ini',
'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.' => 'Wysyłany plik przekracza maksymalny rozmiar ustawiony w dyrektywie \'MAX_FILE_SIZE\' przeznaczonej dla formularzy HTML.',
'The uploaded file was only partially uploaded.' => 'Nahraný soubor byl nahrán pouze částečně.',
'No file was uploaded.' => 'Žádný soubor nebyl odeslán.',
'Missing a temporary folder.' => 'Žádná dočasná složka.',
'Failed to write file to disk.' => 'Nepodařilo se zapsat soubor na disk.',
'File upload stopped by extension.' => 'Nahrávání souboru zastaveno rozšířením.',
'Unknown upload error.' => 'Došlo k neznámé chybě při nahrávání souboru.',
'Upload error' => 'Chyba při odesílání souborů',

//System
'System error' => 'Chyba systému',
'Internal error' => 'Vnitřní chyba',
'Error path' => 'Chyba cesty',
'In file' => 'V soubory',
'Function' => 'Funkce',
'Line' => 'Řádek',
'Directories' => 'Adresáře',
'Directory' => 'Adresář',
'Path' => 'Cesta',
'Status' => 'Status',
'Exist' => 'Existuje',
'Does not exist' => 'Neexistuje',
'File' => 'Soubor',
'Method' => 'Metoda',
'Templates' => 'Šablony',

// Arguments
'Function argument error' => 'Chyba argumentů funkce',
'Parameter of :parametr is wrong.' => 'Parametr :parametr je nesprávny.',

//User
'User error' => 'Chyba uživatele',
'PDO Error' => 'Błąd interpretera PDO',
'PDO Queries' => 'Zapytanie PDO',

//Comments
'Add comments' => 'Přidat komentář',
'Your name' => 'Vaše přezdívka',
'Enter a comment' => 'Vložit komentář...',
'Add replay' => 'Přidat odpověď',
'Adding your comment' => 'Přidávání komentáře',
'Comment has been added' => 'Komentář byl přidán',
'Commenting has been disabled for your group permissions' => 'Oprávnění pro komentování je zakázáno pro Vaší skupinu',
'Error: Not entered the content of comments' => 'Chyba: nebyl vložen obsah komentáře',
'Update comments' => 'Upravit komentář',
'Delete comments' => 'Smazat komentář',

//News Categories
'Bugs' => 'Chyby',
'Downloads' => 'Ke stažení',
'eXtreme-Fusion' => 'eXtreme-Fusion',
'Games' => 'Hry',
'Graphics' => 'Grafika',
'Hardware' => 'Hardware',
'Journal' => 'Cestování',
'Members' => 'Uživatelé',
'Mods' => 'Modifikace',
'Movies' => 'Filmy',
'Network' => 'Síť',
'News' => 'Novinky',
'Security' => 'Bezpečnost',
'Software' => 'Software',
'Themes' => 'Šablony',
'Windows' => 'Windows',
);

