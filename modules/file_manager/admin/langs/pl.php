<?php
/**
* language pack
* @author Rafał Krupiński (kontakt [at] rafik [dot] eu)
* @link rafik.eu
* @since 17/10/2011
*
*/
define('DATE_TIME_FORMAT', 'd/m/Y H:i:s');

//Menu
define('MENU_SELECT', 'Wybierz');
define('MENU_DOWNLOAD', 'Pobierz');
define('MENU_PREVIEW', 'Podgląd');
define('MENU_RENAME', 'Zmień nazwę');
define('MENU_EDIT', 'Edytuj');
define('MENU_CUT', 'Wytnij');
define('MENU_COPY', 'Kopiuj');
define('MENU_DELETE', 'Usuń');
define('MENU_PLAY', 'Odtwórz');
define('MENU_PASTE', 'Wklej');

//Top Action
define('LBL_ACTION_REFRESH', 'Odświerz');
define('LBL_ACTION_DELETE', 'Usuń');
define('LBL_ACTION_CUT', 'Wytnij');
define('LBL_ACTION_COPY', 'Kopiuj');
define('LBL_ACTION_PASTE', 'Wklej');
define('LBL_ACTION_CLOSE', 'Zamknij');
define('LBL_ACTION_SELECT_ALL', 'Zaznacz wszystko');

//File Listing
define('LBL_NAME', 'Nazwa');
define('LBL_SIZE', 'Rozmiar');
define('LBL_MODIFIED', 'Modyfikowany dnia');

//File Information
define('LBL_FILE_INFO', 'Informacje o pliku:');
define('LBL_FILE_NAME', 'Nazwa:');	
define('LBL_FILE_CREATED', 'Utworzony:');
define('LBL_FILE_MODIFIED', 'Zmodyfikowany:');
define('LBL_FILE_SIZE', 'Rozmiar pliku:');
define('LBL_FILE_TYPE', 'Typ pliku:');
define('LBL_FILE_WRITABLE', 'Zapis');
define('LBL_FILE_READABLE', 'Odczyt');

//Folder Information
define('LBL_FOLDER_INFO', 'Informacje o folderze');
define('LBL_FOLDER_PATH', 'Folder:');
define('LBL_CURRENT_FOLDER_PATH', 'Aktualny folder:');
define('LBL_FOLDER_CREATED', 'Utworzony:');
define('LBL_FOLDER_MODIFIED', 'Zmodyfikowany:');
define('LBL_FOLDER_SUDDIR', 'Folder podrzędny:');
define('LBL_FOLDER_FIELS', 'Pliki:');
define('LBL_FOLDER_WRITABLE', 'Zapis');
define('LBL_FOLDER_READABLE', 'Odczyt');
define('LBL_FOLDER_ROOT', 'Folder główny');

//Preview
define('LBL_PREVIEW', 'Podgląd');
define('LBL_CLICK_PREVIEW', 'Kliknij tu aby podejrzeć plik.');

//Buttons
define('LBL_BTN_SELECT', 'Zaznacz');
define('LBL_BTN_CANCEL', 'Anuluj');
define('LBL_BTN_UPLOAD', 'Wyślij');
define('LBL_BTN_CREATE', 'Utwórz');
define('LBL_BTN_CLOSE', 'Zamknij');
define('LBL_BTN_NEW_FOLDER', 'Nowy folder');
define('LBL_BTN_NEW_FILE', 'Nowy plik');
define('LBL_BTN_EDIT_IMAGE', 'Edytuj');
define('LBL_BTN_VIEW', 'Zaznacz podgląd');
define('LBL_BTN_VIEW_TEXT', 'Tekst');
define('LBL_BTN_VIEW_DETAILS', 'Szczegóły');
define('LBL_BTN_VIEW_THUMBNAIL', 'Miniaturki');
define('LBL_BTN_VIEW_OPTIONS', 'Zobacz w:');

//pagination
define('PAGINATION_NEXT', 'Następny');
define('PAGINATION_PREVIOUS', 'Poprzedni');
define('PAGINATION_LAST', 'Ostatni');
define('PAGINATION_FIRST', 'Pierwszy');
define('PAGINATION_ITEMS_PER_PAGE', 'Wyświetl %s pozycji na stronie');
define('PAGINATION_GO_PARENT', 'Idź folder wyżej');

//System
define('SYS_DISABLED', 'Dostęp wzbroniny: System wyłączony.');

//Cut
define('ERR_NOT_DOC_SELECTED_FOR_CUT', 'Brak zaznaczonych dokumentów do wycięcia.');

//Copy
define('ERR_NOT_DOC_SELECTED_FOR_COPY', 'Brak zaznaczonych dokumentów do skopiowania.');

//Paste
define('ERR_NOT_DOC_SELECTED_FOR_PASTE', 'Brak zaznaczonych dokumentów do wklejenia.');
define('WARNING_CUT_PASTE', 'Czy na pewno chcesz przenieść wybrane dokumenty do bieżącego folderu?');
define('WARNING_COPY_PASTE', 'Czy na pewno skopiować wybrane dokumenty do bieżącego folderu?');
define('ERR_NOT_DEST_FOLDER_SPECIFIED', 'Brak określonego folderu docelowego.');
define('ERR_DEST_FOLDER_NOT_FOUND', ' Nie znaleziono folderu docelowego.');
define('ERR_DEST_FOLDER_NOT_ALLOWED', 'Nie masz uprawnień, aby przenieść pliki do tego folderu');
define('ERR_UNABLE_TO_MOVE_TO_SAME_DEST', 'Nie można przenieść tego pliku (%s): oryginalna ścieżka jest taka sama jak ścieżka docelowa.');
define('ERR_UNABLE_TO_MOVE_NOT_FOUND', 'Nie można przenieść tego pliku (%s): Oryginalny plik nie istnieje.');
define('ERR_UNABLE_TO_MOVE_NOT_ALLOWED', 'Nie można przenieść tego pliku (%s): Brak dostępu do pliku.');
define('ERR_NOT_FILES_PASTED', 'Brak plików do wklejenia.');

//Search
define('LBL_SEARCH', 'Szukaj');
define('LBL_SEARCH_NAME', 'Pełna/Częściowa nazwa pliku:');
define('LBL_SEARCH_FOLDER', 'Szukaj w:');
define('LBL_SEARCH_QUICK', 'Szybkie wyszukiwanie');
define('LBL_SEARCH_MTIME', 'Czas modyfikacji plików(zakres):');
define('LBL_SEARCH_SIZE', 'Rozmiar pliku:');
define('LBL_SEARCH_ADV_OPTIONS', 'Opcje zaawansowane');
define('LBL_SEARCH_FILE_TYPES', 'Typ pliku:');
define('SEARCH_TYPE_EXE', 'Aplikacja');
define('SEARCH_TYPE_IMG', 'Obraz');
define('SEARCH_TYPE_ARCHIVE', 'Archiwum');
define('SEARCH_TYPE_HTML', 'HTML');
define('SEARCH_TYPE_VIDEO', 'Wideo');
define('SEARCH_TYPE_MOVIE', 'Film');
define('SEARCH_TYPE_MUSIC', 'Muzyka');
define('SEARCH_TYPE_FLASH', 'Flash');
define('SEARCH_TYPE_PPT', 'PowerPoint');
define('SEARCH_TYPE_DOC', 'Dokument');
define('SEARCH_TYPE_WORD', 'Word');
define('SEARCH_TYPE_PDF', 'PDF');
define('SEARCH_TYPE_EXCEL', 'Excel');
define('SEARCH_TYPE_TEXT', 'Tekst');
define('SEARCH_TYPE_UNKNOWN', 'Nieznany');
define('SEARCH_TYPE_XML', 'XML');
define('SEARCH_ALL_FILE_TYPES', 'Wszystkie typy plików');
define('LBL_SEARCH_RECURSIVELY', 'Szukaj rekurencyjnie:');
define('LBL_RECURSIVELY_YES', 'Tak');
define('LBL_RECURSIVELY_NO', 'Nie');
define('BTN_SEARCH', 'Szukaj');

//thickbox
define('THICKBOX_NEXT', 'Następny&gt;');
define('THICKBOX_PREVIOUS', '&lt;Poprzedni');
define('THICKBOX_CLOSE', 'Zamknij');

//Calendar
define('CALENDAR_CLOSE', 'Zamknij');
define('CALENDAR_CLEAR', 'Wyczyść');
define('CALENDAR_PREVIOUS', '&lt;Poprzedni');
define('CALENDAR_NEXT', 'Następny&gt;');
define('CALENDAR_CURRENT', 'Dziś');
define('CALENDAR_MON', 'Pon');
define('CALENDAR_TUE', 'Wt');
define('CALENDAR_WED', 'Śr');
define('CALENDAR_THU', 'Czw');
define('CALENDAR_FRI', 'Pt');
define('CALENDAR_SAT', 'Sob');
define('CALENDAR_SUN', 'Ndz');
define('CALENDAR_JAN', 'St');
define('CALENDAR_FEB', 'Lu');
define('CALENDAR_MAR', 'Mar');
define('CALENDAR_APR', 'Kw');
define('CALENDAR_MAY', 'Maj');
define('CALENDAR_JUN', 'Cze');
define('CALENDAR_JUL', 'Lip');
define('CALENDAR_AUG', 'Sie');
define('CALENDAR_SEP', 'Wrz');
define('CALENDAR_OCT', 'Paź');
define('CALENDAR_NOV', 'Lis');
define('CALENDAR_DEC', 'Gru');

//ERROR MESSAGES
//deletion
define('ERR_NOT_FILE_SELECTED', 'Proszę wybrać plik.');
define('ERR_NOT_DOC_SELECTED', 'Brak dokumentu(ów) wybranego(ych) do usunięcia.');
define('ERR_DELTED_FAILED', 'Nie można usunąć wybranego(ych) dokumentu(ów).');
define('ERR_FOLDER_PATH_NOT_ALLOWED', 'Ścieżka folderu jest nie dozwolona.');

//class manager
define('ERR_FOLDER_NOT_FOUND', 'Nie można zlokalizować określonego folderu: ');

//rename
define('ERR_RENAME_FORMAT', 'Proszę nadać mu nazwę, w której zawierają się tylko litery, cyfry, spacja, myślnik i podkreślenie.');
define('ERR_RENAME_EXISTS', 'Proszę nadać mu nazwę, która będzie unikalna w tym folderze.');
define('ERR_RENAME_FILE_NOT_EXISTS', 'Plik/folder nie istnieje.');
define('ERR_RENAME_FAILED', 'Nie można zmienić jego nazwy, spróbuj ponownie.');
define('ERR_RENAME_EMPTY', 'Proszę nadać mu nazwę.');
define('ERR_NO_CHANGES_MADE', 'Nie dokonano żadnych zmian.');
define('ERR_RENAME_FILE_TYPE_NOT_PERMITED', 'Nie masz uprawnień aby zmieniać nazwy plików na takie rozszerzenie.');

//folder creation
define('ERR_FOLDER_FORMAT', 'Proszę nadać mu nazwę, w której zawierają się tylko litery, cyfry, spacja, myślnik i podkreślenie.');
define('ERR_FOLDER_EXISTS', 'Proszę nadać mu nazwę, która będzie unikalna w tym folderze.');
define('ERR_FOLDER_CREATION_FAILED', 'Nie można utworzyć folderu, spróbuj jeszcze raz.');
define('ERR_FOLDER_NAME_EMPTY', 'Proszę nadać mu nazwę.');
define('FOLDER_FORM_TITLE', 'Tworzenie nowego folderu');
define('FOLDER_LBL_TITLE', 'Nazwa folderu:');
define('FOLDER_LBL_CREATE', 'Utwórz');

//New File
define('NEW_FILE_FORM_TITLE', 'Tworzenie nowego pliku');
define('NEW_FILE_LBL_TITLE', 'Nazwa pliku:');
define('NEW_FILE_CREATE', 'Utwórz');

//file upload
define('ERR_FILE_NAME_FORMAT', 'Proszę nadać mu nazwę, w której zawierają się tylko litery, cyfry, spacja, myślnik i podkreślenie.');
define('ERR_FILE_NOT_UPLOADED', 'Żadne pliku nie zostały zaznaczone do wysłania na serwer');
define('ERR_FILE_TYPE_NOT_ALLOWED', 'Nie masz uprawnień do wysyłania tego typu pliku.');
define('ERR_FILE_MOVE_FAILED', 'Nie można przenieść pliku.');
define('ERR_FILE_NOT_AVAILABLE', 'Plik jest niedostępny.');
define('ERROR_FILE_TOO_BID', 'Plik jest za duży. (max: %s)');
define('FILE_FORM_TITLE', 'Wysyłanie pliku');
define('FILE_LABEL_SELECT', 'Wybierz plik');
define('FILE_LBL_MORE', 'Dodaj pliki');
define('FILE_CANCEL_UPLOAD', 'Aunuluj wysyłanie');
define('FILE_LBL_UPLOAD', 'Wyślij');

//file download
define('ERR_DOWNLOAD_FILE_NOT_FOUND', 'Nie wybranych plików do pobrania.');

//Rename
define('RENAME_FORM_TITLE', 'Zmiana nazwy');
define('RENAME_NEW_NAME', 'Nowa nazwa');
define('RENAME_LBL_RENAME', 'Zmień nazwę');

//Tips
define('TIP_FOLDER_GO_DOWN', 'Pojedyncze kliknięcie aby dostać się do folderu...');
define('TIP_DOC_RENAME', 'Podwójne kliknięcie w celu edycji...');
define('TIP_FOLDER_GO_UP', 'Pojedyncze kliknięcie aby dostać się do folderu nadrzędnego...');
define('TIP_SELECT_ALL', 'Zaznacz wszystko');
define('TIP_UNSELECT_ALL', 'Odznacz wszystko');

//WARNING
define('WARNING_DELETE', 'Czy na pewno chcesz usunąć wybrany(e) dokument(y).');
define('WARNING_IMAGE_EDIT', 'Proszę wybrać obraz do edycji.');
define('WARNING_NOT_FILE_EDIT', 'Proszę wybrać plik do edycji.');
define('WARING_WINDOW_CLOSE', 'Czy na pewno chcesz zamknąć okno?');

//Preview
define('PREVIEW_NOT_PREVIEW', 'Brak podglądu.');
define('PREVIEW_OPEN_FAILED', 'Nie można otworzyć pliku.');
define('PREVIEW_IMAGE_LOAD_FAILED', 'Nie można załadować obrazu');

//Login
define('LOGIN_PAGE_TITLE', 'Formularz logowania');
define('LOGIN_FORM_TITLE', 'Formularz logowania');
define('LOGIN_USERNAME', 'Login:');
define('LOGIN_PASSWORD', 'Hasło:');
define('LOGIN_FAILED', 'Nieprawidłowy login/hasło.');

//88888888888   Below for Image Editor   888888888888888888888
//Warning 
define('IMG_WARNING_NO_CHANGE_BEFORE_SAVE', 'Nie dokonano żadnych zmian w obrazie.');

//General
define('IMG_GEN_IMG_NOT_EXISTS', 'Obraz nie istnieje');
define('IMG_WARNING_LOST_CHANAGES', 'Wszystkie niezapisane zmiany wprowadzone w obrazie będą utracone, czy na pewno chcesz kontynuować?');
define('IMG_WARNING_REST', 'Wszystkie niezapisane zmiany wprowadzone w obrazie zostaną utracone, czy na pewno zresetować?');
define('IMG_WARNING_EMPTY_RESET', 'Nie dokonano do tej pory żadnych zmian w obrazie.');
define('IMG_WARING_WIN_CLOSE', 'Czy na pewno chcesz zamknąć okno?');
define('IMG_WARNING_UNDO', 'Czy na pewno chcesz przywrócić obraz do poprzedniego stanu?');
define('IMG_WARING_FLIP_H', 'Czy na pewno chcesz odwrócić obraz w poziomie?');
define('IMG_WARING_FLIP_V', 'Czy na pewno chcesz odwrócić obraz w pionie');
define('IMG_INFO', 'Informacje o obrazie');

//Mode
define('IMG_MODE_RESIZE', 'Zmiana rozmiaru:');
define('IMG_MODE_CROP', 'Przytnij:');
define('IMG_MODE_ROTATE', 'Obróć:');
define('IMG_MODE_FLIP', 'Przerzuć:');	
	
//Button
define('IMG_BTN_ROTATE_LEFT', '90&deg;CCW');
define('IMG_BTN_ROTATE_RIGHT', '90&deg;CW');
define('IMG_BTN_FLIP_H', 'Przerzuć w poziomie');
define('IMG_BTN_FLIP_V', 'Przerzuć w pionie');
define('IMG_BTN_RESET', 'Resetuj');
define('IMG_BTN_UNDO', 'Cofnij');
define('IMG_BTN_SAVE', 'Zapisz');
define('IMG_BTN_CLOSE', 'Zamknij');
define('IMG_BTN_SAVE_AS', 'Zapisz jako');
define('IMG_BTN_CANCEL', 'Anuluj');

//Checkbox
define('IMG_CHECKBOX_CONSTRAINT', 'Wymusić?');

//Label
define('IMG_LBL_WIDTH', 'Szerokość:');
define('IMG_LBL_HEIGHT', 'Wyokość:');
define('IMG_LBL_X', 'X:');
define('IMG_LBL_Y', 'Y:');
define('IMG_LBL_RATIO', 'Stosunek:');
define('IMG_LBL_ANGLE', 'Kąt:');
define('IMG_LBL_NEW_NAME', 'Nowa nazwa:');
define('IMG_LBL_SAVE_AS', 'Zapisz jako');
define('IMG_LBL_SAVE_TO', 'Zapisz w:');
define('IMG_LBL_ROOT_FOLDER', 'Folder główny');

//Editor
//Save as 
define('IMG_NEW_NAME_COMMENTS', 'Proszę nie dodawać rozszerzenia do nazwy pliku.');
define('IMG_SAVE_AS_ERR_NAME_INVALID', 'Proszę nadać mu nazwę, w której zawierają się tylko litery, cyfry, spacja, myślnik i podkreślenie.');
define('IMG_SAVE_AS_NOT_FOLDER_SELECTED', 'Brak wybranego katalogu docelowego.');	
define('IMG_SAVE_AS_FOLDER_NOT_FOUND', 'Folder docelowy nie istnieje.');
define('IMG_SAVE_AS_NEW_IMAGE_EXISTS', 'Istnieje obraz o tej samej nazwie.');

//Save
define('IMG_SAVE_EMPTY_PATH', 'Pusta ścieżka obrazu.');
define('IMG_SAVE_NOT_EXISTS', 'Obraz nie istnieje.');
define('IMG_SAVE_PATH_DISALLOWED', 'Nie masz dostępu do tego pliku.');
define('IMG_SAVE_UNKNOWN_MODE', 'Nieznana operacja na obrazku');
define('IMG_SAVE_RESIZE_FAILED', 'Nie można zmienić rozmiar obrazu.');
define('IMG_SAVE_CROP_FAILED', 'Nie można przyciąć obraz.');
define('IMG_SAVE_FAILED', 'Nie można zapisać obrazu.');
define('IMG_SAVE_BACKUP_FAILED', 'Nie można wykonać kopii oryginalnego obrazu.');
define('IMG_SAVE_ROTATE_FAILED', 'Nie można obrócić obrazu.');
define('IMG_SAVE_FLIP_FAILED', 'Nie można przeżucić obrazu.');
define('IMG_SAVE_SESSION_IMG_OPEN_FAILED', 'Nie można otworzyć obraz z sesji.');
define('IMG_SAVE_IMG_OPEN_FAILED', 'Nie można otworzyć obrazu');


//UNDO
define('IMG_UNDO_NO_HISTORY_AVAIALBE', 'Brak dostępnej historii dla cofnąć.');
define('IMG_UNDO_COPY_FAILED', 'Nie można przywrócić obrazu.');
define('IMG_UNDO_DEL_FAILED', 'Nie można usunąć obraz z sesji');

//88888888888   Above for Image Editor   888888888888888888888

//88888888888   Session   888888888888888888888
define('SESSION_PERSONAL_DIR_NOT_FOUND', 'Nie można znaleźć dedykowanego folderu, który powinien zostać utworzony w folderze sesji');
define('SESSION_COUNTER_FILE_CREATE_FAILED', 'Nie można otworzyć pliku licznika sesji.');
define('SESSION_COUNTER_FILE_WRITE_FAILED', 'Nie można zapisać pliku licznika sesji.');
//88888888888   Session   888888888888888888888

//88888888888   Below for Text Editor   888888888888888888888
define('TXT_FILE_NOT_FOUND', 'Plik nie został znaleziony.');
define('TXT_EXT_NOT_SELECTED', 'Wybierz rozszerzenie pliku');
define('TXT_DEST_FOLDER_NOT_SELECTED', 'Wybierz folder docelowy');
define('TXT_UNKNOWN_REQUEST', 'Nieznane żądanie.');
define('TXT_DISALLOWED_EXT', 'Masz uprawnienia by móc edytować/dodawać tego typu plik.');
define('TXT_FILE_EXIST', 'Taki plik już istnieje.');
define('TXT_FILE_NOT_EXIST', 'Nie znalezione takiego pliku.');
define('TXT_CREATE_FAILED', 'Nie udało się utworzyć nowego pliku.');
define('TXT_CONTENT_WRITE_FAILED', 'Nie można zapisać zawartość do pliku.');
define('TXT_FILE_OPEN_FAILED', 'Nie udało się otworzyć pliku.');
define('TXT_CONTENT_UPDATE_FAILED', 'Nie można zaktualizować zawartość do pliku.');
define('TXT_SAVE_AS_ERR_NAME_INVALID', 'Proszę nadać mu nazwę, w której zawierają się tylko litery, cyfry, spacja, myślnik i podkreślenie.');
//88888888888   Above for Text Editor   888888888888888888888
?>