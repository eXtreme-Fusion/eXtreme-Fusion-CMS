var myLayout;

function PanelMenu() {
	// Pokazuje poszczególne sub-menu w nawigacji bocznej
	$('#menu li a.sub-menu').click(function() {
		var checkElement = $(this).next();
		if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
			$('#menu ul:visible').slideUp('fast');
			checkElement.slideDown('slow');
			return false;
		} else {
			$("a.sub-menu").next().slideUp('slow');
		}
	});
	// Animacja linków w ".sub-menu ul li".
	$("#menu li span a").hover(function() {
		$(this).stop().animate({ paddingRight: "6px" }, 200);
	}, function () {
		$(this).stop().animate({ paddingRight: "0px" });
	});
}

$(document).ready(function($) {
	PanelMenu();
	$('#fancyClock').tzineClock();
	$(".tip, .ui-layout-toggler").simpletooltip();
	$("del").animate({opacity:0.3 },1);

	$('#toggle-all').click(function(){
		$('#toggle-all').toggleClass('toggle-checked');
		$('#mainform input[type=checkbox]').checkBox('toggle');
		return false;
	});

	//	Target _blank dla linków z atrybutu rel=""
	$("a[rel*='blank']").click(function(){
		this.target = "_blank";
	});

	// Boczna nawigacja wczytywana AJAXowo
	$("#Navigation .table a").live("click", function() {
		var id      = this.id,
		    menu    = id.split("-"),
		    session = $("#SessionExpire").find("strong");

		if (session.text() == "00:00:00") {
			window.location.href = document.location+"?action=login";
		}

		$("#mainFrame").attr("src", "pages/navigation.php?PageNum="+menu[1]);
	});

	//	Główna nawigacja
	$("#Navigation ul").click(function() {
		var Navigation = $("#Navigation");
		Navigation.find("ul").removeClass("current").addClass("select");
		Navigation.find("ul li div").removeClass("show");
		$(this).removeClass("select");
		$(this).addClass("current");
		$(this).find("div").addClass("show");
	});

	myLayout = $('body').layout({
		applyDefaultStyles: true
	,	west__showOverflowOnHover:	false
	,	north__resizable:						false		// Czy górny panel ma być powiększalny?
	,	north__size:							0			// 0 dla braku powiększania panelu górnego
	,	south__resizable:						false		// Czy dolny panel ma byćpowiększalny?
	,	south__spacing_open:				0			// 0 dla braku powiększania panelu dolnego
	,	west__resizable:						false		// Czy górny panel ma byćpowiększalny?
	,	west__size:								0			// Maksymalna szerokość prawego panelu z nawigacją
	,	west__maxSize:						216		// Maksymalna szerokość prawego panelu z nawigacją
	});
});