// Copyright eXtreme-Fusion Team

$(function() {
	$form = $('form#account');
	$form.find('[name="save"]').click(function(e) {
		e.preventDefault();
		$pass = $form.find('#old_password');
		if ($pass.val() == '') {
			$pass.focus();
		} else {
			$form.submit();
		}
	});
});