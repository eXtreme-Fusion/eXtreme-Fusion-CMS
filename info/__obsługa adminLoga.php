<?php

	###### WERSJA ROZSZERZONA z własnym komunikatem oraz odróznianianiem wykonanej akcji ######

	// Sprawdzanie czy znajdujemy się na stronie z komunkatem
	if (isset($_GET['act']) && isset($_GET['status']))
    {
		// Zapisywanie loga w bazie
		$_log->insertAdminLog($_GET['act'], $_GET['status'], array(
			'insert' => array(												// ...dla dodawania nowego materiału
				'Zapis danych zakończony powodzeniem', 							// Znjadzie się w bazie tylko wtedy, gdy zapis ukończono pomyślnie
				'Bład przy zapisie'												// Znajdzie się w bazie tylko wtedy, gdy zapis się nie zakończył pomyślnie
			),
			'edit' => array(												// ...dla edycji materiału
				'Zaktualizowano materiał',										// Znajdzie się w bazie tylko wtedy, gdy edycję ukończono pomyślnie
				'Bład przy aktualizacji'										// Znajdzie się w bazie tylko wtedy, gdy edycja się nie zakończyła pomyślnie
			)
		));

		// Wyświetlanie komunikatu
		$_tpl->getMessage($_GET['status'], $_GET['act'], array(
			'insert' => array(												// Generowanie komunikatu dla dodawania nowego materiału
				'Zapis danych zakończony powodzeniem', 							// Wyświetli się tylko wtedy, gdy zapis ukończono pomyślnie
				'Bład przy zapisie'												// Wyświetli się tylko wtedy, gdy zapis się nie zakończył pomyślnie
			),
			'edit' => array(													// Generowanie komunikatu dla edycji materiału
				'Zaktualizowano materiał',										// Wyświetli się tylko wtedy, gdy edycję ukończono pomyślnie
				'Bład przy aktualizacji'										// Wyświetli się tylko wtedy, gdy edycja się nie zakończyła pomyślnie
			)
		));
    }

	###### WERSJA OGÓLNA z własnym komunikatem bez odrózniania wykonanej akcji ######

	// Sprawdzanie czy znajdujemy się na stronie z komunkatem
	if (isset($_GET['act']) && isset($_GET['status']))
    {
		// Zapisywanie loga w bazie
		$_log->insertAdminLog($_GET['act'], $_GET['status'], array(			// Dla każdej akcji, bez względu na to czy jest to edycja, dodawanie nowego materiału czy usuwanie, zamieści ten sam komunikat o powodzeniu lub nie	zdefiniowany w tablicy
			'Zapis danych zakończony powodzeniem', 								// Znjadzie się w bazie tylko wtedy, gdy zapis ukończono pomyślnie
			'Bład przy zapisie'													// Znajdzie się w bazie tylko wtedy, gdy zapis się nie zakończył pomyślnie
		));

		// Wyświetlanie komunikatu
		$_tpl->getMessage($_GET['status'], $_GET['act'], array(				// Dla każdej akcji, bez względu na to czy jest to edycja, dodawanie nowego materiału czy usuwanie, wyświetli ten sam komunikat o powodzeniu lub nie	zdefiniowany w tablicy
			'Zapis danych zakończony powodzeniem', 								// Wyświetli się tylko wtedy, gdy zapis ukończono pomyślnie
			'Bład przy zapisie'													// Wyświetli się tylko wtedy, gdy zapis się nie zakończył pomyślnie
		));
    }

	###### WERSJA OGÓLNA z predefiniowanymi komunikatami bez odrózniania wykonanej akcji ######

	// Sprawdzanie czy znajdujemy się na stronie z komunkatem
	if (isset($_GET['act']) && isset($_GET['status']))
    {
		// Zapisywanie loga w bazie
		$_log->insertAdminLog($_GET['act'], $_GET['status']);				// Nie zamieści w bazie żadnego komunikatu!! Tylko predefiniowane dane, które wystarcza do zlokalizowania akcji wykonanej na danej podstronie

		// Wyświetlanie komunikatu
		$_tpl->getMessage($_GET['status'], $_GET['act']);					// Wyświetli predefiniowany komunikat z pliku global.php: __('Action success') albo __('Action error')
    }


	// Dla insertAdminLog() polecam korzystać z wersji 3.
	// Zaś dla getMessage() wersji 1.