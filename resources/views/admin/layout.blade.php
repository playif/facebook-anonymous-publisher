<!DOCTYPE html>
<html lang="zh-TW">
  <head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="/vendor/theme/{{ $config['bootstrap_theme'] }}/bootstrap.min.css">
    <link rel="stylesheet" href="/css/all.css">
    <style>
      .table h4 {padding-left: 5px;}
      .btn {font-weight: bold;}
      .state-unpublished {color: rgba(200,200,200,.5);}
      .state-deny {text-decoration: line-through; color: red;}
      .state-deny .td-action span {opacity: 0;}
      .td-message {max-width: 400px;}
      .td-message p {max-height: 100px; overflow-y: scroll;}
      .table>tbody>tr>td.td-empty {padding: 50px 0;}
      table .pagination a:not(.btn), .table .pagination a:not(.btn) {text-decoration: none;}
      .save-state {font-size: 14px; padding-left: 5px; color: #999;}
      .refresh-button {cursor: pointer;}
      .refresh-button:hover {color: #666;}
      .material-switch {
        margin-top: 8px;
      }
      .material-switch > input[type="checkbox"] {
          display: none;
      }
      .material-switch > label {
          cursor: pointer;
          height: 0px;
          position: relative;
          width: 40px;
      }
      .material-switch > label::before {
          background: rgb(200, 200, 200);
          box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
          border-radius: 8px;
          content: '';
          height: 16px;
          margin-top: -8px;
          position:absolute;
          opacity: 0.3;
          transition: all 0.4s ease-in-out;
          width: 40px;
      }
      .material-switch > label::after {
          background: rgb(255, 255, 255);
          border-radius: 16px;
          box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
          content: '';
          height: 24px;
          left: -4px;
          margin-top: -8px;
          position: absolute;
          top: -4px;
          transition: all 0.3s ease-in-out;
          width: 24px;
      }
      .material-switch > input[type="checkbox"]:checked + label::before {
          background: inherit;
          opacity: 0.5;
      }
      .material-switch > input[type="checkbox"]:checked + label::after {
          background: inherit;
          left: 20px;
      }
    </style>
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <nav class="navbar navbar-default">
            <div class="container-fluid">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-nav" aria-expanded="false">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/admin">Dashboard</a>
              </div>
              <div class="collapse navbar-collapse" id="header-nav">
                <ul class="nav navbar-nav navbar-right">
                  <li><a href="/admin/queue">@lang('dashboard.queue_list')</a></li>
                  <li><a href="/admin/block-guest">@lang('dashboard.block_guest_list')</a></li>
                  <li><a href="/admin/block-keyword">@lang('dashboard.block_keyword_list')</a></li>
                  <li><a href="/admin/setting">@lang('dashboard.setting')</a></li>
                  <li><a href="/" target="_blank">@lang('dashboard.home')</a></li>
                </ul>
              </div>
            </div>
          </nav>

          @yield('content')

        </div>
      </div>
    </div>

    <script src="/vendor/jquery-1.11.3.min.js"></script>
    <script src="/vendor/bootstrap.min.js"></script>
    <script src="/vendor/bootstrap-notify.min.js"></script>
    <script src="/vendor/react.min.js"></script>
    <script src="/vendor/react-dom.js"></script>
    @yield('foot')

  </body>
</html>
