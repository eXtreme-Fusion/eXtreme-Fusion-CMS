$(function() {
	$('body').append('<div id="modFeedback"><form method="post" action=""><textarea name="modFeedbackMessage"></textarea><input type="submit" name="send" value="Wyślij"/></form></div>');
	
	$('#modFeedback input[type="submit"]').click(function(e) {
		e.preventDefault();
		alert($(this).parent().find('textarea').val());
	});
	$('body').liveDraggable({'selector': '#modFeedback'});
});