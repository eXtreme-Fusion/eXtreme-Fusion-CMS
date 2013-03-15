	$(function() {
		$('#ShoutBoxDownArrow').hover(function() {
			interval = setInterval(function() {
				var scroll = $('#ShoutBoxPosts').scrollTop();
				$('#ShoutBoxPosts').scrollTop(scroll+1);
			}, 1);
		},function() {
			clearInterval(interval);
		});

		$('#ShoutBoxUpArrow').hover(function() {
			interval = setInterval(function() {
				var scroll = $('#ShoutBoxPosts').scrollTop();
				$('#ShoutBoxPosts').scrollTop(scroll-1);
			}, 1);
		},function() {
			clearInterval(interval);
		});


		refresh2();

		$('form#ShoutBoxForm').submit(function() {

			var action = $('input.InfoBoxButton').attr('name');

			$('input.InfoBoxButton').attr('name', 'send');

			$.post(addr_site+'modules/chat/ajax/send.php', {
				content: $('textarea[name*="content"]', this).val(),
				post_id: $('input[name*="post_edit_id"]', this).val(),
				send: action,
			  },
			  function(data){
				$('textarea[name*="content"]').val('');
				refresh2();
			  }
			);
			return false;
		});

		/* Usuwanie postów */
		$('#post_shoutbox_delete').live('click', function() {
			var href = $(this).attr('href');
			 $.ajax({
				url: href, type: 'GET', success: function (html){
				  refresh2();
				}, error:function(){
					$('#ShoutBoxPosts').html('An error occurred! Refresh the page.');
				}
			  });
				$('#delete_shoutbox_confirm').remove();
				$('#ShoutBoxForm').show();
			return false;
		});

		$('#post_shoutbox_cancel').live('click', function() {
			$('#delete_shoutbox_confirm').remove();
			$('#ShoutBoxForm').show();
		});

		$('.shoutbox_delete_post').live('click', function() {
			$('#delete_shoutbox_confirm').remove();
			var href = $(this).attr('href');
			$('#ShoutBoxForm').hide();
			$('#ShoutBoxForm').before('<div id="delete_shoutbox_confirm" class="InfoBoxInput">Delete this shout? <a href="'+href+'" id="post_shoutbox_delete" class="InfoBoxButton">Yes</a> <a href="javascript:void(0)" id="post_shoutbox_cancel" class="InfoBoxButton">No</a></div>');
			return false;
		});



		/* Edycja */
		$('.shoutbox_edit_post').live('click', function() {
			var href = $(this).attr('href');
			 $.ajax({
				url: href, type: 'GET', success: function (html){
				  $('form#ShoutBoxForm').html(html);
				}, error:function(){
					$('#ShoutBoxPosts').html('An error occurred! Refresh the page.');
				}
			  });

			return false;

		});

		setInterval(function() {
			refresh2();
		},120000);

		var refresh = false;
		$('#ShoutBoxPosts').hover(function() {
			if (!refresh) {
				refresh2();
				refresh = true;
			}
		}, function() {
			refresh = false;
		});

	});

function refresh2() {
  var posts = $('#chat_post').html();
  $.ajax({
    url: addr_site+'modules/chat/shoutbox_panel/ajax/messages.php', type: 'GET', success: function (html){
      $('#ShoutBoxPosts').html(html);
    }, error:function(){
      $('#ShoutBoxPosts').html('An error occurred! Refresh the page.');
    }
  });
}
