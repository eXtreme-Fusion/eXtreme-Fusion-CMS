<?php
$lang['L_DUMP_HEADLINE'] = 'Tworzenie kopi zapasowej...';
$lang['L_GZIP_COMPRESSION'] = 'Kompresja GZip';
$lang['L_SAVING_TABLE'] = 'Zapisywanie tabeli ';
$lang['L_OF'] = 'z';
$lang['L_ACTUAL_TABLE'] = 'Aktualna tabela';
$lang['L_PROGRESS_TABLE'] = 'Postęp z tabel';
$lang['L_PROGRESS_OVER_ALL'] = 'Całkowity postęp';
$lang['L_ENTRY'] = 'Wejście';
$lang['L_DONE'] = 'Zakończono!';
$lang['L_DUMP_SUCCESSFUL'] = ' została pomyślnie utworzony.';
$lang['L_UPTO'] = 'aż do';
$lang['L_EMAIL_WAS_SEND'] = 'Pomyślnie wysłano wiadomość e-mail do ';
$lang['L_BACK_TO_CONTROL'] = 'Kontynuuj';
$lang['L_BACK_TO_OVERVIEW'] = 'Podgląd bazy danych';
$lang['L_DUMP_FILENAME'] = 'Plik kopii zapasowej: ';
$lang['L_WITHPRAEFIX'] = 'z prefiksem';
$lang['L_DUMP_NOTABLES'] = 'Nie znaleziono tabel w bazie danych `<b>%s</b>` ';
$lang['L_DUMP_ENDERGEBNIS'] = 'Plik zawiera <b>%s</b> tabel z <b>%s</b> rekordami.<br>';
$lang['L_MAILERROR'] = 'Nie udało się wysłać wiadomości e-mail!';
$lang['L_EMAILBODY_ATTACH'] = 'Załącznik zawiera kopię zapasową Twojej bazy danych.<br>Kopia bazy danych `%s`
<br><br>Następujące pliki zostały utworzone:<br><br>%s 
<br><br>Z poważaniem<br><br>MySQLDumper<br>';
$lang['L_EMAILBODY_MP_NOATTACH'] = 'Wieloczęściowa kopia zapasowa została utworzona.<br>Pliki kopi zapasowej nie są załączone do wiadomości e-mail!<br>Kopia bazy danych `%s`
<br><br>Następujące pliki zostały utworzone:<br><br>%s
<br><br>Z poważaniem<br><br>MySQLDumper<br>';
$lang['L_EMAILBODY_MP_ATTACH'] = 'Wieloczęściowa kopia zapasowa została utworzona.<br>Pliki kopi zapasowej są ząłączone do osobnych wiadomości e-mail.<br>Kopia bazy danych `%s`
<br><br>Następujące pliki zostały utworzone:<br><br>%s
<br><br>Z poważaniem<br><br>MySQLDumper<br>';
$lang['L_EMAILBODY_FOOTER'] = '`<br><br>Z poważaniem<br><br>MySQLDumper<br>';
$lang['L_EMAILBODY_TOOBIG'] = 'Plik kopii zapasowej przekroczł maksymalny rozmiar %s i nie został załączony do wiadomości e-mail.<br>Kopia bazy danych `%s`
<br><br>Następujące pliki zostały utworzone:<br><br>%s
<br><br>Z poważaniem<br><br>MySQLDumper<br>';
$lang['L_EMAILBODY_NOATTACH'] = 'Żadne pliki nie są załączone do wiadomości e-mail!<br>Kopia bazy danych `%s`
<br><br>Następujące pliki zostały utworzone:<br><br>%s
<br><br>Z poważaniem<br><br>MySQLDumper<br>';
$lang['L_EMAIL_ONLY_ATTACHMENT'] = ' ... tylko załączniki.';
$lang['L_TABLESELECTION'] = 'Wybieranie tabeli';
$lang['L_SELECTALL'] = 'Zaznacz wszystkie';
$lang['L_DESELECTALL'] = 'Odznacz wszystkie';
$lang['L_STARTDUMP'] = 'Rozpocznij tworzenie kopii zapasowej';
$lang['L_LASTBUFROM'] = 'ostatni update z';
$lang['L_NOT_SUPPORTED'] = 'Ta kopia zapasowa nie obsługuje tej funkcji.';
$lang['L_MULTIDUMP'] = 'Multidump: Kopie zapasowa <b>%d</b> baz danych ukończone.';
$lang['L_FILESENDFTP'] = 'wysyłanie pliku przez FTP ... prosimy o cierpliwość. ';
$lang['L_FTPCONNERROR'] = 'Brak połączenia z FTP! Połączenie z ';
$lang['L_FTPCONNERROR1'] = ' jako użytkownik ';
$lang['L_FTPCONNERROR2'] = ' nie jest możliwe';
$lang['L_FTPCONNERROR3'] = 'Nie udało się wysłać pliku przez FTP! ';
$lang['L_FTPCONNECTED1'] = 'Połączono z ';
$lang['L_FTPCONNECTED2'] = ' na ';
$lang['L_FTPCONNECTED3'] = ' transfer zakończony pomyślnie';
$lang['L_NR_TABLES_SELECTED'] = '- z %s zaznaczonych tabeli';
$lang['L_NR_TABLES_OPTIMIZED'] = '<span class=\'small\'>%s tabel zostało optymalizowanych.</span>';
$lang['L_DUMP_ERRORS'] = '<p class=\'error\'>%s błędów wystąpiło: <a href=\'log.php?r=3\'>podgląd</a></p>';
$lang['L_FATAL_ERROR_DUMP'] = 'Błąd krytyczny: CREATE-Statement tabeli \'%s\' w bazie danych \'%s\' nie może zostać odczytany!';