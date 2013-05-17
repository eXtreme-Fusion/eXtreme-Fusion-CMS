/*********************************************************
| eXtreme-Fusion 5
| Content Management System
|
| Copyright (c) 2005-2013 eXtreme-Fusion Crew
| http://extreme-fusion.org/
|
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
*********************************************************/

jQuery(function() {
	var val = jQuery('form #author').val();
	
	// Usunięcie
	jQuery('body').on('click', '.delete', function() {
		var id = this.id;
		jQuery.ajax({
			type: 'POST',
			url: addr_site + 'pages/ajax/comments.php',
			data: {action: 'delete', request: 'confirm', id: id},
			success: function(response) {
				if (response == 'deleted') {
					//jQuery('#comments #body_'+id).hide();
					jQuery('#facebox').hide();
					jQuery('#facebox_overlay').hide();
					jQuery('#comment-block').load(document.location+' #comments');
				}
			}
		});
		return false;
	});

	// Aktualizacja
	jQuery('body').on('click', '.update', function() {
		var post = jQuery('#ajax #post').val();
		var id = this.id;
		jQuery.ajax({
			type: 'POST',
			url: addr_site + 'pages/ajax/comments.php',
			data: {action: 'edit', request: 'confirm', post: post, id: id},
			dataType: 'json',
			success: function(response) {
				if (response.status == 1) {
					jQuery.facebox.close();
					jQuery('#comments #content_'+id).html(response.content);
				} else {
					jQuery('#facebox .content').html(response.content);
				}
			}
		});
	});

	// Dodawanie komentarza
	jQuery('#comment_form #send').click(function() {
		var post = jQuery('#comment_form #post').val();

		if (post != '') {
			if (jQuery('#comment_form #author').length == 1) {
				var author = jQuery('#comment_form #author').val();
				if (author == val) {
					author = '';
				}
			} else {
				var author = '';
			}


			var item = jQuery('#comment_form #item').val();
			var type = jQuery('#comment_form #type').val();

			jQuery('#comment_form #loading').fadeIn().html('Ładowanie...');

			jQuery.ajax({
				type: 'POST',
				url: addr_site + 'pages/ajax/comments.php',
				data: {action: 'save', author: author, post: post, item: item, type: type},
				success: function(response) {
					if (response == '1') {
						jQuery('#comment_form #loading').hide();
						jQuery('#comment_form #post').val('');
						jQuery('#comment_form #added').fadeIn();
						setTimeout(function() {
							jQuery('#comment_form #added').fadeOut();
						}, 1000);
						jQuery('#comment-block').load(document.location+' #comments');
					}
				}
			});
		}

		return false;
	});
});