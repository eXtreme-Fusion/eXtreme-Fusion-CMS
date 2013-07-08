$(function() {
	// Sprawdzanie, czy wyświetlić komunikat
	if ($.cookie('cookies_accepted') == undefined) {
		// Tworzenie boxa dla komunikatu
		$('body').append('<div id="module-cookies-message"></div>');

		// Zapis obiektu do zmiennej
		var $obj = $('#module-cookies-message');

		// Przypisanie długości równej długości znacznika body
		$obj.css({'width': ($('body').outerWidth()-$obj.outerWidth())+'px'});

		// Ładowanie treści komunikatu
		$obj.load(addr_site+'modules/cookies/pages/message.php', function() {

			// Zapis obiektu do zmiennej
			var $close = $('#module-cookies-close');

			// Wyliczanie parametrów potrzebnych do wyśrodkowania buttona
			var box_height = $obj.outerHeight();
			var item_height = $close.outerHeight();

			// Wyliczanie wartości top - na podstawie wysokości boxa z komunikatem i wysokości buttona
			var top = (box_height-item_height)/2;

			// Przypisywanie wartości top do buttona
			$close.css({'bottom': top+'px'});

			// Obsługa zamykania okienka z komunikatem
			$close.click(function() {
				// Tworzenie ciasteczka, by komunikat nie pokazywał się ponownie
				$.cookie('cookies_accepted', true, {expires: 200});

				$('#module-cookies-message').fadeOut();
			});
		});
	}
});