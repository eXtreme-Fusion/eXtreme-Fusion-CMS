$(function(){
$.fn.simpletooltip=function(){
	return this.each(function(){
		var text=$(this).attr("title");
		$(this).attr("title","");
		if(text!=undefined){
			$(this).live({
				mouseover: function(e){
					var tipX=e.pageX+12;
					var tipY=e.pageY+12;
					$(this).attr("title","");
					$("body").append("<div id='simpleTooltip' style='position:absolute;z-index:999999;display:none;border-top-left-radius:4px;-moz-border-radius-topleft:4px;-webkit-border-top-left-radius:4px;border-top-right-radius:4px;-moz-border-radius-topright:4px;-webkit-border-top-right-radius:4px;border-bottom-left-radius:4px;-moz-border-radius-bottomleft:4px;-webkit-border-bottom-left-radius:4px;border-bottom-right-radius:4px;-moz-border-radius-bottomright:4px;-webkit-border-bottom-right-radius:4px;'>"+text+"</div>").css({'border-radius': '4px'});
					if($.browser.msie)
					var tipWidth=$("#simpleTooltip").outerWidth(true)
					else
					var tipWidth=$("#simpleTooltip").width();
					$("#simpleTooltip").width(tipWidth);
					$("#simpleTooltip").css("left",tipX).css("top",tipY).css("opacity","0.8").fadeIn("slow");
				},
				mouseout: function(){
					$("#simpleTooltip").remove();
					$(this).attr("title",text);
				},
				mousemove: function(e){
					var tipX=e.pageX+20;
					var tipY=e.pageY+20;
					var tipWidth=$("#simpleTooltip").outerWidth(true);
					var tipHeight=$("#simpleTooltip").outerHeight(true);
					if(tipX+tipWidth>$(window).scrollLeft()+$(window).width())
					tipX=e.pageX-tipWidth;
					if($(window).height()+$(window).scrollTop()<tipY+tipHeight)
					tipY=e.pageY-tipHeight;
					$("#simpleTooltip").css("left",tipX).css("top",tipY).fadeIn("medium");
				}
			});
		}
	});
}
});
