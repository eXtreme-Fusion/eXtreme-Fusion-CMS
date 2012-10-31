/** FUNKCJE WSPÓLNE DLA pANELU aDMINA I RESZTY STRONY **/

function searchIt(obj, type)
	{
		var value = $(obj).val();
		var $object = $(obj);

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
			url: addr_site+php_file,
			type: 'POST',
			dataType: 'json',
			data: {to: value, from_admin: is_here_admin_panel, self_search: self_search},
			success: function(oJsonObject, sTextstatus, oXMLHttpRequest){
				$object.css({'borderRadius':'5px 5px 0 0','border-bottom-width':'0'});
				ajax = 'a';
				
				last = $('#defenders').html();
				text = last;
				if (parseInt(oJsonObject.status) == 0) {
					
					
						
						var users = '<ul style="width:'+width+'px">';
						for (i = 0; i < oJsonObject.users.length; i++)
						{
							var users = users + '<li class="defender" id="def-'+ oJsonObject.users[i].id +'">' + oJsonObject.users[i].username + '</li>';
						}
						var users = users + '</ul>';
						
						
						
						
						if (last != users) {
							
							$('#defenders').html(users);
							
							

						}		if (type == 'blur') {
							$('html').click(function(e){
								if(e.target.id == 'defenders') {
									//do something
								} else {
									$('#defenders').html('');
								}
							});
							}					ajax = 'b';
				} else if (parseInt(oJsonObject.status) == 1) {
					text = '<div class="defender_error">Brak wyników wyszukiwania</div>';
					if (last != text) {
						$('#defenders').html(text);
						
					}
				} else {
					text = '<div class="defender_error">'+oJsonObject.error_msg+'</div>';
					if (last != text) {
						$('#defenders').html(text);
					}
				}
				
				if (ajax == 'a' && type == 'blur' && last != text)
				{
				
 						$('html').click(function(e){
							if(e.target.id == 'defenders') {
								//do something
							} else {
								$('#defenders').html('');
							}
						});
						
				}
				
			},
			error: function(oXMLHttpRequest, sTextstatus, oErrorThrown) {
				alert(sTextstatus);
			},
		});
		
		
	}
	
	
// Wyszukiwanie u¿ytkownika po jego loginie
function searchUser(is_here_admin_panel, self_search, php_file)
{
	window.self_search = self_search;
	window.is_here_admin_panel = is_here_admin_panel;

	window.php_file = typeof php_file !== 'undefined' ? php_file : 'ajax/search_users.php';
	
	offset = $('#search_user').offset();
	width_orig = $('#search_user').width();
	
	width = $('#search_user').width()-100;
	
	var marg = (width_orig-width)/2;
	
	var pos_top = offset.top + 26;
	var pos_left = offset.left + marg;
	
	$('#search_user').after('<div id="defenders" style="position:absolute; top:'+pos_top+'px; left:'+pos_left+'px; width:'+width+'px !important"></div>');
	
	
	$('#search_user').keyup(function() {
		searchIt(this, 'keyup');
		
	}).blur(function() {
		
		searchIt(this, 'blur');
		
	});
	// end of Wyszukiwanie adresata wiadomoœci
}