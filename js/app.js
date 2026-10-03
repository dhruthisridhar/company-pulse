// Company Pulse client-side behaviour (jQuery). Loaded on every page by includes/header.php.
$(function () {

  // Sign up: check username availability while typing (AJAX -> checkuser.php)
  var $user = $('#user'), $info = $('#info');
  if ($user.length && $info.length) {
    $user.on('keyup blur', function () {
      var name = $.trim(this.value);
      if (name.length < 3) { $info.empty(); return; }
      $.get('checkuser.php', { user: name }, function (html) { $info.html(html); });
    });
  }

  // Messages: show the recipient list only when sending a private whisper
  var $recipient = $('#whisper-to');
  if ($recipient.length) {
    var sync = function () { $recipient.toggle($('#priv').is(':checked')); };
    $('input[name=type]').on('change', sync);
    $(document).on('click', 'label[for=pub], label[for=priv]', function () { setTimeout(sync, 0); });
    sync();
  }
});
