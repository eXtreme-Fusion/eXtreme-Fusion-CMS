$(function() {
  refresh();

  $('#chat_form form').submit(function() {
    $.post(addr_site+'modules/chat/ajax/send.php', {
        content: $('input[name*="content"]', this).val(),
        send: 'send'
      },
      function(data){
        $('#request_chat').html(data);
        $('input[name*="content"]').val('');
        refresh();
      }
    );
    return false;
  });

  setInterval(function() {
    refresh();
  },+refresh_chat);

});

function refresh() {
  var posts = $('#chat_post').html();
  $.ajax({
    url: addr_site+'modules/chat/ajax/messages.php', type: 'GET', success: function (html){
      $('#chat_messages section').html(html);
      setTimeout(function(){
        var posts2 = $('#chat_post').html();
        if(posts != posts2) {
          var scrollh = $('#chat_messages section').height();
          $('#chat_messages').animate({ scrollTop: scrollh }, 2000);
        }
      },400);
    }, error:function(){
      $('#chat_messages section').html('An error occurred! Refresh the page.');
    }
  });
}
