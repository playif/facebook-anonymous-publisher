#==========================================
# Debug Mode
#==========================================
# DEBUG = true
DEBUG = false


#==========================================
# Events
#==========================================
$ ->
  $.ajaxSetup headers: 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')


#==========================================
# Functions
#==========================================
xx = (x) ->
  DEBUG && console.log x

headerTo = (path) ->
  window.location = path
