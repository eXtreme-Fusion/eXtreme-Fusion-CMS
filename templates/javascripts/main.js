(function($){$.fn.clearDefault=function(){return this.each(function(){var default_value=$(this).val();$(this).focus(function(){if($(this).val()==default_value)$(this).val("");});$(this).blur(function(){/*if($(this).val()=="")$(this).val(default_value);*/});});};})(jQuery);

// jQuery.noConflict();

// Manipulowanie domyœln¹ zawartoœci¹ pola
function valueSystem(id) {
	jQuery(function() {
		var val = jQuery(id).val();
		jQuery(id).focus(
			function() {
				if (this.value == val) {
					this.value = '';
				}
			}
		).blur(function() {
			if (this.value == '') {
				this.value = val;
			}
		});
	});
}
 
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
	// Button Save
	// ============================
	jQuery('span.Save').bind('click', function() {
		var FormSubmitID = (this.id).split('_');
		jQuery('#' +FormSubmitID[1]).submit();
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
	
	function addText(elname, strFore, strAft, formname) {
	   if (formname == undefined) formname = 'inputform';
	   if (elname == undefined) elname = 'message';
	   element = document.forms[formname].elements[elname];
	   element.focus();
	   // for IE 
	   if (document.selection) {
		   var oRange = document.selection.createRange();
		   var numLen = oRange.text.length;
		   oRange.text = strFore + oRange.text + strAft;
		   return false;
	   // for FF and Opera
	   } else if (element.setSelectionRange) {
		  var selStart = element.selectionStart, selEnd = element.selectionEnd;
				var oldScrollTop = element.scrollTop;
		  element.value = element.value.substring(0, selStart) + strFore + element.value.substring(selStart, selEnd) + strAft + element.value.substring(selEnd);
		  element.setSelectionRange(selStart + strFore.length, selEnd + strFore.length);
				element.scrollTop = oldScrollTop;      
		  element.focus();
	   } else {
				var oldScrollTop = element.scrollTop;
		  element.value += strFore + strAft;
				element.scrollTop = oldScrollTop;      
		  element.focus();
		}
		return false;
	}

	function insertText(elname, what, formname) {
	   if (formname == undefined) formname = 'inputform';
	   if (document.forms[formname].elements[elname].createTextRange) {
		   document.forms[formname].elements[elname].focus();
		   document.selection.createRange().duplicate().text = what;
	   } else if ((typeof document.forms[formname].elements[elname].selectionStart) != 'undefined') {
		   // for Mozilla
		   var tarea = document.forms[formname].elements[elname];
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
		   document.forms[formname].elements[elname].value += what;
		   document.forms[formname].elements[elname].focus();
	   }
	}