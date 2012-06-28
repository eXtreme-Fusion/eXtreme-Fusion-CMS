$(function() {

	refresh_pw();

	$('#messages_form form').submit(function() {

		var new_message = $('#message_subject').length;

		if (new_message) {
			new_message = $('#message_subject').val();
			if (new_message == '') {
				alert('Nie podano tematu wiadomości');
				return false;
			}
			var to_user = $('select#message_to').val();
		} else {
			var to_user = $('input[name*="to"]', this).val();
			new_message = '';
		}
		

		
		$.post(addr_site+'pages/ajax/messages.php', {
			message: $('input[name*="message"]', this).val(),
			to: to_user,
			item_id: $('input[name*="item_id"]', this).val(),
			send: true,
			message_subject: new_message,
			action: 'send'
		},
		function(data) {
			$('input[name*="message"]').val('');
			if (new_message) {
				$('#message_subject').parent().remove();
				$('input[name*="item_id"]').val(data);
			}
			else
			{
				$('#form_request').html(data);
			}
			refresh_pw();
		});

		return false;
	});

	var refresh = false;
	$('#messages_frame').hover(function() {
		if (!refresh) {
			refresh_pw();
			refresh = true;
		}
	}, function() {
		refresh = false;
	});

	setInterval(function() {
		refresh_pw();
	}, 30000);

});

function refresh_pw() {

	var posts = $('.pw_message_item').length;
	var item_id = $('input[name*="item_id"]').val();

	$.ajax({
		url: addr_site+'pages/ajax/messages.php', data: 'item_id='+item_id, type: 'GET', success: function (html) {
			$('#messages_frame section').html(html);
			setTimeout(function(){
				var posts2 = $('.pw_message_item').length;
				if (posts != posts2) {
					var scrollh = $('#messages_frame section').height();
					$('#messages_frame').animate({ scrollTop: scrollh }, 800);
				}
			}, 400);
		}, error: function(){
			$('#messages_frame section').html('Wystąpił błąd! Odśwież stronę.');
		}
	});
}