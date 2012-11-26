$(function() {
	// Wyszukiwanie u¿ytkownika po jego loginie
	searchUser(true, true);

	$('body').on('click', '.defender', function() {
		var id = $(this).attr('id').split('-')[1];
		window.location.href = addr_site+'modules/point_system/admin/point_system.php?page=bonus&user='+id;
	});
});