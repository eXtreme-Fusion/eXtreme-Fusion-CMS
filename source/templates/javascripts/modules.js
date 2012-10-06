/**
 * Ajax modules for page action
 */

function showAdminBox() {
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
}