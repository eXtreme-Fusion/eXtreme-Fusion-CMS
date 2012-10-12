$(function() {
	// WYSIWYG
	var editing = $('#EditingGroups').val();

	if (editing != '') {
		$('#wysiwyg').show();
	} else {
		$('#wysiwyg').hide();
	}

	$('#EditingGroups').change(function() {

		var value = $(this).val();
		var id = this.id;

		if (multiSelectDefault(value, id)) {
			var value = $(this).val();
		}
		
		if (value != '') {
			$('#wysiwyg').show();
		} else {
			$('#wysiwyg').hide();
		}
	});

	// INSIGHT GROUPS
	$('#InsightGroups').change(function() {

		var value = $(this).val();
		var id = this.id;

		multiSelectDefault(value, id);
	});

	// SUBMITTING GROUPS
	$('#SubmittingGroups').change(function() {

		var value = $(this).val();
		var id = this.id;

		multiSelectDefault(value, id);
	});

	// CHANGE AUTHOR
	var author = $('input:radio[name=change_author]:checked').val();

	if (author != 1) {
		$('#author').show();
	} else {
		$('#author').hide();
	}

	$('input:radio[name=change_author]').change(function() {

		var value = $(this).val();
		if (value != 1) {
			$('#author').show();
		} else {
			$('#author').hide();
		}
	});

	// CHANGE DATE
	var date = $('input:radio[name=change_date]:checked').val();

	if (date != 1) {
		$('#date').show();
	} else {
		$('#date').hide();
	}

	$('input:radio[name=change_date]').change(function() {

		var value = $(this).val();
		if (value != 1) {
			$('#date').show();
		} else {
			$('#date').hide();
		}
	});

});
