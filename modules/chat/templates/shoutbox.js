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
					$('#ShoutBoxPosts').html('Wystąpił błąd! Odśwież stronę.');
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
			$('#ShoutBoxForm').before('<div id="delete_shoutbox_confirm" class="InfoBoxInput">Na pewno chcesz usunąć ten post? <a href="'+href+'" id="post_shoutbox_delete" class="InfoBoxButton">Tak</a> <a href="javascript:void(0)" id="post_shoutbox_cancel" class="InfoBoxButton">Nie</a></div>');
			return false;
		});



		/* Edycja */
		$('.shoutbox_edit_post').live('click', function() {
			var href = $(this).attr('href');
			 $.ajax({
				url: href, type: 'GET', success: function (html){
				  $('form#ShoutBoxForm').html(html);
				}, error:function(){
					$('#ShoutBoxPosts').html('Wystąpił błąd! Odśwież stronę.');
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
      $('#ShoutBoxPosts').html('Wystąpił błąd! Odśwież stronę.');
    }
  });
}

function addText(elname, wrap1, wrap2) {
	if (document.selection) { // for IE
		var str = document.selection.createRange().text;
		document.forms['chat'].elements[elname].focus();
		var sel = document.selection.createRange();
		sel.text = wrap1 + str + wrap2;
		return;
	} else if ((typeof document.forms['chat'].elements[elname].selectionStart) != 'undefined') { // for Mozilla
		var txtarea = document.forms['chat'].elements[elname];
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
		insertText(elname, wrap1 + wrap2);
	}
}

function insertText(elname, what) {
	if (document.forms['chat'].elements[elname].createTextRange) {
		document.forms['chat'].elements[elname].focus();
		document.selection.createRange().duplicate().text = what;
	} else if ((typeof document.forms['chat'].elements[elname].selectionStart) != 'undefined') { // for Mozilla
		var tarea = document.forms['chat'].elements[elname];
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
		document.forms['chat'].elements[elname].value += what;
		document.forms['chat'].elements[elname].focus();
	}
}
