$(function() {
  
	  $('#NewsletterForm').submit(function() {
	  
		$('#NewsletterPanel .InfoBoxCon .InfoBoxCenterRight').fadeOut();
			
		$.post(addr_site+'modules/newsletter_panel/ajax/newsletter_submit.php', {
			email: $('[name="UserEmail"]', this).val(),
			rules: $('[name="NewsletterZgoda"]:checked', this).val(),
			send: true
		  },function(data){	
			setTimeout(function() {
			  $('#NewsletterPanel .InfoBoxCon .InfoBoxCenterRight').html(data).fadeIn();
			},500);
		  }); 
		  return false;
	  
	  });
	  
});