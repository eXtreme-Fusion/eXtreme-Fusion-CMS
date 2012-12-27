/**
 * @author: 	Inscure
 * @email: 		drimer.eco@gmail.com
 * @website:	http://extreme-fusion.pl/
 * @github:		https://github.com/Inscure/
 */

(function($) {
  $.fn.liveDraggable = function (opts) {
		$(opts.selector).css({'cursor': 'move'});
		this.on('mousemove', opts.selector, function() {
			$(this).draggable();
		});
	};
}(jQuery));