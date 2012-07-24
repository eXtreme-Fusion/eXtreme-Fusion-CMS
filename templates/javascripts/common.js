// Wyszukiwanie u¿ytkownika po jego loginie


function searchUser(is_here_admin_panel, self_search)
{
	window.self_search = self_search;
	window.is_here_admin_panel = is_here_admin_panel;
	$('#search_user').after('<div id="defenders"></div>');

	$('#search_user').keyup(function() {
		var value = $(this).val();
		var $object = $(this);

		if (window.is_here_admin_panel == false) {
			var is_here_admin_panel = 0;
		} else {
			var is_here_admin_panel = 1;
		}
		

		if (window.self_search == false) {
			var self_search = 0;
		} else {
			var self_search = 1;
		}
		
		$.ajax({
			url: addr_site+'ajax/search_users.php',
			type: 'POST',
			dataType: 'json',
			data: {to: value, from_admin: is_here_admin_panel, self_search: self_search},
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
}