(function($){$.fn.clearDefault=function(){return this.each(function(){var default_value=$(this).val();$(this).focus(function(){if($(this).val()==default_value)$(this).val("");});$(this).blur(function(){/*if($(this).val()=="")$(this).val(default_value);*/});});};})(jQuery);

// ============================
// Replace all forum images and avatar icon
// ============================
function ReplaceImg(ReplaceWith, ReplaceTo) {
	jQuery("img[src$='" +ReplaceWith+ "']")
	.attr("src","../themes/eXtreme-fusion_v5/forum/" +ReplaceTo);
}
jQuery.noConflict();
jQuery(function(){
	// ============================
	// Replace all forum images and avatar icon
	// ============================
	ReplaceImg('forum/folder.gif', 'folder.png');
	ReplaceImg('forum/foldernew.gif', 'foldernew.png');
	ReplaceImg('forum/folderhot.gif', 'folderhot.png');
	ReplaceImg('forum/stickythread.gif', 'stickythread.png');
	ReplaceImg('forum/folderlock.gif', 'folderlock.png');
	ReplaceImg('forum/edit.gif', 'edit.png');
	ReplaceImg('forum/quote.gif', 'quote.png');
	ReplaceImg('forum/newthread.gif', 'newthread.png');
	ReplaceImg('forum/reply.gif', 'reply.png');
	ReplaceImg('forum/email.gif', 'email.png');
	ReplaceImg('forum/pm.gif', 'pm.png');
	ReplaceImg('forum/www.gif', 'www.png');
	ReplaceImg('noavatar100.png', 'no-avatar.png');

	// ============================
	// Remove <strong> from thread title
	// ============================
	jQuery(".forum_thread_title").each(function(){
		var GetTitle = jQuery(this).children("strong").html();
		jQuery(this).replaceWith(GetTitle);
	});

	// ============================
	// Remove forum signatures
	// ============================
	jQuery(".forum_sig").prevAll("hr").remove();
	jQuery(".forum_sig").remove();

	// ============================
	// Dark tooltips
	// ============================
	jQuery(".tip").simpletooltip();

	// ============================
	// Others
	// ============================
	jQuery(".news-footer").css({opacity:0.4});
	jQuery('input[type=text], input[type=password], #CommentForm textarea, #NewsEmail').clearDefault();
	jQuery("input[type=checkbox]:not(.logincheck), input:radio, input:file").uniform();
	jQuery('#fancyClock').tzineClock();

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
	// SEnding form with link
	// ============================
	jQuery('a.SendButton').bind('click', function() {
		var FormSubmitID = (this.id).split('_');
		jQuery('#' +FormSubmitID[1]).submit();
	});

	// ============================
	// UserPanel
	// ============================
	jQuery('#Cloud #CloudStart').click(function() {
		jQuery('#Cloud #CloudDynamic').slideToggleWidth();
	});

	jQuery.fn.extend({
		slideRight: function() {
			return this.each(function() {
				jQuery(this).animate({width: 'show'});
				jQuery('#Cloud #CloudStart').removeClass('inactive').addClass('active');
			});
		},
		slideLeft: function() {
			return this.each(function() {
				jQuery(this).animate({width: 'hide'});
				jQuery('#Cloud #CloudStart').removeClass('active').addClass('inactive');
			});
		},
		slideToggleWidth: function() {
			return this.each(function() {
				var el = jQuery(this);
				if (el.css('display') == 'none') {
					el.slideRight();
				} else {
					el.slideLeft();
				}
			});
		}
	});
});