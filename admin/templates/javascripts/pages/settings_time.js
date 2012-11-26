$(function() {
	$('#NewTimeFormat').keyup(function() {
		var value = this.value;
		$.ajax({
			type: "POST",
			url: "ajax/date.php",
			data: {value: value},
			success: function(theResponse) {
				$('#time_preview').html(theResponse);
			}
		});
	}).keydown(function() {
		var value = this.value;
		$.ajax({
			type: "POST",
			url: "ajax/date.php",
			data: {value: value},
			success: function(theResponse) {
				$('#time_preview').html(theResponse);
			}
		});
	});
});
