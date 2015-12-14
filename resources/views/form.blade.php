@extends('layout')

@section('content')
  <a href="https://github.com/kxgen/facebook-anonymous-publisher"><img style="position: fixed; top: 0; right: 0; border: 0;" src="https://camo.githubusercontent.com/365986a132ccd6a44c23a9169022c0b5c890c387/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f7265645f6161303030302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png"></a>

  <div class="jumbotron">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
          @foreach ($alert as $item)
            <div class="alert alert-danger">{{ $item }}</div>
          @endforeach
          <h1>{{ $config['site_title'] }}</h1>
          <p>{{ $config['site_description'] }}</p>
          <p><a class="btn btn-default" href="http://facebook.com/{{ $config['page_id'] or '#' }}" target="_blank">@lang('form.go_to') {{ $config['page_name'] }}</a></p>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-6  col-md-offset-3" id="container">
        <div class="well text-center">{!! $config['say_hello'] !!}</div>
        <div class="well">
          <form class="form-horizontal">
            <fieldset>
              <div class="form-group">
                <label for="message" class="col-lg-3 control-label">@lang('form.message_content')</label>
                <div class="col-lg-9">
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <textarea class="form-control" id="message" name="message" placeholder="@lang('form.placeholder')" maxlength="1024" rows="5"></textarea>
                    </div>
                    <div class="panel-footer">
                      <div class="radio">
                        <label>
                          <input type="radio" name="mode" value="1" checked="checked">
                          @lang('form.text_post_mode') (@lang('form.text_limit'): 1024)
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" name="mode" value="3">
                          @lang('form.img_text_post_mode') (@lang('form.text_limit'): 140)
                        </label>
                      </div>
                      <div id="preview-block" class="hidden">
                        <button type="button" id="preview-button" class="btn btn-default">@lang('form.show_preview_image')</button>
                        <div id="preview-image"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">@lang('form.publish_license')</label>
                <div class="col-lg-9">
                  <div class="panel">
                    <div class="panel-body">
                      <ol>
                        @foreach ($config['license'] as $item)
                          <li>{!! $item !!}</li>
                        @endforeach
                      </ol>
                    </div>
                    <div class="panel-footer">
                      <div class="checkbox">
                        <label>
                          <input id="accept-license" name="accept-license" type="checkbox">
                          @lang('form.accept_license')
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="message" class="col-lg-3 control-label">@lang('form.human_verify')</label>
                <div class="col-lg-9" style="overflow-x:hidden;">
                  <div id="recaptcha"></div>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                  <button id="submit" type="button" class="btn btn-danger btn-lg">@lang('form.submit')</button>
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="/js/init.js" type="text/javascript"></script>
  <script>
    var alert_content_empty = "@lang('form.alert_content_empty')";
    var alert_agree_license = "@lang('form.alert_agree_license')";
    var alert_human_verify = "@lang('form.alert_human_verify')";
    var processing = "@lang('form.processing')";
    var recaptcha_key = "{{ $config['recaptcha_key'] or 'NULL'}}";
    var max_length = 1024;
  </script>
  <script src="/js/form.js" type="text/javascript"></script>
  <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
@endsection
