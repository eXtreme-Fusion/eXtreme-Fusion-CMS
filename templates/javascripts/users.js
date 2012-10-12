$(function() {
	$('#message_from').on('keyup', function() {
		var user = $(this).val();
		$.ajax({
			url: addr_site+'ajax/search_users.php?user='+user, type: 'GET', success: function (html){
				$('#message_from_result').html(html);
			}
		});
	});
});