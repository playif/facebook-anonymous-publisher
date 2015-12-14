@extends('layout')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div id="state">{!! $state !!}</div>
      </div>
    </div>
  </div>
  <script src="/vendor/xxcountdown.js" type="text/javascript"></script>
  <script src="/js/init.js" type="text/javascript"></script>

  {!! $foot !!}

@endsection
