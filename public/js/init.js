var DEBUG, headerTo, xx;

DEBUG = false;

$(function() {
  return $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
});

xx = function(x) {
  return DEBUG && console.log(x);
};

headerTo = function(path) {
  return window.location = path;
};
