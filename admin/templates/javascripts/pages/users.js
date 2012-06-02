$(function() {
	$('#Avatar').change(function() {
		var value = $(this).attr('value');
		
		if (value != '') {
			$('#DelAvatarBox').remove();
		} 
	});
	
	
	$('#search_user').on('keyup', function() {
		var user = $(this).val();
		$.ajax({
			url: ADDR_SITE+'admin/pages/ajax/search_users.php?user='+user, type: 'GET', success: function (html){
				$('#search_user_result').html(html);
			}
		});
	});
	
});