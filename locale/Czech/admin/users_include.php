<?php defined('EF5_SYSTEM') || exit;
$locale['susp00'] = "Aktivní";
$locale['susp01'] = "Zablokovaní";
$locale['susp02'] = "Neaktivní";
$locale['susp03'] = "Zawieszone";
$locale['susp04'] = "Zablokovaní bezpečnostním systémem";
$locale['susp05'] = "Wyłączone zp. bezczynności";
$locale['susp06'] = "Skrytí";
$locale['susp07'] = "Wyłączone";
$locale['susp08'] = "Nečinní";

/*Sprawdzenie i wybranie odpowiedniej odmiany wyrazów*/
if (!isset($_GET['action'])) {
	/*Normalny widok w PA, bez podjęcia jakiejkolwiek akcji (pole wyboru w przy liście użytkowników*/
	$locale['susp10'] = "Obnovit";
	$locale['susp11'] = "Zablokovaní";
	$locale['susp12'] = "Deaktivování";
	$locale['susp13'] = "Držení";
	$locale['susp14'] = "Zablokování bezpečnostním systémem zp";
	$locale['susp15'] = "Mimo zp. nečinný";
	$locale['susp16'] = "Skrytí";
	$locale['susp17'] = "Mimo";
	$locale['susp18'] = "Nečinní";
} else {
	/*Odmiana dla rejestru zdarzeń*/
	$locale['susp10'] = "Navrácení";
	$locale['susp11'] = "Zamknuto";
	$locale['susp12'] = "Deaktivace";
	$locale['susp13'] = "Suspendace";
	$locale['susp14'] = "Zablokování bezpečnostním systémem";
	$locale['susp15'] = "Wyłączeń zp. nečinný";
	$locale['susp16'] = "Skrytí";
	$locale['susp17'] = "Wyłączeń";
	$locale['susp18'] = "Nečinný";
}
$locale['susp_sys'] = "Akcje";
$locale['susp100'] = "Rejestr zdarzeń dla %s";
$locale['susp100b'] = "%s dla %s";
$locale['susp101'] = "Záznam událostí pro  %s";
$locale['susp102'] = "Počátek historie <strong>%s</strong> podle účtu %s";
$locale['susp103'] = "Nr";
$locale['susp104'] = "Data";
$locale['susp105'] = "Typ/Důvod";
$locale['susp106'] = "Administrátor";
$locale['susp107'] = "Nebyl přidán důvod!";
$locale['susp108'] = "IP";
$locale['susp109'] = "Akcje";
$locale['susp110'] = "Účet není zaregistrovaný akcji.";
$locale['susp111'] = "Aktivováno";
$locale['susp112'] = "IP: ";
$locale['susp113'] = "Odstraněno";
$locale['susp114'] = "Protokol záznamů";
$locale['susp115'] = "Účty uživatelů";
?>
