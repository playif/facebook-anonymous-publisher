@extends('admin.layout')

@section('content')

  <div class="row">
    <div class="col-sm-6 col-sm-offset-3">
      <h3>
        @lang('dashboard.setting')
      </h3>
      <hr>
      <form class="form-horizontal" method="post" target="_self">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.lang_mode')
          </label>
          <div class="col-sm-7">
            <select class="form-control input-sm" name="lang">
              @foreach ($langs as $key => $value)
                <option value="{{$key}}" @if ($setting['lang'] == $key) selected="selected" @endif>{{$value}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.theme_mode')
            <span tabindex="0" class="glyphicon glyphicon-exclamation-sign" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="This application's front-end UI is base on bootstrap, we have built-in multiple themes (source from https://bootswatch.com). Just change this setting to get different UI style."></span>
          </label>
          <div class="col-sm-7">
            <select class="form-control input-sm" name="bootstrap_theme">
              @foreach ($themes as $value)
                <option value="{{$value}}" @if ($setting['bootstrap_theme'] == $value) selected="selected" @endif>{{ucfirst($value)}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.site_name')
          </label>
          <div class="col-sm-7">
            <input type="text" name="site_name" class="form-control input-sm" value="{{ $setting['site_name'] or '' }}" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.site_title')
          </label>
          <div class="col-sm-7">
            <input type="text" name="site_title" class="form-control input-sm" value="{{ $setting['site_title'] or '' }}" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.site_description')
          </label>
          <div class="col-sm-7">
            <input type="text" name="site_description" class="form-control input-sm" value="{{ $setting['site_description'] or '' }}" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.page_id')
          </label>
          <div class="col-sm-7">
            <input type="text" name="page_id" class="form-control input-sm" value="{{ $setting['page_id'] or '' }}" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.page_name')
          </label>
          <div class="col-sm-7">
            <input type="text" name="page_name" class="form-control input-sm" value="{{ $setting['page_name'] or '' }}" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.hello_message')
            <span tabindex="0" class="glyphicon glyphicon-exclamation-sign" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="Here you can write down your hello message with your guest, tell them why you create this website and your Page core concept."></span>
          </label>
          <div class="col-sm-7">
            <input type="text" name="say_hello" class="form-control input-sm" value="{{ $setting['say_hello'] or '' }}" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.license')
            <span tabindex="0" class="glyphicon glyphicon-exclamation-sign" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="Here you can establish some rules to require guest to accept."></span>
          </label>
          <div class="col-sm-7">
            <textarea name="license" class="form-control input-sm" rows="6">{{ $setting['license'] or '' }}</textarea>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.publish_frequency')
          </label>
          <div class="col-sm-7">
            <select class="form-control input-sm" name="publish_frequency">
              @foreach ($frequency as $value)
                <option value="{{$value}}" @if ($setting['publish_frequency'] == $value) selected="selected" @endif>{{$value}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-5">
            @lang('dashboard.custom_message')
          </label>
          <div class="col-sm-7">
            <input type="text" name="custom_message" class="form-control input-sm" value="{{ $setting['custom_message'] or '' }}" />
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-5">
            @lang('dashboard.advance_block_mode')
            <span tabindex="0" class="glyphicon glyphicon-exclamation-sign" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="Only effective with chinese content. Advanced mode will automatically find content containing homonyms."></span>
          </label>
          <div class="col-xs-7">
            <div class="material-switch pull-right">
              <input id="advanceBlockModeToggle" name="advance_block_mode" type="checkbox" @if ($setting['advance_block_mode'] == 'on') checked="checked" @endif />
              <label for="advanceBlockModeToggle" class="label-primary"></label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-5">
            @lang('dashboard.log_guest_ip')
            <span tabindex="0" class="glyphicon glyphicon-exclamation-sign" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="We don't suggest to log guest's ip --which is inconsistent with the concept of anonymous-- only if you CONNOT easily deal with spam & abuse. It still dose not be recommended as a best response due to people can change the IP with VPN or proxy service."></span>
          </label>
          <div class="col-xs-7">
            <div class="material-switch pull-right">
              <input id="logGuestIpToggle" name="log_guest_ip" type="checkbox" @if ($setting['log_guest_ip'] == 'on') checked="checked" @endif />
              <label for="logGuestIpToggle" class="label-primary"></label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-5">
            @lang('dashboard.encrypt_guest_ip')
          </label>
          <div class="col-xs-7">
            <div class="material-switch pull-right">
              <input id="encryptGuestIpToggle" name="encrypt_guest_ip" type="checkbox" @if ($setting['encrypt_guest_ip'] == 'on') checked="checked" @endif />
              <label for="encryptGuestIpToggle" class="label-primary"></label>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-xs-5">
            @lang('dashboard.maintain_mode')
            <span tabindex="0" class="glyphicon glyphicon-exclamation-sign" role="button" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="When in maintenance mode, guest will not allow to visit the form page until you turn it off. Be sure to change the setting when you are going to maintain or change settings for your website."></span>
          </label>
          <div class="col-xs-7">
            <div class="material-switch pull-right">
              <input id="maintainToggle" name="maintain" type="checkbox" @if ($setting['maintain'] == 'on') checked="checked" @endif />
              <label for="maintainToggle" class="label-primary"></label>
            </div>
          </div>
        </div>


        <div class="form-group">
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary btn-block">Save</button>
          </div>
        </div>

      </form>
    </div>
  </div>

@endsection
@section('foot')

  <style>
    [data-toggle="popover"],
    [data-toggle="popover"]:hover,
    [data-toggle="popover"]:focus, {
      cursor: pointer;
      outline: none;
    }
  </style>

  <script>
    $(function () {
      $('[data-toggle="popover"]').popover()
    })
  </script>

@endsection
