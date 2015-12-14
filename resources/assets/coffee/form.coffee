#==========================================
# Google reCAPTCHA
#==========================================
window.verifying = false
onloadCallback = ->
  grecaptcha.render 'recaptcha',
    'sitekey': recaptcha_key
    'callback': (r) ->
      window.verifying = true


#==========================================
# Events
#==========================================
$ ->
  $('body').delegate '[name="mode"]', 'change', ->
    mode = $(this).val()
    if mode is '3'
      $('#preview-block').removeClass 'hidden'
    else
      $('#preview-block').addClass 'hidden'


  $('body').delegate '#preview-button', 'click', ->
    $.ajax
      type: 'post'
      data:
        message: $('#message').val()
      url: '/preview'
      success: (r) ->
        $('#preview-image').html r

  $('body').delegate '#message', 'keydown change', ->
    if $('#preview-image img').length > 0
      $('#preview-image').html ''

  $('body').delegate '#submit', 'click', ->
    message = $('#message').val()
    mode = $('[name="mode"]:checked').val()

    if message.length > max_length
      message = message.substring(0, max_length)

    if message is '' or message is null
      alert alert_content_empty
      $('#message').focus()
      return

    if $('#accept-license').prop('checked') is false
      alert alert_accept_license
      return

    if window.verifying is false
      alert alert_human_verify
      return

    $('#submit').replaceWith '<button type="button" class="btn btn-danger btn-lg active">' + processing + '</button>'

    $.ajax
      type: 'post'
      dataType: 'json'
      cache: false
      data:
        message: message
        mode: mode
        recaptcha: $('[name="g-recaptcha-response"]').val()
      url: '/submit'
      error: (r) ->
        console.log r
      success: (r) ->
        if r.state is 'success' then headerTo(r.redirct) else headerTo('/')
