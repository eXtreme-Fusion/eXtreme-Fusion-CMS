<div id="content">
<h3>O projekcie</h3>
Inicjatorem projektu jest Daniel Schlichtholz.<p>W 2004 założył forum <a href="http://forum.mysqldumper.de" target="_blank">MySQLDumper</a> i wkrótce, programiści zaczęli uzupełniać skrypty Daniela.<br />Po krótkim czasie, opublikowany został niewielki skrypt kopii zapasowych.<p>Jeśli chcesz dodać coś ciekawego do projektu, odwiedź forum MySQLDumper: <a href="http://forum.mysqldumper.de" target="_blank">http://www.mysqldumper.de</a>.<p>We wish you a lot of fun with this project.<br /><br /><h4>The MySQLDumper-Team</h4>
<table><tr><td><img src="images/logo.gif" alt="MySQLDumper" border="0"></td><td valign="top">
Daniel Schlichtholz<br />
</td></tr></table>

<h3>MySQLDumper - Pomoc</h3>

<h4>Pobieranie</h4>
Skrypt jest dostępny na stronie MySQLDumper.<br />
Rekomendowane jest, by odwiedzać stronę MySQLDumper regularnie w celu posiadania najświeższych wiadomości o aktualizacjach i pomocy.<br />
Adres strony: <a href="http://forum.mysqldumper.de" target="_blank">
http://forum.mysqldumper.de
</a>

<h4>Szczegóły systemowe</h4>
Skrypt współpracuje z (prawie) każdym serwerem - Windows, Linux, itp.<br />
oraz z PHP 4.3.4 i nowszymi, z biblioteką GZip, MySQL (3.23 i nowsze) oraz JavaScript (musi być uruchomiony).

<a href="install.php?language=de" target="_top"><h4>Instalacja</h4></a>
Instalacja skryptu jest bardzo łatwa.
Wypakuj archiwum do dowolnego folderu dostępnego na serwerze<br />
(np. w folderze głównym [serwer root/]MySQLDumper)<br />
zmień chmod pliku config.php na 777<br />
i gotowe!<br />
Możesz uruchomić MySQLDumper w przeglądarce internetowej wpisując w pasek adresu "http://twoj_serwer/MySQLDumper"
By ukończyć instalacje, postępuj zgodnie z instrukcją.

<br /><b>Uwaga:</b><br /><i>Jeśli Twój serwer ma uruchomioną opcję <b>safemode=ON</b> MySqlDump nie będzie mógł tworzyć katalogów.<br />
Będziesz musiał robić to ręcznie.<br />
MySqlDump w takim wypadku przerwie pracę i poinformuje Cię co robić dalej.<br />
Gdy utworzysz katalogi, MySqlDump będzie już funkcjonował bez problemów.</i><br />

<a name="perl"></a><h4>Wskazówki dla posiadaczy Perl</h4>

Większość posiada katalog cgi-bin, do którego można wypakować skrypt Perl. <br />
Zwykle w przeglądarce jest to dostępne pod http://twoja_strona/cgi-bin/. <br /><br />

Aby wypakować pearl na swoim serwerze, wykonaj następujące kroki:  <br /><br />

1. W MySQLDumper otwórz stronę "Kopia zapasowa" i kliknij "Stwórz kopię zapasową Perl"   <br />
2. Skopiuj ścieżkę absolute_path_of_configdir dla crondumpl.pl (pierwsza z 3 podanych):   <br />
3. Otwórz w edytorze plik "crondump.pl" <br />
4. Wklej skopiowaną ścieżkę absolute_path_of_configdir (bez przerw) <br />
5. Zapisz crondump.pl <br />
6. Skopiuj pliki crondump.pl, perltest.pl i simpletest.pl do folderu cgi-bin (w kliencie ftp musi być uruchomione ASCII!) <br />
7. Ustaw chmod 755 dla tyych plików.  <br />
7b. Jeśli wymagana jest nazwa plików cgi, zmień ją we wszystkich 3 plikach pl -> cgi (zmień nazwę)  <br />
8. W MySQLDumper otwórz podstronę Konfiguracja<br />
9. Kliknij Skrypt Cron <br />
10. Zmień ścieżkę Pearl na /cgi-bin/<br />
10b. Jeśli skrypty są nazwane .cgi, zmień ich rozszerzenie na cgi <br />
11 Zapisz konfigurację <br /><br />

Gotowe! Skrypty są dostępne na stronie "Kopia zapasowa" <br /><br />

Jeśli możesz wypakować Perl wszędzie, wystarczy wykonać następujące kroki:  <br /><br />

1. W MySQLDumper otwórz stronę "Kopia zapasowa:"  <br />
2. Skopiuj ścieżkę absolute_path_of_configdir dla crondumpl.pl (pierwsza z 3 podanych)  <br />
3. Otwórz w edytorze plik "crondump.pl" <br />
4. Wklej skopiowaną ścieżkę z absolute_path_of_configdir (bez przerw) <br />
5. Zapisz plik "crondump.pl" <br />
6. Ustaw chmod 755 dla skryptów podanych powyżej w punkcie 6.  <br /> 
6b. Jeśli wymagana jest nazwa plików cgi, zmień ją we wszystkich 3 plikach pl -> cgi (zmień nazwę)  <br />
(ewentualnie wykonaj kroki 10b i 11) <br /><br />


Użytkownicy serwerów Windows muszą zmienić pierwszą linię wszystkich skryptów Pearl na ścieżkę do Pearl.  <br /><br />

Przykład:  <br />

zamiast:  #!/usr/bin/perl w <br />
musi być: #!C:\perl\bin\perl.exe w<br />

<h4>Obsługa:</h4><ul>

<h6>Menu</h6>
W dolnej części menu należy wybrać swoją bazę danych.<br />
Wszystkie działania będą oddziaływały tylko na wybraną bazę.

<h6>Strona główna</h6>
Tutaj znajdują się informacje o Twoim systemie, wersji skryptu oraz konfiguracji bazy danych.<br />
Jeśli klikniesz na bazie danych w tabeli (po wejściu w "Bazy danych", wyświetli się lista tabeli wraz z listą rekordów, ich rozmiarem i informacją o ostatniej aktualizacji rekordu.

<h6>Konfiguracja</h6>
Tutaj możesz edytować konfigurację skryptu, zapisać ją lub wczytać domyślne ustawienia.
<ul>
	<li><a name="conf1"><strong>Konfigurowana baza danych:</strong> lista konfigurowanych baz danych. Aktywna baza danych jest pogrubiona.</li>
	<li><a name="conf2"><strong>Prefiks tabeli:</strong> możesz wybrać osobny prefiks dla każdej bazy danych. Prefiks jest filtrem, który trzyma tabele bazy każdej bazy danych koło siebie (np. tabele zaczynające się "phpBB_"). Jeśli nie chcesz używac tej opcji, pozostaw to pole puste.</li>
	<li><a name="conf3"><strong>Kompresja GZip:</strong> Tutaj możesz aktywować kompresję. Jest to rekomendowana opcja, ponieważ dzięki mniejszemu rozmiarowi plików, zaoszczędzamy więcej miejsca na dysku.</li>
	<li><a name="conf19"></a><strong>Liczba rekordów do stworzenia kopii zapasowej:</strong> Liczba rekordów, które są odczytywane w czasie tworzenia kopii zapasowej. Dla wolniejszych serwerów można zmniejszyć liczbę, by nie przekroczyć limitów czasowych.</li>
	<li><a name="conf20"></a><strong>Liczba rekordów do przywrócenia:</strong> Liczba rekordów, które są odczytywane w czasie przywracania kopii zapasowej. Dla wolniejszych serwerów można zmniejszyć liczbę, by nie przekroczyć limitów czasowych.</li>
	<li><a name="conf4"></a><strong>Katalog dla kopii zapasowej:</strong> wybierz katalog w którym będą przechowywane kopie zapasowe plików. Jeśli wybierzesz nieistniejacy katalog, skrypt stworzy go za Ciebie. Możesz użyć ścieżek relatywnych lub absolutnych..</li>
	<li><a name="conf5"></a><strong>Wyślij kopię jako e-mail:</strong> Gdy ta opcja jest aktywna, skrypt automatycznie wyśle stworzoną kopię zapasową jako załącznik do wiadomości e-mail (Uwaga! Dla tej opcji powinna być uruchomiona kompresja GZip, by rozmiar plików nie przekroczył dopuszczalnej wartości dla wiadomości e-mail!).</li>
	<li><a name="conf6"></a><strong>Adres e-mail:</strong> Adres e-mail odbiorcy</li>
	<li><a name="conf7"></a><strong>Temat e-mail:</strong> Temat wysyłanej wiadomości e-mail</li>
	<li><a name="conf13"></a><strong>Transfer FTP:</strong> Gdy ta opcja jest aktywna, skrypt automatycznie wyśle plik kopii zapasowej przy użyciu FTP.</li>
	<li><a name="conf14"><strong>Serwer FTP: </strong>Adres serwera FTP, na który ma być wysłany plik (np. ftp.mybackups.de)</li>
	<li><a name="conf15"></a><strong>Port serwera FTP: </strong>Port serwera, na który ma być wysłany plik (zwykle 21)</li>
	<li><a name="conf16"></a><strong>Użytkownik FTP: </strong>Nazwa użytkownika dla konta FTP</li>
	<li><a name="conf17"></a><strong>Hasło FTP: </strong>Hasło użytkownika dla konta FTP</li>
	<li><a name="conf18"></a><strong>Miejsce zapisu na FTP: </strong>Folder w którym będą zapisywane pliki kopii zapasowych (wymagane jest ustawienie odpowiednich chmodów!)</li>
	
	<li><a name="conf8"></a><strong>Automatyczne usuwanie plików kopii zapasowych:</strong> Gdy aktywujesz tą opcję, pliki kopii zapasowych będą automatycznie usuwane według podanych wytycznych.</li>
	<li><a name="conf10"></a><strong>Usuń według numeru pliku:</strong> Wartość większa od 0 - usuwa wszystkie pliki z wyjątkiem podanego numeru</li>
	<li><a name="conf11"></a><strong>Język:</strong> Wybierz język interfejsu.</li>
</ul>

<h6>Zarządzanie</h6>
Wszystkie dostępne działania są wypisane tutaj.<br />
Widzisz wszystkie pliki w katalogu kopii zapasowych.
Dla akcji "Przywróć" i "Usuń" najpierw należy wybrać plik.
<UL>
	<li><strong>Przywróć:</strong> przywracasz bazę danych z rekordami wybranymi z kopii zapasowej.</li>
	<li><strong>Usuń:</strong> usuwasz wybrane kopie zapasowe.</li>
	<li><strong>Rozpocznij nową kopię:</strong> rozpoczynasz nową kopię zapasową według ustawionych parametrów.</li>
</UL>

<h6>Logi</h6>
Możesz odczytać logi wejść i usunąć je.

<h6>Pomoc</h6>
Ta strona.
</ul>