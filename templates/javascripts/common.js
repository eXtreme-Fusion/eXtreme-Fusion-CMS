/** FUNKCJE WSPÓLNE DLA PANELU aDMINA I RESZTY STRONY **/

window.last_value = '';

// Wyszukiwanie użytkownika po jego loginie
function searchUser(is_here_admin_panel, self_search, php_file)
{
	window.self_search = self_search;
	window.is_here_admin_panel = is_here_admin_panel;

	window.php_file = typeof php_file !== 'undefined' ? php_file : 'ajax/search_users.php';

	var offset = $('#search_user').offset();
	var width_orig = $('#search_user').width();

	window.width = $('#search_user').width()-100;

	var marg = (width_orig-width)/2;

	var pos_top = offset.top + 26;
	var pos_left = offset.left + marg;

	$('#search_user').after('<div id="defenders" style="position:absolute; top:'+pos_top+'px; left:'+pos_left+'px; width:'+width+'px !important"></div>');

	$('#search_user').keyup(function() {
		var value = $(this).val();
		if (last_value != value) {
			searchIt(this);
			window.last_value = value;
		}
	}).click(function() {
		var value = $(this).val();
		if (last_value != value) {
			searchIt(this);
			window.last_value = value;
		}
	}).blur(function() {
		$('html').click(function(e){
			if(e.target.id != 'defenders' && e.target.id != 'search_user') {
				hideTip();
			}
		});
	});
	// end of Wyszukiwanie adresata wiadomości
}

function searchIt(obj)
{
	var value = $(obj).val();

	if (is_here_admin_panel == false) {
		window.is_here_admin_panel = 0;
	} else {
		window.is_here_admin_panel = 1;
	}

	if (self_search == false) {
		window.self_search = 0;
	} else {
		window.self_search = 1;
	}

	$.ajax({
		url: addr_site+php_file,
		type: 'POST',
		dataType: 'json',
		data: {to: value, from_admin: is_here_admin_panel, self_search: self_search},
		success: function(oJsonObject, sTextstatus, oXMLHttpRequest){
			$(obj).css({'borderRadius':'5px 5px 0 0','border-bottom-width':'0'});
			if (parseInt(oJsonObject.status) == 0) {
				var users = '<ul style="width:'+width+'px">';
				for (i = 0; i < oJsonObject.users.length; i++)
				{
					var users = users + '<li class="defender" id="def-'+ oJsonObject.users[i].id +'">' + oJsonObject.users[i].username + '</li>';
				}
				var users = users + '</ul>';
				$('#defenders').html(users);
			} else if (parseInt(oJsonObject.status) == 2) {
				hideTip();
			} else {
				$('#defenders').html('<div class="defender_error">'+oJsonObject.error_msg+'</div>');
			}
		},
		error: function(oXMLHttpRequest, sTextstatus, oErrorThrown) {
			alert(sTextstatus);
		}
	});
}

function hideTip() {
	$('#defenders').html('');
	window.last_value = '';
}