$(function() {
	$('#Avatar').change(function() {
		var value = $(this).attr('value');
		
		if (value != '') {
			$('#DelAvatarBox').remove();
		} 
	});
	
	// Wyszukiwanie użytkownika po jego loginie
	searchUser(true, true);
	
	// Wybieranie adresata wiadomości
	$('body').on('click', '.defender', function() {
		var id = $(this).attr('id').split('-')[1];
		window.location.href = addr_site+'admin/pages/users.php?page=users&user='+id;
	});
	// end of Wybieranie adresata wiadomości
});