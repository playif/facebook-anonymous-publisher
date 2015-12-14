var onloadCallback;

window.verifying = false;

onloadCallback = function() {
  return grecaptcha.render('recaptcha', {
    'sitekey': recaptcha_key,
    'callback': function(r) {
      return window.verifying = true;
    }
  });
};

$(function() {
  $('body').delegate('[name="mode"]', 'change', function() {
    var mode;
    mode = $(this).val();
    if (mode === '3') {
      return $('#preview-block').removeClass('hidden');
    } else {
      return $('#preview-block').addClass('hidden');
    }
  });
  $('body').delegate('#preview-button', 'click', function() {
    return $.ajax({
      type: 'post',
      data: {
        message: $('#message').val()
      },
      url: '/preview',
      success: function(r) {
        return $('#preview-image').html(r);
      }
    });
  });
  $('body').delegate('#message', 'keydown change', function() {
    if ($('#preview-image img').length > 0) {
      return $('#preview-image').html('');
    }
  });
  return $('body').delegate('#submit', 'click', function() {
    var message, mode;
    message = $('#message').val();
    mode = $('[name="mode"]:checked').val();
    if (message.length > max_length) {
      message = message.substring(0, max_length);
    }
    if (message === '' || message === null) {
      alert(alert_content_empty);
      $('#message').focus();
      return;
    }
    if ($('#accept-license').prop('checked') === false) {
      alert(alert_accept_license);
      return;
    }
    if (window.verifying === false) {
      alert(alert_human_verify);
      return;
    }
    $('#submit').replaceWith('<button type="button" class="btn btn-danger btn-lg active">' + processing + '</button>');
    return $.ajax({
      type: 'post',
      dataType: 'json',
      cache: false,
      data: {
        message: message,
        mode: mode,
        recaptcha: $('[name="g-recaptcha-response"]').val()
      },
      url: '/submit',
      error: function(r) {
        return console.log(r);
      },
      success: function(r) {
        if (r.state === 'success') {
          return headerTo(r.redirct);
        } else {
          return headerTo('/');
        }
      }
    });
  });
});
