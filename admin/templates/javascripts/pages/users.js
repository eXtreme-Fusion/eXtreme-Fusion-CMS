$(function() {
	$('#Avatar').change(function() {
		var value = $(this).attr('value');
		
		if (value != '') {
			$('#DelAvatarBox').remove();
		} 
	});
	
	/*
	$('#search_user').on('keyup', function() {
		var user = $(this).val();
		$.ajax({
			url: addr_site+'admin/pages/ajax/search_users.php?user='+user, type: 'GET', success: function (html){
				$('#search_user_result').html(html);
			}
		});
	});*/
	
	// Wyszukiwanie adresata wiadomoœci
	$('#search_user').after('<div id="defenders"></div>');

	$('#search_user').keyup(function() {
		var value = $(this).val();
		var $object = $(this);

		if (typeof here_is_admin_panel == 'undefined')
		{
			var is_here_admin_panel = 0;
		} else {
			var is_here_admin_panel = 1;
		}
		$.ajax({
			url: addr_site+'ajax/search_users.php',
			type: 'POST',
			dataType: 'json',
			data: {to: value, from_admin: is_here_admin_panel},
			success: function(oJsonObject, sTextstatus, oXMLHttpRequest){
				$object.css({'borderRadius':'5px 5px 0 0','border-bottom-width':'0'});
				if (parseInt(oJsonObject.status) == 0) {
					var users = '<ul>';
					for (i = 0; i < oJsonObject.users.length; i++)
					{
						var users = users + '<li class="defender" id="def-'+ oJsonObject.users[i].id +'">' + oJsonObject.users[i].username + '</li>';
					}
					var users = users + '</ul>';
					$('#defenders').html(users);
				} else if (parseInt(oJsonObject.status) == 1) {
					$('#defenders').html('<div class="defender_error">Brak wyników wyszukiwania</div>');
				} else {
					$('#defenders').html('<div class="defender_error">'+oJsonObject.error_msg+'</div>');
				}
			},
			error: function(oXMLHttpRequest, sTextstatus, oErrorThrown) {
				alert(sTextstatus);
			},
		});
	});
	// end of Wyszukiwanie adresata wiadomoœci
	
	// Wybieranie adresata wiadomoœci
	$('body').on('click', '.defender', function() {
		var id = $(this).attr('id').split('-')[1];

		window.location.href = addr_site+'admin/pages/users.php?page=users&user='+id;
	});
	// end of Wybieranie adresata wiadomoœci
});