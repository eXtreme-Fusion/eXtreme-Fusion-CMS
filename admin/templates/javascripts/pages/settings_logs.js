$(function() {
	function changeView(speed) {
		var checked = $('#OptimizeActive:checked').val();
		if (!checked) {
			$('#ExpireSett').hide(speed);
		} else {
			$('#ExpireSett').show(speed);
		}
	}
	changeView(0);
	$('#OptimizeActive').change(function() {
		changeView(400);
	});
});
