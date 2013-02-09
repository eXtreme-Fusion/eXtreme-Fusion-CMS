// ============================
// Odznaczanie nieważnych opcji
// listy wielokrotnego wyboru
// po zaznaczeniu "Brak wyboru"
// ============================
function multiSelectDefault(data, id)
{
	var $object = $('#'+id);

	if ($object.val() == null) {
		$('#'+id+' option[value=0]').attr('selected', 'selected');
		return true;
	} else if (!$object.hasClass('changed') && data != '0') {
		$object.addClass('changed');
		$('#'+id+' option[value=0]').removeAttr('selected');
		return false;
	} else if ($object.hasClass('changed')) {
		for(var i in data)
		{
			if (data[i] == '0') {
				$('#'+id+' option[value!=0]').removeAttr('selected');
				$object.removeClass('changed');
				return true;
			}
		}
	}

	return false;
}

/**
 * Wyświetlanie kolejnych pól formularza jeśli pole radio zaznaczone z opcją 1.
 * Przykład użycia: boxByStatus('input[name="maintenance"]', '#maintenance-box');
 */
function boxByStatus(id, box) {

	function controlBox(id, box) {
		var value = $(id+':checked').val();

		if (value == '1') {
			$(box).fadeIn();
		} else {
			$(box).fadeOut();
		}
	}

	controlBox(id, box);

	$(id).change(function() {
		controlBox(id, box);
	});
}

$(function(){

	/** Wsparcie dla multilist select **/
	$('.select-multi').each(function() {
		multiSelectDefault($(this).val(), $(this).attr('id'));
	}).change(function() {
		multiSelectDefault($(this).val(), $(this).attr('id'));
	});
	/**/

	counter();
	$("#menu a.opened").next().slideDown('slow');
	$("input:checkbox, input:radio, input:file, select, #TableOPT_wrapper #TableOPT_length select").uniform();
	$(".tipTip").tipTip();
	$(".tip").simpletooltip();

	$('.Buttons .Cancel').click(function() {
		history.back();
	});
	
	$('.Buttons img').css({'position': 'absolute'});

	// ============================
	// Uprawnienia
	// ============================
	var checked   = $('input#wildcard').prop('checked');
	var $wildcard = $('tr:not(#wildcards)', '#permissions tbody').toggle( ! checked);

	$('input#wildcard').click(function () {
		$wildcard.toggle( ! this.checked);
	});

	if ($('.UserAdd').length) {
		var title2 = $('#role_2').data('title');
		var title3 = $('#role_3').data('title');
		$('#role').append('<option value="2" id="switcher_2">'+title2+'</option>');
		$('#switcher_2').attr('selected', 'selected');
	}

	$('.roles_switch').click(function() {
		var id    = $(this).data('id');
		var title = $(this).data('title');

		if (this.checked)
		{
			$('#role').append('<option value="'+id+'" id="switcher_'+id+'">'+title+'</option>');
		}
		else
		{
			$('#switcher_'+id).remove();
		}
	});

	// ============================
	// Zaznaczanie wszystkich checkboxów jednym - głównym
	// ============================
	$('#selectAll').click(function () {
		var $checkbox = $(':checkbox').not(this).prop('checked', this.checked);
		$checkbox.parent().toggleClass('checked');
	});

	// ============================
	// Hack dla linków w nowym oknie
	// ============================
	$("a[rel*='blank']").click(function() {
		this.target = "_blank";
	});

	// ============================
	// Powrót
	// ============================
	$("a[rel*='back']").click(function(){
		parent.history.back();
		return false;
	});

	// ============================
	// Komunikaty
	// ============================
	$(".valid, .error, .info, .status").each(function () {
		var GetStatus = $(this).attr("class");
		$(this).addClass("close-" +GetStatus);
		$(".close-" +GetStatus).click(function () {
			$(this).fadeOut("slow");
		});
	});

	// ============================
	// Przesłanie wartości z select do input
	// ============================
	$('select.Update option').bind('click', function() {
		var UpdateInput = $(this).parent().attr("id").split('Text');
		var UpdateInputValue = $(this).val();
		$('#' +UpdateInput[0]).val(UpdateInputValue);
	});

	// ============================
	// Dodanie walidacji do pól wymaganych przez <sup>
	// ============================
	$("form label").each(function() {
		var GetFor = $(this).attr("for");
		var GetRequired = $(this).children("sup").html();
		if (GetRequired == "*") {
			$("input#" +GetFor).addClass("validate[required]");
			$("select#" +GetFor).addClass("validate[required]");
			$("textarea#" +GetFor).addClass("validate[required]");
		}
	});

	// ============================
	// Pokazywanie/ukrywanie listy administracyjnej
	// ============================
	$("#AdminLevel").live("click", function(event) {
		var is_super = $(this).is(':checked');
		if (!is_super) {
			$('#AdminFilesList').css('display','block');
		} else {
			$('#AdminFilesList').css('display','none');
		}
	});

	// ============================
	// Toggle dla paneli z H3
	// ============================
	$(function() {
		$("h3").toggle(function(){
			var checkElement = $(this).attr("class");
			var runElement = "div#" +checkElement;
			if (checkElement != "") {
				$(runElement).animate({opacity:0},1);
				$(runElement).slideDown("slow");
				$(runElement).animate({opacity:1},400);
				$(this).children("strong").css("background-position", "-192px -24px");
			}
		}, function(){
			var checkElement = $(this).attr("class");
			var runElement = "div#" +checkElement;
			if (checkElement != "") {
				$(runElement).animate({opacity:0},400);
				$(runElement).slideUp("slow");
				$(runElement).animate({opacity:1},1);
				$(this).children("strong").css("background-position", "-216px 0");
			}
		});
		return false;
	});

	// ============================
	// Zmiana uprawnień formularza w Ustawieniach Galeri zdjęć
	// ============================
	$("input[name=PhotoWatermark]").click(function() {
		var ThisValue = $(this).val();
		if (ThisValue == 1) {
			ChangeValue("PhotoWatermarkSave", "Value1", "Enable", "Input", "tbl2");
			ChangeValue("PhotoWatermarkImage", "Value0", "Enable", "Input", "tbl1");
			ChangeValue("PhotoWatermarkText", "Value1", "Enable", "Input", "tbl2");
			ChangeValue("PhotoWatermarkTextColor1", "", "Enable", "Input", "tbl1");
			ChangeValue("PhotoWatermarkTextColor2", "", "Enable", "Input", "tbl2");
			ChangeValue("PhotoWatermarkTextColor3", "", "Enable", "Input", "tbl1");
		} else {
			ChangeValue("PhotoWatermarkSave", "Value0", "Reselect", "Input");
			ChangeValue("PhotoWatermarkSave", "Value0", "Disable", "Input", "tbl2");
			ChangeValue("PhotoWatermarkImage", "Value0", "Disable", "Input", "tbl1");
			ChangeValue("PhotoWatermarkText", "Value0", "Reselect", "Input");
			ChangeValue("PhotoWatermarkText", "Value0", "Disable", "Input", "tbl2");
			ChangeValue("PhotoWatermarkTextColor1", "", "Disable", "Input", "tbl1");
			ChangeValue("PhotoWatermarkTextColor2", "", "Disable", "Input", "tbl2");
			ChangeValue("PhotoWatermarkTextColor3", "", "Disable", "Input", "tbl1");
		}
	});
	// ============================
	// Funkcje dla kolorowania
	// ============================
	$("label.PrefixText").each(function() {
		var ChangeEl = $(this).attr('for').split('_')[1];
		$(this).click(function() {
			var TextEl = '#PrefixText_' +ChangeEl;
			var ImageEl = '#PrefixImage_' +ChangeEl;
			$(TextEl).show();
			if ($(ImageEl+ ':visible')) {$(ImageEl).hide();}
		});
	});
	$("label.PrefixImage").each(function() {
		var ChangeEl = $(this).attr('for').split('_')[1];
		$(this).click(function() {
			var TextEl = '#PrefixText_' +ChangeEl;
			var ImageEl = '#PrefixImage_' +ChangeEl;
			$(ImageEl).show();
			if ($(TextEl+ ':visible')) {$(TextEl).hide();}
		});
	});

	// ============================
	// Funkcje dodająca kontenery
	// ============================
	$('.sep_1').before('<div class="grid_1">&nbsp;</div>');
	$('.sep_2').before('<div class="grid_2">&nbsp;</div>');
	$('.sep_3').before('<div class="grid_3">&nbsp;</div>');
	$('.sep_4').before('<div class="grid_4">&nbsp;</div>');
	$('.sep_5').before('<div class="grid_5">&nbsp;</div>');
	$('.sep_6').before('<div class="grid_6">&nbsp;</div>');

	// Wyśrodkowywanie buttonów

	$('.Buttons').each(function() {
		var c = $(this).children('.button-c');
		var r = $(this).children('.button-r');
		var l = $(this).children('.button-l');

		var sum = 0;

		// Tylko jeden button na środku
		if (c.length == 1 && !r.lenght && !l.length) {

			c.each(function() {
				if ($(this).hasClass('grid_2')) {
					sum = sum + 2;
				} else if ($(this).hasClass('grid_4')) {
					sum = sum + 4;
				} else {
					sum = sum + 6;
				}
			});

			var bg = (12-sum)/2;

			c.before('<div class="grid_'+bg+' buttons-bg">&nbsp;</div>');
			c.after('<div class="grid_'+bg+' buttons-bg">&nbsp;</div>');
		}
		// Brak buttonów na środku, jeden button z lewej i jeden z prawej strony
		else if (!c.length && (r.length == 1 && l.length == 1))
		{
			if (r.hasClass('grid_2')) {
				sum = sum + 2;
			} else {
				sum = sum + 4;
			}
			if (l.hasClass('grid_2')) {
				sum = sum + 2;
			} else {
				sum = sum + 4;
			}

			var bg = (12-sum)/2;

			l.before('<div class="grid_'+bg+' buttons-bg">&nbsp;</div>');
			r.after('<div class="grid_'+bg+' buttons-bg">&nbsp;</div>');

		}
		// W środku jest kilka buttonów lub jeden; ponadto jeden z lewej i jeden z prawej;
		else if (l.length == 1 && c.length >= 1 && r.length == 1)
		{
			if (r.hasClass('grid_2')) {
				sum = sum + 2;
			} else {
				sum = sum + 4;
			}
			if (l.hasClass('grid_2')) {
				sum = sum + 2;
			} else {
				sum = sum + 4;
			}

			c.each(function() {
				if ($(this).hasClass('grid_2')) {
					sum = sum + 2;
				} else {
					sum = sum + 4;
				}
			});

			var bg = (12-sum)/2;

			l.before('<div class="grid_'+bg+' buttons-bg">&nbsp;</div>');
			r.after('<div class="grid_'+bg+' buttons-bg">&nbsp;</div>');
		} else alert('ulozenie buttonow na tej podstronie jest nieprawidlowe');
	});

	/* Zakładki w Panelu Admina */
	if (typeof(page_active_tab) != 'undefined' && page_active_tab != '') {
		$('.page-tab-cont').hide();
		$('#'+page_active_tab).show();
		$('a[title="'+page_active_tab+'"]').addClass('page-tab-active');
	} else {
		$('.page-tab:first').addClass('page-tab-active');
		$('.page-tab-cont').hide();
		$('.page-tab-cont:first').show();
	}

	$('.page-tab').live('click', function(event) {
		event.preventDefault();

		var link = this.title;
		$('.page-tab-cont').fadeOut(500);
		$('#'+link).fadeIn(500);
		$('.page-tab').removeClass('page-tab-active');
		$(this).addClass('page-tab-active');
	});

});
