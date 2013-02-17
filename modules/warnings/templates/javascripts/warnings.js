$(function() {
	$('#search_user').on('keyup', function() {
		var user = $(this).val();
		$.ajax({
			url: addr_modules+'warnings/admin/ajax/search_users.php?user='+user, type: 'GET', success: function (html){
				$('#search_user_result').html(html);
			}
		});
	});
});