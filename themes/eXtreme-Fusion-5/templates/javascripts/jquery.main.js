$(document).ready(function() {



//********** Wyświetlanie zawartości title w dymku **********//

	$('a[title]').qtip({
		position: {my: 'bottom left', target: 'mouse', viewport: $(window), adjust: {x: 10,  y: -10}},
		hide: {fixed: true},
		style: {classes: 'ui-tooltip-dark ui-tooltip-shadow ui-tooltip-rounded', tip: {corner: false}}
	});
	
	$(":input[title]").qtip({
		position: {at: "right center", my: "left center", adjust: {x: 5}},
		show: {event: "focus"},
		hide: {event: "blur"},
		style: 'ui-tooltip-dark ui-tooltip-shadow ui-tooltip-rounded'
		
	});


//********** Wygląd emementów formularzy **********//

	$('select, input:text, input:password, input:checkbox, input:radio, input:file, input:email').uniform();
	
	
	
	$('ul#nav > li').hover(
		function () {
			$('a',$(this)).stop().animate({'marginLeft':'0'},200);
			$('a.long',$(this)).stop().animate({'marginLeft':'0'},200);
			},
		function () {
			$('a',$(this)).stop().animate({'marginLeft':'-70px'},200);
			$('a.long',$(this)).stop().animate({'marginLeft':'-75px'},200);
		}
	);

	
});