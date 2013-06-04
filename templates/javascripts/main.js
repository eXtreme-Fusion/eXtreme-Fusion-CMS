(function($){$.fn.clearDefault=function(){return this.each(function(){var default_value=$(this).val();$(this).focus(function(){if($(this).val()==default_value)$(this).val("");});$(this).blur(function(){/*if($(this).val()=="")$(this).val(default_value);*/});});};})(jQuery);

jQuery(function() {

	jQuery('.dataTable').dataTable({
		"aaSorting": [],
		"bJQueryUI": true,
		"bProcessing": true,
		"sPaginationType": "full_numbers"
	});

	// ============================
	// Remove <strong> from thread title
	// ============================
	jQuery('.forum_thread_title').each(function(){
		var GetTitle = jQuery(this).children('strong').html();
		jQuery(this).replaceWith(GetTitle);
	});

	// ============================
	// Dark tooltips
	// ============================
	jQuery(".tip").simpletooltip();

	// ============================
	// Hack for links for new tab
	// ============================
	jQuery("a[rel*='blank']").click(function(){
		this.target = "_blank";
	});

	// ============================
	// Back
	// ============================
	jQuery("a[rel*='back']").click(function(){
		parent.history.back();
		return false;
	});

	// ============================
	// Facebook frame W3C Hack
	// ============================
	jQuery('.FacebookLike iframe').each(function() {
		jQuery(this).attr("allowtransparency", "true");
	});

	// ============================
	// UserPanel
	// ============================
	jQuery('#Cloud #CloudStart').live('click', function() {
		jQuery('#Cloud #CloudDynamic').slideToggleWidth();
	});

	// ============================
	// AdminBox
	// ============================
	jQuery(".admin-box").click(function(){

		var page = jQuery(this).attr("rel");
		jQuery("#admin-box").html('<div id="admin-box-cross"></div><iframe id="mainFrame" name="mainFrame" frameborder="0" src="'+page+'"></iframe>');

		var elX = document.getElementsByTagName("div")["admin-box"].offsetWidth;
		var elY = document.getElementsByTagName("div")["admin-box"].offsetHeight;

		var arrayPageSize = adminGetPageSize();
		var arrayPageScroll = adminGetPageScroll();

		var top = (arrayPageScroll[1] + ((arrayPageSize[3] - 35 - elY) / 2) + 'px');
		var left = (((arrayPageSize[0] - 20 - elX) / 2) + 'px');

		jQuery('#admin-box').css('top', top).css('left', left).show();
		setTimeout(function(){jQuery('#admin-box-cross').fadeIn();}, 5000);
		return false;
	});

	jQuery('#admin-box-cross').live('click', function() {
		jQuery('#admin-box').hide().html('').removeAttr('style');
	});

	var bpos = '';
	var perc = 0 ;
	var minperc = 0 ;
	jQuery('#UserPass').css({backgroundPosition: "0 0"});
	jQuery('#UserPass').keyup(function(){
		perc = passwordStrengthPercent($('#UserPass').val(),$('#UserName').val());
		bpos=" $('#colorbar').css( {backgroundPosition: \"0px -" ;
		bpos = bpos + perc + "px";
		bpos = bpos + "\" } );";
		bpos=bpos +" $('#colorbar').css( {width: \"" ;
		bpos = bpos + (perc * 3) + "px";
		bpos = bpos + "\" } );";
		eval(bpos);
	    	$('#percent').html(" " + perc  + "% ");
	});
});

	// ============================
	// PL: Funkcja pobieraj¹ca nazwe buttona i zmieniaj¹ca treœæ w textarea
	// EN: Function grabbing button name and add text in textarea
	// ============================

	function ReplaceText(Button, To, Before, After) {
		$("input[name=" +Button+ "]").click(function() {
			addText(To, Before, After);
		});
	}
	/*
	------------------------------------------
	// From EF4/PF6
	Flipbox written by CrappoMan
	simonpatterson@dsl.pipex.com
	This code maybe is not a flipbox, but we added this info about author
	becuse it had been written in original file in EF4.
	------------------------------------------
	*/
	function addText(elname, wrap1, wrap2, form) {
		if (document.selection) { // for IE
			var str = document.selection.createRange().text;
			document.forms[form].elements[elname].focus();
			var sel = document.selection.createRange();
			sel.text = wrap1 + str + wrap2;
			return;
		} else if ((typeof document.forms[form].elements[elname].selectionStart) != 'undefined') { // for Mozilla
			var txtarea = document.forms[form].elements[elname];
			var selLength = txtarea.textLength;
			var selStart = txtarea.selectionStart;
			var selEnd = txtarea.selectionEnd;
			var oldScrollTop = txtarea.scrollTop;
			//if (selEnd == 1 || selEnd == 2)
			//selEnd = selLength;
			var s1 = (txtarea.value).substring(0,selStart);
			var s2 = (txtarea.value).substring(selStart, selEnd)
			var s3 = (txtarea.value).substring(selEnd, selLength);
			txtarea.value = s1 + wrap1 + s2 + wrap2 + s3;
			txtarea.selectionStart = s1.length;
			txtarea.selectionEnd = s1.length + s2.length + wrap1.length + wrap2.length;
			txtarea.scrollTop = oldScrollTop;
			txtarea.focus();
			return;
		} else {
			insertText(elname, wrap1 + wrap2, form);
		}
	}

	function insertText(elname, what, form) {
		if (document.forms[form].elements[elname].createTextRange) {
			document.forms[form].elements[elname].focus();
			document.selection.createRange().duplicate().text = what;
		} else if ((typeof document.forms[form].elements[elname].selectionStart) != 'undefined') { // for Mozilla
			var tarea = document.forms[form].elements[elname];
			var selEnd = tarea.selectionEnd;
			var txtLen = tarea.value.length;
			var txtbefore = tarea.value.substring(0,selEnd);
			var txtafter =  tarea.value.substring(selEnd, txtLen);
			var oldScrollTop = tarea.scrollTop;
			tarea.value = txtbefore + what + txtafter;
			tarea.selectionStart = txtbefore.length + what.length;
			tarea.selectionEnd = txtbefore.length + what.length;
			tarea.scrollTop = oldScrollTop;
			tarea.focus();
		} else {
			document.forms[form].elements[elname].value += what;
			document.forms[form].elements[elname].focus();
		}
	}
	// end of from ef4/pf6