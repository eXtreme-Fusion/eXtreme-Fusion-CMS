$(function() {
	$('body').append('<div id="module-cookies-message"></div>');
	
	var $obj = $('#module-cookies-message');
	
	$obj.load(addr_site+'modules/cookies/pages/message.php', function() {
		var $close = $('#module-cookies-close');
		var box_height = $obj.outerHeight();
		var item_height = $close.outerHeight();
		var top = (box_height-item_height)/2;
		$close.css({'bottom': top+'px'});
		
		$close.click(function() {
			$('#module-cookies-message').fadeOut();
		});	
	});

});