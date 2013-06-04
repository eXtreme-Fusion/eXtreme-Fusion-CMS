jQuery(document).ready(function() {

	// Domyślne ustawienia
	jQuery('.tab_cont').hide();
	jQuery('.tab').removeClass('selected');

	// Aktywacja pierwszej zakładki
	jQuery('.tab_cont:first').show();
	jQuery('.tab:first').addClass('selected');

	jQuery('.tab').click(function() {

		// Dezaktywacja wszystkich zakładek
		jQuery('.tab_cont').hide();
		jQuery('.tab').removeClass('selected');

		// Aktywacja wybranej zakładki
		var id = this.id;
		var num = id.split('_');
		jQuery('#'+this.id).addClass('selected');

		jQuery('#tab_cont_'+num[1]).show();

	});
});