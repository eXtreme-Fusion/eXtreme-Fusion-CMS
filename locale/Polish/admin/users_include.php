<?php defined('EF5_SYSTEM') || exit;
$locale['susp00'] = "Aktywne";
$locale['susp01'] = "Zablokowane";
$locale['susp02'] = "Nieaktywne";
$locale['susp03'] = "Zawieszone";
$locale['susp04'] = "Zablokowane zp. bezpieczeństwa";
$locale['susp05'] = "Wyłączone zp. bezczynności";
$locale['susp06'] = "Ukryte";
$locale['susp07'] = "Wyłączone";
$locale['susp08'] = "Bezczynne";

/*Sprawdzenie i wybranie odpowiedniej odmiany wyrazów*/
if (!isset($_GET['action'])) {
	/*Normalny widok w PA, bez podjęcia jakiejkolwiek akcji (pole wyboru w przy liście użytkowników*/
	$locale['susp10'] = "Przywróć";
	$locale['susp11'] = "Zablokuj";
	$locale['susp12'] = "Deaktywuj";
	$locale['susp13'] = "Zawieś";
	$locale['susp14'] = "Zablokuj zp. bezpieczeństwa";
	$locale['susp15'] = "Wyłącz zp. bezczynności";
	$locale['susp16'] = "Ukryj";
	$locale['susp17'] = "Wyłącz";
	$locale['susp18'] = "Bezczynne";
} else {
	/*Odmiana dla rejestru zdarzeń*/
	$locale['susp10'] = "Przywróceń";
	$locale['susp11'] = "Blokad";
	$locale['susp12'] = "Deaktywacji";
	$locale['susp13'] = "Zawieszeń";
	$locale['susp14'] = "Blokad zp. bezpieczeństwa";
	$locale['susp15'] = "Wyłączeń zp. bezczynności";
	$locale['susp16'] = "Ukryć";
	$locale['susp17'] = "Wyłączeń";
	$locale['susp18'] = "Bezczynności";
}
$locale['susp_sys'] = "Akcje";
$locale['susp100'] = "Rejestr zdarzeń dla %s";
$locale['susp100b'] = "%s dla %s";
$locale['susp101'] = "Rejestr zdarzeń dla  %s";
$locale['susp102'] = "Wcześniejsza historia <strong>%s</strong> dla konta %s";
$locale['susp103'] = "Nr";
$locale['susp104'] = "Data";
$locale['susp105'] = "Typ/Powód";
$locale['susp106'] = "Administrator";
$locale['susp107'] = "Nie podano powodu!";
$locale['susp108'] = "IP";
$locale['susp109'] = "Akcje";
$locale['susp110'] = "Konto nie posiada zarejestrowanych akcji.";
$locale['susp111'] = "Aktywowano";
$locale['susp112'] = "IP: ";
$locale['susp113'] = "Zdjęty";
$locale['susp114'] = "Rejestr zdarzeń";
$locale['susp115'] = "Konta użytkowników";
?>
