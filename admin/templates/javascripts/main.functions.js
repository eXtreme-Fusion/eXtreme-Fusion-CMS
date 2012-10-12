	// ============================
	// PL: Funkcja zmieniające opcje radio, select, etc.
	// EN: Function amending options radiom sekect, etc.
	// ============================
	function ChangeValue(Selector, Value, Change, Type, Box) {
		if (Type == "Input") {
			var RunSelector = $("input[name=" +Selector+ "]");
			var RunSelectorClass = $("input[name=" +Selector+ "]." +Value);
		} else {
			var RunSelector = $("select#" +Selector);
		}
		if (Change == "Reselect" && Type == "Input") {
			RunSelector.parents("div#uniform-").removeClass("focus");
			RunSelector.parent("span").removeClass("checked");
			RunSelectorClass.parent("div#uniform-").addClass("focus");
			RunSelectorClass.parent("span").addClass("checked");
		} else if (Change == "Disable" && Type == "Input") {
			RunSelector.parents("div." +Box).animate({opacity: 0.15}, 400);
			RunSelector.attr("disabled","disabled");
		} else if (Change == "Enable" && Type == "Input") {
			RunSelector.parents("div." +Box).animate({opacity: 1}, 400);
			RunSelector.attr("disabled","");
		} else if (Change == "Disable" && Type == "Select") {
			RunSelector.parents("div." +Box).animate({opacity: 0.15}, 400);
			RunSelector.attr("disabled","disabled");
		} else if (Change == "Enable" && Type == "Select") {
			RunSelector.parents("div." +Box).animate({opacity: 1}, 400);
			RunSelector.attr("disabled","");
		}
	}

	// ============================
	// PL: Funkcja zmieniające zaznaczony tekst na HTML
	// EN: Function amending selected text to HTML
	// ============================
	function addText(f, i, a) {
		if (f == undefined) {f = "message"}
		element = document.forms[0].elements[f];
		element.focus();
		if (document.selection) {
			var c=document.selection.createRange();
			var h=c.text.length;
			c.text=i+c.text+a;
			return false
		} else { 
			if (element.setSelectionRange) {
				var b = element.selectionStart,g=element.selectionEnd;
				var d = element.scrollTop;
				element.value=element.value.substring(0,b)+i+element.value.substring(b,g)+a+element.value.substring(g);
				element.setSelectionRange(b+i.length,g+i.length);
				element.scrollTop=d;
				element.focus()
			} else {
				var d=element.scrollTop;
				element.value+=i+a;
				element.scrollTop=d;
				element.focus()
			}
		}
	}
	// ============================
	// PL: Funkcja pobierająca nazwe buttona i zmieniająca treść w textarea
	// EN: Function grabbing button name and add text in textarea
	// ============================
	function ReplaceText(Button, To, Before, After) {
		$("input[name=" +Button+ "]").click(function() {
			addText(To, Before, After);
		});
	}