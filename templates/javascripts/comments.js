/*---------------------------------------------------------------+
| eXtreme-Fusion - Content Management System - version 5         |
+----------------------------------------------------------------+
| Copyright (c) 2005-2012 The eXtreme-Fusion Crew                |
| http://www.extreme-fusion.org/                                 |
+----------------------------------------------------------------+
| The work is provided under the terms of this Creative Commons  |
| public license ("CCPL" or "License"). the work is protected by |
| copyright and/or other applicable law. Any use of the work     |
| other than as authorized under this license or copyright law   |
| is prohibited.                                                 |
|                                                                |
| By exercising any rights to the work provided here, you accept |
| and agree to be bound by the terms of this license. To the     |
| extent this license may be considered to be a contract, the    |
| licensor grants you the rights contained here in consideration |
| of your acceptance of such terms and conditions.               |
+----------------------------------------------------------------+
| http://creativecommons.org/licenses/by/3.0/pl/legalcode        |
+---------------------------------------------------------------*/

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
					jQuery('#comments #body_'+id).hide();
					jQuery('#facebox').hide();
					jQuery('#facebox_overlay').hide();
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
			success: function(response) {
				if (response == '1') {
					jQuery.facebox.close();
					jQuery('#comments #content_'+id).html(post);
				} else {
					jQuery('#facebox .content').html(response);
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
						jQuery('#comment-block').load(addr_site + 'pages/ajax/comments.php?action=load&comment_type='+type+'&comment_item='+item);
					}
				}
			});
		}

		return false;
	});
});