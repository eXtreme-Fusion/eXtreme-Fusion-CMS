$(function() {

	$('#messages_page form').submit(function(e) {

		// Blokowanie standardowego wysłania danych formularza
		e.preventDefault();

		var $this = $(this);
		var $search_user = $this.find('#search_user');

		var new_message = '';

		if ($search_user) {

			// Sprawdzanie, czy wybrano adresata wiadomości
			if ($search_user.is(':visible'))
			{
				$search_user.focus();
				$search_user.addClass('error-field');

				// Przerywanie działania funkcji
				return false;
			}

			new_message = $('#message_subject').val();

			// Sprawdzanie, czy podano temat wiadomości.
			if (new_message == '') {
				// Wyświetlanie informacji
				alert('Nie podano tematu wiadomości');
				// Przerywanie działania funkcji.
				return false;
			}
		}

		var to_user = $('input[name*="to"]', this).val();

		$.post(addr_site+'pages/ajax/messages.php', {
			message: $('#message', this).val(),
			to: to_user,
			item_id: $('input[name*="item_id"]', this).val(),
			send: true,
			message_subject: new_message,
			action: 'send'
		},
		function(data) {
			if (new_message) {
				$('#message_subject').parent().remove();
				$('input[name*="item_id"]').val(data);
				$this.find('#defender_user img').remove();
			}

			$this.find('#message').val('').focus();

			refresh_pw();
		});
	});

	// Wyszukiwanie użytkownika po jego loginie
	$('#search_user').searchEngine({'is_here_admin_panel': 0, 'self_search': 0, 'only_active': 1, 'php_file': 'ajax/search_users_extended.php'});

	// Zmiana adresata wiadomości
	$('body').on('click', '#defender_user img', function() {
		$(this).parent().remove();
		$('#messages_page form #search_user').val('').show();
	});

	// Wybieranie adresata wiadomości
	$('body').on('click', '#defenders li', function() {
		var id = $(this).attr('id').split('-')[1];
		var username = $(this).text();

		$('#message_to').val(id);
		$('#defenders').before('<div id="defender_user">'+username+' <img src="../admin/templates/images/icons/cross.png" alt="Cross"></div>');

		$('#defenders').html('');
		$('#search_user').hide();
	});
	// end of Wybieranie adresata wiadomości

	// Odświeżanie okna rozmowy
	function refresh_pw() {
		var posts = $('#ajax_messages article').length;
		var item_id = $('input[name*="item_id"]').val();

		$.ajax({
			url: addr_site+'pages/ajax/messages.php', 
			data: 'item_id='+item_id, 
			type: 'GET', 
			success: function(html){
				$('#ajax_messages').html(html);
				setTimeout(function(){
					var posts2 = $('#ajax_messages article').length;
					if (posts != posts2) {
						var scrollh = $('#ajax_messages').height();
						$('#messages_page').animate({ scrollTop: scrollh }, 800);
					}
				}, 400);
			}, error: function(){
				$('#ajax_messages').html('Wystąpił błąd! Odśwież stronę.');
			}
		});
	}

	var refresh = false;
	$('#messages_page').hover(function(){
		if (!refresh){
			refresh_pw();
			refresh = true;
		}
	}, function() {
		refresh = false;
	});

	setInterval(function(){
		refresh_pw();
	}, 30000);

	refresh_pw();
	// end of Odświeżania okna rozmowy
});