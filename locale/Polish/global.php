<?php defined('EF5_SYSTEM') || exit;
/**
 * Polish (UTF-8) Language Fileset for eXtreme-Fusion,
 * based at translation for PHP-Fusion v7.01.xx.
 *
 * @author   Polish Official PHP-Fusion's Support
 * @see      http://php-fusion.pl
 * @license  Affero GPL license
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
setlocale(LC_TIME, array('pl_PL.UTF-8', 'Polish'));

return array(

	// Tutaj zamieszczać locale pisane tylko przez EF Team, tak by przepisać ten plik na własny
	'html_charset' => 'UTF-8',
	// Koniec locali EF Team

	'An unexpected error occurred' => 'Wystąpił nieoczekiwany błąd',

	# Komunikaty analizy danych

	'Action error' => 'Wystąpił błąd :( Akcja nie została wykonana poprawnie',
	'Action success' => 'Wszystko wporzo :) Akcja wykonana poprawnie',


	/* INDEKSY JUŻ GOTOWE DLA EXTREME-FUSION 5 */

	// Zarządzanie (edycja, usuń)
	'Management' => 'Zarządzanie',
	'Author' => 'Autor',
	'Title:' => 'Tytuł:',
	'Content' => 'Treść',
	// userException class
	'Entered the wrong data type.' => 'Wprowadzono dane o niepoprawnym typie',

	'Username' => 'Nazwa użytkownika',
	'Online' => 'na stronie',
	'Offline' => 'poza stroną',

	// Dla listy wyboru ochrony formularza przed spamem
	'Without protection' => 'bez ochrony',


	'Statistics' => 'Statystyki',
	/* koniec indeksów EF5, poniżej indeksy z PF lub namieszane */

	'Description:' => 'Opis:',
	'Keywords:' => 'Słowa kluczowe:',
	'Select all' => 'Zaznacz wszystko',
	'Group' => 'Grupa',
	'Name:' => 'Nazwa:',
	'Login' => 'Zaloguj się',
	'Username:' => 'Nazwa użytkownika:',
	'Password:' => 'Hasło:',
	'Confirm password:' => 'Powtórz hasło:',
	'E-mail address:' => 'Adres e-mail:',
	'Hide e-mail:' => 'Ukryć e-mail:',
	'Remember me' => 'Pamiętaj logowanie',
	'Settings' => 'Ustawienia',

	'Register' => 'Rejestracja',
	'Forgot password' => 'Odzyskiwanie hasła',

	'Visible for:' => 'Widoczne dla:',
	'Order:' => 'Kolejność:',
	'Error' => 'Błąd',
	'Options:' => 'Opcje:',

	'Database error' => 'Błąd podczas aktualizacji danych: ustawienia nie zostały zmienione!',

	//Informations
	'Author:' => 'Autor:',
	'Date:' => 'Data dodania:',
	'Reads:' => 'Czytań:',
	'Comments:' => 'Komentarzy:',
	'User:' => 'Użytkownik:',
	'Message:' => 'Wiadomość:',

	'User Panel' => 'Panel użytkownika',
	'Admin Panel' => 'Administracja',
	
	'--No selection--' => '--Brak wyboru--',
	'--Default--' => '--Domyślne--',

	'Private messages' => 'Prywatne wiadomości',

	'Failed to request account data. Error:' => 'Nie udało się pobrać danych konta. Błąd:',
	'Failed to request report data. Error:' => 'Nie udało się pobrać danych raportu. Błąd:',
	'Failed to authenticate user. Error:' => 'Nie udało się uwierzytelnić użytkownika. Błąd:',
	'Invalid http interface defined. No such interface as' => 'Podano nieprawidłowy infetfejs http. Nie istnieje interfejs',
	'Request failed, fopen provides no further information' => 'Żądanie nie powiodło się, fopen nie dostarcza dodatkowych informacji',
	'No valid root parameter or aggregate metric called' => 'Niepoprawny parametr root',
	'There is no valid property called' => 'Niepoprawna własność',
	'Data has been saved.' => 'Dane zostały zapisane.',
	'Error! Data has not been saved.' => 'Wystąpił błąd! Dane nie zostały zapisane.',
	'There is no such function as' => 'Nie istnieje funkcja',

	// Full & Short Months
	'months' => '&nbsp|styczeń|luty|marzec|kwiecień|maj|czerwiec|lipiec|sierpień|wrzesień|październik|listopad|grudzień',
	'shortmonths' => '&nbsp|st.|lt.|mar.|kwi.|maj|czer.|lip.|sier.|wrz.|paź.|lis.|gru.',

	//Paging
	'Go to first page' => 'Idź do pierwszej podstrony',
	'Go to page' => 'Idź do podstrony',
	'Go to last page' => 'Idź do ostatniej podstrony',


	// Standard User Levels
	'Guest' => 'Gość',
	'User' => 'Użytkownik',
	'Admin' => 'Administrator',
	'hAdmin' => 'Główny administrator',
	'Administration' => 'Administracja',
	'Site' => 'Strona',
	'user_na' => 'Nd.',
	'user_anonymous' => 'Konto ukryte',
	// Forum Moderator Level(s)
	'userf1' => 'Moderator',
	// Navigation
	'global_001' => 'Nawigacja',
	'global_002' => 'Brak linków\n',
	// Forum Side panel
	'global_020' => 'Ostatnio na forum',
	'global_021' => 'Najnowsze tematy',
	'global_022' => 'Najciekawsze tematy',
	'global_023' => 'Brak tematów na forum',
	// Articles Side panel
	'global_030' => 'Ostatnie artykuły',
	'global_031' => 'Brak artykułów',
	// Welcome panel
	'global_035' => 'Powitanie',
	'global_036' => 'Odśwież stronę',
	'Welcome :username!' => 'Witaj :username!',
	// Latest Active Forum Threads panel
	'global_040' => 'Ostatnio poruszane tematy',
	'global_041' => 'Moje ostatnie tematy',
	'global_042' => 'Moje ostatnie posty',
	'global_043' => 'Nowe posty',
	'global_044' => 'Temat',
	'global_045' => 'Wyświetleń',
	'global_046' => 'Odpowiedzi',
	'global_047' => 'Ostatni post',
	'global_048' => 'Forum',
	'global_049' => 'Napisane przez',
	'global_050' => 'Autor',
	'global_051' => 'Ankieta',
	'global_052' => 'Przeniesiony',
	'global_053' => 'Brak rozpoczętych przez Ciebie tematów.',
	'global_054' => 'Brak napisanych przez Ciebie postów.',
	'global_055' => 'Nowych postów od Twojej ostatniej wizyty: %u',
	'global_056' => 'Moje obserwowane tematy',
	'global_057' => 'Opcje',
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
	'Edit profile' => 'Edytuj profil',//120
	'Messages' => 'Wiadomości',
	'Users' => 'Użytkownicy',
	'Logout' => 'Wyloguj',

	'global_128' => 'Twój avatar',
	'global_129' => 'Nie avatar ',
	// Poll
	'global_130' => 'Ankieta',
	'global_131' => 'Zagłosuj',
	'global_132' => 'Musisz zalogować się, aby móc zagłosować.',
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
	'Powered by :system' => 'Strona oparta na systemie CMS :system.<br />Copyright 2002-2013 <a href="http://php-fusion.co.uk/">PHP-Fusion</a>. Released as free software without warranties under <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html">aGPL v3</a>.',

	'Powered by :system under :license License' => 'Strona oparta na systemie CMS :system.<br />Copyright 2002-2013 <a href="http://php-fusion.co.uk/">PHP-Fusion</a>. Released as free software without warranties under <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html">aGPL v3</a>.',

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
	'Show all' => 'Bez filtrowania',
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
	'Edit account' => 'Edytuj konto',
	'Language:' => 'Język:',

	'Polish' => 'Polski',
	'English' => 'Angielski',
	'Czech' => 'Czeski',

	'Modules under Development.' => 'Moduł w trakcie rozwoju.',

	// Error exceptions

	// Uload
	'The uploaded file exceeds the upload_max_filesize directive in php.ini.' => 'Wysyłany plik przekracza maksymalny rozmiar ustawiony w dyrektywie \'upload_max_filesize\' w pliku php.ini',
	'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.' => 'Wysyłany plik przekracza maksymalny rozmiar ustawiony w dyrektywie \'MAX_FILE_SIZE\' przeznaczonej dla formularzy HTML.',
	'The uploaded file was only partially uploaded.' => 'Wysyłany plik został zapisany tylko w części.',
	'No file was uploaded.' => 'Żaden plik nie został wysłany.',
	'Missing a temporary folder.' => 'Brak folderu tymczasowego.',
	'Failed to write file to disk.' => 'Przerwane zapisywanie pliku na dysku.',
	'File upload stopped by extension.' => 'Wysyłanie pliku zatrzymane przez wyjątek.',
	'Unknown upload error.' => 'Wystąpił nie znany błąd podczas wysyłania pliku.',
	'Upload error' => 'Błąd wysyłanie plików',

	//System
	'System error' => 'Błąd systemu',
	'Internal error' => 'Błąd wewnętrzny',
	'Error path' => 'Ścieżka błędu',
	'In file' => 'W pliku',
	'Function' => 'Funckja',
	'Line' => 'Linia',
	'Directories' => 'Katalogi',
	'Directory' => 'Katalog',
	'Path' => 'Ścieżka',
	'Status' => 'Status',
	'Exist' => 'Istnieje',
	'Does not exist' => 'Nie istnieje',
	'File' => 'Plik',
	'Method' => 'Metoda',
	'Templates' => 'Templateka',

	// Arguments
	'Function argument error' => 'Błąd argumentów funkcji',
	'Parameter of :parametr is wrong.' => 'Parametr :parametr jest nie prawidłowy.',

	//User
	'User error' => 'Błąd użytkownika',
	'PDO Error' => 'Błąd interpretera PDO',

	//Comments
	'Add comments' => 'Skomentuj',
	'Your name' => 'Twoja ksywka',
	'Enter a comment' => 'Wpisz komentarz...',
	'Add replay' => 'Dodaj odpowiedź',
	'Adding your comment' => 'Dodawanie komentarza',
	'Comment has been added' => 'Komentarz został dodany',
	'Commenting has been disabled for your group permissions' => 'Komentowanie zostało wyłączone dla twojej grupy uprawnień',

	//News Categories
	'Bugs' => 'Błędy',
	'Downloads' => 'Download',
	'eXtreme-Fusion' => 'eXtreme-Fusion',
	'Games' => 'Gry',
	'Graphics' => 'Grafika',
	'Hardware' => 'Sprzęt',
	'Journal' => 'Dziennik',
	'Members' => 'Użytkownicy',
	'Mods' => 'Modyfikacje',
	'Movies' => 'Filmy',
	'Network' => 'Sieć',
	'News' => 'Newsy',
	'Security' => 'Bezpieczeństwo',
	'Software' => 'Oprogramowanie',
	'Themes' => 'Skórki',
	'Windows' => 'Windows',
);
