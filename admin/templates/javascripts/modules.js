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
// Zaznaczanie na czerwono licznika sesji zalogowania, gdy pozostało x sekund do końca jej trwania
function highlightLast5(periods) {
    if ($.countdown.periodsToSeconds(periods) == 180) {
        $(this).addClass('red');
    }
}

$(function(){

	/** 
	 * Pobieranie prywatnych wiadomości
	 * Wyłaczone, bo zakomentowane jest pokazywanie wiadomości w PA.
	 * Pliki framework.tpl i ajax/modules.php
	 */
	/*$("#Navigation a").click(function() {
		var UserID = $("#GetMessages").attr("name").split("-");
		$.ajax({
			type: "POST",
			url: "pages/ajax/modules.php",
			data: {Action: "GetMessages", UserID: UserID[1]},
			success: function(theResponse) {
				if (Response != "0") {
					$("#GetMessages img").after().html("<strong>(" +theResponse+ ")</strong>");
					$("#GetMessages strong").show();
				} else {
					$("#GetMessages strong").hide();
				}
			}
		});
	});*/

	/* Podliczanie ilości postów */
	$("#CountPosts").click(function() {
		$.ajax({
			type: "POST",
			url: "ajax/modules.php",
			data: {Action: "CountPosts"},
			success: function(theResponse) {
				$("#CountPosts").removeClass("CancelButton").addClass("SendButton");
				$("#CountPosts strong strong strong").text(theResponse);
			}
		});
	});

	/* Usuwanie znaków wodnych */
	$("#DeleteWatermarks").click(function() {
		$.ajax({
			type: "POST",
			url: "ajax/modules.php",
			data: {Action: "DeleteWatermarks"},
			success: function(theResponse) {
				$("#DeleteWatermarks").removeClass("SendButton").addClass("CancelButton");
				$("#DeleteWatermarks strong strong strong").text(theResponse);
			}
		});
	});

	/* Przemieszczanie paneli */
	$(function(){
		$('.DragBox').each(function(){
			$(this).click(function(){
				//$(this).siblings('.DragBoxContent').toggle();
			}).end();
		});

		function sortReady() {
			$('.PanelColumn').sortable({
				connectWith: '.PanelColumn',
				handle: 'h2:not(.SiteContent)',
				cursor: 'move',
				placeholder: 'PlaceHolder ui-corner-all',
				forcePlaceholderSize: true,
				opacity: 0.5,
				stop: function(event, ui){
					$(ui.item).find('h2').click();
					
					var Column = [];
					
					
					$('.PanelColumn').each(function(){
						var ItemOrder = $(this).sortable('toArray');

						if (ItemOrder.length > 0) {
							Column.push('"'+$(this).attr('id')+'": ['+ '"' +ItemOrder.join('", "')+'"]');
						} else {
							Column.push('"'+$(this).attr('id')+'": []');
						}
					});
					
					var SortOrder = '{';
					SortOrder += Column.join(', ');
					SortOrder += '}';
					
					var DragColumnID = $(this).attr('id');

					$.ajax({
						type: "POST",
						url: "ajax/modules.php",
						data: {Action: "UpdatePanels", SortOrder: SortOrder, DragColumnID: DragColumnID},
						success: function(theResponse) {
							//if (theResponse == 'removed') {
								$('#inactive').load('ajax/inactive_panels.php', function() {
									sortReady();
								});
							//}
						}
					});
				}
			})
			.disableSelection();
		}
		sortReady();
	});


	/* Zmiana statusu panelu */
	$("a.ChangeStatus").live("click", function(event) {
		var ChangeID = $("a#"+(this.id));
		var Change = (this.id).split("_");
		var PanelStatus = Change[1]; var PanelID = Change[2];
					var status = $('#status-types').html();

				status = status.split('_');

		if (PanelStatus == 0) {
			$("#Item_" +PanelID+ " h2 img[src$='checkmark.png']").attr("src","../templates/images/icons/pixel/_loading.gif");
			$(this).attr('rel', status[1]);

		} else {
			$("#Item_" +PanelID+ " h2 img[src$='against.png']").attr("src","../templates/images/icons/pixel/_loading.gif");
			$(this).attr('rel', status[0]);

		}
		$.ajax({
			type: "POST",
			url: "ajax/modules.php",
			data: {Action: "ChangeStatus", PanelStatus: PanelStatus, PanelID: PanelID},
			success: function(theResponse) {
				var ChangeHeader = $("#Item_" +PanelID+ " h2 .Title").text();

				if (PanelStatus == 0) {
					$("#Item_" +PanelID+ " h2 .Title").replaceWith("<span class='Title'>" +ChangeHeader+ "</span>");
					$("#Item_" +PanelID+ " h2 img[src$='_loading.gif']").attr("src","../templates/images/icons/pixel/against.png");

				} else {
					$("#Item_" +PanelID+ " h2 .Title").replaceWith("<em class='Title'>" +ChangeHeader+ "</em>");
					$("#Item_" +PanelID+ " h2 img[src$='_loading.gif']").attr("src","../templates/images/icons/pixel/checkmark.png");

				}
				$(ChangeID).attr("id", "Status_" +theResponse);
			}
		});
	});

	/* Usuwanie panelu */
	$("a.Delete").live("click", function(event) {

		if (confirm($(this).attr('rel')))
		{
			var Data = (this.id).split("_");
			var PanelID = Data[1];

			$.ajax({
				type: "POST",
				url: "ajax/modules.php",
				data: {Action: "Delete", PanelID: PanelID},
				success: function(theResponse)
				{
					if (theResponse == 'success') $('#Item_'+PanelID).remove();
				}
			});
		}

		$('#simpleTooltip').hide();
	});

	$('#SessionExpire').live('click', function() {
		$.ajax({
			type: 'POST',
			url: 'pages/ajax/modules.php',
			data: {Action: 'renewSession'},
			success: function(theResponse)
			{
				if (parseInt(theResponse)) {
					$('#SessionExpire strong').countdown('change', {until: +theResponse});
					$('#SessionExpire strong').removeClass('red');
				} else {
					$('#SessionExpire').html(theResponse);
				}
			}
		});
	});

	// Dran-n-Drop w ustawienaich linków nawigacji strony
	function SlideoutNavigations(){
		setTimeout(function(){
			$('#ResponseNavigations').corner('8px').slideUp('slow', function () {
			});
		}, 2000);
	}

	$('#ResponseNavigations').hide();

	$(function() {
		$('#ListNavigations ul').sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable('serialize') + '&UpdateOrderNavigations=Ok';
			$.post('ajax/modules.php', order, function(theResponse){
				$('#ResponseNavigations').html(theResponse);
				$('#ResponseNavigations').slideDown('slow').css('display','block');
				SlideoutNavigations();
			});
		}
		});
	});

	// Dran-n-Drop w ustawienaich bbcode strony
	function SlideoutBBcode(){
		setTimeout(function(){
			$('#ResponseBBcode').corner('8px').slideUp('slow', function () {
			});
		}, 2000);
	}

	$('#ResponseBBcode').hide();

	$(function() {
		$('#ListBBcode ul').sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable('serialize') + '&UpdateOrderBBcode=Ok';
			$.post('ajax/modules.php', order, function(theResponse){
				$('#ResponseBBcode').html(theResponse);
				$('#ResponseBBcode').slideDown('slow').css('display','block');
				SlideoutBBcode();
			});
		}
		});
	});

	// Dran-n-Drop w zarządzaniu kategori pól profilu
	function SlideoutUserFieldsCats(){
		setTimeout(function(){
			$('#ResponseUserFieldsCats').corner('8px').slideUp('slow', function () {
			});
		}, 2000);
	}

	$('#ResponseUserFieldsCats').hide();

	$(function() {
		$('#ListUserFieldsCats ul').sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable('serialize') + '&UpdateOrderUserFieldsCats=Ok';
			$.post('ajax/modules.php', order, function(theResponse){
				$('#ResponseUserFieldsCats').html(theResponse);
				$('#ResponseUserFieldsCats').slideDown('slow').css('display','block');
				SlideoutUserFieldsCats();
			});
		}
		});
	});

});