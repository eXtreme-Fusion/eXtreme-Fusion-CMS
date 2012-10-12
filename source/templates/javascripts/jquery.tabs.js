jQuery(document).ready(function() {

	// Domyœlne ustawienia
	jQuery('.tab_cont').hide();
	jQuery('.tab').removeClass('selected');

	// Aktywacja pierwszej zak³adki
	jQuery('.tab_cont:first').show();
	jQuery('.tab:first').addClass('selected');

	jQuery('.tab').click(function() {

		// Dezaktywacja wszystkich zak³adek
		jQuery('.tab_cont').hide();
		jQuery('.tab').removeClass('selected');

		// Aktywacja wybranej zak³adki
		var id = this.id;
		var num = id.split('_');
		jQuery('#'+this.id).addClass('selected');

		jQuery('#tab_cont_'+num[1]).show();

	});
});