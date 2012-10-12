$(function() {
	$('.faq_question').on('click', function() {
		$('.faq_answer').slideUp();
		$('.faq_answer#answer_'+$(this).attr('id')).slideDown();
	});
});