#==========================================
# Events
#==========================================
$ ->
  $("#countdown").xxcountdown({
    "callback": ->
      location.reload()
  })
